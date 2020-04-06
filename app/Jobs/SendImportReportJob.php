<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Storage;

class SendImportReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $mess = __METHOD__ . ' l' .  __LINE__ . PHP_EOL . 'cleanUp() and sendNotification()';
        Storage::disk('import')->append('log.txt', '[' . Carbon::now() . '] ' . $mess);
    }

    /**
     * @inheritDoc
     */
    public function cleanUp()
    {
        // TODO: Implement cleanUp() method.
    }
}
