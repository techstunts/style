<?php
namespace App\Http\Mapper;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Bookings\Booking;

class BookingMapper extends Controller
{
    public function getList($request)
    {
        if(Auth::user()->hasRole('stylist')){
            $this->where_raw = $this->where_raw. " AND (stylist_id = ".Auth::user()->id.")";
        }

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
            ->whereRaw($this->where_raw)
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
}