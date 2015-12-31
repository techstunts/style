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
        'stylish_id', 'price', 'created_at'];

    public $timestamps = false;

    public function stylist(){
        return $this->belongsTo('App\Stylist', 'stylish_id');
    }

    public function products(){
        return $this->belongsToMany('App\Product', 'looks_products');
    }

    public function gender(){
        return $this->belongsTo('App\Gender', 'gender_id');
    }

    public function body_type(){
        return $this->belongsTo('App\BodyType', 'body_type_id');
    }

    public function occasion(){
        return $this->belongsTo('App\Occasion', 'occasion_id');
    }

    public function budget(){
        return $this->belongsTo('App\Budget', 'budget_id');
    }

    public function age_group(){
        return $this->belongsTo('App\AgeGroup', 'age_group_id');
    }

}
