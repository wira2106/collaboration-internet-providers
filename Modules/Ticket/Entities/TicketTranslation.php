<?php

namespace Modules\Ticket\Entities;

use Illuminate\Database\Eloquent\Model;

class TicketTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'ticket__ticket_translations';
}
