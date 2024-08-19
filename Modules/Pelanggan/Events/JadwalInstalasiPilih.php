<?php

namespace Modules\Pelanggan\Events;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Modules\Pelanggan\Emails\JadwalInstalasiPilihMail;
use Illuminate\Queue\SerializesModels;
use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;
use Modules\Company\Entities\Company;
use Modules\Pelanggan\Entities\Pelanggan;
use Illuminate\Support\Facades\DB;

class JadwalInstalasiPilih extends AbstractEntityHook implements EntityIsChanging
{
    use SerializesModels;

    private $pelanggan;
    private $pengajuan_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public function __construct($pelanggan, $pengajuan_id)
    {
        $this->pelanggan = $pelanggan;
        $this->pengajuan_id = $pengajuan_id;
        $data = [
            'pelanggan_id' => $this->pelanggan->pelanggan_id,
            'pengajuan_id' => $pengajuan_id
        ];
        parent::__construct($data);
        $this->send_email();
    }

    public function send_email()
    {
        $company = Company::find($this->pelanggan->osp_id);
        $companyISP = Company::find($this->pelanggan->isp_id);
        $type = $company->type;
        $admin = $company->admin_email($this->pelanggan->osp_id);
        $jadwal_instalasi_pilih = DB::table('tanggal_pengajuan_instalasi')->where('pengajuan_id', $this->pengajuan_id)
            ->join('slot_instalasi', 'slot_instalasi.slot_id', '=', 'tanggal_pengajuan_instalasi.slot_id')->first();

        $this->pelanggan->withPacket();
        $this->pelanggan->withDataPresales();

        $jamStart = date('H:i', strtotime($jadwal_instalasi_pilih->jam_start));
        $jamEnd = date('H:i', strtotime($jadwal_instalasi_pilih->jam_end));
        $data = [
            'title' => trans('pelanggan::installations.mail.insert.pengajuan jadwal.title'),
            'name' => $companyISP->name,
            'type' => strtoupper($type),
            'tanggal' => date('d M Y', strtotime($jadwal_instalasi_pilih->tgl_instalasi)),
            'jam' => date('H:i', strtotime($jadwal_instalasi_pilih->jam_start)) . " sampai " . date('H:i', strtotime($jadwal_instalasi_pilih->jam_end)),
            'url' => route('admin.pelanggan.form.step', [
                'pelanggan' => $this->pelanggan->pelanggan_id
            ]),
            'pelanggan' => $this->pelanggan->toArray(),
            'slot' => $jadwal_instalasi_pilih->name . " ( $jamStart - $jamEnd )",
            'company_name' => $company->name
        ];



        Mail::to($admin)->send(new JadwalInstalasiPilihMail($data));
    }
}
