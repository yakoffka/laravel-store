<?php

namespace App\Console\Commands;

use App\Jobs\ImportJob;
use App\Jobs\SendImportReportJob;
use App\Services\ImportServiceInterface;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportWatchDog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check if import file exists';
    private ImportServiceInterface $importService;

    /**
     * Create a new command instance.
     *
     * @param ImportServiceInterface $importService
     */
    public function __construct(ImportServiceInterface $importService)
    {
        parent::__construct();
        $this->importService = $importService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        if (Storage::disk('import')->exists(ImportServiceInterface::CSV_NAME)) {
            $mess = 'Discovered file "' . ImportServiceInterface::CSV_NAME . '". Start import process';
            Storage::disk('import')->append(ImportServiceInterface::LOG, '[' . Carbon::now() . '] ' . $mess);

            dispatch((new ImportJob($this->importService ))->onQueue('high'));
            dispatch((new SendImportReportJob(1))->onQueue('low'));

            $this->line(__("Job successfully submitted to queue. Import file ':name'", ['name' => ImportServiceInterface::CSV_NAME]));
            return;
        }
        $this->line(__("Import file ':name' not found.", ['name' => ImportServiceInterface::CSV_NAME]));
    }
}
