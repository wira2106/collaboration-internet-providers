<?php

namespace Modules\Invoice\Events;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Modules\Company\Entities\Company;
use Modules\Invoice\Entities\Invoice;
use Modules\Invoice\Entities\InvoiceItem;
use Modules\Saldo\Entities\Saldo;
use Modules\Wilayah\Entities\Pengajuan;


date_default_timezone_set('Asia/Jakarta');
setlocale(LC_TIME, 'id_ID.utf8');
class MonthlyCharge
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    private $data;
    private $invoice;
    private $data_pelanggan_per_wilayah;
    private $saldo_isp;
    private $total_tagihan_per_wilayah;
    private $items_per_wilayah;
    private $data_isp_osp_id_per_wilayah;
    private $invoice_no;
    private $osp;

    public function __construct(Collection $data)
    {
        $this->invoice_no = DB::table('invoice')->where('periode', 'like', date('Y-m').'%')->count() + 1; 
        $this->invoice = new Invoice();
        $this->data = $data;
        $this->data_pelanggan_per_wilayah = [];
        $this->total_tagihan_per_wilayah = [];
        $this->data_isp_osp_id_per_wilayah = [];
        $this->osp = [];
        $this->filter_data_pelanggan();
        $this->hitung_total_dan_items_per_wilayah();
        $this->getSaldoIsp();
        $this->getConfigPpnOsp();
        $this->create_invoice();
    }

    public function filter_data_pelanggan() {
        foreach ($this->data as $key => $pelanggan) {
            $this->data_pelanggan_per_wilayah[$pelanggan->request_wilayah_id][] = $pelanggan;
        }
    }

    public function getSaldoIsp() {
        foreach ($this->data_isp_osp_id_per_wilayah as $key => $value) {
            $this->saldo_isp[$key] = Saldo::where('company_id', $value['isp'])->first();
        }
    }

    public function getConfigPpnOsp() {
        foreach ($this->data_isp_osp_id_per_wilayah as $key => $value) {
            $this->osp[$key] = Company::find($value['osp']);
        }
    }

    public function hitung_total_dan_items_per_wilayah() {
        foreach ($this->data_pelanggan_per_wilayah as $request_wilayah_id => $pelanggans) {
            $total = 0;
            $items = [];
            $data_isp_osp_id_per_wilayah = [];
            foreach ($pelanggans as $key => $pelanggan) {
                // hitung total tagihan pada setiap wilayah 
                // yang dimiliki oleh isp
                $total += $pelanggan->biaya_mrc;

                // Cek apakah osp tersebut menerapkan ppn
                $osp = $this->getOsp($pelanggan->osp_id);
                
                $ppn = $osp->ppn === 1 ? 10 : 0;

                $dpp = $osp->ppn === 1 ? round($pelanggan->biaya_mrc / 1.1) : 0;
                $total_ppn = $pelanggan->biaya_mrc - $dpp;
                // masukkan pelanggan aktif yang ada di wilayah tsb               
                $items[] = [
                    'pelanggan_id' => $pelanggan->pelanggan_id,
                    'amount' => $pelanggan->biaya_mrc,
                    'dpp' => $dpp,
                    'total_ppn' => $total_ppn,
                    'ppn' => $ppn
                ];

                $data_isp_osp_id_per_wilayah['isp'] = $pelanggan->isp_id;
                $data_isp_osp_id_per_wilayah['osp'] = $pelanggan->osp_id;
                
            }

            $this->total_tagihan_per_wilayah[$request_wilayah_id] = $total;
            $this->items_per_wilayah[$request_wilayah_id] = $items;
            $this->data_isp_osp_id_per_wilayah[$request_wilayah_id] = $data_isp_osp_id_per_wilayah;
        }
    }

    private function getOsp($company_id) {
        if(isset($this->osp[$company_id])) return $this->osp[$company_id];

        $this->osp[$company_id] = Company::find($company_id);

        return $this->osp[$company_id];
    }

    public function create_invoice() {
        foreach ($this->data_pelanggan_per_wilayah as $request_wilayah_id => $pelanggans) {
            
            $isp_id = $this->data_isp_osp_id_per_wilayah[$request_wilayah_id]['isp'];
            $osp_id = $this->data_isp_osp_id_per_wilayah[$request_wilayah_id]['osp'];
            $periode = date('Y-m-d');
            $osp = $this->getOsp($osp_id);
            $ppn = $osp->ppn === 1 ? 10 : 0 ;
            $status = null;
            if($this->saldo_isp[$request_wilayah_id]['saldo'] >= $this->total_tagihan_per_wilayah[$request_wilayah_id]) {
                
                $status = 'pending';
                
                $total_tagihan = $this->total_tagihan_per_wilayah[$request_wilayah_id];
                $company_id = $this->saldo_isp[$request_wilayah_id]['company_id'];
                $created_at = date_create(date('Y-m-d'));
                $deskripsi = trans('invoice::invoices.tagihan bulanan pelanggan', [
                    'bulan' =>  strftime('%B %Y', $created_at->getTimestamp() )
                ]);

                $this->saldo_isp[$request_wilayah_id]->potong_saldo(
                    $company_id, 
                    $total_tagihan, 
                    $deskripsi
                );

                Saldo::tambah_saldo_dibekukan(1, $total_tagihan, $deskripsi );
            }

            
            $invoice = Invoice::create_invoice_pelanggan($isp_id,$osp_id,$request_wilayah_id,$periode,$this->generateInvoice(),$status, $ppn);

            
            $this->create_invoice_items($invoice, $request_wilayah_id);
            
        }
    }

    public function create_invoice_items($invoice, $request_wilayah_id) {
        // to invoice
        $total_amount = 0;
        $nominal_ppn = 0;
        $nominal_dpp = 0;

        foreach($this->items_per_wilayah[$request_wilayah_id] as $key => $item) {
            $item['invoice_id'] = $invoice->invoice_id;
            $this->items_per_wilayah[$request_wilayah_id][$key] = $item;

            // to invoice
            $total_amount += ($item['dpp'] + $item['total_ppn']);
            $nominal_dpp += $item['dpp'];
            $nominal_ppn += $item['ppn'];
        }
        $create = InvoiceItem::insert($this->items_per_wilayah[$request_wilayah_id]);

        // update amount, ppn, dpp and title on invoice
        $wilayah = Pengajuan::detailWilayahByPengajuan($request_wilayah_id);
        $nama_wilayah   = $wilayah->name;
        $title = "Invoice MRC pelanggan wilayah $nama_wilayah";
        Invoice::where('invoice_id',$invoice->invoice_id)
                ->update(['amount'=>$total_amount,
                        'nominal_ppn' => $nominal_ppn,
                        'nominal_dpp' => $nominal_dpp,
                        'title' => $title]);
        return $create;
    }

    public function generateInvoice() {
        $invoice = 'INV'.date('ymd').str_pad($this->invoice_no, 4, "0", STR_PAD_LEFT);

        $this->invoice_no++;

        return $invoice;
    } 


}
