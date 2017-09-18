<?php
namespace App\Models\Scraper;

use Illuminate\Database\Eloquent\Model;

class Imports extends Model
{
    protected $table = 'isy_imports';
    protected $primaryKey = 'id';
//    protected $connection = 'mysqlScraper';
}