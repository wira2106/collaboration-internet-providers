<?php

namespace Modules\Invoice\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use DB;

class InvoiceItem extends Model
{

    protected $table = 'invoice_item_pelanggan';
    protected $primaryKey = 'invoice_item_id';
    public $timestamps = false;
    protected $fillable = [
        'invoice_id',
        'pelanggan_id',
        'amount',
        'dpp',
        'total_ppn',
        'ppn'
    ];


    public function serverPaginationFilteringFor(Request $request): LengthAwarePaginator
    {
        $select = [
            'invoice_item_pelanggan.*',
            'pelanggan.nama_pelanggan',
            'pelanggan.hold_pengembalian',
            'invoice.status as status_invoice',
            'paket_berlangganan.nama_paket'
        ];

        $data = InvoiceItem::select($select)
            ->join('pelanggan', 'pelanggan.pelanggan_id', 'invoice_item_pelanggan.pelanggan_id')
            ->join('invoice', 'invoice.invoice_id', 'invoice_item_pelanggan.invoice_id')
            ->join('paket_berlangganan', 'paket_berlangganan.paket_id', 'pelanggan.paket_id')
            ->where('invoice.invoice_id', $request->invoice_id);

        if ($request->get('search') !== null) {
            $term = $request->get('search');
            $data->where(function ($query) use ($term) {
                $query->where('nama_pelanggan', 'LIKE', "%{$term}%")
                    ->orWhere('nama_paket', 'LIKE', "%{$term}%");
            });
        }

        if ($request->get('status') != null) {
            if ($request->get('status') == 'pending') {
                $data->where(DB::raw('(CASE WHEN (invoice.status is NULL OR invoice.status = "pending") OR hold_pengembalian = 1 THEN 1 ELSE 0 END)'), '1');
            } else {
                $data->where(DB::raw('(CASE WHEN (invoice.status is NULL OR invoice.status = "pending") OR hold_pengembalian = 1 THEN 1 ELSE 0 END)'), '0');
            }
        }

        if ($request->get('order_by') !== null && $request->get('order') !== 'null') {
            $order = $request->get('order') === 'ascending' ? 'asc' : 'desc';

            $data->orderBy($request->get('order_by'), $order);
        } else {
            $data->orderBy('nama_pelanggan', 'asc');
        }
        return $data->paginate($request->get('per_page', 10));
    }

    public static function getJumlahPendingInvoice($invoice)
    {
        $data = InvoiceItem::join('pelanggan', 'pelanggan.pelanggan_id', 'invoice_item_pelanggan.pelanggan_id')
            ->join('invoice', 'invoice.invoice_id', 'invoice_item_pelanggan.invoice_id')
            ->where('invoice.invoice_id', $invoice->invoice_id)
            ->where(DB::raw('(CASE WHEN (invoice.status is NULL OR invoice.status = "pending") OR hold_pengembalian = 1 THEN 1 ELSE 0 END)'), '1')
            ->count();
        return $data;
    }
}
