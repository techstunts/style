<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Enums\EntityType;
use App\Models\Enums\EntityTypeName;
use App\Models\Enums\RecommendationType;
use App\Models\Lookups\AppSections;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
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

        $view_properties['from_date'] = $request->input('from_date');
        $view_properties['to_date'] = $request->input('to_date');

        $paginate_qs = $request->query();
        unset($paginate_qs['page']);

        if(Auth::user()->hasRole('stylist')){
                $this->where_raw = $this->where_raw. " AND (stylists.stylish_id = ".Auth::user()->stylish_id.")";
        }
        $this->where_raw = $this->where_raw. " AND recommendations.style_request_id is NULL";

        $requests  = DB::table($this->base_table)
                ->join('userdetails', $this->base_table . '.user_id', '=', 'userdetails.user_id')
                ->join('stylists', 'userdetails.stylish_id', '=', 'stylists.stylish_id')
                ->join('lu_budget', 'lu_budget.id', '=', $this->base_table.'.budget_id')
                ->join('lu_occasion', 'lu_occasion.id', '=', $this->base_table.'.occasion_id')
                ->join('lu_entity_type', 'lu_entity_type.id', '=', $this->base_table.'.entity_type_id')
                ->leftJoin('recommendations', $this->base_table.'.id', '=', 'recommendations.style_request_id')
                ->where($this->where_conditions)
                ->whereRaw($this->where_raw)
                ->select($this->base_table.'.id as request_id', 'userdetails.user_id', 'userdetails.username',
                    'stylists.stylish_id', 'stylists.name as stylist_name', 'userdetails.age', 'userdetails.bodytype',
                    'userdetails.user_id', 'lu_budget.name as budget', 'lu_occasion.name as occasion',
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
