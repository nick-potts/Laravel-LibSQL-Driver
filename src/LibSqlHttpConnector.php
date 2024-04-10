<?php

namespace NickPotts\LibSql;

use NickPotts\LibSql\LibSql\Requests\LibSqlQueryRequest;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Response;

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
     * @throws FatalRequestException
     * @throws RequestException
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
