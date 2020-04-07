<?php

namespace App\Jobs;

use App\Http\Controllers\Import\ImportController;
use App\Notifications\ImportNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Notification;
use Storage;
use Str;

class SendImportReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    private int $initiatorId;

    /**
     * Create a new job instance.
     *
     * @param int $initiatorId
     */
    public function __construct($initiatorId)
    {
        $this->initiatorId = $initiatorId;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        $mess = __METHOD__ . ' l' .  __LINE__ . PHP_EOL . 'cleanUp() and sendNotification()';
        Storage::disk('import')->append(ImportController::LOG, '[' . Carbon::now() . '] ' . $mess);

        User::all()
            ->where('id', '=', $this->initiatorId)
            ->first()
            ->notify(new ImportNotification($this->getDirPathMovedReportFiles()));
    }

    /**
     * @inheritDoc
     * @throws FileNotFoundException
     */
    public function getDirPathMovedReportFiles(): string
    {
        $dirDstPath = 'import_results/' . Str::random(42) . '/';
        $fileNames = [
            'log' => ImportController::LOG,
            'e_log' => ImportController::E_LOG,
        ];

        foreach ($fileNames as $fileName) {
            if ( !Storage::disk('import')->exists($fileName) ) {
                continue;
            }
            $file = Storage::disk('import')->get($fileName);
            Storage::disk('public')->put($dirDstPath.$fileName, $file);
            Storage::disk('import')->delete($fileName);
        }

        return $dirDstPath;
    }
}
