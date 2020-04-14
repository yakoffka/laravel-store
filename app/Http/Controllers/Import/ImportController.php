<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Services\ImportServiceInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ImportController extends Controller
{

    /**
     * @return View
     */
    public function queuingImportJob(): View
    {
        $importPath = Storage::disk('import')->path('');
        $csv = ImportServiceInterface::CSV_NAME;
        return view('dashboard.adminpanel.import_export.import', compact('importPath', 'csv'));
    }
}
