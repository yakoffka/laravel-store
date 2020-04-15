<?php

namespace App\Services;

interface ImportServiceInterface
{
    public const CSV_SRC_NAME = 'goods.csv';
    public const CSV_NAME = 'temp/goods.csv';
    public const LOG = 'log.txt';
    public const E_LOG = 'err_log.txt';

    /**
     * @return mixed
     */
    public function import();
}
