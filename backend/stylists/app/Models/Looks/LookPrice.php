<?php

namespace App\Models\Looks;

use Illuminate\Database\Eloquent\Model;

class LookPrice extends Model
{
    protected $table = 'isy_look_prices';

    public function currency(){
        return $this->belongsTo('App\Models\Lookups\Currency', 'currency_id');
    }
    public function type(){
        return $this->belongsTo('App\Models\Lookups\PriceType', 'price_type_id');
    }
}