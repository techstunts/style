<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    protected $table = 'products_prices';

    public function type(){
        return $this->belongsTo('App\Models\Lookups\PriceType', 'price_type_id');
    }

    public function currency(){
        return $this->belongsTo('App\Models\Lookups\currency', 'currency_id');
    }
}