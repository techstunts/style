<?php

namespace App\Models\Lookups;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Lookup extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = '';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    public $timestamps = false;

    public function type($name){
        return $this->setTable('isy_lu_' . $name);
    }
}
