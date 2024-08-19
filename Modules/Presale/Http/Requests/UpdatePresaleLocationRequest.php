<?php

namespace Modules\Presale\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class UpdatePresaleLocationRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'lat' => 'required',
            'lon' => 'required',
            'path' => 'required'
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
