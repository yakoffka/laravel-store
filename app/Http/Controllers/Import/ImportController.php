<?php

namespace App\Http\Controllers\Import;

use App\Jobs\ImportJob;
use App\Services\ImportServiceInterface;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ImportController extends Controller
{
    private ImportServiceInterface $importService;
    private string $csvName = 'products.csv';

    /**
     * ImportController constructor.
     * @param ImportServiceInterface $importService
     */
    public function __construct(ImportServiceInterface $importService)
    {
        $this->middleware('auth');
        $this->importService = $importService;
    }

    /**
     * @return RedirectResponse|Redirector
     */
    public function queuingImportJob()
    {
        Storage::disk('import')->append('log.txt', '[' . Carbon::now() . '] ' . 'Start process');
        dispatch(new ImportJob($this->importService, $this->csvName));
        session()->flash('message', __('Job successfully submitted to queue'));
        return redirect()->back();
    }

    /**
     * @return Factory|View
     */
    public function showForm()
    {
        dd('Данная страница находится в разработке.');
        return view('dashboard.adminpanel.import.show_form');
    }

//    /**
//     * @param ProductImportRequest $request
//     * @return RedirectResponse|Redirector
//     */
//    public function fromForm(ProductImportRequest $request)
//    {
//        $validated = $request->validated();
//        $importArchPath = public_path($validated['import_archive']);
//        $importFilePath = public_path($validated['import_file']);
//
//        $this->moveToImportDisk($importArchPath);
//
//        $this->import($importArchPath, $importFilePath);
//
//        return redirect('/')->with('success', 'ImportController: All good!');
//    }

//    /**
//     * @return bool
//     */
//    private function cleanUp(): bool
//    {
//        // @todo: убраться за собой после выполнения всех операций.. тоже в очередь?
//        if (true) {
//            return true;
//        }
//        return false;
//    }
}
