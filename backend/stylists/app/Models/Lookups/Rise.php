<?php

namespace App\Models\Lookups;

use Illuminate\Database\Eloquent\Model;

class Rise extends Model
{
    protected $table = 'lu_rise';
    protected $fillable = ['name'];
    public $timestamps = false;
}
