<?php

namespace NickPotts\LibSql\LibSql\Requests\Dto;

use Exception;

class ResponseDto
{
    public string|null $baton;
    public string|null $baseUrl;
    public ExecuteResult|null $executeResult = null;

    public function __construct(array $response)
    {
        $this->baton = $response['baton'] ?? null;
        $this->baseUrl = $response['baseUrl'] ?? null;
        if ($result = $response['results'][0] ?? null) {
            if (($result['type'] ?? null) === 'ok') {
                $response = $result['response'] ?? null;
                if ($response && $response['type'] === 'execute') {
                    $this->executeResult = new ExecuteResult($response['result']);
                }
            } elseif ($result['type'] === 'error') {
                throw new Exception($result['error']['message']);
            }
        }
        if (!$this->executeResult) {
            throw new Exception('No execute result found');
        }
    }
}
