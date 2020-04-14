<?php

namespace App\Services;

use App\Imports\ProductImport;
use Maatwebsite\Excel\Facades\Excel;
use Storage;

class ImportService implements ImportServiceInterface
{
    /**
     *
     */
	public function import()
	{
        Excel::import(new ProductImport, Storage::disk('import')->path(self::CSV_NAME));
	}
}
