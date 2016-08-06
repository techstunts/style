<?php
namespace App\Http\Mapper;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Bookings\Booking;

class BookingMapper extends Controller
{
    public function getList($request, $where_conditions = [], $where_raw = "1=1")
    {
        $paginate_qs = $request->query();
        unset($paginate_qs['page']);

        $client = function ($query) {
            $query->with('genders');
            $query->select('id', 'name','image', 'email', 'gender_id');
        };

        $stylist = function ($query) {
            $query->select('id', 'name');
        };

        $bookings = Booking::with(['client' => $client, 'slot', 'stylist' => $stylist])
            ->where($where_conditions)
            ->whereRaw($where_raw)
            ->orderBy('id', 'desc')
            ->simplePaginate($this->records_per_page)
            ->appends($paginate_qs);
        return $bookings;
    }

    public function isAdmin()
    {
        if (Auth::user()->hasRole('admin')) {
            return true;
        } else {
            return false;
        }
    }

    public function userBookedStylist($client_id, $stylist_id, $booking_id)
    {
        return Booking::where(['id' => $booking_id, 'client_id' => $client_id, 'stylist_id' => $stylist_id])
            ->where('status_id', 1)
            ->exists();
    }
}