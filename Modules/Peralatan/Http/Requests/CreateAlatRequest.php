<?php

namespace Modules\Peralatan\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class CreateAlatRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'nama_alat' => 'required|max:30'
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
