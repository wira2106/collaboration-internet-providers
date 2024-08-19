<?php

namespace Modules\Pelanggan\Events;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Modules\Pelanggan\Emails\JadwalInstalasiCreateMail;
use Illuminate\Queue\SerializesModels;
use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;
use Modules\Company\Entities\Company;
use Modules\Pelanggan\Entities\Pelanggan;

class JadwalInstalasiISCreated extends AbstractEntityHook implements EntityIsChanging
{
    use SerializesModels;

    private $pelanggan;
    private $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public function __construct($pelanggan, array $data)
    {
        $this->pelanggan = $pelanggan;
        $this->data = $data;
        parent::__construct($data);
        $this->send_email_isp();
        $this->send_email_osp();
    }

    public function getData($company)
    {

        return [
            'title' => trans('pelanggan::installations.mail.insert.pengajuan jadwal.title'),
            'name' => $company->name,
            'type' => strtoupper($company->type),
            'url' => route('admin.pelanggan.form.step', [
                'pelanggan' => $this->pelanggan->pelanggan_id
            ]),
            'jadwals' => $this->data['jadwals'],
            'pelanggan' => $this->pelanggan
        ];
    }
    public function send_email_isp()
    {
        $company = new Company;
        $company = Company::find($this->pelanggan->isp_id);
        $admin_email = $company->admin_email($this->pelanggan->isp_id);
        Mail::to($admin_email)->send(new JadwalInstalasiCreateMail($this->getData($company)));
    }

    public function send_email_osp()
    {
        $company = new Company;
        $company = Company::find($this->pelanggan->osp_id);
        $admin_email = $company->admin_email($this->pelanggan->osp_id);

        Mail::to($admin_email)->send(new JadwalInstalasiCreateMail($this->getData($company)));
    }
}
