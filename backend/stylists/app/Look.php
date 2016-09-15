<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Look extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'looks';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['image', 'name', 'description', 'body_type_id', 'budget_id', 'age_group_id', 'occasion_id', 'gender_id',
        'stylist_id', 'price', 'created_at'];

    public $timestamps = true;

    public function stylist(){
        return $this->belongsTo('App\Stylist', 'stylist_id');
    }

    public function products(){
        return $this->belongsToMany('App\Product', 'looks_products');
    }

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

    public function look_products(){
        return $this->hasMany('App\LookProduct');
    }
    public function recommendation(){
        return $this->hasMany('App\Recommendation', 'entity_id');
    }

}
