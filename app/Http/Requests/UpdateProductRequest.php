<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        $rules = Product::$rules;
        $rules['code'] = 'required|unique:products,code,'.$this->route('product');

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'code.unique' => __('messages.error.code_taken'),
        ];
    }
}
