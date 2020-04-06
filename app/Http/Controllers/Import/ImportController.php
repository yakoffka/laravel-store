<?php

namespace App\Http\Controllers\Import;

use App\Jobs\ImportJob;
use App\Jobs\SendImportReportJob;
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
    // @todo: добавить описание процесса импорта
    // @todo: удалить/спрятать killQueueWorker.php, restartWithClean.php
    // @todo: отправить отчет
    // @todo: почистить за собой после импорта
    // @todo: продумать уникальный slug

    private ImportServiceInterface $importService;
    private string $csvName = 'goods.csv';

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
        abort_if( !auth()->user()->can(['create_categories', 'create_products', 'create_manufacturers']), 403);
        if ( Storage::disk('import')->exists($this->csvName) ) {
            Storage::disk('import')->append('log.txt', '[' . Carbon::now() . '] ' . 'Start import process');

            dispatch((new ImportJob($this->importService, $this->csvName))->onQueue('high'));
            dispatch((new SendImportReportJob())->onQueue('low'));

            session()->flash('message', __('Job successfully submitted to queue ' . $this->csvName));
            return redirect()->back();
        }
        session()->flash('message', __("Import file ':name' not found.",
            ['name' => Storage::disk('import')->path($this->csvName)]));
        return redirect()->back();

    }

    /**
     * @return Factory|View
     */
    public function showForm()
    {
        dd('Данная функциональность находится в разработке.');
        return view('dashboard.adminpanel.import.show_form');
    }
}
