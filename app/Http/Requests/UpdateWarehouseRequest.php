<?php

namespace App\Http\Requests;

use App\Models\Warehouse;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateWarehouseRequest
 */
class UpdateWarehouseRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return string[]
     */
    public function rules()
    {
        $rules = Warehouse::$rules;
        $rules['name'] = 'required|unique:warehouses,name,'.$this->route('warehouse');
        $rules['email'] = 'nullable|email|unique:warehouses,email,'.$this->route('warehouse');

        return $rules;
    }
}
