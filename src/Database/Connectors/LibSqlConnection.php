<?php

namespace Squark\LibSqlDriver\Database\Connectors;

use Illuminate\Database\SQLiteConnection;

class LibSqlConnection extends SQLiteConnection
{
    public function __construct($pdo, $database = '', $tablePrefix = '', array $config = [])
    {
        parent::__construct($pdo, $database, $tablePrefix, $config);
    }

    public function bindValues($statement, $bindings): void
    {
        if ($statement instanceof \Squark\LibSqlDriver\Database\Pdo\LibSqlPdoStatement) {
            $statement->bindValues($bindings);
        } else {
            throw new \RuntimeException('Unexpected PDOStatement type');
        }
    }
}
