<?php

namespace App\Models\Lookups;

use Illuminate\Database\Eloquent\Model;

class ProductError extends Model
{
    protected $connection = 'mysqlScraper';
    protected $table = 'isy_lu_product_error';

}
