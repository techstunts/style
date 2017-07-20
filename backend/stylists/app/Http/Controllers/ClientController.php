<?php

namespace App\Http\Controllers;

use App\Client;
use App\Models\Enums\DeviceStatus;
use App\Models\Enums\EntityType;
use App\Models\Enums\EntityTypeName;
use App\Models\Enums\Gender;
use App\Models\Enums\RecommendationType;
use App\Models\Enums\StylistStatus;
use App\Models\Lookups\AppSections;
use App\Models\Lookups\ChatOnlineStatus;
use Illuminate\Support\Facades\DB;

use App\Models\Stylist\ChatOnline;
use App\Stylist;
use App\Http\Mapper\BookingMapper;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Validator;
use Illuminate\Support\Facades\Redirect;

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
        );

        $view_properties['entity_type_names']= array(
            EntityTypeName::LOOK,
            EntityTypeName::PRODUCT,
        );
        if (!env('IS_NICOBAR')){
            array_push($view_properties['popup_entity_type_ids'], EntityType::TIP, EntityType::COLLECTION);
            array_push($view_properties['entity_type_names'], EntityTypeName::TIP, EntityTypeName::COLLECTION);
        }

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
                ->where(['account_id' => $request->user()->account_id])
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
        if (env('IS_NICOBAR')) {
            $view_properties = array(
                'api_origin' => env('API_ORIGIN'),
                'client_id' => $this->resource_id,
            );
            return view('client.view', $view_properties);
        }
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
        $all_stylist_online_status = [];

        $is_admin = $stylist->hasRole('admin');

        $is_authorised_for_chat_as_admin = in_array($stylist_id_to_chat, $authorised_stylists_for_chat_as_admin);
        if(!$is_admin && $stylist->status_id != StylistStatus::Active
            && !$is_authorised_for_chat_as_admin){
            return redirect('look/list')->withError('Chat access denied!');
        }

        if($is_admin || $is_authorised_for_chat_as_admin){
            $stylists = Stylist::whereIn('status_id',[StylistStatus::Active])->where('account_id', $request->user()->account_id)
                ->orderBy('name')->get();

            //select `s1`.*, `lc`.`name` as `chat_online_status` from `stylist_chat_online` as `s1` left join `stylist_chat_online` as `s2` on `s1`.`stylist_id` = `s2`.`stylist_id` and `s1`.`id` < `s2`.`id` inner join `lu_chat_online_status` as `lc` on `s1`.`chat_online_status_id` = `lc`.`id` where `s2`.`id` is null
            $stylists_online = DB::table('stylist_chat_online as s1')
                        ->leftJoin('stylist_chat_online as s2', function ($join) {
                            $join->on('s1.stylist_id', '=', 's2.stylist_id')
                                ->on('s1.id', '<', 's2.id');
                        })
                        ->join('lu_chat_online_status as lc', 's1.chat_online_status_id', '=', 'lc.id')
                        ->whereNull('s2.id')
                        ->select('s1.*', 'lc.name as chat_online_status')
                        ->get();

            foreach($stylists_online as $k){
                $all_stylist_online_status[$k->stylist_id] = $k->chat_online_status;
            }


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
        $view_properties['all_stylist_online_status'] = $all_stylist_online_status;
        $view_properties['account_id'] = $request->user()->account_id;

        return view('client/chat', $view_properties);
    }

    public function getGetcsv ()
    {
        return view('client/updatecsv');
    }
    public function postUpdatecsv (Request $request)
    {
        $file = $request->file('clients');
        $data = array(
            'clients' => $file,
            'extension' => $file->getClientOriginalExtension(),
        );
        $validator = Validator::make($data, [
            'clients' => 'required',
            'extension' => 'required|in:csv',
        ]);
        if ($validator->fails()) {
            return Redirect::back()
                  ->withErrors($validator);
        }
        $clients = $this->formatData($request);
        $existing_clients = Client::whereIn('email', array_keys($clients))->where(['account_id' => $request->user()->account_id])->select(['email'])->get();
        $existing_clients_arr = array();
        foreach ($existing_clients as $existing_client) {
            $existing_clients_arr[] = $existing_client->email;
        }
        unset($existing_clients);
        $new_clients = array_diff(array_keys($clients), $existing_clients_arr);
        $non_existing_clients = array();
        foreach ($new_clients as $new_client_email) {
            $non_existing_clients[] = $clients[$new_client_email];
        }
        if (count($non_existing_clients) > 0) {
            DB::begintransaction();
            try {
                Client::insert($non_existing_clients);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                Log::info('Exception : '. $e->getMessage());
                return view('client/updatecsv', ['exception' => 'Exception : '. $e->getMessage()]);
            }

        }
        return view('client/updatecsv', ['success' => 'Clients data import successful']);
    }


    public function formatData($request)
    {
        $index = null;
        $data = array();
        $gender = array('male' => Gender::Male, 'female' => Gender::Female);
        $additional_data = array(
            'account_id' => $request->user()->account_id,
            'stylist_id' => $request->user()->id,
            'regId' => 'excelclientdata',
            'device_status' => DeviceStatus::Inactive,
            'created_at' => date("Y-m-d H:i:s"),
        );
        if (($file = fopen($request->file('clients')->getRealPath(), 'r')) !== false)
        {
            while (($row = fgetcsv($file, 1000)) !== false)
            {
                if (!$index) {
                    $index = array();
                    foreach ($row as $item)
                        $index[] = strtolower(trim($item));
                }
                else {
                    $file_data = array_combine($index, $row);
                    $file_data['gender_id'] = isset($gender[$file_data['gender']]) ? $gender[$file_data['gender']] : Gender::NA;

                    if ($file_data['gender_id'] == Gender::Female)
                        $file_data['image'] = 'http://d36o0t9p57q98i.cloudfront.net/resources/images/android/female-v2.png';
                    elseif ($file_data['gender_id'] == Gender::Male)
                        $file_data['image'] = 'http://d36o0t9p57q98i.cloudfront.net/resources/images/android/male-v2.png';

                    $data[$file_data['email']] = array_merge($file_data, $additional_data);
                }
            }
            fclose($file);
        }
        return $data;
    }
}
