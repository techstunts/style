<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Mapper\BookingMapper;
use App\Http\Requests;

class BookingsController extends Controller
{
    protected $filter_ids = ['stylist_id', 'status_id', ];
    protected $filters = ['stylists', 'bookingStatuses'];

    public function index(Request $request, $action, $id = null)
    {
        $method = strtolower($request->method()) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
        if($id){
            $this->resource_id = $id;
        }
        return $this->$method($request);
    }

    public function getList(Request $request)
    {
        $this->setStylistCondition();
        $this->base_table = 'bookings';
        $this->initWhereConditions($request);
        $this->initFilters();

        $bookingsMapperObj = new BookingMapper();
        $view_properties = array(
            'stylists' => $this->stylists,
            'bookingStatuses' => $this->bookingStatuses,
            'is_admin' => $bookingsMapperObj->isAdmin(),
        );

        foreach($this->filter_ids as $filter){
            $view_properties[$filter] = $request->has($filter) && $request->input($filter) !== "" ? intval($request->input($filter)) : "";
        }

        $view_properties['book_date'] = $request->input('book_date');
        $view_properties['from_date'] = $request->input('from_date');
        $view_properties['to_date'] = $request->input('to_date');
        $bookings = $bookingsMapperObj->getList($request, $this->where_conditions, $this->where_raw);

        $view_properties['bookings'] = $bookings;
        return view('bookings.list', $view_properties);
    }
}
