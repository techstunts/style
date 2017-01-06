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
}
