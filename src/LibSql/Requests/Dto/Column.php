<?php

namespace NickPotts\LibSql\LibSql\Requests\Dto;


class Column {
    public $name;
    public $decltype;

    public function __construct($col) {
        $this->name = $col['name'];
        $this->decltype = $col['decltype'];
    }
}
