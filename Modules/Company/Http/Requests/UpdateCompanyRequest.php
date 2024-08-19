<?php

namespace Modules\Company\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => "required",
            'provinces_id' => "required",
            'regencies_id' => "required",
            'districts_id' => "required",
            'villages_id' => "required",
            'address' => "required",
            'pop_address' => "required",
            'post_code' => "required"
        ];
    }

    public function translationRules()
    {
        return [];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [];
    }

    public function translationMessages()
    {
        return [];
    }
}
