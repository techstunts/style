<?php

namespace App\Models\Looks;

use Illuminate\Database\Eloquent\Model;

class LookTag extends Model
{
   
    protected $table = 'look_tags';

    public $timestamps = false;
    public function tag(){
        return $this->belongsTo('App\Models\Lookups\Tag', 'tag_id');
    }
}
