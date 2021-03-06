<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class StyleRequests extends Model
{

    protected $table = 'style_requests';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id'];

    public function client()
    {
        return $this->belongsTo('App\Client', 'user_id');
    }
    public function requested_entity()
    {
        return $this->belongsTo('App\Models\Lookups\EntityType', 'entity_type_id');
    }
    public function question_ans()
    {
        return $this->hasMany('App\Models\Client\ClientAnswer', 'request_id');
    }
    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id');
    }
    public function occasion()
    {
        return $this->belongsTo('App\Models\Lookups\Occasion', 'occasion_id');
    }
    public function budget()
    {
        return $this->belongsTo('App\Models\Lookups\Budget', 'budget_id');
    }
    public function entity_type()
    {
        return $this->belongsTo('App\Models\Lookups\EntityType', 'entity_type_id');
    }
    public function status()
    {
        return $this->belongsTo('App\Models\Lookups\BookingStatus', 'status_id');
    }
    public function style()
    {
        return $this->belongsTo('App\Models\Lookups\Style', 'style_id');
    }
    public function uploadedStyleImage()
    {
        return $this->belongsTo('App\UploadImages', 'upload_element_id');
    }
    public function requestBooking()
    {
        return $this->hasOne('App\RequestsBooking', 'request_id');
    }
    public function request_styling_element_texts()
    {
        return $this->hasMany('App\Models\Questionnaire\StyleElementText', 'request_id');
    }
    public function reco_looks()
    {
        return $this->hasMany('App\Recommendation', 'style_request_id');
    }
    public function reco_products()
    {
        return $this->hasMany('App\Recommendation', 'style_request_id');
    }
}
