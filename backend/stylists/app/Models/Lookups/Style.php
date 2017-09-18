<?php

namespace App\Models\Lookups;

use Illuminate\Database\Eloquent\Model;

class Style extends Model
{
    protected $table = 'isy_lu_style';
    public function category(){
        return $this->belongsTo('App\Category', 'category_id');
    }
}
