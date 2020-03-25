<?php

namespace App\Http\Controllers\Import;

use App\Category;
use App\Http\Requests\ProductImportRequest;
use App\Imports\ProductImport;
use App\Jobs\ImportJob;
use App\Services\ImportServiceInterface;
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
    private ImportServiceInterface $importService;
    private string $archImagesName = 'images_all.zip';
    private string $csvName = 'products.csv';

    /**
     * ProductImportController constructor.
     * @param ImportServiceInterface $importService
     */
    public function __construct(ImportServiceInterface $importService)
    {
        $this->middleware('auth');
        $this->importService = $importService;
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
//        return redirect('/')->with('success', 'ProductImportController: All good!');
//    }

    /**
     * @return RedirectResponse|Redirector
     */
    public function fromFtp()
    {
        dispatch(new ImportJob($this->importService, $this->archImagesName, $this->csvName));
        session()->flash('message', 'ProductImportController: All good!');
        return redirect()->back();
    }

//    /**
//     * @param string $archImagesPath
//     * @param string $csvPath
//     */
//    private function import(string $archImagesPath, string $csvPath): void
//    {
//        info(__METHOD__ . '@' . __LINE__);
//        // $this->prepareImages($archImagesPath);
//        // Excel::import(new ProductImport, $csvPath);
//        $this->importService->procrastinatedPrepareImages($this->archImagesName);
//        $this->importService->procrastinatedImport($csvPath);
//    }

    /*
     * @param string $archPath
     * @return bool|RedirectResponse
     */
    /*private function prepareImages(string $archPath)
    {
        if (!Storage::disk('import')->exists($this->archImagesName)) {
            return true;
        }

        // @todo: после тестирования вернуть переименование! copy->move
        Storage::disk('import')->copy($this->archImagesName, 'images.zip');

        $zip = new ZipArchive();
        if ($zip->open(Storage::disk('import')->path('images.zip')) === true) {
            $zip->extractTo(Storage::disk('import')->path('temp/images'));
            $zip->close();
            Storage::disk('import')->delete('images.zip');
            return true;
        }
        return back()->withErrors([' err' . __LINE__ . __(': Failed to process image archive')]);
    }*/

//    /**
//     * @param string $importArchPath
//     */
//    private function moveToImportDisk(string $importArchPath): void
//    {
//        if (
//            copy($importArchPath, Storage::disk('import')->path('images.zip'))
//            && unlink($importArchPath)
//        ) {
//            dd("file...\n");
//        }
//        dd("не удалось скопировать file...\n");
//
//        // $r = Storage::disk('import')->put('Photo.N.zip', Storage::disk('public')->get($importArchPath));
//        // $r = Storage::disk('import')->put('Photo.N.zip', $importArchPath);
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
