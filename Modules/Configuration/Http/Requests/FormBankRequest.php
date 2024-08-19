<?php

namespace Modules\Configuration\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;
use Modules\Configuration\Entities\Bank;
use Illuminate\Validation\Rule;

class FormBankRequest extends BaseFormRequest
{
    public function rules()
    {
        if ($this->bank_id != null) {
            $data = Bank::find($this->bank_id);

            return [
                'name' => "required|unique:bank,namaBank,".$data->namaBank.",namaBank"
            ];            
        }else{
            return [
                'name' => "required|unique:bank,namaBank"
            ];            
        }
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
