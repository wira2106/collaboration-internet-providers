<?php

namespace Modules\Invoice\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Company\Entities\Company;
use Modules\Core\Traits\ConvertToCurrency;
use Modules\Invoice\Entities\Interfaces\InvoiceInterface;
use Modules\Invoice\Events\MonthlyCharge;
use Modules\Invoice\Events\Pengembalian;
use Modules\Pelanggan\Entities\Pelanggan;
use Modules\Ticket\Entities\TicketSla;
use Modules\Invoice\Entities\InvoiceItem;


class Invoice extends Model implements InvoiceInterface
{

    use ConvertToCurrency;
    protected $table = 'invoice';
    public $translatedAttributes = [];
    protected $primaryKey = 'invoice_id';
    public $timestamps = false;
    protected $fillable = [
        'isp_id',
        'osp_id',
        'periode',
        'invoice_no',
        'ppn',
        'settlement_at',
        'created_at',
        'status',
        'due_date',
        'type'
    ];

    public static function charge_pelanggan()
    {
        DB::beginTransaction();
        $pelanggan = Pelanggan::whereNull('suspend_id')
            ->join('request_wilayah', function ($join) {
                $join->on('request_wilayah.isp_id', 'pelanggan.isp_id');
                $join->on('request_wilayah.wilayah_id', 'pelanggan.wilayah_id');
            })
            ->where('pelanggan.status', 'aktif')
            ->get();


        event($charge = new MonthlyCharge($pelanggan));

        DB::commit();
        // dd('asd');
    }

    public static function hitung_pengembalian()
    {
        DB::beginTransaction();
        $pelanggan_suspend = DB::table('suspend')->join('pelanggan as p', 'p.suspend_id', '=', 'suspend.suspend_id')
            ->where('tgl_suspend', 'LIKE', date('Y-m') . "%")
            ->get();
        // $pelanggan_suspend = Pelanggan::where('suspended_at','LIKE', date('Y-m')."%")->get();
        $pelanggan_ticket_support_sla = TicketSla::getTicketSupportSlaPelangganBulanIni();
        event(new Pengembalian($pelanggan_suspend, $pelanggan_ticket_support_sla));

        DB::commit();
    }

    public function serverPaginationFilteringFor(Request $request): LengthAwarePaginator
    {
        if ($request->get('company_id') == null) {
            $company_id = Auth::user()->company()->company_id;
        } else {
            $company_id = $request->company_id;
        }

        $company = Company::find($company_id);
        $invoice = self::select(['invoice.*', 'a.name as nama_osp', 'b.name as nama_isp'])
            ->leftJoin('companies as a', 'a.company_id', 'invoice.osp_id')
            ->leftJoin('companies as b', 'b.company_id', 'invoice.isp_id');
        if ($company->type == 'isp') {
            $invoice = $invoice->where('isp_id', $company_id);
        } else if ($company->type === 'osp') {
            $invoice = $invoice->where('osp_id', $company_id);
        }

        if ($request->get('status') != 'seluruh') {
            $invoice->where('invoice.status', $request->get('status'));
        }


        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $invoice->where(function ($query) use ($term) {
                $query->where('invoice_no', 'LIKE', "%{$term}%")
                    ->orWhere('title', 'LIKE', "%{$term}%");
            });
        }

