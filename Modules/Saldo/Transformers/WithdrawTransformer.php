<?php

namespace Modules\Saldo\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class WithdrawTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'tarik_saldo_id' => $this->tarik_saldo_id,
            'company_id' => $this->company_id,
            'nama_perusahaan' => $this->nama_perusahaan,
            'bank_account_id' => $this->bank_account_id,
            'amount' => number_format($this->amount),
            'tarik' => $this->amount,
            'status' => $this->status,
            'keterangan' => $this->keterangan,
            'bukti_transfer' => url('/uploadgambar') . '/' . $this->bukti_transfer,
            'created_at' => date('d-M-Y', strtotime($this->created_at)),
            'atas_nama' => $this->atas_nama,
            'nama_bank_penerima' => $this->nama_bank_penerima,
            'rekening_penerima' => $this->rekening_penerima,
        ];
    }
}
