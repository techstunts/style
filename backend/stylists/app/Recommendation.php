<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Recommendation extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'recommendations';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    public $timestamps = true;
}
