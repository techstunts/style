<?php
namespace App\Models\Scraper;

use Illuminate\Database\Eloquent\Model;

class Imports extends Model
{
    protected $table = 'imports';
    protected $primaryKey = 'id';
    protected $connection = 'mysqlScraper';
}