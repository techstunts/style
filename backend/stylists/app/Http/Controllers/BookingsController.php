<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Mapper\BookingMapper;
use App\Http\Requests;

class BookingsController extends Controller
{
    public function index(Request $request, $action, $id = null)
    {
        $method = strtolower($request->method()) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
        if($id){
            $this->resource_id = $id;
        }
        return $this->$method($request);
    }

    public function getList(Request $request){
        $bookingsMapperObj = new BookingMapper();
        $view_properties = array(
            'is_admin' => $bookingsMapperObj->isAdmin(),
        );
        $bookings = $bookingsMapperObj->getList($request);

        $view_properties['bookings'] = $bookings;
        return view('bookings.list', $view_properties);
    }
}
