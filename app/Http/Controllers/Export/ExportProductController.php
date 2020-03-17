<?php

namespace App\Http\Controllers\Export;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\ProductsExport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Handle the incoming request.
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function __invoke(Request $request)
    {
        abort_if ( auth()->user()->cannot('view_users'), 403 );
        // return Excel::download(new ProductsExport, 'export_products.xlsx');
        return (new ProductsExport())->download('export_products_' . date('Y-m-d_H-i') . '.xlsx');
        // return (new ProductsExport())->download('export_products.csv');

    }
}