        if ($request->get('tipe') != null) {
            $invoice->where('invoice.type', $request->get('tipe'));
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $invoice->orderBy($request->get('order_by'), $order);
        } else {
            $invoice->orderBy('status', 'asc');
        }
        return $invoice->paginate($request->get('per_page', 10));
    }

    public function item()
    {
        if ($this->type == 'pelanggan') {
            $item = InvoiceItem::select([
                'pelanggan.pelanggan_id',
                'invoice_item_pelanggan.invoice_item_id',
                'pelanggan.nama_pelanggan as name',
                'invoice_item_pelanggan.amount'
            ])
                ->where('invoice_id', $this->invoice_id)
                ->join('pelanggan', 'pelanggan.pelanggan_id', 'invoice_item_pelanggan.pelanggan_id')
                ->get();
        }

        if ($this->type === 'konfirmasi presale') {
            $invoice_item = InvoiceItemKonfirmasiPresale::where('invoice_id', $this->invoice_id)->first();
            $item = [[
                'name' =>  'Konfirmasi Presale',
                'qty' => InvoiceItemKonfirmasiPresale::where('invoice_id', $this->invoice_id)->count(),
                'amount' => $invoice_item->amount
            ]];
        }

        if ($this->type === 'biaya endpoint') {

            $item = [[
                'name' =>  'Biaya Endpoint',
                'qty' => InvoiceItemEndpoint::where('invoice_id', $this->invoice_id)->count(),
                'amount' => InvoiceItemEndpoint::where('invoice_id', $this->invoice_id)->sum('amount')
            ]];
        }

        return $item;
    }

    public function total_tagihan()
    {
        $total = 0;
        foreach ($this->item() as $key => $value) {
            $total += $value->amount;
        }
        $ppn = $total * ($this->ppn / 100);

        return $total + $ppn;
    }

    public static function create_invoice_pelanggan($isp_id, $osp_id, $request_wilayah_id, $periode, $invoice_no, $status, $ppn)
    {
        $due = DB::table('kontrak_wilayah')->where('request_wilayah_id', $request_wilayah_id)
            ->where('active', 1)->first();
        if ($due) {
            $due = $due->toleransi_tunggakan;
            $due = date('Y-m-d', strtotime("+$due Month", strtotime(date('Y-m-d H:i:s'))));
        }


        $insert = [
            'isp_id' => $isp_id,
            'osp_id' => $osp_id,
            'periode' => $periode,
            'invoice_no' => $invoice_no,
            'ppn' => $ppn,
            'status' => $status,
            'due_date' => $due,
            'created_at' => date('Y-m-d H:i:s'),
            'type' => 'pelanggan'
        ];

        if ($status === "settlement") {
            $insert['settlement_at'] = date('Y-m-d H:i:s');
        }

        $invoice = Invoice::create($insert);

        InvoicePelanggan::create([
            'request_wilayah_id' => $request_wilayah_id,
            'invoice_id' => $invoice->invoice_id
        ]);

        return $invoice;
    }

    public static function create_invoice_endpoint($osp_id, $amount, $end_poin, $wilayah, $status)
    {
        $title = "Invoice registrasi End Point $end_poin ( $wilayah )";
        if (is_numeric($end_poin)) {
            $title = "Invoice registrasi $end_poin End Point ( $wilayah )";
        }

        $insert = [
            'invoice_no' => self::generateLastedInvoiceNo(),
            'isp_id' => 1,
            'osp_id' => $osp_id,
            'periode' => date('Y-m-d'),
            'status' => $status,
            'created_at' => date('Y-m-d H:i:s'),
            'ppn' => 0,
            'due_date' => null,
            'type' => 'biaya endpoint',
            'amount' => $amount,
            'nominal_ppn' => 0,
            'nominal_dpp' => $amount,
            'title' => $title
        ];

        if ($status === "settlement") {
            $insert['settlement_at'] = date('Y-m-d H:i:s');
        }

        $invoice = Invoice::create($insert);

        return $invoice;
    }

    public static function create_invoice_konfirmasi_presale($osp_id, $amount, $jumlah, $wilayah, $status)
    {
        $title = "Invoice Konfirmasi $jumlah Presales wilayah " . $wilayah->name . " ( " . $wilayah->nama_osp . " )";
        $insert = [
            'invoice_no' => self::generateLastedInvoiceNo(),
            'osp_id' => $osp_id,
            'isp_id' => 1,
            'periode' => date('Y-m-d'),
            'status' => $status,
            'created_at' => date('Y-m-d H:i:s'),
            'ppn' => 0,
            'due_date' => null,
            'type' => 'konfirmasi presale',
            'amount' => $amount,
            'nominal_ppn' => 0,
            'nominal_dpp' => $amount,
            'title' => $title
        ];

        if ($status === "settlement") {
            $insert['settlement_at'] = date('Y-m-d H:i:s');
        }

        $invoice = Invoice::create($insert);

        return $invoice;
    }

    public static function findInvoiceItemByPelangganId($pelanggan_id)
    {
        $data = InvoiceItem::where('pelanggan_id', $pelanggan_id)
            ->orderBy('invoice_item_id', 'desc')
            ->first();

        return $data;
    }

    public static function setSettlement($invoice_id)
    {
        $invoice = self::find($invoice_id);
        if ($invoice) {
            $invoice->status = 'settlement';
            $invoice->settlement_at = date('Y-m-d H:i:s');
            return true;
        }

        return false;
    }

    public static function generateLastedInvoiceNo()
    {
        $jumlah_invoice = DB::table('invoice')->where('periode', 'like', date('Y-m') . '%')->count();
        return 'INV' . date('ymd') . str_pad($jumlah_invoice + 1, 4, "0", STR_PAD_LEFT);
    }

    public static function create_invoice_activation_pelanggan($pelanggan, $data_request_wilayah, $ppn)
    {


        $date_pelanggan_aktif = date('Y-m-d');

        return self::create_invoice_pelanggan(
            $pelanggan->isp_id,
            $data_request_wilayah->osp_id,
            $data_request_wilayah->request_wilayah_id,
            $date_pelanggan_aktif,
            self::generateLastedInvoiceNo(),
            'pending',
            $ppn
        );
    }

    public function generatePotonganProrata($date, $biaya)
    {
        $tgl_1 = date('Y-m-01');
        $total_jam_bulan  = ((int)date('t') * 24) * 60;
        $jam_prorata = (strtotime($date) - strtotime($tgl_1)) / 60;

        $presentase_prorata = ($jam_prorata / $total_jam_bulan) * 100;

        $potongan_prorata = floor(($presentase_prorata / 100) * $biaya);
        return $potongan_prorata;
    }


    public function create_refund_pelanggan_prorata($date, $biaya, $invoice_item_pelanggan, $pelanggan)
    {


        $potongan_prorata = $this->generatePotonganProrata($date, $biaya);

        $invoice = new Invoice();

        return RefundItem::create([
            'invoice_item_id' => $invoice_item_pelanggan->invoice_item_id,
            'refund_type' => 'prorata',
            'status' => 'pending',
            'refund_amount' => $potongan_prorata,
            'description' => trans('invoice::invoices.refund item prorata', [
                'jumlah' => $invoice->rupiah($potongan_prorata),
                'name' => $pelanggan->nama_pelanggan
            ]),
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
