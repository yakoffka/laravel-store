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
    private string $archImagesName = 'photo_20.zip';
    private string $csvName = 'export_products_20.csv';

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
        dd('Данная страница находится в разработке.');
        return view('dashboard.adminpanel.import.show_form');
    }

    /**
     * @param ProductImportRequest $request
     * @return RedirectResponse|Redirector
     */
    public function fromForm(ProductImportRequest $request)
    {
        $validated = $request->validated();
        $importArchPath = public_path($validated['import_archive']);
        $importFilePath = public_path($validated['import_file']);

        $this->moveToImportDisk($importArchPath);

        $this->import($importArchPath, $importFilePath);

        return redirect('/')->with('success', 'ProductImportController: All good!');
    }

    /**
     * @return RedirectResponse|Redirector
     */
    public function fromFtp()
    {
        $archImagesPath = Storage::disk('import')->path($this->archImagesName);
        $csvPath = Storage::disk('import')->path($this->csvName);
        $this->import($archImagesPath, $csvPath);

        return redirect()->back()->with('success', 'ProductImportController: All good!');
    }

    /**
     * @param string $archImagesPath
     * @param string $csvPath
     */
    private function import(string $archImagesPath, string $csvPath): void
    {
        $this->prepareImages($archImagesPath);
        Excel::import(new ProductImport, $csvPath);
        $this->cleanUp();
    }

    /**
     * @param string $archPath
     * @return bool|RedirectResponse
     */
    private function prepareImages(string $archPath)
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
    }

    /**
     * @param string $importArchPath
     */
    private function moveToImportDisk(string $importArchPath): void
    {
        if (
            copy($importArchPath, Storage::disk('import')->path('images.zip'))
            && unlink($importArchPath)
        ) {
            dd("file...\n");
        }
        dd("не удалось скопировать file...\n");

        // $r = Storage::disk('import')->put('Photo.N.zip', Storage::disk('public')->get($importArchPath));
        // $r = Storage::disk('import')->put('Photo.N.zip', $importArchPath);
    }

    /**
     * @return bool
     */
    private function cleanUp(): bool
    {
        // @todo: убраться за собой после выполнения всех операций.. тоже в очередь?
        if (true) {
            return true;
        }
        return false;
    }
}
