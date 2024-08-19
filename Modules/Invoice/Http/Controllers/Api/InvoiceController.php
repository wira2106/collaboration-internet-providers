<?php

namespace Modules\Invoice\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Company\Entities\Company;
use Modules\Company\Transformers\CompanyForSelectTransformer;
use Modules\Company\Transformers\FullCompanyTransformer;
use Modules\Invoice\Entities\Invoice;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Invoice\Repositories\InvoiceRepository;
use Modules\Invoice\Transformers\FullInvoiceTransformer;
use Modules\Invoice\Transformers\InvoiceTransformer;
use Modules\Saldo\Entities\Saldo;
use Modules\Invoice\Entities\InvoiceItem;
use Modules\Invoice\Transformers\InvoicePelangganTransformer;

class InvoiceController extends AdminBaseController
{
    private $invoice;
    public function __construct(InvoiceRepository $invoice)
    {
        $this->invoice = $invoice;
        parent::__construct();
    }

    public function index(Request $request) {

        $company = Auth::user()->company();
        $company_id = $company->company_id;
        if(Auth::user()->hasRoleName('Super Admin')) {
            if(!$request->company_id) {
                $request->merge([
                    'company_id' => $company->company_id,
                ]);
            }
            
            $company_id = $request->company_id;
        }

        $invoice = (new Invoice())->serverPaginationFilteringFor($request);
        // dd($invoice);
        $companies = $company->getAllCompany();
        
        $allDataWilayah = [
            'companies' => count($companies) > 0 ? CompanyForSelectTransformer::collection($companies) : $companies, 
            "selected" => $company_id
        ];

        return InvoiceTransformer::collection($invoice)->additional($allDataWilayah);
        
    }

    public function show(Invoice $invoice) {
        $invoice->item = $invoice->item();
        $invoice->from = new FullCompanyTransformer(Company::find($invoice->osp_id));
        $invoice->to = new FullCompanyTransformer(Company::find($invoice->isp_id));

        return (new FullInvoiceTransformer($invoice))->additional([
            'saldo' => Saldo::where('company_id',$invoice->isp_id)->first()
        ]);
    }

    public function listInvoicePelanggan(Request $request)
    {
        $data = (new InvoiceItem())->serverPaginationFilteringFor($request);

        return InvoicePelangganTransformer::collection($data);
    }

    public function getJumlahPendingInvoicePelanggan($invoice)
    {
        $jumlah = InvoiceItem::getJumlahPendingInvoice($invoice);

        return response()->json($jumlah);
    }

    public function monthly_charge() {
        Invoice::charge_pelanggan();
        return response()->json([
            'errors' => false
        ]);
    }
    
    public function hitung_pengembalian() {
        Invoice::hitung_pengembalian();
    }

    public function bayar(Invoice $invoice) {
        DB::beginTransaction();
        $total_tagihan = $invoice->total_tagihan();
        $saldo = Company::find($invoice->isp_id)->saldo();

        if($saldo->saldo < $total_tagihan) {
            return abort(417, trans('invoice::invoices.saldo tidak mencukupi'));
        }

        $company_id = $invoice->isp_id;
        $created_at = date_create(date('Y-m-d', strtotime($invoice->created_at)));
        $deskripsi = trans('invoice::invoices.tagihan bulanan pelanggan', [
            'bulan' =>  strftime('%B %Y', $created_at->getTimestamp() )
        ]);

        Saldo::potong_saldo(
            $company_id, 
            $total_tagihan, 
            $deskripsi
        );

        Saldo::tambah_saldo_dibekukan(1, $total_tagihan, $deskripsi );
        
        $invoice->status = 'pending';
        $invoice->save();
        DB::commit();
        return response()->json([
            'errors' => false,
            'messages' => trans('invoice::invoices.pembayaran sukses')
        ]);

    }
    
    

}
