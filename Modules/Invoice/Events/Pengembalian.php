<?php

namespace Modules\Invoice\Events;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Modules\Configuration\Entities\Configuration;
use Modules\Core\Traits\ConvertToCurrency;
use Modules\Invoice\Entities\Invoice;
use Modules\Invoice\Entities\InvoiceItem;
use Modules\Invoice\Entities\RefundItem;
use Modules\Pelanggan\Http\Controllers\Api\PelangganController;
use Modules\Saldo\Entities\Saldo;


date_default_timezone_set('Asia/Jakarta');
setlocale(LC_TIME, 'id_ID.utf8');
class Pengembalian
{
    use SerializesModels, ConvertToCurrency;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    private $pelanggans;
    private $pelanggans2;
    private $total_hari;
    private $pengembalian_per_pelanggan;
    private $refund_items;
    private $invoice_items;
    private $total_refund_per_invoice;
    private $invoice;
    private $total_tagihan_per_invoice;

    public function __construct($pelanggans_suspend, $pelanggan_ticket_support)
    {
        $this->pelanggans = $pelanggans_suspend;
        $this->pelanggans2 = $pelanggan_ticket_support;
        $this->invoice_items = [];
        $this->invoice = [];
        $this->pengembalian_per_pelanggan = [];
        $this->refund_items = [];
        $this->total_refund_per_invoice = [];
        $this->total_tagihan_per_invoice = [];
        $this->total_hari = (int) date('t');
        $this->buat_refund_items_sla();
        $this->buat_refund_items_suspend();
        $this->invoice_bulan_ini();
        $this->get_invoice_items();
        $this->get_refund_items_dan_total_tagihan();
        $this->refunds();
        $this->hitung_komisi();
    }

