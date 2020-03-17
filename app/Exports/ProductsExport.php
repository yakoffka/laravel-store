<?php

namespace App\Exports;

use App\Product;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ProductsExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize
{

    use Exportable;

    /*
     * @inheritDoc
     */
    public function startCell(): string
    {
        return 'A5';
    }

    /**
     * @inheritDoc
     */
    public function query()
    {
        return Product::query();
    }

    /**
     * @inheritDoc
     */
    public function map($product): array
    {
        $images = $product->images;
        $ArrayImages = [];
        if ( $images->count() > 0 ) {
            foreach ( $images as $image ) {
                $ArrayImages[] = $image->path . '/' . $image->name . '-l' . $image->ext;
            }
        }

        return [
            /* A */ $product->id,
            /* B */ $product->name,
            /* C */ $product->category->name,
            /* D */ Date::dateTimeToExcel($product->created_at),
            /* E */ Date::dateTimeToExcel($product->updated_at),
            /* F */ $product->price,
            /* G */ implode(', ', $ArrayImages),
        ];
    }

    /**
     * @inheritDoc
     */
    public function headings(): array
    {
        return [
            /* A */ '#',                // A
            /* B */ 'product name',     // B
            /* C */ 'category name',    // C
            /* D */ 'created_at',       // D
            /* E */ 'updated_at',       // E
            /* F */ 'price',            // F
            /* G */ 'images',           // G
        ];
    }

    /**
     * @inheritDoc
     */
    public function columnFormats(): array
    {
        return [
            /* A */
            /* B */
            /* C */
            /* D */ 'D' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            /* E */ 'E' => NumberFormat::FORMAT_DATE_DMYSLASH,
            /* F */ 'F' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
            /* G */
        ];
    }
}
