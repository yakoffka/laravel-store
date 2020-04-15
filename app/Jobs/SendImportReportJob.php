<?php

namespace App\Jobs;

use App\Notifications\ImportReportNotification;
use App\Services\ImportServiceInterface;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Storage;
use Str;

class SendImportReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    private int $initiatorId;
    private Carbon $started_at;

    /**
     * Create a new job instance.
     *
     * @param int $initiatorId
     */
    public function __construct(int $initiatorId = 1)
    {
        $this->initiatorId = $initiatorId;
        $this->started_at = now();
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        $mess = 'End import process';
        Storage::disk('import')->append(ImportServiceInterface::LOG, '[' . Carbon::now() . '] ' . $mess);

        $sysUser = User::all()->where('id', '=', $this->initiatorId)->first();
        $sysUser->notify(new ImportReportNotification($this->getDirPathMovedReportFiles(), $this->started_at));
    }

    /**
     * @inheritDoc
     * @throws FileNotFoundException
     */
    public function getDirPathMovedReportFiles(): string
    {
        $dirDstPath = 'import_results/' . Str::random(42) . '/';
        $fileNames = [
            'csv_src_file' => ImportServiceInterface::CSV_SRC_NAME,
            'csv_file' => ImportServiceInterface::CSV_NAME,
            'log' => ImportServiceInterface::LOG,
            'e_log' => ImportServiceInterface::E_LOG,
        ];

        foreach ($fileNames as $fileName) {
            if ( !Storage::disk('import')->exists($fileName) ) {
                continue;
            }
            $fileContents = Storage::disk('import')->get($fileName);
            Storage::disk('public')->put($dirDstPath.$fileName, $fileContents);
            Storage::disk('import')->delete($fileName);
        }
        Storage::disk('import')->append(ImportServiceInterface::LOG, '');

        return $dirDstPath;
    }
}
