<?php

namespace Modules\Ticket\Entities;

use Modules\Ticket\Entities\TicketMessage;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Ticket\Transformers\TicketMessagesTransformer;

class Ticket extends Model
{
	use SoftDeletes;
	protected $table = 'ticket_support';
	protected $dates = ['deleted_at'];
	protected $primaryKey = "ticket_id";
	protected $fillable = [
		'keterangan',
		'created_at',
		'created_by',
		'updated_at',
		'updated_by',
		'closed_at',
		'closed_by',
		'deleted_at',
		'deleted_by'
	];

	// public static function detail($ticket_id){
	// 	$data = Self::select(['ticket_support.*','a.full_name as user_approve_isp','b.full_name as user_approve_osp'])
	// 			->leftJoin('users as a','a.id','=','ticket_support.accept_isp_by')
	// 			->leftJoin('users as b','b.id','=','ticket_support.accept_osp_by')
	// 			->where('ticket_id',$ticket_id)
	// 			->first();

	// 	return $data;
	// }

	// public function withMessage()
	// {
	// 	$messages = TicketMessage::getMessages($this->ticket_id);
	// 	$this->messages = TicketMessagesTransformer::collection($messages);

	// 	return $this;
	// }

	public static function getTicketSupportSlaPelangganBulanIni()
	{
		$select = [
			'ticket_support.*',
			'ticket_support_sla.*',
			'a.pelanggan_id',
			'a.nama_pelanggan',
			'a.biaya_mrc',
			'b.sla'
		];
		$data = Self::select($select)
			->join('ticket_support_sla', 'ticket_support.ticket_id', '=', 'ticket_support_sla.ticket_id')
			->join('pelanggan as a', 'a.pelanggan_id', 'ticket_support_sla.pelanggan_id')
			->join('paket_berlangganan as b', 'b.paket_id', 'a.paket_id')
			->where('ticket_support_sla.start_gangguan', 'LIKE', date('Y-m') . "%")
			->whereNotNull('accept_osp_by')
			->whereNotNull('accept_isp_by')
			->whereNull('ticket_support.deleted_at')
			->orderBy('a.pelanggan_id')
			->get();
		$pelanggan = [];
		$index_pelanggan = [];
		foreach ($data as $key => $val) {
			$cek = in_array($val->pelanggan_id, $index_pelanggan);
			if ($cek) {
				$index = array_search($val->pelanggan_id, $index_pelanggan);
				$pelanggan[$index]->jam[] = [
					'start_gangguan' => $val->start_gangguan,
					'end_gangguan' => $val->end_gangguan
				];
			} else {
				$index_pelanggan[] = $val->pelanggan_id;
				$pelanggan[] = (object) array(
					'ticket_id' => $val->ticket_id,
					'code' => $val->code,
					'pelanggan_id' => $val->pelanggan_id,
					'subject' => $val->subject,
					'nama_pelanggan' => $val->nama_pelanggan,
					'biaya_mrc' => $val->biaya_mrc,
					'sla' => $val->sla,
					'jam' => [
						[
							'start_gangguan' => $val->start_gangguan,
							'end_gangguan' => $val->end_gangguan
						]
					]
				);
			}
		}


		// looping hitung jam mati pelanggan
		foreach ($pelanggan as $key => $val) {
			$jam_mati = 0;
			foreach ($val->jam as $key => $value) {
				$jam_mati += (strtotime($value['end_gangguan']) - strtotime($value['start_gangguan'])) / 60;
			}
			$val->jam_mati = $jam_mati;
		}


		return $pelanggan;
	}
}
