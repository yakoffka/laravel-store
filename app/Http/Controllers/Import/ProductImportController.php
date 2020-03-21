<?php

namespace App\Http\Controllers\Import;

use App\Http\Requests\ProductImportRequest;
use App\Imports\ProductImport;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Storage;
use ZipArchive;

class ProductImportController extends Controller
{
    private string $ftpImportArch = 'images_20.zip';
    private string $ftpImportFile = 'export_products_20.csv';

    /**
     * ProductImportController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return Factory|View
     */
    public function showForm()
    {
        return view('dashboard.adminpanel.import.show_form');
    }

    /**
     * @param ProductImportRequest $request
     * @return RedirectResponse|Redirector
     */
    public function fromForm(ProductImportRequest $request)
    {
        $validated = $request->validated();
        $importArchPath = $validated['import_archive'];
        $importFilePath = $validated['import_file'];

        $this->import($importArchPath, $importFilePath);

        return redirect('/')->with('success', 'ProductImportController: All good!');
    }

    /**
     * @return RedirectResponse|Redirector
     */
    public function fromFtp()
    {
        $ftpImportArchPath = Storage::disk('import')->path($this->ftpImportArch);
        $ftpImportFilePath = Storage::disk('import')->path($this->ftpImportFile);

        $this->import($ftpImportArchPath, $ftpImportFilePath);

        return redirect('/')->with('success', 'ProductImportController: All good!');
    }

    /**
     * @param string $importArchPath
     * @param string $importFilePath
     */
    private function import(string $importArchPath, string $importFilePath): void
    {
        $this->processingImportArch($importArchPath);
        Excel::import(new ProductImport, $importFilePath);
        $this->deleteImportFiles();
    }

    /**
     * @param string $archPath
     * @return bool|RedirectResponse
     */
    private function processingImportArch(string $archPath)
    {
        if ( $archPath === '' || !is_file($archPath) ) {
            return true;
        }

        $zip = new ZipArchive();
        if ( $zip->open(public_path($archPath)) === true ) {
            $zip->extractTo(Storage::disk('import')->path('temp'));
            $zip->close();
            return true;
        }
        return back()->withErrors(['something wrong. err' . __LINE__]);
    }

    /**
     * @return bool
     */
    private function deleteImportFiles(): bool
    {
        // @todo: убраться за собой после выполнения всех операций.. тоже в очередь?
        if ( true ) {
            return true;
        }
        return false;
    }
}
