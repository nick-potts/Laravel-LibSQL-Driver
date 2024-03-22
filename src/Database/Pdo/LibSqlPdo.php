<?php

namespace Squark\LibSqlDriver\Database\Pdo;

class LibSqlPdo extends \PDO
{
    public string $baseUrl;

    public function __construct(string $url)
    {
        parent::__construct($url);
        $this->baseUrl = $url;
    }

    public function prepare(string $query, array $options = []): LibSqlPdoStatement
    {
        return new LibSqlPdoStatement($query);
    }
}
