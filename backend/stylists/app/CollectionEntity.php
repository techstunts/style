<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollectionEntity extends Model
{
    protected $table = 'collection_entities';
    public $timestamps = false;

    public function product(){
        return $this->belongsTo('App\Product', 'entity_id');
    }
    public function look(){
        return $this->belongsTo('App\Look', 'entity_id');
    }
}
