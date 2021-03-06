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

    public static function checkRecommended($entity) {
        if (!empty($entity) && !empty($entity->recommendation)) {
            if (count($entity->recommendation) > 0) {
                return true;
            }
        }
        return false;
    }
    public function look ()
    {
        return $this->belongsTo('App\Look', 'entity_id');
    }
    public function product ()
    {
        return $this->belongsTo('App\Product', 'entity_id');
    }
}
