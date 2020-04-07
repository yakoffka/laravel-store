<?php

namespace App\Listeners;

use App\Http\Controllers\Import\ImportController;
use Carbon\Carbon;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Storage;

class SendEmailImportNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
        $mess = '__construct end import. thanks.';
        Storage::disk('import')->append(ImportController::LOG, '[' . Carbon::now() . '] ' . $mess);
    }

    /**
     * Handle the event.
     *
     * @param  AfterSheet  $event
     * @return void
     */
    public function handle(AfterSheet $event)
    {
        info(var_dump($event));
        if (Storage::disk('import')->exists('err_log.txt')) {
            $mess = 'failed end import. thanks.';
        } else {
            $mess = 'success end import. thanks.';
        }
        Storage::disk('import')->append(ImportController::LOG, '[' . Carbon::now() . '] ' . $mess);
    }
}
