<?php

namespace NickPotts\LibSql\LibSql\Requests\Dto;

class ResponseDto
{
    public string|null $baton;
    public string|null $baseUrl;
    public ExecuteResult|null $executeResult = null;
    public array|null $error = null;

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
                $this->error = $result['error'];
            }
        }
    }
}
