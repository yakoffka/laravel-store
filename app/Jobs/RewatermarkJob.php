<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\Log;
use App\Product;
use App\Traits\Yakoffka\ImageYoTrait; // Traits???
use Exception;


class RewatermarkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    // protected $image_name;
    // protected $product_id;
    // protected $product;
    protected $product_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(/*$image_name, $product_id*/ /*Product $product*/ $product_id)
    {
        // $this->image_name =$image_name, $product_id $image_name;
        // $this->product_id = $product_id;
        // $this->product = $product;
        $this->product_id = $product_id;
        // info(__method__ . '@' . __line__ . ' $this->product_id = ' . $this->product_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        info(__method__ . '@' . __line__ . ' $this->product_id = ' . $this->product_id);

        $product = Product::find($this->product_id);

        $image = storage_path() . config('imageyo.dirdst_origin') . '/' . $product->id . '/' . $product->image . '_origin' . config('imageyo.res_ext');

        $name_img = ImageYoTrait::saveImgSet($image, $product->id, 'rewatermark');
        info(__method__ . '@' . __line__ . ' $name_img = ' . $name_img);

        if ( !$name_img ) {
            throw new Exception('не удалось преобразовать изображения для товара с $product->id = ' . $product->id);
        }
    }

    public function failed(Exception $exception)
    {
        // info(__method__ . '@' . __line__ . $exception);
        // session()->flash('message', 'Rewatermarks is complited. execute time: ' . $time);
    }
}
