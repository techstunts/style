<?php

namespace App\Http\Controllers;

use App\Client;
use App\Look;
use App\Models\Enums\EntityType;
use App\Models\Enums\Gender;
use App\Product;
use App\Models\Lookups\Status;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        foreach($this->filter_ids as $filter){
            $view_properties[$filter] = $request->has($filter) && $request->input($filter) !== "" ? intval($request->input($filter)) : "";
        }

        $paginate_qs = $request->query();
        unset($paginate_qs['page']);

        $authWhereClauses = $this->authWhereClauses();
        $clients =
            Client::where($this->where_conditions)
                ->whereRaw($authWhereClauses)
                ->orderBy('user_id', 'desc')
                ->simplePaginate($this->records_per_page)
                ->appends($paginate_qs);

        $view_properties['clients'] = $clients;
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

}
