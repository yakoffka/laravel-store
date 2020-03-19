<?php

namespace App\Http\Controllers\Import;

use App\Http\Requests\ImportProductRequest;
use App\Imports\ProductsImport;
use Composer\Downloader\ZipDownloader;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Storage;
use ZipArchive;

class ImportProductController extends Controller
{
    /**
     * ImportProductController constructor.
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
     * @param ImportProductRequest $request
     * @return RedirectResponse|Redirector
     */
    public function import(ImportProductRequest $request)
    {
        $validated = $request->validated();
        $this->unpackImportArch($validated['import_archive'] ?? '');
        Excel::import(new ProductsImport, public_path($validated['import_file']));
        dd('stop');
        return redirect('/')->with('success', 'ImportProductController: All good!');
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
}
