<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HomePage extends Model
{
    protected $table = 'home_page';

    public $timestamps = true;

    public function products(){
        return $this->hasMany('App\Product', 'id', 'entity_id');
    }
}
