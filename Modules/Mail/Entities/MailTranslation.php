<?php

namespace Modules\Mail\Entities;

use Illuminate\Database\Eloquent\Model;

class MailTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'mail__mail_translations';
}
