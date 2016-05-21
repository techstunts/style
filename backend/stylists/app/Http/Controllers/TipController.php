<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Enums\EntityType;
use App\Models\Enums\EntityTypeName;
use App\Models\Enums\RecommendationType;
use App\Models\Lookups\AppSections;
use App\Tip;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Validator;

class TipController extends Controller
{
    
    protected $filter_ids = [];
    protected $filters    = [];

    
    public function index(Request $request, $action, $id = null, $action_id = null)
    {
        $method = strtolower($request->method()) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
        
        if($id) {
            
            $this->resource_id = $id;
            
        }
        
        if($action_id) {
            
            $this->action_resource_id = $action_id;
            
        }

        return $this->$method($request);
    }

    
    public function getList(Request $request)
    {
        $this->base_table = 'tips';
        $this->initWhereConditions($request);
        $this->initFilters();
        
        $view_properties = array(
            'stylists' => $this->stylists,
            'statuses' => $this->statuses,
            'genders' => $this->genders,
            'occasions' => $this->occasions,
            'body_types' => $this->body_types,
            'budgets' => $this->budgets,
            'age_groups' => $this->age_groups
        );
        
        $entity_nav_tabs = array(
            EntityType::CLIENT
        );

        $view_properties['entity_type_names']= array(
            EntityTypeName::CLIENT
        );
        
        $remove_deleted_looks = '1=1';
        $paginate_qs = $request->query();
        unset($paginate_qs['page']);
        
        $tips  = Tip::where($this->where_conditions)
                       ->whereRaw($this->where_raw)
                       ->whereRaw($remove_deleted_looks)
                       ->orderBy('id', 'desc')
                       ->simplePaginate($this->records_per_page)
                       ->appends($paginate_qs);
         
         $view_properties['tips'] = $tips;
        
        $view_properties['nav_tab_index'] = '0';
        $user_data = Auth::user();
        
        $view_properties['search'] = $request->input('search');
        $view_properties['exact_word'] = $request->input('exact_word');
        $view_properties['from_date'] = $request->input('from_date');
        $view_properties['to_date'] = $request->input('to_date');

        $view_properties['min_price'] = $request->input('min_price');
        $view_properties['max_price'] = $request->input('max_price');
        
        $view_properties['app_sections'] = AppSections::all();
        $view_properties['logged_in_stylist_id'] = $user_data->id;
        $view_properties['popup_entity_type_ids'] = $entity_nav_tabs;
        $view_properties['entity_type_to_send'] = EntityType::LOOK;
        $view_properties['recommendation_type_id'] = RecommendationType::MANUAL;
        $view_properties['is_owner_or_admin'] = Auth::user()->hasRole('admin');
        
        return view('tip.list', $view_properties);

    }
    
    
}
