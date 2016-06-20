<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipEntity extends Model
{
    protected $table = 'tip_entities';
    public $timestamps = false;

    public function product(){
        return $this->belongsTo('App\Product', 'entity_id');
    }
    public function look(){
        return $this->belongsTo('App\Look', 'entity_id');
    }
}
