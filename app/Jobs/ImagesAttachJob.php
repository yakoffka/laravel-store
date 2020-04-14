<?php

namespace App\Jobs;

use App\Image;
use App\Services\AdaptationImageService;
use App\Services\ImportServiceInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
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
     * @throws Exception
     */
    public function handle(): void
    {
        $oldImages = Image::get()->where('product_id', '=', $this->productId);
        $arrayNamesNewImages = $this->AttachNewImages($oldImages);
        $this->deleteUnnecessaryImages($oldImages, $arrayNamesNewImages);
    }

    /**
     * Неудачная обработка задачи.
     *
     * @param Exception $exception
     * @return void
     */
    public function failed(Exception $exception): void
    {
        $mess = __METHOD__ . ': ' . '[' . Carbon::now() . '] ' . $exception->getMessage();
        Storage::disk('import')->append(ImportServiceInterface::LOG, $mess);
        Storage::disk('import')->append(ImportServiceInterface::E_LOG, $mess);
    }

    /**
     * @param int $productId
     * @param $imageNameWE
     * @param $srcImageName
     */
    private function attachImage(int $productId, string $imageNameWE, string $srcImageName): void
    {
        if (!empty($productId) && !empty($imageNameWE) && !empty($srcImageName)) {
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
        }
    }

    /**
     * @param $srcImageName
     */
    private function handleImage($srcImageName): void
    {
        $adaptationImageService = new AdaptationImageService();
        $srcImgPath = Storage::disk('import')->path('images/' . $srcImageName);

        if (is_file($srcImgPath)) {
            $imageNameWE = $adaptationImageService->createSet($srcImgPath, $this->productId, 'import');
            $this->attachImage($this->productId, $imageNameWE, $srcImageName);

            $mess = sprintf('Успешная обработка и прикрепление изображения %s для товара #%d. 1С-код товара: \'%s\';',
                $srcImageName,
                $this->productId,
                $this->productCode1C
            );
            Storage::disk('import')->append(ImportServiceInterface::LOG, '[' . Carbon::now() . '] ' . $mess);

        } else {
            $mess = sprintf('WARNING: Отсутствует изображение "%s" для товара с id = \'%d\'. 1С-код товара: \'%s\';',
                $srcImageName,
                $this->productId,
                $this->productCode1C
            );
            throw new RuntimeException($mess);
        }
    }

    /**
     * @param Collection $oldImages
     * @param array $arrayNamesNewImages
     */
    private function deleteUnnecessaryImages(Collection $oldImages, array $arrayNamesNewImages): void
    {
        $unnecessaryImages = $oldImages->filter(static function (Image $image) use ($arrayNamesNewImages) {
            return !in_array($image->orig_name, $arrayNamesNewImages, true);
        });

        foreach ($unnecessaryImages as $image) {
            $mess = 'Открепление и удаление изображения orig_name = ' . $image->orig_name;
            Storage::disk('import')->append(ImportServiceInterface::LOG, '[' . Carbon::now() . '] ' . $mess);
            $image->delete();
        }
    }

    /**
     * @param Collection $oldImages
     * @return mixed
     */
    private function AttachNewImages(Collection $oldImages): array
    {
        if ($this->imageNames !== '') {
            $arrayNamesNewImages = array_unique(explode(';', $this->imageNames));
            foreach ($arrayNamesNewImages as $srcImageName) {
                if (!$oldImages->contains('orig_name', $srcImageName)) { // @todo: добавить проверку размера существующего изображения
                    $this->handleImage($srcImageName);
                }
            }
        }
        return $arrayNamesNewImages ?? [];
    }
}
