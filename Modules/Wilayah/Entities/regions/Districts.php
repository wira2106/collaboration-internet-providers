<?php

namespace Modules\Wilayah\Entities\regions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class Districts extends Model
{
    protected $table = 'districts';
    protected $primaryKey = "id";
}
