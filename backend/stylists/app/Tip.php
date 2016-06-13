<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tip extends Model
{
   
    protected $table      = 'tips';
    protected $primaryKey = 'id';
    
    
    protected $fillable = [];

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
    
    public function age_group(){
        return $this->belongsTo('App\Models\Lookups\AgeGroup', 'age_group_id');
    }
    
    public function budget(){
        return $this->belongsTo('App\Models\Lookups\Budget', 'budget_id');
    }
    
    public function occasion(){
        return $this->belongsTo('App\Models\Lookups\Occasion', 'occasion_id');
    }
    
    public function createdBy(){
        return $this->belongsTo('App\Stylist', 'created_by');
    }
     
    public function updated_by(){
        return $this->belongsTo('App\Stylist', 'stylist_id');
    }
    public function entities(){
        return $this->hasMany('App\TipEntity');
    }

}
