<?php
namespace App\Models\Scraper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Merchants extends Model
{
    protected $table = 'merchants';
    protected $primaryKey = 'id';
    protected $connection = 'mysqlScraper';

}