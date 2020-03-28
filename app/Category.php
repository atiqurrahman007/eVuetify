<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
   protected $fillable = [
        'cat_name', 'cat_slug', 'cat_link','cat_image','cat_status','id',
    ];



    public function dailyproducts(){
     return	$this->hasMany('App\Dailyproduct', 'id');
    }
}
