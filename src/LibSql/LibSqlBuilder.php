<?php

namespace NickPotts\LibSql\LibSql;

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

    public function dropAllViews()
    {
        $views = $this->connection->select("SELECT name FROM sqlite_master WHERE type='view'");
        foreach ($views as $view) {
            $this->connection->statement("DROP VIEW IF EXISTS {$view->name}");
        }
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
        $this->connection->statement("DELETE FROM sqlite_sequence");
    }
}
