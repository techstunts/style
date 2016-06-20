<?php

namespace App\Models\Lookups;

use Illuminate\Database\Eloquent\Model;

class ProductError extends Model
{
    protected $connection = 'mysqlScraper';
    protected $table = 'lu_product_error';

}
