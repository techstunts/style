<?php

namespace App\Models\Lookups;

use Illuminate\Database\Eloquent\Model;

class ClothingFit extends Model
{
    protected $table = 'lu_clothing_fit';
    protected $fillable = ['name'];
    public $timestamps = false;
}
