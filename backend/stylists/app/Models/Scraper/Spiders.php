<?php
namespace App\Models\Scraper;

use Illuminate\Database\Eloquent\Model;
class Spiders extends Model
{
    protected $table = 'isy_spiders';
    protected $primaryKey = 'id';
//    protected $connection = 'mysqlScraper';

    public function merchant(){
        return $this->belongsTo('App\Models\Scraper\Merchants', 'merchant_id');
    }

    public function project(){
        return $this->belongsTo('App\Models\Scraper\Projects', 'project_id');
    }
}