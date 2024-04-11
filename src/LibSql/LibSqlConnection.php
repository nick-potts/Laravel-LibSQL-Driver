<?php

namespace NickPotts\LibSql\LibSql;

use Illuminate\Database\Query\Grammars\MySqlGrammar;
use Illuminate\Database\SQLiteConnection;
use NickPotts\LibSql\LibSql\Pdo\LibSqlPdo;
use NickPotts\LibSql\LibSqlHttpConnector;

class LibSqlConnection extends SQLiteConnection
{
    public function __construct(
        protected LibSqlHttpConnector $connector,
        protected                     $config = [],
    )
    {
        $queryGrammar = (new MySqlGrammar())->setConnection($this);
        parent::__construct(
            new LibSqlPdo('sqlite::memory:', $this->connector, $queryGrammar),
            '',
            $config['prefix'] ?? '',
            $config,
        );
    }

    public function getSchemaBuilder()
    {
        return new LibSqlBuilder($this);
    }
}
