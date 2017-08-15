<?php

namespace App\Models\Lookups;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'lu_material';
    protected $fillable = ['name'];
    public $timestamps = false;
}
