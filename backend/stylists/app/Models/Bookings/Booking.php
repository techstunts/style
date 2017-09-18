<?php

namespace App\Models\Bookings;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'isy_bookings';

    protected $primaryKey = 'id';

    public $timestamps = true;

    public function client(){
        return $this->belongsTo('App\Client', 'client_id');
    }
    public function slot(){
        return $this->belongsTo('App\Models\Lookups\Slot', 'slot_id');
    }
    public function stylist(){
        return $this->belongsTo('App\Stylist', 'stylist_id');
    }
    public function status(){
        return $this->belongsTo('App\Models\Lookups\BookingStatus', 'status_id');
    }
    public function bookingRequest(){
        return $this->hasOne('App\RequestsBooking', 'booking_id');
    }
    public function country(){
        return $this->belongsTo('App\Models\Lookups\Country', 'country_id');
    }
    public function category(){
        return $this->belongsTo('App\Category', 'category_id');
    }
}
