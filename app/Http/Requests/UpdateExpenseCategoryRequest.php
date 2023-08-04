<?php

namespace App\Http\Requests;

use App\Models\ExpenseCategory;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateExpenseCategoryRequest
 */
class UpdateExpenseCategoryRequest extends FormRequest
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
        $rules = ExpenseCategory::$rules;
        $rules['name'] = 'required|unique:expense_categories,name,'.$this->route('expense_category');

        return $rules;
    }
}
