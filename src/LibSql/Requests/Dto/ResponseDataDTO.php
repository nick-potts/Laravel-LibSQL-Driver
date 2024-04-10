<?php

namespace NickPotts\LibSql\LibSql\Requests\Dto;

class ResponseDataDTO
{
    public $type;
    public $response;

    public function __construct($type, $response)
    {
        $this->type = $type;
        $this->response = $response;
    }
}
