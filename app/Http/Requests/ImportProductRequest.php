<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->user()->can('view_products');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            // 'import_file' => 'nullable|file|size:512|mimetypes:application/csv,text/csv,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet|mimes:csv',
            // 'import_archive' => 'nullable|file|size:512|mimetypes:application/zip,application/gzip|mimes:zip',
            // 'import_file' => 'nullable|file', // @todo: не срабатывает правило валидации mimetypes:application/csv,text/csv
            // https://stackoverflow.com/questions/35570540/laravel-mime-issue-while-validating-csv-file
            // https://habr.com/en/sandbox/94463/
            // + добавить валидацию полей .csv https://laravel.demiart.ru/ways-of-laravel-validation/
            // 'import_archive' => 'nullable|file|mimetypes:application/zip|mimes:zip',
            'import_file' => 'nullable|string',
            'import_archive' => 'nullable|string',
        ];
    }
}
