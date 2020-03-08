<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if ( isset( $this->category ) ) {
            return auth()->user()->can('edit_categories');
        }

        return auth()->user()->can('create_categories');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'          => [
                'required',
                'string',
                'max:255',
                isset( $this->category ) ? 'unique:categories,name,'.$this->category->id.',id' : 'unique:categories,name',
            ],
            'title'         => 'nullable|string|max:255',
            'slug'          => 'nullable|string|max:255',
            'description'   => 'nullable|string|max:65535',
            'imagepath'     => 'nullable|string|max:255',
            'parent_id'     => 'required|integer|max:255',
            'sort_order'    => 'required|string|max:1',
            'publish'       => 'nullable|string|in:on',
        ];
    }
}
