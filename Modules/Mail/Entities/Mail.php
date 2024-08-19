<?php

namespace Modules\Mail\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    use Translatable;

    protected $table = 'mail__mails';
    public $translatedAttributes = [];
    protected $fillable = [];
}
