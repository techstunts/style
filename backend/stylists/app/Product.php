<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'lookdescrip';

    protected $fillable = ['agency_id', 'merchant_id', 'product_name', 'product_price', 'product_link', 'upload_image',
        'image_name','merchant_product_id', 'brand_id', 'category_id', 'gender_id'];

}
