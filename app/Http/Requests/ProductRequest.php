<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'              => [
                'required',
                'string',
                isset( $this->product ) ? 'unique:products,name,'.$this->product->id : 'unique:products,name',
            ],
            'title'             => 'nullable|string',
            'slug'              => 'nullable|string',
            'manufacturer_id'   => 'required|integer',
            'category_id'       => 'required|integer',
            'seeable'           => 'nullable|string|in:on',
            'materials'         => 'nullable|string',
            'description'       => 'nullable|string',
            'modification'      => 'nullable|string',
            'workingconditions' => 'nullable|string',
            'imagespath'        => 'nullable|string',
            'date_manufactured' => 'nullable|string|min:10|max:10',
            'price'             => 'nullable|integer',
            'copy_img'          => 'nullable|integer',
        ];
    }

    public function authorize(): bool
    {
        if ( isset( $this->product ) ) {
            return auth()->user()->can('edit_products');
        }

        return auth()->user()->can('create_products');
    }
}
