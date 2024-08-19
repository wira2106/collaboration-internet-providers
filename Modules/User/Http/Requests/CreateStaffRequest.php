<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\User\Entities\Sentinel\User;

class CreateStaffRequest extends FormRequest
{
    public function rules()
    {

        $valid = [
            'full_name' => 'required',
            'phone' => 'required',
            'email' => 'required|unique:users,email',            
        ];

        if ($this->user_id != null) {
            $data = User::find($this->user_id);
            $valid["email"] = $valid["email"].",".$data->email.",email";
            if ($this->password != null && $this->password != "") {
                $valid["password"] = "min:3";
            }
        }else{
            $valid["password"] = "required|min:3|";
        }
        return $valid;
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [];
    }
}
