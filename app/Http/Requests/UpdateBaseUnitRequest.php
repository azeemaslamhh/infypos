<?php

namespace App\Http\Requests;

use App\Models\BaseUnit;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateBaseUnitRequest
 */
class UpdateBaseUnitRequest extends FormRequest
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
        $rules = BaseUnit::$rules;
        $rules['name'] = 'required|unique:base_units,name,'.$this->route('base_unit');

        return $rules;
    }
}