    private function buat_refund_items_suspend()
    {
        foreach ($this->pelanggans as $key => $pelanggan) {
            $sisa_hari = $this->total_hari - (int) date('d', strtotime($pelanggan->tgl_suspend)) + 1;
            $amount = ($sisa_hari / $this->total_hari) * $pelanggan->biaya_mrc;

            $invoice_item = Invoice::findInvoiceItemByPelangganId($pelanggan->pelanggan_id);
            // dd($pelanggan->pelanggan_id, $invoice_item);
            RefundItem::updateOrCreate([
                'invoice_item_id' => $invoice_item->invoice_item_id,
                'refund_type' => 'suspend',
            ], [
                'status' => 'pending',
                'refund_amount' => ceil($amount),
                'description' => trans('invoice::invoices.refund item suspend', [
                    'jumlah' => $this->rupiah(ceil($amount)),
                    'name' => $pelanggan->nama_pelanggan
                ]),
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    private function buat_refund_items_sla()
    {
        foreach ($this->pelanggans2 as $key => $pelanggan) {
            $total_jam_bulan  = ((int)date('t') * 24) * 60;
            $total_jam_sla    = (int)round(($pelanggan->sla / 100) * $total_jam_bulan);
            $total_jam_mati   = $pelanggan->jam_mati;

            $persentaseJamMati      = ($total_jam_mati / $total_jam_bulan) * 100;
            $persentaseToleransi    = 100 - $pelanggan->sla;
            $selisih_tolerasi       = $persentaseJamMati - $persentaseToleransi;

            if ($selisih_tolerasi > 0) {
                PelangganController::getHistorySLApelanggan($pelanggan->pelanggan_id);
                $potongan_sla = floor(($selisih_tolerasi / 100) * $pelanggan->biaya_mrc);
                // lama jam mati
                $lama_mati = "";
                $jam = floor($total_jam_mati / 60);
                $menit = $total_jam_mati % 60;

                if ($jam != 0) {
                    $lama_mati .= $jam . " Jam ";
                }
                if ($menit != 0) {
                    $lama_mati .= $menit . " Menit";
                }
                // buat refund invoice
                $invoice_item = Invoice::findInvoiceItemByPelangganId($pelanggan->pelanggan_id);
                if ($invoice_item !== null) {
                    RefundItem::updateOrCreate([
                        'invoice_item_id' => $invoice_item->invoice_item_id,
                        'refund_type' => 'sla',
                    ], [
                        'status' => 'pending',
                        'refund_amount' => $potongan_sla,
                        'description' => trans('invoice::invoices.refund item sla', [
                            'jumlah' => $this->rupiah($potongan_sla),
                            'name' => $pelanggan->nama_pelanggan,
                            'waktu' => $lama_mati
                        ]),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }
    }

    private function invoice_bulan_ini()
    {
        $select = [
            'invoice.*',
            'companies.name as nama_isp',
            'wilayah.name as wilayah_name'
        ];
        $invoices = [];
        $this->invoice =  Invoice::select($select)
            ->where('invoice.created_at', 'like', date('Y-m') . "%")
            ->where('invoice.status', 'pending')
            ->join('companies', 'companies.company_id', 'invoice.isp_id')
            ->join('invoice_pelanggan', 'invoice_pelanggan.invoice_id', 'invoice.invoice_id')
            ->join('request_wilayah', 'request_wilayah.request_wilayah_id', 'invoice_pelanggan.request_wilayah_id')
            ->join('wilayah', 'wilayah.wilayah_id', 'request_wilayah.wilayah_id')
            ->get();

        foreach ($this->invoice as $key => $invoice) {
            $invoices[$invoice->invoice_id] = $invoice;
        }

        $this->invoice = $invoices;
    }

    private function get_invoice_items()
    {
        foreach ($this->invoice as $key => $invoice) {
            $this->invoice_items[$invoice->invoice_id] = InvoiceItem::where('invoice_id', $invoice->invoice_id)->get();
        }
    }

    public function get_refund_items_dan_total_tagihan()
    {
        foreach ($this->invoice_items as $invoice_id => $invoice_items) {
            $this->refund_items[$invoice_id] = Invoice::select('refund_item.*')
                ->join('invoice_item', 'invoice_item.invoice_id', 'invoice.invoice_id')
                ->join('refund_item', 'refund_item.invoice_item_id', 'invoice_item.invoice_item_id')
                ->where('invoice.invoice_id', $invoice_id)
                ->where('refund_item.status', 'pending')
                ->get();

            $total = 0;
            foreach ($invoice_items as $key => $invoice_item) {
                $total += $invoice_item->amount;
            }

            $this->total_tagihan_per_invoice[$invoice_id] = $total;
        }
    }

    private function refunds()
    {
        foreach ($this->refund_items as $invoice_id => $refund_items) {
            foreach ($refund_items as $key => $refund_item) {
                $amount = ceil($refund_item->refund_amount);
                Saldo::tambah_saldo($this->invoice[$invoice_id]->isp_id, $amount, $refund_item->description);
                Saldo::potong_saldo(1, $amount, $refund_item->description);
                $this->total_refund_per_invoice[$invoice_id] =
                    isset($this->total_refund_per_invoice[$invoice_id])
                    ? $this->total_refund_per_invoice[$invoice_id] + $amount
                    : $amount;

                event(new RefundsToIsp($this->invoice[$invoice_id]->isp_id, $refund_item));
            }
        }
    }

    public function hitung_komisi()
    {
        $setting = Configuration::find(1);
        foreach ($this->invoice as $invoice_id => $invoice) {
            $total_tagihan = $this->total_tagihan_per_invoice[$invoice_id];
            $total_refunded = isset($this->total_refund_per_invoice[$invoice_id])
                ? $this->total_refund_per_invoice[$invoice_id]
                : 0;
            $komisi_openaccess = count($this->invoice_items[$invoice_id]) * $setting->admin_fee;
            $komisi_osp = ceil($total_tagihan - $total_refunded - $komisi_openaccess);

            $created_at = date_create(date('Y-m-d',  strtotime($invoice->created_at)));

            Saldo::potong_saldo_dibekukan(1, $total_tagihan);
            Saldo::potong_saldo(1, $komisi_osp, trans('invoice::invoices.tagihan bulanan pelanggan dari isp', [
                'bulan' => strftime('%B %Y', $created_at->getTimestamp()),
                'isp' => $invoice->nama_isp,
                'wilayah' => $invoice->wilayah_name
            ]));
            Saldo::tambah_saldo($invoice->osp_id, $komisi_osp, trans('invoice::invoices.tagihan bulanan pelanggan dari isp', [
                'bulan' => strftime('%B %Y', $created_at->getTimestamp()),
                'isp' => $invoice->nama_isp,
                'wilayah' => $invoice->wilayah_name
            ]));
            $invoice->status = 'settlement';
            $invoice->settlement_at = date('Y-m-d H:i:s');
            $invoice->save();
        }
    }
}
