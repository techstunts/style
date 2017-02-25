<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestsBooking extends Model
{
    protected $table = 'requests_books_map';
    public $timestamps = false;

    public function booking()
    {
        return $this->belongsTo('App\Booking', 'booking_id');
    }
    public function request()
    {
        return $this->belongsTo('App\StyleRequests', 'request_id');
    }
}