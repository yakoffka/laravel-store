<?php

namespace App\Imports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Storage;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\{Jobs\ImagesAttachJob,
    Manufacturer,
    Product,
    Category,
    Services\ImportServiceInterface};
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Throwable;

class ProductImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsOnError
{
    /**
     * @param array $row
     *
     * @return Product
     */
    public function model(array $row): Product
    {
        $categoryId = $this->getCategoryId(explode(';', $row['category_chain']));
        $manufacturerId = $this->getManufacturerId($row);
        $product = $this->getProduct($row, $categoryId, $manufacturerId);

        dispatch((new ImagesAttachJob($product->id, $row['images'] ?? '', $row['code_1c'] ?? ''))->onQueue('high'));

        return $product;
    }

    /**
     * @param array $categoryChain
     * @param int $parentId
     * @return int
     */
    private function getCategoryId(array $categoryChain, int $parentId = 1): int
    {
        $categoryName = array_shift($categoryChain) ?? __('No name category');
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

            $mess = sprintf('Успешное создание категории %s (id = %d)', $category->name, $category->id);
            Storage::disk('import')->append(ImportServiceInterface::LOG, '[' . Carbon::now() . '] ' . $mess);
        }
        $category_id = $category->id;

        if (count($categoryChain) > 0) {
            $category_id = $this->getCategoryId($categoryChain, $category->id);
        }

        return $category_id;
    }

    /**
     * @param $row
     * @return null | int
     */
    private function getManufacturerId($row): ?int
    {
        if (!empty($row['manufacturer'])) {
            return Manufacturer::firstOrCreate(['name' => $row['manufacturer']],)->id;

        }
        return null;
    }

    /**
     * @param array $row
     * @param int $categoryId
     * @param $manufacturerId
     * @return Product|Model
     */
    private function getProduct(array $row, int $categoryId, $manufacturerId): Product
    {
        return Product::updateOrCreate(
            [
                'code_1c' => $row['code_1c'],
            ], [
            'name' => $row['name'],
            'manufacturer_id' => $manufacturerId,
            'vendor_code' => $row['vendor_code'] ?? null,
            'category_id' => $categoryId,
            'materials' => $row['materials'] ?? null,
            'publish' => true,
            'length' => $row['length'] ?? null,
            'width' => $row['width'] ?? null,
            'height' => $row['height'] ?? null,
            'diameter' => $row['diameter'] ?? null,
            'price' => $row['price'] ?? null,
            'promotional_price' => $row['promotional_price'] ?? null,
            'promotional_percentage' => $row['promotional_percentage'] ?? null,
            'remaining' => $row['remaining'] ?? null,
            'description' => $row['description'] ?? null,
            'modification' => $row['modification'] ?? null,
            'workingconditions' => $row['workingconditions'] ?? null,
            'date_manufactured' => $row['date_manufactured'] ?? null,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'code_1c' => 'required|string',
            'name' => 'required|string',
            'vendor_code' => 'nullable',
            'category_chain' => 'required|string',
            'length' => 'nullable|integer',
            'width' => 'nullable|integer',
            'height' => 'nullable|integer',
            'diameter' => 'nullable|integer',
            'price' => 'nullable|numeric',
            'promotional_price' => 'nullable|numeric',
            'promotional_percentage' => 'nullable|integer',
            'remaining' => 'nullable|integer',
            'images' => 'nullable|string',
            'description' => 'nullable|string',
            'manufacturer' => 'nullable|string',
            'materials' => 'nullable|string',
            'workingconditions' => 'nullable|string',
            'modification' => 'nullable|string',
            'date_manufactured' => 'nullable|date_format:Y-m-d',
        ];
    }

    /**
     * @inheritDoc
     *
     * $failure->row(); // row that went wrong
     * $failure->attribute(); // either heading key (if using heading row concern) or column index
     * $failure->errors(); // Actual error messages from Laravel validator
     * $failure->values(); // The values of the row that has failed.
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $mess = __METHOD__ . ': ' . $this->getMessagesWithValues($failure);
            Storage::disk('import')->append(ImportServiceInterface::LOG, '[' . Carbon::now() . '] ' . $mess);
            Storage::disk('import')->append(ImportServiceInterface::E_LOG, '[' . Carbon::now() . '] ' . $mess);
        }
    }

    /**
     * @inheritDoc
     */
    public function onError(Throwable $e)
    {
        $mess = __METHOD__ . ' ERROR: ' . $e->getMessage();
        Storage::disk('import')->append(ImportServiceInterface::LOG, '[' . Carbon::now() . '] ' . $mess);
        Storage::disk('import')->append(ImportServiceInterface::E_LOG, '[' . Carbon::now() . '] ' . $mess);
    }

    /**
     * @param Failure $failure
     * @return string
     */
    private function getMessagesWithValues(Failure $failure): string
    {
        $value = $failure->values()[$failure->attribute()];
        $code1C = $failure->values()['code_1c'];
        $arrMess = array_map(static function ($m) use ($value, $code1C) {
            return $m . " Невалидное значение: '$value'. 1С-код товара: '$code1C';";
        }, $failure->toArray());

        return implode(PHP_EOL, $arrMess);
    }
}
