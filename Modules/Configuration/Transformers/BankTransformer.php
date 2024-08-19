<?php

namespace Modules\Configuration\Transformers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
class BankTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'bank_id' => $this->bank_id,
            'namaBank' => $this->namaBank,
            'logo' => $this->logo,
            'logo_url' => url('/uploadgambar/'.$this->logo),
            'label' => $this->namaBank,
            'value' => $this->bank_id,
            'created_at' => date('d M Y H:i:s', strtotime($this->created_at)),
            'urls' => [
                'delete_url' => Auth::user()->hasAccess('configuration.bank.destroy') ? route('api.configuration.bank.destroy', $this->bank_id) : null,                
                'edit_url' => Auth::user()->hasAccess('configuration.bank.edit') ? true : false
            ],
        ];
    }
}