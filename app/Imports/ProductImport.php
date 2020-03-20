<?php

namespace App\Imports;

use Artisan;
use Storage;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\{Jobs\ProductImportJob, Product, Category, Image, Traits\Yakoffka\ImageYoTrait};
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Product
     */
    public function model(array $row): Product
    {
        info(__METHOD__ . ' ');
//        $this->runQueueWork();

        $categoryId = $this->getCategoryId(explode(';', $row['categories']));
        $product = $this->getProduct($row, $categoryId);
        $this->processingImages($product->id, $row['images'] ?? '');

        return $product;
    }

    /**
     * @param array $categoryChain
     * @param int $parentId
     * @return int
     */
    private function getCategoryId(array $categoryChain, int $parentId = 1): int
    {
        info(__METHOD__ . ' ');
        $category = Category::firstOrCreate([
            'name' => array_shift($categoryChain),
            'parent_id' => $parentId,
            'publish' => true,
        ]);
        $category_id = $category->id;

        if (count($categoryChain) > 0) {
            $category_id = $this->getCategoryId($categoryChain, $category->id);
        }

        return $category_id;
    }

    /**
     * @param array $row
     * @param int $categoryId
     * @return Product|Model
     */
    private function getProduct(array $row, int $categoryId): Product
    {
        info(__METHOD__ . ' ');
        // @todo: проверить наличие товара в базе по code_1c;
        // @todo: при отсутствии - создать, при наличии - обновить!
        return Product::firstOrCreate([
            'name' => $row['name'],
            'vendor_code' => $row['vendor_code'],
            'code_1c' => $row['code_1c'],
            'category_id' => $categoryId,
            'publish' => true,
            'length' => $row['length'],
            'width' => $row['width'],
            'height' => $row['height'],
            'diameter' => $row['diameter'],
            'price' => $row['price'],
            'promotional_price' => $row['promotional_price'],
            'promotional_percentage' => $row['promotional_percentage'],
            'remaining' => $row['remaining'],
            'description' => $row['description'],
        ]);
    }

    /**
     * @param int $productId
     * @param string $imageNames
     * @return bool
     */
    public function processingImages(int $productId, string $imageNames): bool
    {
        info(__METHOD__ . ' $productId = ' . $productId );
        $arrayImageNames = explode(';', $imageNames);

        foreach ($arrayImageNames as $srcImageName) {
            $srcImgPath = Storage::disk('import')->path('temp/images/' . $srcImageName);

            if ( is_file($srcImgPath) ) {
                /*$nameWithoutExtension = ImageYoTrait::saveImgSet($srcImgPath, $productId, 'lfm-mode');
                $this->attachImage($productId, $nameWithoutExtension, $srcImageName);*/
                dispatch(new ProductImportJob($srcImgPath, $productId));
                // $this->runQueueWork();
                info(__METHOD__ . ' exit.' . "\n");
            }
        }

        return true;
    }

    /**
     * restarting the queue to make sure they are started
     */
    private function runQueueWork(): void
    {
        info(__METHOD__ . ' before');
        /*if ( !empty(config('custom.exec_queue_work')) ) {
            info(
                __METHOD__ . ' '
                . config('custom.exec_queue_work') . ': '
                . exec(config('custom.exec_queue_work'))
            );
        }*/
        // exec(config('custom.exec_queue_work'));
        Artisan::call('queue:work');
        info(__METHOD__ . ' after');
    }

    /*
     * @param int $productId
     * @param $nameWithoutExtension
     * @param $srcImageName
     */
    /*private function attachImage(int $productId, $nameWithoutExtension, $srcImageName): void
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
    }*/
}
