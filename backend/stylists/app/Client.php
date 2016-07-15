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
}
