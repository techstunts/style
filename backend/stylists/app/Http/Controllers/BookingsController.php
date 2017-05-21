<?php

namespace App\Http\Controllers;

use App\Client;
use App\Models\Enums\BookingStatus;
use App\Models\Enums\EntityType;
use App\Models\Lookups\Lookup;
use App\Stylist;
use Illuminate\Http\Request;
use App\Http\Mapper\BookingMapper;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;

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
        $view_properties['is_admin'] = $bookingsMapperObj->isAdmin();
        return view('bookings.view', $view_properties);
    }

    public function getSendReminders()
    {
        $this->base_table = 'bookings';

        $ts_window = time() + env('BOOKING_REMINDER_TIME_WINDOW');
        $current_ts = time();
        echo $ts_window;
        $where_conditions['date'] = date('Y-m-d');
        $where_conditions['status_id'] = BookingStatus::Confirm;
        $where_conditions['reminders_sent_count'] = 0;

        $bookingsMapperObj = new BookingMapper();
        $bookings = $bookingsMapperObj->getReminderList($where_conditions);

        if(count($bookings) == 0){
            echo "\n" . date("d-m-Y h:i:s") . " No bookings scheduled for email reminder";
        }

        foreach($bookings as $booking){
            $booking_ts = strtotime($booking->date . ' ' . explode(' ', $booking->slot->name)[0]);
            echo "\n" . $booking->id . " " . $booking_ts . "\n";

            $booking_readable_datetime = date("h:i a", $booking_ts) . ' on ' . date("M d", $booking_ts);

            if($booking_ts <= $ts_window && $booking_ts > $current_ts){
                try{
                    $this->sendReminderMail($booking->client, $booking->stylist, $booking_readable_datetime);
                    $booking->reminders_sent_count = $booking->reminders_sent_count + 1;
                    $booking->save();
                }
                catch(Exception $e){
                    echo "\n Exception : " . $e->getMessage();
                }
                echo "\n Sent\n";
            }
        }

        return true;

    }

    public function sendReminderMail($client, $stylist, $booking_readable_datetime){


        $words = explode(" ", $stylist->name);
        $stylist_first_name = $words[0];

        $words = explode(" ", $client->name);
        $client_first_name = strtoupper(substr($words[0], 0, 1)) . strtolower(substr($words[0], 1)) ;

        $recommendation_template = env('IS_NICOBAR') ? ('emails.booking_reminder') : ('emails.booking_reminder');
        Mail::send($recommendation_template,

            [
                'client' => $client, 'stylist' => $stylist,
                'stylist_first_name' => $stylist_first_name,
                'client_first_name' => $client_first_name,
                'booking_readable_datetime' => $booking_readable_datetime,
                'nicobar_website' => env('NICOBAR_WEBSITE'),
                'chat_link' => env('CHAT_LINK')
            ],
            function ($mail) use ($client, $stylist) {
                $mail->from(env('FROM_EMAIL'), (env('IS_NICOBAR') ? 'Nicobar' : 'IStyleYou'));
                $mail->to($client->email, $client->name)
                //$mail->to('amit.istyleyou@gmail.com', $client->name)
                    ->bcc('stylists@nicobar.com')
                    ->bcc('amit.istyleyou@gmail.com')
                    ->subject(env('BOOKING_REMINDER_EMAIL_SUBJECT'));
            });
    }
}
