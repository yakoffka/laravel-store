<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if ( isset($this->order) ) {
            return auth()->user()->can('edit_orders');
        }

        return auth()->user()->can('create_orders');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        if ( isset($this->order) ) {
            return [
                'status_id' => 'required|integer|exists:statuses,id',
            ];
        }

       return [
            'comment' => 'nullable|string|max:1000',
        ];
    }
}
