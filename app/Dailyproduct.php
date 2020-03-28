<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dailyproduct extends Model
{
    public function supplier(){
     return $this->belongsTo('App\User','supplier_id');
    }

    public function category(){
     return $this->belongsTo('App\Category','cat_id');
    }


     protected $fillable = [
        'id',
    ];

}
