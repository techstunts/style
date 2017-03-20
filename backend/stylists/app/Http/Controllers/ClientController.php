<?php

namespace App\Http\Controllers;

use App\Client;
use App\Models\Enums\EntityType;
use App\Models\Enums\EntityTypeName;
use App\Models\Enums\RecommendationType;
use App\Models\Enums\StylistStatus;
use App\Models\Lookups\AppSections;
use App\Models\Lookups\ChatOnlineStatus;

use App\Models\Stylist\ChatOnline;
use App\Stylist;
use App\Http\Mapper\BookingMapper;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    protected $records_per_page=100;
    protected $filter_ids = ['stylist_id', 'device_status', 'gender_id', 'body_type_id', 'age_group_id'];
    protected $filters = ['stylists', 'devicesStatuses', 'genders', 'body_types', 'age_groups'];
    protected $relations = ['stylist', 'genders', 'body_type', 'body_shape', 'complexion', 'daringness', 'age_group', 'height_group'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $action, $id = null, $action_id = null)
    {
        $method = strtolower($request->method()) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
        if($id){
            $this->resource_id = $id;
        }
        if($action_id){
            $this->action_resource_id = $action_id;
        }

        return $this->$method($request);
    }

    public function getList(Request $request){
        $this->base_table = 'clients';
        $this->initWhereConditions($request);
        $this->initFilters();

        $view_properties = array(
            'stylists' => $this->stylists,
            'devicesStatuses' => $this->devicesStatuses,
            'genders' => $this->genders,
            'body_types' => $this->body_types,
            'age_groups' => $this->age_groups
        );

        $view_properties['popup_entity_type_ids'] = array(
            EntityType::LOOK,
            EntityType::PRODUCT,
            EntityType::TIP,
            EntityType::COLLECTION,
        );

        $view_properties['entity_type_names']= array(
            EntityTypeName::LOOK,
            EntityTypeName::PRODUCT,
            EntityTypeName::TIP,
            EntityTypeName::COLLECTION,
        );
        $view_properties['nav_tab_index'] = '0';

        $view_properties['search'] = $request->input('search');
        $view_properties['exact_word'] = $request->input('exact_word');

        $view_properties['from_date'] = $request->input('from_date');
        $view_properties['to_date'] = $request->input('to_date');
        $view_properties['min_discount'] = $request->input('min_discount');
        $view_properties['max_discount'] = $request->input('max_discount');
        $view_properties['show_discount_fields'] = false;

        foreach($this->filter_ids as $filter){
            $view_properties[$filter] = $request->has($filter) && $request->input($filter) !== "" ? intval($request->input($filter)) : "";
        }

        $paginate_qs = $request->query();
        unset($paginate_qs['page']);

        $authWhereClauses = $this->authWhereClauses();
        $clients =
            Client::with($this->relations)
                ->where($this->where_conditions)
                ->whereRaw($this->where_raw)
                ->whereRaw($authWhereClauses)
                ->orderBy('id', 'desc')
                ->simplePaginate($this->records_per_page)
                ->appends($paginate_qs);

        $view_properties['clients'] = $clients;
        $view_properties['app_sections'] = AppSections::all();
        $view_properties['recommendation_type_id'] = RecommendationType::MANUAL;
        $view_properties['show_price_filters'] = 'YES';
        $view_properties['is_super_admin'] = Auth::user()->hasRole('superadmin') ? true : false;
        return view('client.list', $view_properties);
    }


    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getView(Request $request)
    {
        $authWhereClauses = $this->authWhereClauses($request);
        $client = Client::with('genders')
                ->whereRaw($authWhereClauses)
                ->find($this->resource_id);
        if($client){
            $view_properties = array('client' => $client);
        }
        else{
            return view('404', array('title' => 'Client not found'));
        }

        return view('client.view', $view_properties);
    }

    protected function authWhereClauses($request = null){
        $where = "1=1";
        $stylist = Auth::user();
        $booking_id = $request ? $request->input('booking_id') : '';
        if(!$stylist->hasRole('admin') && !env('ANY_STYLIST_CAN_APPROACH_ANY_CLIENT')){
            if (!empty($booking_id)) {
                $bookingMapperObj = new BookingMapper();
                $booking_exists = $bookingMapperObj->userBookedStylist($this->resource_id, $stylist->id, $booking_id);
                if (!$booking_exists) {
                    $where .= " AND stylist_id = " . $stylist->id;
                }
            } else {
                $where .= " AND stylist_id = " . $stylist->id;
            }
        }
        return $where;
    }

    public function getChat(Request $request)
    {
        $authorised_stylists_for_chat_as_admin = [63, 76];

        $stylists=[];
        $stylist = Auth::user();
        $stylist_id_to_chat = $stylist->id;

        $is_admin = $stylist->hasRole('admin');

        $is_authorised_for_chat_as_admin = in_array($stylist_id_to_chat, $authorised_stylists_for_chat_as_admin);
        if(!$is_admin && $stylist->status_id != StylistStatus::Active
            && !$is_authorised_for_chat_as_admin){
            return redirect('look/list')->withError('Chat access denied!');
        }

        if($is_admin || $is_authorised_for_chat_as_admin){
            $stylists = Stylist::whereIn('status_id',[StylistStatus::Active])
                ->orderBy('name')->get();
            $stylist_id_to_chat = $request->input('stylist_id') ? $request->input('stylist_id') : $stylist_id_to_chat;
        }

        $online_statuses = ChatOnlineStatus::get();
        $stylist_online_status = ChatOnline::where('stylist_id', $stylist_id_to_chat)->orderBy('created_at', 'desc')->limit(1)->first();

        $view_properties['stylist_id_to_chat'] = $stylist_id_to_chat;
        $view_properties['stylists'] = $stylists;
        $view_properties['is_admin'] = $is_admin;
        $view_properties['is_authorised_for_chat_as_admin'] = $is_authorised_for_chat_as_admin;
        $view_properties['online_statuses'] = $online_statuses;
        //dd($stylist_online_status);
        $view_properties['stylist_online_status'] = $stylist_online_status ? $stylist_online_status->chat_online_status_id : "";

        return view('client/chat', $view_properties);
    }

}
