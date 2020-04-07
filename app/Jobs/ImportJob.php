<?php

namespace App\Jobs;

use App\Http\Controllers\Import\ImportController;
use App\Services\ImportServiceInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

class ImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ImportServiceInterface $importService;
    private string $csvName;
    public int $tries = 3;

    /**
     * Create a new job instance.
     *
     * @param ImportServiceInterface $importService
     * @param string $csvName
     */
    public function __construct(ImportServiceInterface $importService, string $csvName)
    {
        $this->importService = $importService;
        $this->csvName = $csvName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->importService->import($this->csvName);
    }

    /**
     * Неудачная обработка задачи.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception): void
    {
        $mess = __METHOD__ . ': ' . $exception->getMessage();
        Storage::disk('import')->append(ImportController::LOG, '[' . Carbon::now() . '] ' . $mess);
        Storage::disk('import')->append(ImportController::E_LOG, '[' . Carbon::now() . '] ' . $mess);
        // info($exception->getMessage());
    }
}
