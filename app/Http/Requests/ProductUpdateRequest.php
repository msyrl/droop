<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
            ],
            'sku' => [
                'nullable',
                'string',
            ],
            'price' => [
                'required',
                'integer',
                'min:1',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'image' => [
                'nullable',
                'image',
            ],
            'gallery' => [
                'nullable',
                'array',
            ],
            'gallery.*' => [
                'nullable',
                'image',
            ],
            'deleted_image_ids' => [
                'nullable',
                'array',
            ],
            'deleted_image_ids.*' => [
                'nullable',
                'string',
            ],
        ];
    }
}
