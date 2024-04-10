<?php

namespace NickPotts\LibSql;

use Saloon\Contracts\Connector;
use Saloon\Http\Request;

abstract class LibSqlRequest extends Request
{
    protected LibSqlConnector $connector;

    public function __construct($connector)
    {
        $this->connector = $connector;
    }
}
