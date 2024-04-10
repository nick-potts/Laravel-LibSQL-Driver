<?php

namespace NickPotts\LibSql\LibSql\Requests;

use Exception;
use JsonException;
use NickPotts\LibSql\LibSql\Requests\Dto\ResponseDto;
use NickPotts\LibSql\LibSqlRequest;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class LibSqlQueryRequest extends LibSqlRequest implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        $connector,
        protected string $sql,
        protected string|null $baton = null,
    )
    {
        parent::__construct($connector);
    }

    public function resolveEndpoint(): string
    {
        return '/v2/pipeline';
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function createDtoFromResponse(Response $response): mixed
    {
        if (!$response->ok() || $response->header('content-type') !== 'application/json') {
            $body = $response->body();
            throw new Exception($body);
        }

        $json = $response->json();
        return new ResponseDto($json);
    }


    protected function defaultBody(): array
    {
        $body = [
            'baton' => $this->baton,
            'requests' => [
                [
                    'type' => 'execute',
                    'stmt' => [
                        'sql' => $this->sql,
                    ]
                ]
            ]
        ];
        return $body;
    }
}
