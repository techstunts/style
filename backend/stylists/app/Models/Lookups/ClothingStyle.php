<?php

namespace App\Models\Lookups;

use Illuminate\Database\Eloquent\Model;

class ClothingStyle extends Model
{
    protected $table = 'lu_clothing_style';
    protected $fillable = ['name'];
    public $timestamps = false;
}
