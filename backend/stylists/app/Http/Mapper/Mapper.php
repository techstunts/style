<?php
namespace App\Http\Mapper;

use App\Models\Enums\EntityType;
use App\Product;

class Mapper
{
    public static function productsByIds($product_ids)
    {
        return Product::whereIn('id', explode(',', $product_ids))->get();
    }
}