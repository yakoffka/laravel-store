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
    private string $csvName;

    // @todo: добавить параметры очереди: имя, задержку, кол-во попыток и прочие
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
        Storage::disk('import')->append('log.txt', '[' . Carbon::now() . '] ' . 'start ImportJob');
        $this->importService->import($this->csvName);
        // $this->importService->cleanUp();
    }

    /**
     * Неудачная обработка задачи.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        // Send user notification of failure, etc...
        Storage::disk('import')->append('log.txt', '[' . Carbon::now() . '] ' . $exception->getMessage());
        Storage::disk('import')->append('err_log.txt', '[' . Carbon::now() . '] ' . $exception->getMessage());
        info($exception->getMessage());
    }
}
