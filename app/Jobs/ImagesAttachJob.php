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

    // @todo: добавить параметры очереди: имя, задержку, кол-во попыток и прочие
    public int $tries = 3;

    /**
     * Create a new job instance.
     *
     * @param int $productId
     * @param string $imageNames
     */
    public function __construct(int $productId, string $imageNames)
    {
        $this->productId = $productId;
        $this->imageNames = $imageNames;
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

            if ( is_file($srcImgPath) ) {
                $imageNameWE = $adaptationImageService->createSet($srcImgPath, $this->productId, 'import');
                $this->attachImage($this->productId, $imageNameWE, $srcImageName);

                $mess = sprintf(
                    'success createSet() and attach() image %s to product #%d',
                    $srcImageName,
                    $this->productId
                );
                Storage::disk('import')->append('log.txt', '[' . Carbon::now() . '] ' . $mess);
            } else {
                $mess = sprintf('ERROR! Изображение "%s" для товара #%d отсутствует!', $srcImgPath, $this->productId);
                throw new RuntimeException($mess);
            }
        }
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
        info(__METHOD__ . '@' . __LINE__ . ': image ' . $srcImageName . ' attached to product #' . $productId . '. ');
    }
}
