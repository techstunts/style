<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Collection extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'collections';

    protected $primaryKey = 'id';

    public $timestamps = true;

    public function status(){
        return $this->belongsTo('App\Models\Lookups\Status', 'status_id');
    }

    public function gender(){
        return $this->belongsTo('App\Models\Lookups\Gender', 'gender_id');
    }

    public function body_type(){
        return $this->belongsTo('App\Models\Lookups\BodyType', 'body_type_id');
    }

    public function occasion(){
        return $this->belongsTo('App\Models\Lookups\Occasion', 'occasion_id');
    }

    public function budget(){
        return $this->belongsTo('App\Models\Lookups\Budget', 'budget_id');
    }

    public function age_group(){
        return $this->belongsTo('App\Models\Lookups\AgeGroup', 'age_group_id');
    }
}
