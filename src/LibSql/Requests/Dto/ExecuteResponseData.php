<?php

namespace NickPotts\LibSql\LibSql\Requests\Dto;

class ExecuteResponseData extends ResponseDataDTO
{
    public ExecuteResult $result;

    public function __construct($type, $result)
    {
        parent::__construct($type, new ExecuteResult($result));
    }
}
