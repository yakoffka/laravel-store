<?php

namespace App\Imports;

use Storage;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\{Image, Jobs\ImageAttachImportJob, Product, Category, Traits\Yakoffka\ImageYoTrait};
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
        $categoryId = $this->getCategoryId(explode(';', $row['category_chain']));
        $product = $this->getProduct($row, $categoryId);
        //$this->processingImages($product->id, $row['images'] ?? '');
        dispatch(new ImageAttachImportJob($product->id, $row['images'] ?? ''));

        return $product;
    }

    /**
     * @param array $categoryChain
     * @param int $parentId
     * @return int
     */
    private function getCategoryId(array $categoryChain, int $parentId = 1): int
    {
        $categoryName = array_shift($categoryChain);
        $category = Category::all()
            ->where('name', '=', $categoryName)
            ->where('parent_id', '=', $parentId)
            ->first();
        if ($category === null) {
            $category = Category::create([
                'name' => $categoryName,
                'parent_id' => $parentId,
                'publish' => true,
            ]);
            info(__METHOD__ . '@' . __LINE__ . ': created category #' . $category->id . ' ' . $category->name);
        }
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
        // @todo: проверить наличие товара в базе по code_1c;
        // @todo: при отсутствии - создать, при наличии - обновить!
        $product = Product::updateOrCreate(
        [
            'code_1c' => $row['code_1c'],
        ],[
            'name' => $row['name'],
            'vendor_code' => $row['vendor_code'],
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
        info(__METHOD__ . '@' . __LINE__ . ': created product #' . $product->id . ' ' . $product->name);
        return $product;
    }

    /**
     * @param int $productId
     * @param string $imageNames
     * @return bool
     */
    public function processingImages(int $productId, string $imageNames): bool
    {
        $arrayImageNames = explode(';', $imageNames);

        foreach ($arrayImageNames as $srcImageName) {
            $srcImgPath = Storage::disk('import')->path('temp/images/' . $srcImageName);

            if ( is_file($srcImgPath) ) {
                $nameWithoutExtension = ImageYoTrait::saveImgSet($srcImgPath, $productId, 'import');
                $this->attachImage($productId, $nameWithoutExtension, $srcImageName);
            }
        }

        return true;
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
