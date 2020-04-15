<?php

namespace App\Console\Commands;

use App\Jobs\ImportJob;
use App\Jobs\SendImportReportJob;
use App\Notifications\ImportNotification;
use App\Services\ImportServiceInterface;
use App\User;
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
        // @todo: добавить отправку уведомления о начале импорта (успешном либо неуспешном)

        if ($this->isSuccessImportStart()) {

            dispatch((new ImportJob($this->importService))->onQueue('high'));
            dispatch((new SendImportReportJob())->onQueue('low'));

            $this->line(__("Job successfully submitted to queue. Import file ':name'", ['name' => ImportServiceInterface::CSV_NAME]));
            return;
        }
        $this->line(__("Import file ':name' not found.", ['name' => ImportServiceInterface::CSV_SRC_NAME]));
    }

    /**
     * @return bool
     */
    private function isSuccessImportStart(): bool
    {
        if (Storage::disk('import')->exists(ImportServiceInterface::CSV_SRC_NAME)) {
            $mess = 'Discovered file "' . ImportServiceInterface::CSV_SRC_NAME . '". Start import process';
            Storage::disk('import')->append(ImportServiceInterface::LOG, '[' . Carbon::now() . '] ' . $mess);

            $sysUser = User::all()->where('id', '=', 1)->first();
            $sysUser->notify(new ImportNotification($mess));

            // @todo: поймать ошибки!
            Storage::disk('import')->move(ImportServiceInterface::CSV_SRC_NAME, ImportServiceInterface::CSV_NAME);
            return true;
        }
        return false;
    }
}
