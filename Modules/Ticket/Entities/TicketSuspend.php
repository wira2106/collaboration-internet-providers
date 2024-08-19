<?php

namespace Modules\Ticket\Entities;

use Modules\Ticket\Entities\TicketMessage;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Ticket\Transformers\TicketMessagesTransformer;

class TicketSuspend extends Model
{
    // use SoftDeletes;
    protected $table = 'ticket_support_suspend';
    public $timestamps = false;
    protected $fillable = [
    	'ticket_id',
        'pelanggan_id',
        'alasan',
        'status',
        'accept_osp_by',
        'tgl_acc_osp',
		'tgl_suspend'
    ];    

    public static function detail($ticket_id){
		$data = Self::select(['ticket_support_suspend.*','b.full_name as user_approve_osp','x.*'])
				->join('ticket_support as x','x.ticket_id','=','ticket_support_suspend.ticket_id')
				->leftJoin('users as b','b.id','=','ticket_support_suspend.accept_osp_by')
				->where('x.ticket_id',$ticket_id)
				->first();
                
		return $data;
	}

	public function withMessage()
	{
		$messages = TicketMessage::getMessages($this->ticket_id);
		$this->messages = TicketMessagesTransformer::collection($messages);

		return $this;
	}
    
}