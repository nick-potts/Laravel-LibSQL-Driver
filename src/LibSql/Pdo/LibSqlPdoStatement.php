<?php

namespace NickPotts\LibSql\LibSql\Pdo;

use Exception;
use NickPotts\LibSql\LibSql\Requests\Dto\ExecuteResult;
use NickPotts\LibSql\LibSql\Requests\Dto\ResponseDto;
use PDO;
use PDOException;
use PDOStatement;

class LibSqlPdoStatement extends PDOStatement
{
    protected int $fetchMode = PDO::FETCH_ASSOC;
    protected array $bindings = [];
    protected ExecuteResult|null $response = null;


    public function __construct(
        protected LibSqlPdo &$pdo,
        protected string    $query,
        protected array     $options = [],
    )
    {
    }

    public function setFetchMode(int $mode, mixed ...$args): bool
    {
        $this->fetchMode = $mode;

        return true;
    }

    public function bindValue($param, $value, $type = PDO::PARAM_STR): bool
    {
        $this->bindings[$param] = match ($type) {
            PDO::PARAM_LOB => base64_encode($value),
            PDO::PARAM_STR => (string)$value,
            PDO::PARAM_BOOL => (bool)$value,
            PDO::PARAM_INT => (int)$value,
            PDO::PARAM_NULL => null,
            default => $value,
        };

        return true;
    }

    public function execute($params = []): bool
    {
        $bindings = $this->bindings ?: $params;
        $query = $this->pdo->grammar()->substituteBindingsIntoRawSql($this->query, $bindings);

        try {
            $response = $this->pdo->libsql()->databaseQuery(
                $query,
                $this->pdo->getBaton(),
                $this->pdo->getBaseUrl(),
                $this->pdo->inTransaction(),
            );
            if (!$response->ok()) {
                $body = $response->body();
                throw new Exception(
                    $body,
                );
            }

            /** @var ResponseDto $responseData */
            $responseData = $response->dto();
        } catch (Exception $e) {
            throw new PDOException(
                $e->getMessage(),
                $e->getCode(),
            );
        }

        $this->pdo->setBaseUrl($responseData->baseUrl);
        $this->pdo->setBaton($responseData->baton);

        if ($responseData->error) {
            throw new PDOException(
                $responseData->error['message'],
                0,
            );
        }

        $this->response = $responseData->executeResult;

        $lastId = $this->response?->last_insert_rowid;

        if (!in_array($lastId, [0, null])) {
            $this->pdo->setLastInsertId(value: $lastId);
        }

        return true;
    }

    public function fetchAll(int $mode = PDO::FETCH_DEFAULT, ...$args): array
    {
        return match ($this->fetchMode) {
            PDO::FETCH_ASSOC => $this->rowsFromResponses(),
            PDO::FETCH_OBJ => collect($this->rowsFromResponses())->map(function ($row) {
                return (object)$row;
            })->toArray(),
            default => throw new PDOException('Unsupported fetch mode.'),
        };
    }

    protected function rowsFromResponses(): array
    {
        $rows = [];
        if (isset($this->response->rows) && isset($this->response->cols)) {
            foreach ($this->response->rows as $row) {
                $mappedRow = [];
                foreach ($this->response->cols as $index => $col) {
                    $value = $row[$index]['value'] ?? null;
                    $type = $row[$index]['type'] ?? null;
                    $value = match ($type) {
                        'null' => null,
                        'integer' => (int)$value,
                        'float' => (float)$value, // laravel doesn't do floats
                        'text' => (string)$value,
                        'blob' => base64_decode($value), // laravel doesn't do blobs
                        default => $value,
                    };

                    $mappedRow[$col->name] = $value;
                }
                $rows[] = $mappedRow;
            }
        }
        return $rows;
    }

    public function rowCount(): int
    {
        return count($this->response?->rows);
    }
}
