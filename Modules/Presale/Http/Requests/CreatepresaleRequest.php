<?php

namespace Modules\Presale\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class CreatepresaleRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'lat',
            'lon',
            'end_point_id',
            'class_id',
            'panjang_kabel',
            'provinces_id',
            'regencies_id',
            'districts_id',
            'villages_id',
            'address',
            'status_id',
            'wilayah_id'
            
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
