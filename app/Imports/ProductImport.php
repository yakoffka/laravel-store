<?php

namespace App\Imports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Storage;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\{Jobs\ProductImportJob, Product, Category};
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductImport implements ToModel, WithHeadingRow/*, WithBatchInserts*//*, WithChunkReading, ShouldQueue*//*, WithEvents*/
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
        // @todo: проверить наличие товара в базе по code_1c;
        // @todo: при отсутствии - создать, при наличии - обновить!
        return Product::updateOrCreate(
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
                dispatch(new ProductImportJob($srcImgPath, $productId));
            } else {
                dump($srcImgPath . ' не существует!');
            }
        }

        return true;
    }


    public function batchSize(): int
    {
        return 10;
    }

    public function chunkSize(): int
    {
        return 10;
    }

    /**
     * @inheritDoc
     */
    public function registerEvents(): array
    {
        return [
            // ImportFailed::class => function(ImportFailed $event) {
            //     $this->importedBy->notify(new ImportHasFailedNotification);
            // },
        ];
    }
}
