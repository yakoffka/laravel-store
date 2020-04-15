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
        // @todo: переделать отлов ошибок, используя try/catch
        $csvSrcName = ImportServiceInterface::CSV_SRC_NAME;
        $csvName = ImportServiceInterface::CSV_NAME;

        if (Storage::disk('import')->exists($csvSrcName)) {
            $this->sendMessage("Discovered file '$csvSrcName'. Start import process");

            if (!Storage::disk('import')->exists($csvName)) {
                if (Storage::disk('import')->move($csvSrcName, $csvName)) {
                    $this->sendMessage("File '$csvSrcName' moved");
                    return true;
                }

                $this->sendError("Error moved file '$csvSrcName'! Abort import process");
            } else {
                $this->sendError("File '$csvName' already exists! Abort import process");
            }
            dispatch((new SendImportReportJob())->onQueue('high'));
        }
        return false;
    }

    /**
     * @param $mess
     */
    private function sendMessage($mess): void
    {
        Storage::disk('import')->append(ImportServiceInterface::LOG, '[' . Carbon::now() . '] ' . $mess);

        $sysUser = User::all()->where('id', '=', 1)->first();
        $sysUser->notify(new ImportNotification($mess));
    }

    /**
     * @param $mess
     */
    private function sendError($mess): void
    {
        Storage::disk('import')->append(ImportServiceInterface::E_LOG, '[' . Carbon::now() . '] ' . $mess);
        $this->sendMessage($mess);
    }
}
