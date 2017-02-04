<?php
namespace App\Http\Mapper;

use App\Models\Enums\PriceType;
use App\Models\Enums\Currency;
use App\Product;

class Mapper
{
    public static function productsByIds($product_ids)
    {
        return Product::whereIn('id', explode(',', $product_ids))->get();
    }

    public function getPriceClosure($min_price = null, $max_price = null, $min_discount = null, $max_discount = null)
    {
        return function ($query) use($min_price, $max_price, $min_discount, $max_discount) {
            $query->with(['type', 'currency']);
            $query->where(['price_type_id' => PriceType::RETAIL, 'currency_id' => Currency::INR]);
            if (!empty($min_price)) {
                $query->where('value', '>=', $min_price);
            }
            if (!empty($max_price)) {
                $query->where('value', '<=', $max_price);
            }
        };
    }
}