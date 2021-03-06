<?php

namespace App\Models\Looks;

use Illuminate\Database\Eloquent\Model;

class LookSequence extends Model
{
    protected $table = 'look_sequence';

    public function look(){
        return $this->belongsTo('App\Look', 'look_id');
    }
}