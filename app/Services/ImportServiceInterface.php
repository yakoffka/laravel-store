<?php

namespace App\Services;

interface ImportServiceInterface
{
    /**
     * @param string $csvPath
     * @return mixed
     */
    public function import(string $csvPath);
}
