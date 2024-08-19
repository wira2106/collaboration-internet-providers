<?php

namespace Modules\Company\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class CreateBiayaKabelRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'panjang_kabel' => "required|numeric",
            'biaya' => "required|numeric"
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
