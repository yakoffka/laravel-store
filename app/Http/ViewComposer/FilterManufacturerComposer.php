<?php

namespace App\Http\ViewComposer;

use Illuminate\View\View;
use App\Manufacturer;

class FilterManufacturerComposer
{

    public function compose (View $view)
    {
        $manufacturers = Manufacturer::all();
        return $view->with('manufacturers', $manufacturers);
    }
}