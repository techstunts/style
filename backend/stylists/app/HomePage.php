<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HomePage extends Model
{
    protected $table = 'home_page';

    public $timestamps = true;

    public function collections(){
        return $this->hasMany('App\Collection', 'id', 'entity_id');
    }
    public function products(){
        return $this->hasMany('App\Product', 'id', 'entity_id');
    }
    public function looks(){
        return $this->hasMany('App\Look', 'id', 'entity_id');
    }
    public function tips(){
        return $this->hasMany('App\Tip', 'id', 'entity_id');
    }
}
