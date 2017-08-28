<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
   
    protected $table = 'product_tags';

    public $timestamps = false;

     public function tag(){
        return $this->belongsTo('App\Models\Lookups\Tag', 'tag_id');
    }
    public function product(){
        return $this->belongsTo('App\Product', 'product_id');
    }
}
