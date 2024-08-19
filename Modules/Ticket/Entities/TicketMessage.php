<?php

namespace Modules\Ticket\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class TicketMessage extends Model
{
    protected $table = 'ticket_message';   
    protected $primaryKey = "message_id";
    public $timestamps = false;
    protected $fillable = [
    	'ticket_id',
        'osp_id',
        'isp_id',
        'message',
        'unsend',
        'created_at',
        'created_by',
        'attachments'
    ];  
    
    
    public static function getMessages($ticket_id, $id_terakhir = null)
    {
        $data = Self::where('ticket_id',$ticket_id)
                ->select([
                        'ticket_message.*',
                        'a.name as osp_name',
                        'b.name as isp_name',
                        'c.full_name as nama_user',
                        'c.photo_profile as foto_profile'])
                ->leftJoin('companies as a','a.company_id','=','ticket_message.osp_id')
                ->leftJoin('companies as b','b.company_id','=','ticket_message.isp_id')
                ->join('users as c','c.id','=','ticket_message.created_by');

        if($id_terakhir != null){
            $data = $data->where('ticket_message.message_id','>',$id_terakhir);
        }
        
        $data = $data->get();

        return $data;
    }
}