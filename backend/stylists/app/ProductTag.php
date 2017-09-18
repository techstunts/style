<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
   
    protected $table = 'isy_product_tags';

    public $timestamps = false;

     public function tag(){
        return $this->belongsTo('App\Models\Lookups\Tag', 'tag_id');
    }
}
