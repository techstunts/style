<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantProduct extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'merchant_products';

    public function brand()
    {
        return $this->belongsTo('App\Brand', 'brand_id');
    }
    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id');
    }
    public function color()
    {
        return $this->belongsTo('App\Models\Lookups\Color', 'primary_color_id');
    }
}
