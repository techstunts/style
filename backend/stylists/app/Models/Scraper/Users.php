<?php
namespace App\Models\Scraper;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'isy_users';
    protected $primaryKey = 'id';
    protected $connection = 'mysqlScraper';
}