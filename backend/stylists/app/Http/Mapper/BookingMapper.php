<?php
namespace App\Http\Mapper;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Bookings\Booking;
use App\Models\Lookups\BookingStatus;
use App\Models\Enums\BookingStatus as BookingStatusEnum;
use App\Models\Enums\EntityType;
use Illuminate\Support\Facades\DB;

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
            $query->select('id', 'name', 'category_id');
        };

        $select_fields = ['id', 'stylist_id', 'client_id', 'mobile', 'slot_id', 'status_id', 'service', 'price', 'message',
            'cancelled_by_entity_type_id', 'cancelled_by_entity_id', 'reason','created_at', 'updated_at', 'country_id', 'category_id'];
        if (env('IS_NICOBAR')){
            $select_fields[] =  DB::raw("to_char(date, 'DD-Mon-YYYY') as date");
        } else {
            $select_fields[] =  DB::raw("DATE_FORMAT(date, '%d-%b-%Y') as date");
        }
        $bookings = Booking::with(['client' => $client, 'slot', 'stylist' => $stylist, 'status', 'bookingRequest.request', 'country', 'category'])
            ->select($select_fields)
            ->where($where_conditions)
            ->where('account_id', $request->user()->account_id)
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

    public function validStatus($request)
    {
        $status_id = $request->input('status_id');
        if (empty($status_id)) {
            return false;
        }
        return BookingStatus::where('id', $status_id)->exists();
    }
    public function updatePossible($request)
    {
        $status_id = $request->input('status_id');
        $booking_id = $request->input('booking_id');

        return !(Booking::where(['id' => $booking_id])
            ->where(function($query) use ($status_id){
                $query->where('status_id', [$status_id])
                    ->orWhereIn('status_id', [BookingStatusEnum::Conducted, BookingStatusEnum::Canceled_by_stylist, BookingStatusEnum::Canceled_by_client]);
            })
            ->exists());
    }
    public function updateStatus($request)
    {
        $data = array(
            'status_id' => $request->input('status_id'),
            'cancelled_by_entity_type_id' => EntityType::STYLIST,
            'cancelled_by_entity_id' => Auth::user()->id,
            'reason' => $request->input('reason'),
        );
        try{
            Booking::where('id', $request->input('booking_id'))->update($data);
            $status = true;
            $message = 'Status updated successfully';
        } catch(\Exception $e) {
            $status = false;
            $message = 'Status update error : ' . $e->getMessage();
        }
        return array(
            'status' => $status,
            'message' => $message,
        );
    }

    public function getReminderList($where_conditions = [], $where_raw = "1=1")
    {

        $client = function ($query) {
            $query->with('genders');
            $query->select('id', 'name','image', 'email', 'gender_id');
        };

        $stylist = function ($query) {
            $query->with('category');
            $query->select('id', 'name', 'category_id');
        };

        $bookings = Booking::with(['client' => $client, 'slot', 'stylist' => $stylist, 'status', 'bookingRequest.request', 'country'])
            ->select(['id', 'stylist_id', 'client_id', 'mobile', DB::raw("to_char(date, 'DD-Mon-YYYY') as date"), 'slot_id', 'status_id', 'service', 'price', 'message',
                'cancelled_by_entity_type_id', 'cancelled_by_entity_id', 'reason','created_at', 'updated_at', 'country_id'])
            ->where($where_conditions)
            ->whereRaw($where_raw)
            ->orderBy('id')
            ->get();
        return $bookings;
    }
}