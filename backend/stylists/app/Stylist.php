<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Zizaco\Entrust\Traits\EntrustUserTrait;

class Stylist extends Model implements AuthenticatableContract,
                                    //AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;
    // use Authorizable; //This conflicts with Entrust role and permission module with error "Trait method can has not been applied",
    use EntrustUserTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'isy_stylists';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'image',  'profile',
        'code', 'expertise_id', 'age', 'gender_id', 'description', 'status_id'];

    public $timestamps = true;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function looks(){
        return $this->hasMany('App\Look', 'stylist_id');
    }

    public function gender(){
        return $this->belongsTo('App\Models\Lookups\Gender', 'gender_id');
    }

    public function designation(){
        return $this->belongsTo('App\Models\Lookups\Designation', 'designation_id');
    }

    public function expertise(){
        return $this->belongsTo('App\Models\Lookups\Expertise', 'expertise_id');
    }

    public function upload_images(){
        return $this->hasMany('App\UploadImages', 'uploaded_by_entity_id');
    }

    public function category(){
        return $this->belongsTo('App\Category', 'category_id');
    }

    public static function getExposableData($stylist)
    {
        $data['id'] = $stylist->id;
        $data['name'] = $stylist->name;
        $data['description'] = $stylist->description;
        $data['code'] = $stylist->code;
        $data['designation'] = $stylist->designation->name;
        $data['image'] = strpos($stylist->image, 'http') === false ? env('IMAGE_BASE_URL') . '/' . $stylist->image : $stylist->image;
        $data['icon'] = strpos($stylist->icon, 'http') === false ? env('IMAGE_BASE_URL') . '/' . $stylist->icon : $stylist->icon;

        return $data;
    }

}
