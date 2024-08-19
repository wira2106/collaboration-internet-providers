<?php

namespace Modules\Company\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class CreateCompanyRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'name' => "required",
            'type' => "required",
            'provinces_id' => "required",
            'regencies_id' => "required",
            'districts_id' => "required",
            'villages_id' => "required",
            'address' => "required",
            'pop_address' => "required",
            'post_code' => "required",
            'display_name' => "required",
            'total_endpoint' => "required",
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
