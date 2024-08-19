<?php

namespace Modules\Presale\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Configuration\Entities\Configuration;
use Modules\Invoice\Entities\Invoice;
use Modules\Invoice\Entities\InvoiceItemEndpoint;
use Modules\Presale\Entities\Endpoint;
use Modules\Saldo\Entities\MutasiSaldo;
use Modules\Saldo\Entities\Saldo;

class BayarEndpoint
{
    use SerializesModels;

    private $wilayah;
    private $data;
    private $endpoints;
    private $saldo;
    private $setting;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($wilayah, $data)
    {
        $this->wilayah = $wilayah;
        $this->data = $data;
        $this->find_endpoints();
        $this->setting = Configuration::find(1);
        $this->saldo = Saldo::where('company_id', $wilayah->company_id)->first();
        
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function find_endpoints()
    {
        $endpoint_id = []; 
        foreach ($this->data as $key => $endpoint) {
            $endpoint_id[] = $endpoint['end_point_id'];
        }

        $this->endpoints = Endpoint::findMany($endpoint_id);
    }

    public function potong_saldo() {
        $biaya = 0;
        $jumlah_end_poin = 0;
        $invoice = Invoice::create_invoice_endpoint($this->wilayah->company_id, $biaya, $jumlah_end_poin, $this->wilayah->name, null);
        foreach ($this->endpoints as $key => $endpoint) {
            $jumlah_end_poin+=1;
            if($this->saldo->saldo >= $this->setting->biaya_ep) {

                $this->saldo->decrement('saldo', $this->setting->biaya_ep);
                $deskripsi = "Pembayaran pembuatan endpoint dengan nama : " . $endpoint->nama_end_point;
                MutasiSaldo::createMutasi($this->saldo->saldo_id, $this->setting->biaya_ep,'credit', $deskripsi);
                
                Saldo::tambah_saldo(1,$this->setting->biaya_ep,$deskripsi);
                
                
                $endpoint->settlement_at = date('Y-m-d H:i:s');
                $endpoint->save();
                
                $biaya +=$this->setting->biaya_ep;
                
                InvoiceItemEndpoint::create([
                    'invoice_id' => $invoice->invoice_id,
                    'endpoint_id' => $endpoint->end_point_id,
                    'amount' => $this->setting->biaya_ep
                    ]);
            } else {
                $invoice->title = "Invoice Konfirmasi $jumlah_end_poin Presales wilayah " . $this->wilayah->name . " ( " . $this->wilayah->nama_osp . " )";
                $invoice->status = "settlement";
                $invoice->save();
                return abort(417, trans('presale::endpoint.message.payment failed'));
            }
        }


        $invoice->title = "Invoice Konfirmasi $jumlah_end_poin Presales wilayah " . $this->wilayah->name . " ( " . $this->wilayah->nama_osp . " )";
        $invoice->status = "settlement";
        $invoice->save();

        
        return $biaya;
    }
}