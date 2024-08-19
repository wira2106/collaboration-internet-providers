<?php

namespace Modules\Peralatan\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class CreateBarangRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'nama_barang' => 'required|max:30',
            'tipe_qty' => 'required',
            'harga_per_pcs' => 'required|number',
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
