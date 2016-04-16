<?php

namespace App\Http\Controllers;

use App\Client;
use App\Models\Enums\EntityType;
use App\Models\Enums\EntityTypeName;
use App\Models\Enums\RecommendationType;
use App\Models\Enums\StylistStatus;
use App\Models\Lookups\AppSections;
use App\Stylist;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    protected $filter_ids = ['stylish_id',];
    protected $filters = ['stylists',];
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
        $this->base_table = 'userdetails';
        $this->initWhereConditions($request);
        $this->initFilters();

        $view_properties = array(
            'stylists' => $this->stylists,
        );

        $view_properties['popup_entity_type_ids'] = array(
            EntityType::LOOK,
            EntityType::PRODUCT
        );

        $view_properties['entity_type_names']= array(
            EntityTypeName::LOOK,
            EntityTypeName::PRODUCT
        );
        $view_properties['nav_tab_index'] = '0';

        foreach($this->filter_ids as $filter){
            $view_properties[$filter] = $request->has($filter) && $request->input($filter) !== "" ? intval($request->input($filter)) : "";
        }

        $paginate_qs = $request->query();
        unset($paginate_qs['page']);

        $authWhereClauses = $this->authWhereClauses();
        $clients =
            Client::with('stylist')
                ->where($this->where_conditions)
                ->whereRaw($authWhereClauses)
                ->orderBy('user_id', 'desc')
                ->simplePaginate($this->records_per_page)
                ->appends($paginate_qs);

        $view_properties['clients'] = $clients;
        $view_properties['app_sections'] = AppSections::all();
        $view_properties['recommendation_type_id'] = RecommendationType::MANUAL;
        $view_properties['show_price_filters'] = 'YES';
        return view('client.list', $view_properties);
    }


    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getView()
    {
        $authWhereClauses = $this->authWhereClauses();
        $client = Client::whereRaw($authWhereClauses)
                ->find($this->resource_id);
        if($client){
            $view_properties = array('client' => $client);
        }
        else{
            return view('404', array('title' => 'Client not found'));
        }

        return view('client.view', $view_properties);
    }

    protected function authWhereClauses(){
        $where = "1=1";
        if(!Auth::user()->hasRole('admin')){
            $where .= " AND stylish_id = " . Auth::user()->stylish_id;
        }
        return $where;
    }

    public function getChat(Request $request)
    {
        $authorised_stylists_for_chat = [36, 49, 66];
        $stylists=[];
        $stylist_id_to_chat = Auth::user()->stylish_id;

        $is_admin = Auth::user()->hasRole('admin');
        if(!$is_admin && !in_array($stylist_id_to_chat, $authorised_stylists_for_chat)){
            return redirect('look/list')->withError('Chat access denied!');
        }

        if($is_admin){
            $stylists = Stylist::whereIn('status_id',[StylistStatus::Active, StylistStatus::Inactive])
                ->orderBy('name')->get();
            $stylist_id_to_chat = $request->input('stylist_id') ? $request->input('stylist_id') : $stylist_id_to_chat;
        }

        $view_properties['stylist_id_to_chat'] = $stylist_id_to_chat;
        $view_properties['stylists'] = $stylists;
        $view_properties['is_admin'] = $is_admin;

        return view('client/chat', $view_properties);
    }

}
