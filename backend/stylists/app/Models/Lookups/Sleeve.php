<?php

namespace App\Models\Lookups;

use Illuminate\Database\Eloquent\Model;

class Sleeve extends Model
{
    protected $table = 'lu_sleeve';
    protected $fillable = ['name'];
    public $timestamps = false;
}
