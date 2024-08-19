<?php

namespace Modules\Company\Entities;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'tipe_rating',
        'pemberi_rating_id' ,
        'penerima_rating_id',
        'rating',
        'description'
    ];
    protected $table = 'rating';
    protected $primaryKey = 'rating_id';
}
