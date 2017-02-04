<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Client extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'clients';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = true;

    public function stylist(){
        return $this->belongsTo('App\Stylist', 'stylist_id');
    }
    public function genders(){
        return $this->belongsTo('App\Models\Lookups\Gender', 'gender_id');
    }
    public function client_reg_details(){
        return $this->hasMany('App\ClientDeviceRegDetails');
    }
    public function body_type(){
        return $this->belongsTo('App\Models\Lookups\BodyType', 'body_type_id');
    }
    public function body_shape(){
        return $this->belongsTo('App\Models\Lookups\BodyShape', 'body_shape_id');
    }
    public function age_group(){
        return $this->belongsTo('App\Models\Lookups\AgeGroup', 'age_group_id');
    }
    public function complexion(){
        return $this->belongsTo('App\Models\Lookups\Complexion', 'complexion_id');
    }
    public function daringness(){
        return $this->belongsTo('App\Models\Lookups\Daringness', 'daringness_id');
    }
    public function height_group(){
        return $this->belongsTo('App\Models\Lookups\HeightGroup', 'height_group_id');
    }
}
