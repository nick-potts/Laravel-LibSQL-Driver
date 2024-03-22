<?php

namespace Squark\LibSqlDriver\Database\Connectors;

use Illuminate\Database\Connectors\SQLiteConnector;
use Squark\LibSqlDriver\Database\Pdo\LibSqlPdo;

class LibSqlConnector extends SQLiteConnector
{
    public function connect(array $config): LibSqlPdo
    {
        $org = $config['organization'];
        $domain = $config['domain'];
        if (empty($org) || empty($domain)) {
            throw new \InvalidArgumentException('Organization and domain must be set in the database configuration.');
        }

        $url =  $config['organization'] . '.' . $config['domain'];

        return new LibSqlPdo($url);
    }
}
