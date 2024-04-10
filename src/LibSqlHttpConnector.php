<?php

namespace NickPotts\LibSql;

use NickPotts\LibSql\LibSql\Requests\LibSqlQueryRequest;
use ReflectionException;
use Saloon\Contracts\Response;
use Saloon\Exceptions\InvalidResponseClassException;
use Saloon\Exceptions\PendingRequestException;

class LibSqlHttpConnector extends LibSqlConnector
{
    public function __construct(
        protected ?string $token = null,
        public string     $apiUrl = 'http://127.0.0.1:8080',
    )
    {
        parent::__construct($token, $apiUrl);
    }

    /**
     * @throws ReflectionException
     * @throws InvalidResponseClassException
     * @throws PendingRequestException
     */
    public function databaseQuery(
        string      $sql,
        string|null $baton,
        string|null $baseUrl,
    ): Response
    {
        $this->apiUrl = $baseUrl ?? $this->apiUrl;

        $request = new LibSqlQueryRequest($this, $sql, $baton);

        return $this->send(
            $request,
        );
    }
}
