<?php

namespace App\Services;

interface ImportServiceInterface
{
    /*
     * @param string $archImagesName
     * @return mixed
     */
    //public function prepareImages(string $archImagesName);

    /**
     * @param string $csvPath
     * @return mixed
     */
    public function import(string $csvPath);

    /**
     * @return mixed
     */
    public function cleanUp();
}
