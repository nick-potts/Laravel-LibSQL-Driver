<?php

namespace Squark\LibSqlDriver\Database\Pdo;

use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Grammars\SQLiteGrammar;
use PDO;

class LibSqlPdoStatement extends \PDOStatement
{
    public string $queryString;
    public string $internalQueryString;
    private int $rowCount;
    private array $results;
    public Grammar $grammar;
    public function __construct(string $queryString)
    {
        $this->queryString = $queryString;
        $this->grammar = new SQLiteGrammar();
    }

    public function execute(?array $params = null): bool
    {
        $this->rowCount = 0;
        $this->results = [];
        return true;
    }

    public function rowCount(): int
    {
        return $this->rowCount;
    }

    public function nextRowset(): bool
    {
        return false;
    }

    public function fetch(int $mode = PDO::FETCH_BOTH, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0): array
    {
        return $this->results;
    }

    public function fetchAll(int $mode = PDO::FETCH_BOTH, ...$args): array
    {
        return $this->results;
    }

    public function bindValue(int|string $param, mixed $value, int $type = PDO::PARAM_STR): bool
    {
        throw new \RuntimeException('Not implemented');
    }

    public function bindValues(array $bindings): void
    {
        $this->internalQueryString = $this->grammar->substituteBindingsIntoRawSql($this->queryString, $bindings);
    }

}
