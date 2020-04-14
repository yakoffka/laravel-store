<?php

namespace App\Jobs;

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
    public int $tries = 3;

    /**
     * Create a new job instance.
     *
     * @param ImportServiceInterface $importService
     */
    public function __construct(ImportServiceInterface $importService)
    {
        $this->importService = $importService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->importService->import();
    }

    /**
     * Неудачная обработка задачи.
     *
     * @param Exception $exception
     * @return void
     */
    public function failed(Exception $exception): void
    {
        $mess = __METHOD__ . ': ' . $exception->getMessage() . ' in file "' . $exception->getFile() . '" line:' . $exception->getLine();
        Storage::disk('import')->append(ImportServiceInterface::LOG, '[' . Carbon::now() . '] ' . $mess);
        Storage::disk('import')->append(ImportServiceInterface::E_LOG, '[' . Carbon::now() . '] ' . $mess);
    }
}
