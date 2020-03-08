<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManufacturerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if ( isset($this->manufacturer) ) {
            return auth()->user()->can('edit_manufacturers');
        }
        return auth()->user()->can('create_manufacturers');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'              => [
                'required',
                'max:255',
                isset($this->manufacturer) ? 'unique:manufacturers,name,'.$this->manufacturer->id.',id' : 'unique:manufacturers,name'
            ],
            'description'       => 'nullable|string',
            'imagepath'         => 'nullable|string',
            'sort_order'        => 'required|string|max:1',
            'title'             => 'nullable|string',
        ];
    }
}
