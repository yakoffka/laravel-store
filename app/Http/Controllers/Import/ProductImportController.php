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
    private string $ftpImportArchPath;
    private string $ftpImportFilePath;

    /**
     * ProductImportController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->ftpImportArchPath = Storage::disk('import')->path('images_20.zip');
        $this->ftpImportFilePath = Storage::disk('import')->path('export_products_20.csv');
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

        dd('stop');

        $this->deleteImportFiles();
        return redirect('/')->with('success', 'ProductImportController: All good!');
    }

    /**
     * @return RedirectResponse|Redirector
     */
    public function fromFtp()
    {
        info(__METHOD__ . ' ');
        $this->import($this->ftpImportArchPath, $this->ftpImportFilePath);

        dd('stop');

        $this->deleteImportFiles();
        return redirect('/')->with('success', 'ProductImportController: All good!');
    }

    /**
     * @param string $importArchPath
     * @param string $importFilePath
     */
    private function import(string $importArchPath, string $importFilePath): void
    {
        info(__METHOD__ . ' ');
        $this->unpackImportArch($importArchPath);
        Excel::import(new ProductImport, $importFilePath);
        info(__METHOD__ . ' EXIT' . "\n");
    }

    /**
     * @param string $archPath
     * @return bool|RedirectResponse
     */
    private function unpackImportArch(string $archPath)
    {
        if ( $archPath === '' ) {
            return true;
        }

        $zip = new ZipArchive();
        if ( $zip->open(public_path($archPath)) === true ) {
            $zip->extractTo(Storage::disk('import')->path('temp'));
            $zip->close();

            // @todo: delete $archPath file!
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
