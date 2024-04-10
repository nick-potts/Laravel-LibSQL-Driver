<?php

namespace NickPotts\LibSql\LibSql;

use Illuminate\Database\Grammar;
use Illuminate\Database\Query\Grammars\MySqlGrammar;
use Illuminate\Database\Schema\Grammars\SQLiteGrammar;
use Illuminate\Database\SQLiteConnection;
use NickPotts\LibSql\LibSqlHttpConnector;
use NickPotts\LibSql\LibSql\Pdo\LibSqlPdo;

class LibSqlConnection extends SQLiteConnection
{
    public function __construct(
        protected LibSqlHttpConnector $connector,
        protected                     $config = [],
    ) {
        $queryGrammar = (new MySqlGrammar())->setConnection($this);
        parent::__construct(
            new LibSqlPdo('sqlite::memory:', $this->connector, $queryGrammar),
            $config['database'] ?? '',
            $config['prefix'] ?? '',
            $config,
        );
    }

    protected function getDefaultSchemaGrammar(): Grammar
    {
        ($grammar = new LibSqlSchemaGrammar())->setConnection($this);

        return $this->withTablePrefix($grammar);
    }

    public function libsql(): LibSqlHttpConnector
    {
        return $this->connector;
    }
}
