<?php

namespace App\Http\Requests;

use App\Enums\SalesOrderStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class SalesOrderUpdateRequest extends FormRequest
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
            'total_additional_charges_price' => [
                'required',
                'integer',
                'min:0',
            ],
            'status' => [
                'required',
                'enum:' . SalesOrderStatusEnum::class,
            ],
            'paid' => [
                'required',
                'boolean',
            ],
        ];
    }
}
