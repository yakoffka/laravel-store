<?php

namespace App\Jobs;

use App\Image;
use App\Traits\Yakoffka\ImageYoTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Storage;

class ImageAttachImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $productId;
    private string $imageNames;

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
        $arrayImageNames = explode(';', $this->imageNames);

        foreach ($arrayImageNames as $srcImageName) {
            $srcImgPath = Storage::disk('import')->path('temp/images/' . $srcImageName);

            if ( is_file($srcImgPath) ) {
                $nameWithoutExtension = ImageYoTrait::saveImgSet($srcImgPath, $this->productId, 'import');
                $this->attachImage($this->productId, $nameWithoutExtension, $srcImageName);
            }
        }
    }

    /**
     * @param int $productId
     * @param $nameWithoutExtension
     * @param $srcImageName
     */
    private function attachImage(int $productId, $nameWithoutExtension, $srcImageName): void
    {
        Image::firstOrCreate([
            'product_id' => $productId,
            'slug' => $nameWithoutExtension,
            'path' => '/images/products/' . $productId,
            'name' => $nameWithoutExtension,
            'ext' => config('imageyo.res_ext'),
            'alt' => $nameWithoutExtension,
            'sort_order' => 9,
            'orig_name' => $srcImageName,
        ]);
        info(__METHOD__ . '@' . __LINE__ . ': image ' . $srcImageName . ' attached to product #' . $productId . '. ');
    }
}
