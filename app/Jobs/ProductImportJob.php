<?php

namespace App\Jobs;

use App\Image;
use App\Traits\Yakoffka\ImageYoTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProductImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private int $productId;
    private string $srcImgPath;
    // @todo: добавить параметры очереди: имя, задержку, кол-во попыток и прочие

    /**
     * Create a new job instance.
     *
     * @param int $productId
     * @param string $srcImgPath
     */
    public function __construct(string $srcImgPath, int $productId)
    {
        $this->srcImgPath = $srcImgPath;
        $this->productId = $productId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $nameWithoutExtension = ImageYoTrait::saveImgSet($this->srcImgPath, $this->productId, 'import');
        $this->attachImage($this->productId, $nameWithoutExtension, $this->srcImgPath);
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
    }
}
