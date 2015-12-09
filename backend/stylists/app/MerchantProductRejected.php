<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantProductRejected extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'merchant_products_rejected';
    protected $fillable = ['agency_id', 'merchant_id', 'm_product_id', 'm_product_sku'];
    public $timestamps = false;
}
