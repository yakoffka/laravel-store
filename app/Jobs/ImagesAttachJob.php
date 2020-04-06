<?php

namespace App\Jobs;

use App\Image;
use App\Services\AdaptationImageService;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use RuntimeException;
use Storage;

class ImagesAttachJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $productId;
    private string $imageNames;
    private string $productCode1C;
    public int $tries = 3;

    /**
     * Create a new job instance.
     *
     * @param int $productId
     * @param string $imageNames
     * @param string $productCode1C
     */
    public function __construct(int $productId, string $imageNames, string $productCode1C)
    {
        $this->productId = $productId;
        $this->imageNames = $imageNames;
        $this->productCode1C = $productCode1C;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $adaptationImageService = new AdaptationImageService();
        $arrayImageNames = explode(';', $this->imageNames);

        foreach ($arrayImageNames as $srcImageName) {
            $srcImgPath = Storage::disk('import')->path('temp/images/' . $srcImageName);

            if (is_file($srcImgPath)) {
                $imageNameWE = $adaptationImageService->createSet($srcImgPath, $this->productId, 'import');
                $this->attachImage($this->productId, $imageNameWE, $srcImageName);

                $mess = sprintf('Успешная обработка изображения %s для товара #%d. 1С-код товара: \'%s\';',
                    $srcImageName,
                    $this->productId,
                    $this->productCode1C
                );
                Storage::disk('import')->append('log.txt', '[' . Carbon::now() . '] ' . $mess);
            } else {
                $mess = sprintf('WARNING: Отсутствует изображение "%s" для товара с id = \'%d\'. 1С-код товара: \'%s\';',
                    $srcImageName,
                    $this->productId,
                    $this->productCode1C
                );
                throw new RuntimeException($mess);
            }
        }
    }

    /**
     * Неудачная обработка задачи.
     *
     * @param Exception $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        // Send user notification of failure, etc...
        Storage::disk('import')->append('log.txt', '[' . Carbon::now() . '] ' . $exception->getMessage());
        Storage::disk('import')->append('err_log.txt', '[' . Carbon::now() . '] ' . $exception->getMessage());
    }

    /**
     * @param int $productId
     * @param $imageNameWE
     * @param $srcImageName
     */
    private function attachImage(int $productId, $imageNameWE, $srcImageName): void
    {
        Image::firstOrCreate([
            'product_id' => $productId,
            'slug' => $imageNameWE,
            'path' => '/images/products/' . $productId,
            'name' => $imageNameWE,
            'ext' => config('adaptation_image_service.res_ext'),
            'alt' => $imageNameWE,
            'sort_order' => 9,
            'orig_name' => $srcImageName,
        ]);
        // info(__METHOD__ . '@' . __LINE__ . ': image ' . $srcImageName . ' attached to product #' . $productId . '. ');
    }
}
