<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Enums\EntityType;
use App\Models\Enums\EntityTypeName;
use App\Models\Enums\RecommendationType;
use App\Models\Lookups\Lookup;
use App\Models\Lookups\AppSections;
use App\Models\Lookups\Status;
use App\Tip;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Validator;

class TipController extends Controller
{
    
    protected $filter_ids   = ['age_group_id', 'gender_id','created_by', 'body_type_id', 'budget_id', 'occasion_id', 'status_id'];
    protected $filters      = ['age_groups', 'genders','createdBy', 'body_types', 'budgets', 'occasions', 'statuses'];

    
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

    public function getCreate(Request $request)
    {
        $this->base_table = 'tips';
        $this->initWhereConditions($request);
        $this->initFilters();
        
        $view_properties = array(
            'stylists' => $this->createdBy,
            'statuses' => $this->statuses,
            'genders' => $this->genders,
            'occasions' => $this->occasions,
            'body_types' => $this->body_types,
            'budgets' => $this->budgets,
           'age_groups' => $this->age_groups
        );
        
        foreach ($this->filter_ids as $filter) {
            $view_properties[$filter] = $request->has($filter) && $request->input($filter) !== "" ? intval($request->input($filter)) : "";
        }
        $view_properties['stylist_id'] = $request->has('stylist_id') && $request->input('stylist_id') !== "" ? intval($request->input('stylist_id')) : "";
        
        return view('tip.create', $view_properties);
    }
    
    public function postCreate(Request $request)
    {
        
        $tip = new Tip();
        
        $tip->name          = isset($request->name) && $request->name != '' ? $request->name : '';
        $tip->description   = isset($request->description) && $request->description != '' ? $request->description : '';
        $tip->image         = isset($request->image) && $request->image != '' ? $request->image : '';
        $tip->image_url     = isset($request->image_url) && $request->image_url != '' ? $request->image_url : '';
        $tip->video_url     = isset($request->video_url) && $request->video_url != '' ? $request->video_url : '';
        $tip->external_url  = isset($request->external_url) && $request->external_url != '' ? $request->external_url : '';
        $tip->budget_id     = isset($request->budget_id) && $request->budget_id != '' ? $request->budget_id : '';
        $tip->age_group_id  = isset($request->age_group_id) && $request->age_group_id != '' ? $request->age_group_id : '';
        $tip->body_type_id  = isset($request->body_type_id) && $request->body_type_id != '' ? $request->body_type_id : '';
        $tip->occasion_id   = isset($request->occasion_id) && $request->occasion_id != '' ? $request->occasion_id : '';
        $tip->gender_id     = isset($request->gender_id) && $request->gender_id != '' ? $request->gender_id : '';
        $tip->created_by    = $request->user()->id != '' ? $request->user()->id : '';
        
        $tip->created_at    = date('Y-m-d H:i:s');
        
        if ($tip->save()) {
            return redirect('tip/view/' . $tip->id);
        } else {
            Redirect::back()->withError('Error occur while creating a tip');
        }

    }

    
    public function getList(Request $request)
    {
        $this->base_table = 'tips';
        $this->initWhereConditions($request);
        $this->initFilters();
        
        $view_properties = array(
            'stylists' => $this->createdBy,
            'statuses' => $this->statuses,
            'genders' => $this->genders,
            'occasions' => $this->occasions,
            'body_types' => $this->body_types,
            'budgets' => $this->budgets,
           'age_groups' => $this->age_groups
        );
        
        foreach ($this->filter_ids as $filter) {
            $view_properties[$filter] = $request->has($filter) && $request->input($filter) !== "" ? intval($request->input($filter)) : "";
        }
        $view_properties['stylist_id'] = $request->has('stylist_id') && $request->input('stylist_id') !== "" ? intval($request->input('stylist_id')) : "";
        
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
  
    public function getView()
    {
        $tip                = Tip::find($this->resource_id);
        $view_properties    = [];
        
        if ($tip) {
            $products           = $tip->products;
            $status             = Status::find($tip->status_id);
            
            $view_properties    = array('tip' => $tip, 'products' => $products, 'stylist' => $tip->stylist, 'status' => $status);
            $view_properties['is_owner_or_admin'] = Auth::user()->hasRole('admin') || $tip->stylist_id == Auth::user()->id;
            
        } 
        else {
            return view('404', array('title' => 'Tip not found'));
        }
        
        return view('tip.view', $view_properties);
    }
    
    public function getEdit()
    {
        if ( empty($this->resource_id) ) {
            Redirect::back()->withError('Tip Not Found');
        }
        
        $tip = Tip::find($this->resource_id);
        $view_properties = null;
        
        if ($tip) {
            $lookup = new Lookup();
            
            $view_properties['tip']             = $tip;
            $view_properties['gender_id']       = intval($tip->gender_id);
            $view_properties['genders']         = $lookup->type('gender')->get();
            $view_properties['status_id']       = intval($tip->status_id);
            $view_properties['statuses']        = $lookup->type('status')->get();
            $view_properties['occasion_id']     = intval($tip->occasion_id);
            $view_properties['occasions']       = $lookup->type('occasion')->get();
            $view_properties['age_group_id']    = intval($tip->age_group_id);
            $view_properties['age_groups']      = $lookup->type('age_group')->get();
            $view_properties['budget_id']       = intval($tip->budget_id);
            $view_properties['budgets']         = $lookup->type('budget')->get();
            $view_properties['body_type_id']    = intval($tip->body_type_id);
            $view_properties['body_types']      = $lookup->type('body_type')->get();
        } 
        else {
            return view('404', array('title' => 'Tip not found'));
        }
        
        return view('tip.edit', $view_properties);
    }
    
    public function postUpdate(Request $request)
    {
        $validator = $this->validator($request->all());
        
        if($validator->fails()) {
            return redirect('tip/edit/' . $this->resource_id)
                   ->withErrors($validator)
                   ->withInput();
        }

        $tip               = Tip::find($this->resource_id);
        $tip->name         = isset($request->name) && $request->name != '' ? $request->name : '';
        $tip->description  = isset($request->description) && $request->description != '' ? $request->description : '';
        $tip->age_group_id = isset($request->age_group_id) && $request->age_group_id != '' ? $request->age_group_id : '';
        $tip->body_type_id = isset($request->body_type_id) && $request->body_type_id != '' ? $request->body_type_id : '';
        $tip->budget_id    = isset($request->budget_id) && $request->budget_id != '' ? $request->budget_id : '';
        $tip->gender_id    = isset($request->gender_id) && $request->gender_id != '' ? $request->gender_id : '';
        $tip->occasion_id  = isset($request->occasion_id) && $request->occasion_id != '' ? $request->occasion_id : '';
        $tip->image_url    = isset($request->image_url) && $request->image_url != '' ? $request->image_url : '';
        $tip->video_url    = isset($request->video_url) && $request->video_url != '' ? $request->video_url : '';

        if ($tip->save()) {
            return redirect('tip/view/' . $this->resource_id);
        } 
        else {
            return Redirect::back()->withError('Error occur while updating the tip');
        }

    }
    
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:256|min:5',
            'description' => 'required|min:25',
            'body_type_id' => 'required',
            'budget_id' => 'required',
            'age_group_id' => 'required',
            'occasion_id' => 'required',
            'gender_id' => 'required',
        ]);
    }
}
