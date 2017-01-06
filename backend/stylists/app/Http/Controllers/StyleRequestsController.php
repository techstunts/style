<?php

namespace App\Http\Controllers;

use App\Error;
use App\Http\Mapper\StyleRequestMapper;
use App\StyleRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Enums\EntityType;
use App\Models\Enums\EntityTypeName;
use App\Models\Enums\RecommendationType;
use App\Models\Lookups\AppSections;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Validator;

class StyleRequestsController extends Controller
{
    protected $filter_ids = ['occasion_id', 'budget_id'];
    protected $filters = ['occasions', 'budgets'];

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
        return $this->$method($request);
    }

    public function getList(Request $request){
        $this->base_table = 'style_requests';
        $this->initWhereConditions($request);
        $this->initFilters();

        $view_properties = array(
            'occasions' => $this->occasions,
            'budgets' => $this->budgets,
        );

        $entity_nav_tabs = array(
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

        foreach($this->filter_ids as $filter){
            $view_properties[$filter] = $request->has($filter) && $request->input($filter) !== "" ? intval($request->input($filter)) : "";
        }

        $view_properties['from_date'] = $request->input('from_date');
        $view_properties['to_date'] = $request->input('to_date');
        $view_properties['min_discount'] = $request->input('min_discount');
        $view_properties['max_discount'] = $request->input('max_discount');
        $view_properties['show_discount_fields'] = false;

        $paginate_qs = $request->query();
        unset($paginate_qs['page']);

        if(Auth::user()->hasRole('stylist')){
                $this->where_raw = $this->where_raw. " AND (stylists.id = ".Auth::user()->id.")";
        }
        $this->where_raw = $this->where_raw. " AND recommendations.style_request_id is NULL";

        $requests  = DB::table($this->base_table)
                ->join('clients', $this->base_table . '.user_id', '=', 'clients.id')
                ->join('stylists', 'clients.stylist_id', '=', 'stylists.id')
                ->join('lu_budget', 'lu_budget.id', '=', $this->base_table.'.budget_id')
                ->join('lu_occasion', 'lu_occasion.id', '=', $this->base_table.'.occasion_id')
                ->join('lu_entity_type', 'lu_entity_type.id', '=', $this->base_table.'.entity_type_id')
                ->leftJoin('recommendations', $this->base_table.'.id', '=', 'recommendations.style_request_id')
                ->where($this->where_conditions)
                ->whereRaw($this->where_raw)
                ->select($this->base_table.'.id as request_id', 'clients.id as user_id', 'clients.name',
                    'stylists.id as stylist_id', 'stylists.name as stylist_name', 'clients.age', 'clients.bodytype',
                    'lu_budget.name as budget', 'lu_occasion.name as occasion',
                    $this->base_table.'.created_at', $this->base_table.'.description', 'lu_entity_type.name as request_type'
                )
                ->orderBy($this->base_table.'.id', 'desc')
                ->simplePaginate($this->records_per_page)
                ->appends($paginate_qs);
        $view_properties['requests'] = $requests;
        $view_properties['app_sections'] = AppSections::all();
        $view_properties['popup_entity_type_ids'] = $entity_nav_tabs;
        $view_properties['recommendation_type_id'] = RecommendationType::STYLE_REQUEST;
        $view_properties['show_price_filters'] = 'YES';

        return view('requests.list', $view_properties);
    }

    public function getView(Request $request)
    {
        if (!$this->resource_id || !ctype_digit($this->resource_id)) {
            Redirect::back()->withError('Request Not Found');
        }
        $client = function ($query) {
            $query->with(['genders']);
            $query->select(['id', 'name', 'email', 'gender_id']);
        };
        $styleRequest = StyleRequests::with(['client' => $client, 'requested_entity'])
            ->where(['id' => $this->resource_id])
//            ->select(['id', 'user_id','created_at'])
            ->first();
        if (!$styleRequest) {
            return Redirect::to('requests/list')->withError('Request Not Found');
        }

        $view_properties = array();
        $view_properties['request'] = $styleRequest;
        $styleRequestMapperObj = new StyleRequestMapper();
        $view_properties = array_merge($view_properties, $styleRequestMapperObj->popupProperties($request));
        return view('requests.view', $view_properties);
    }
}
