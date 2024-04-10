<?php

namespace NickPotts\LibSql\LibSql\Pdo;

use Illuminate\Database\Query\Grammars\MySqlGrammar;
use Illuminate\Database\Query\Grammars\SQLiteGrammar;
use NickPotts\LibSql\LibSql\LibSqlSchemaGrammar;
use PDO;
use PDOStatement;
use NickPotts\LibSql\LibSqlHttpConnector;

class LibSqlPdo extends PDO
{
    protected array $lastInsertIds = [];

    protected string|null $baton = null;
    protected string|null $baseUrl = null;
    protected bool $inTransaction = false;

    public function __construct(
        protected string              $dsn,
        protected LibSqlHttpConnector $connector,
        protected MySqlGrammar $grammar,
    ) {
        parent::__construct('sqlite::memory:');
    }

    public function prepare($query, $options = []): PDOStatement|false
    {
        return new LibSqlPdoStatement(
            $this,
            $query,
            $options,
        );
    }

    public function grammar(): MySqlGrammar
    {
        return $this->grammar;
    }

    public function libsql(): LibSqlHttpConnector
    {
        return $this->connector;
    }

    public function setLastInsertId($name = null, $value = null): void
    {
        if ($name === null) {
            $name = 'id';
        }

        $this->lastInsertIds[$name] = $value;
    }

    public function lastInsertId($name = null): false|string
    {
        if ($name === null) {
            $name = 'id';
        }

        return $this->lastInsertIds[$name] ?? false;
    }

    public function beginTransaction(): bool
    {
        $statement = $this->prepare('BEGIN');
        $statement->execute();
        return $this->inTransaction = true;
    }

    public function commit(): bool
    {
        $statement = $this->prepare('COMMIT');
        $statement->execute();
        return ! ($this->inTransaction = false);
    }

    public function inTransaction(): bool
    {
        return $this->inTransaction;
    }

    public function rollBack(): bool
    {
        $statement = $this->prepare('ROLLBACK');
        $statement->execute();
        return ! ($this->inTransaction = false);
    }

    public function setBaseUrl(mixed $baseUrl): void
    {
        $this->baseUrl = $baseUrl;
    }

    public function setBaton(mixed $baton): void
    {
        $this->baton = $baton;
    }

    public function getBaton(): ?string
    {
        return $this->baton;
    }

    public function getBaseUrl(): ?string
    {
        return $this->baseUrl;
    }
}
