<?php

namespace Modules\Presale\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\Company\Entities\Company;
use Modules\Configuration\Entities\Configuration;
use Modules\Core\Contracts\EntityIsChanging;
use Modules\Core\Events\AbstractEntityHook;
use Modules\Core\Traits\ConvertToCurrency;
use Modules\Invoice\Entities\Invoice;
use Modules\Invoice\Entities\InvoiceItemEndpoint;
use Modules\Presale\Emails\EndpointIsCreatedMail;
use Modules\Presale\Entities\Endpoint;
use Modules\Saldo\Entities\MutasiSaldo;
use Modules\Saldo\Entities\Saldo;
use Modules\User\Entities\Sentinel\User;
use Modules\Wilayah\Entities\Wilayah;

class EndpointIsCreated extends AbstractEntityHook implements EntityIsChanging
{
    use ConvertToCurrency;

    private $endpoint;
    private $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Endpoint $endpoint, array $data, $bayar = true)
    {
        $this->endpoint = $endpoint;
        $this->data = $data;
        parent::__construct($data);
        if($bayar) {
            $this->potongSaldo();
        }
        $this->log(Auth::user());
    }

    public function potongSaldo() {
        $setting    = Configuration::find(1);
        $wilayah    = Wilayah::find($this->getAttribute('wilayah_id'));
        $saldo      = Saldo::where('company_id', $wilayah->company_id)->first();
        $osp        = Company::find($wilayah->company_id);
        
        if($saldo->saldo > $setting->biaya_ep) {
            $saldo->decrement('saldo', $setting->biaya_ep);
            $deskripsi = "Pembayaran pembuatan endpoint dengan nama : " . $this->getAttribute('nama_end_point');
            MutasiSaldo::createMutasi($saldo->saldo_id, $setting->biaya_ep,'credit', $deskripsi);
            $this->send_email($setting->biaya_ep);
            Saldo::tambah_saldo(1,$setting->biaya_ep,$deskripsi);

            $this->setAttributes([
                'endpoint' => $this->endpoint->toArray(),
                'biaya' => $setting->biaya_ep,
                'emails' => (new Company())->admin_email($this->endpoint->company_id())
            ]);
            $this->endpoint->settlement_at = date('Y-m-d H:i:s');
            $this->endpoint->save();
            
            $invoice = Invoice::create_invoice_endpoint($wilayah->company_id, $setting->biaya_ep, $this->getAttribute('nama_end_point')."", $osp->name, 'settlement');

            InvoiceItemEndpoint::create([
                'invoice_id' => $invoice->invoice_id,
                'endpoint_id' => $this->endpoint->end_point_id,
                'amount' => $setting->biaya_ep
            ]);

            return true;
        }

        return abort(417, trans('presale::endpoint.message.payment failed'));
    }
    
    public function send_email($biaya) {
        $emails = (new Company())->admin_email($this->endpoint->company_id());

        $data = [
            "title" => trans('presale::endpoint.mail.endpoint created.title'),
            "url" => route('admin.presale.presale.index'),
            "biaya" => $this->rupiah($biaya),
            'data' => $this->endpoint
        ];

        Mail::to($emails)->send(new EndpointIsCreatedMail($data));
    }

    public function log(User $user) {
        $user->createLog(
            trans('presale::endpoint.logs.create.tipe'), 
            trans('presale::endpoint.logs.create.description', [
                'data' => $this->getAttribute('nama_end_point')
            ])
        );
    }
    
}
