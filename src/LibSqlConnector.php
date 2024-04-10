<?php

namespace NickPotts\LibSql;

use Saloon\Http\Connector;

abstract class LibSqlConnector extends Connector
{
    public function __construct(
        protected ?string $token = null,
        public string $apiUrl = 'http://127.0.0.1:8080',
    ) {
        if ($this->token) {
            $this->withTokenAuth($token);
        }
    }

    public function resolveBaseUrl(): string
    {
        return $this->apiUrl;
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }
}
