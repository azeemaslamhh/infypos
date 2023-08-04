<?php

namespace App\Http\Requests;

use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;

class CreateLanguageRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return Language::$rules;
    }

    /**
     *
     *
     * @return array
     */
    public function messages()
    {
        $messages['iso_code.required'] = 'The ISO Code field is required.';
        $messages['iso_code.unique'] = 'The ISO code has already been taken.';

        return $messages;
    }
    
}
