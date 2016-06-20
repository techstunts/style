<?php
namespace App\Models\Scraper;

use Illuminate\Database\Eloquent\Model;
class Jobs extends Model
{
    protected $table = 'jobs';
    protected $primaryKey = 'id';
    protected $connection = 'mysqlScraper';

    public function spider(){
        return $this->belongsTo('App\Models\Scraper\Spiders', 'spider_id');
    }
}