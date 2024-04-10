<?php

namespace NickPotts\LibSql\LibSql\Requests\Dto;

class CloseResponseData extends ResponseDataDTO
{
    public function __construct($type)
    {
        parent::__construct($type, null);
    }
}
