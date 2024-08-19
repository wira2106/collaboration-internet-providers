<?php

namespace Modules\Saldo\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class TopupTransformer extends Resource
{
    public function toArray($request)
    {
        return [
            'topup_id' => $this->topup_id,
            'company_id' => $this->company_id,
            'mode' => ucfirst($this->mode),
            'bank_id' => $this->bank_id,
            'bank_account_id' => $this->bank_account_id,
            'amount' => number_format($this->amount),
            'nama_perusahaan' => $this->nama_perusahaan,
            'rekening_pengirim' => $this->rekening_pengirim,
            'atas_nama' => $this->atas_nama,
            'bukti_transfer' => url('/uploadgambar') . '/' . $this->bukti_transfer,
            'status' => $this->status,
            'tgl_transfer' => date('d M Y', strtotime($this->tgl_transfer)),
            'created_at' => date('d M Y', strtotime($this->created_at)),
            'expired_at' => $this->expired_at == null ? '-': date('d M Y', strtotime($this->expired_at)),
            'keterangan' => $this->keterangan,
            'nama_bank_penerima' => $this->nama_bank_penerima,
            'nama_bank_pengirim' => $this->nama_bank_pengirim,
            'rekening_penerima' => $this->rekening_penerima,
            'nama_penerima' => $this->nama_penerima,
            'brivaNo' => $this->brivaNo,
            'custCode' => $this->custCode
        ];
    }
}
