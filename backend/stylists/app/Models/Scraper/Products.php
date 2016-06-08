<?php

namespace App\Models\Scraper;


use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $connection = 'mysqlScraper';
    public $timestamps = false;
}