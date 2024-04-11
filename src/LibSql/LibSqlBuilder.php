<?php

namespace NickPotts\LibSql\LibSql;

use Exception;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Grammars\SQLiteGrammar;
use Illuminate\Database\Schema\SQLiteBuilder;

class LibSqlBuilder extends SQLiteBuilder
{
    public function __construct(Connection $connection)
    {
        parent::__construct($connection);
        $this->grammar = new SQLiteGrammar();
    }

    public function createDatabase($name)
    {
        throw new Exception('LibSql does not support creating databases');
    }

    public function dropDatabaseIfExists($name)
    {
        throw new Exception('LibSql does not support dropping databases');
    }

    public function refreshDatabaseFile()
    {
        $this->dropAllTables();
        $this->dropAllViews();
    }

    public function dropAllTables()
    {
        $tables = $this->connection->select("SELECT name FROM sqlite_master WHERE type='table'");
        foreach ($tables as $table) {
            if ($table->name === 'sqlite_sequence') {
                continue;
            }
            $this->connection->statement("DROP TABLE IF EXISTS {$table->name}");
        }

        if ($this->connection->select("SELECT name FROM sqlite_master WHERE type='table' AND name='sqlite_sequence'")) {
            $this->connection->statement("DELETE FROM sqlite_sequence");
        }
    }

    public function dropAllViews()
    {
        $views = $this->connection->select("SELECT name FROM sqlite_master WHERE type='view'");
        foreach ($views as $view) {
            $this->connection->statement("DROP VIEW IF EXISTS {$view->name}");
        }
    }
}
