<?php

namespace NickPotts\LibSql\LibSql\Requests\Dto;

class ExecuteResult
{
    public $cols;
    public $rows;
    public $affected_row_count;
    public $last_insert_rowid;
    public $replication_index;

    public function __construct($result)
    {
        $this->cols = array_map(function ($col) {
            return new Column($col);
        }, $result['cols'] ?? []);
        $this->rows = $result['rows'] ?? [];
        $this->affected_row_count = $result['affected_row_count'] ?? null;
        $this->last_insert_rowid = $result['last_insert_rowid'] ?? null;
        $this->replication_index = $result['replication_index'] ?? null;
    }
}
