<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Client;
use App\Models\Enums\EntityType;
use App\Models\Lookups\Lookup;
use App\Stylist;
use Illuminate\Http\Request;
use App\Http\Mapper\BookingMapper;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;

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

        $lookup = new Lookup();
        $bookingsMapperObj = new BookingMapper();
        $view_properties = array(
            'stylists' => $this->stylists,
            'bookingStatuses' => $this->bookingStatuses,
            'booking_statuses_list' => $lookup->type('booking_status')->get(),
            'is_admin' => $bookingsMapperObj->isAdmin(),
        );

        foreach($this->filter_ids as $filter){
            $view_properties[$filter] = $request->has($filter) && $request->input($filter) !== "" ? intval($request->input($filter)) : "";
        }

        $view_properties['book_date'] = $request->input('book_date');
        $view_properties['from_date'] = $request->input('from_date');
        $view_properties['to_date'] = $request->input('to_date');
        $view_properties['change_status_only_one'] = true;
        $bookings = $bookingsMapperObj->getList($request, $this->where_conditions, $this->where_raw);

        $view_properties['bookings'] = $bookings;
        return view('bookings.list', $view_properties);
    }

    public function postUpdateStatus(Request $request)
    {
        $redirect = Redirect::to('/bookings/list');
        $bookingsMapperObj = new BookingMapper();

        $booking_status_id_exists = $bookingsMapperObj->validStatus($request);
        if (!$booking_status_id_exists) {
            return $redirect->with('errorMsg', 'Status does not exist')
                ->withInput($request->all());
        }

        $status_change_posssible = $bookingsMapperObj->updatePossible($request);
        if (!$status_change_posssible) {
            return $redirect->with('errorMsg', 'Booking status change not possible')
                ->withInput($request->all());
        }

        $response = $bookingsMapperObj->updateStatus($request);
        if ($response['status'] == false) {
            return $redirect->with('errorMsg', $response['message']);
        }
        return $redirect->with('successMsg', $response['message']);
    }

    public function getView(Request $request)
    {
        $booking_id = $this->resource_id;
        $view_properties = array();
        $bookingsMapperObj = new  BookingMapper();
        $bookings = $bookingsMapperObj->getList($request, ['id' => $booking_id]);
        if (count($bookings) < 1) {
            return Redirect::to('bookings/list')->withError('Booking Not Found');
        }
        $booking = $bookings[0];
        if ($booking->cancelled_by_entity_type_id == EntityType::STYLIST){
            $booking->updatedBy = Stylist::where(['id' => $booking->cancelled_by_entity_id])->select('id', 'name')->first();
        } elseif ($booking->cancelled_by_entity_type_id == EntityType::CLIENT){
            $booking->updatedBy = Client::where(['id' => $booking->cancelled_by_entity_id])->select('id', 'name')->first();
        } else {
            $bookings->updatedBy = null;
        }
        $view_properties['booking'] = $booking;
        return view('bookings.view', $view_properties);
    }
}
