<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Product;
use App\Traits\Yakoffka\ImageYoTrait; // Traits???
use Exception;


class RewatermarkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    protected int $product_id;

    /**
     * Create a new job instance.
     *
     * @param int $product_id
     */
    public function __construct(int $product_id)
    {
        $this->product_id = $product_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle(): void
    {
        $product = Product::find($this->product_id);
        $images = $product->images;

        foreach( $images as $image ) {
            $path_image = storage_path() . config('imageyo.dirdst_origin') . '/' . $product->id . '/' . $image->name . '-origin' . $image->ext;
            $name_img = ImageYoTrait::saveImgSet($path_image, $product->id, 'rewatermark');
            if ( !$name_img ) {
                throw new Exception('не удалось преобразовать изображения для товара с $product->id = ' . $product->id);
            }
        }
    }

    /**
     * @param Exception $exception
     */
    public function failed(Exception $exception): void
    {
         info(__method__ . '@' . __line__ . $exception);
        // session()->flash('message', 'ReWatermarks is completed. execute time: ' . $time);
    }
}
