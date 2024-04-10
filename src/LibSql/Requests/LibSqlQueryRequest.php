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
        protected bool $shouldClose = true,
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
                ],
            ]
        ];
        if ($this->shouldClose) {
            $body['requests'][] = [
                'type' => 'close'
            ];
        }

        return $body;
    }
}
