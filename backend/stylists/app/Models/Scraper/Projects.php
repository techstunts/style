<?php
namespace App\Models\Scraper;

use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    protected $table = 'projects';
    protected $primaryKey = 'id';
    protected $connection = 'mysqlScraper';
}