<?php

namespace App\Services;

use App\Imports\ProductImport;
use Maatwebsite\Excel\Facades\Excel;
use Storage;

class ImportServiceInterfaceImpl implements ImportServiceInterface
{
    /**
     * @param string $csvPath
     */
	public function import(string $csvPath)
	{
        Excel::import(new ProductImport, Storage::disk('import')->path($csvPath));
	}
}
