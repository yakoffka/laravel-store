<?php

namespace App\Imports;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\{Product, Category, Image, Traits\Yakoffka\ImageYoTrait};
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Product
     */
    public function model(array $row): Product
    {
        // dump($row);
        $categoryId = $this->getCategoryId(explode(';', $row['categories']));

        $product = Product::firstOrCreate([
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
        // dump($product);

        $this->attachImages($product->id, $row['images']);

        return $product;
    }

    /**
     * @param array $categoryChain
     * @param int $parentId
     * @return int
     */
    private function getCategoryId(array $categoryChain, int $parentId = 1): int
    {
        /*$categoryName = array_pop($categoryChain);
        $category = Category::where('name', '=', $categoryName)
            ->first();

        if ( !$category ) { // @todo: привести к boolean!
            if ( count($categoryChain) > 0 ) {
                $parentId = $this->getCategoryId($categoryChain);
            } else {
                $parentId = 1;
            }

            $category = Category::create([
                'name' => $categoryName,
                'parent_id' => $parentId,
            ]);
        }*/

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
     * @param int $productId
     * @param string $imagePaths
     * @return bool
     * @throws FileNotFoundException
     */
    public function attachImages(int $productId, string $imagePaths): bool
    {
        if ( $imagePaths === '' ) {
            return true;
        }
        $a_paths = explode(';', $imagePaths);
        dump($a_paths);

        foreach ($a_paths as $imagePath) {

            $dirPath = Storage::disk('import')->path('temp/images/');
            $exists = Storage::disk('import')->exists('temp/images/' . $imagePath);
            if ( !$exists ) {
                return true;
            }
            // $image = Storage::disk('import')->get('temp/images/' . $imagePath);
            $fullImagePath = $dirPath . $imagePath;
//            dump($dirPath, $imagePath, $exists, $image);
//
//            dd('s1');
//            $image = storage_path('app/public') . str_replace(config('filesystems.disks.lfm.url'), '', $imagePath);

            // info('$image = ' . $image);
            // image re-creation
            $image_name = ImageYoTrait::saveImgSet($fullImagePath, $productId, 'lfm-mode');
            $originalName = basename($image_name);
            $path = '/images/products/' . $productId;

            // create record
            $images[] = Image::create([
                'product_id' => $productId,
                'slug' => Str::slug($image_name, '-'),
                'path' => $path,
                'name' => $image_name,
                'ext' => config('imageyo.res_ext'),
                'alt' => str_replace(strrchr($originalName, '.'), '', $originalName),
                'sort_order' => 9,
                'orig_name' => $originalName,
            ]);
        }

        /*if (!$this->isDirty() and !empty($images)) {
            $this->touch();
        }*/

         return true;
    }
}
