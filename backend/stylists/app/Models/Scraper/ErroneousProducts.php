<?php

namespace App\Models\Scraper;
use Illuminate\Database\Eloquent\Model;

class ErroneousProducts extends Model
{
    protected $table = 'isy_erroneous_products';
    protected $connection = 'mysqlScraper';
    public $timestamps = false;
}