<?php

namespace App\Http\Controllers;

use App\Collection;
use App\Look;
use App\Models\Enums\EntityType;
use App\Models\Enums\EntityTypeName;
use App\Models\Enums\Gender;
use App\Product;
use App\Models\Lookups\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lookups\AppSections;
use App\Models\Enums\RecommendationType;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Validator;

class CollectionController extends Controller
{
    
    protected $filter_ids   = ['age_group_id', 'gender_id','created_by', 'body_type_id', 'budget_id', 'occasion_id', 'status_id'];
    protected $filters      = ['age_groups', 'genders','createdBy', 'body_types', 'budgets', 'occasions', 'statuses'];
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

    public function getList(Request $request)
    {
        $this->base_table = 'collections';
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
        
        $view_properties['nav_tab_index'] = '0';
        $user_data = Auth::user();
        $entity_nav_tabs = array(
            EntityType::CLIENT
        );

        $view_properties['entity_type_names']= array(
            EntityTypeName::CLIENT
        );
        
        $paginate_qs = $request->query();
        unset($paginate_qs['page']);

        $collections =
            Collection::with('gender','status','body_type','budget','occasion','age_group')
                ->where($this->where_conditions)
                ->orderBy('id', 'desc')
                ->simplePaginate($this->records_per_page)
                ->appends($paginate_qs);

        $view_properties['collections'] = $collections;
        $view_properties['search']      = $request->input('search');
        $view_properties['exact_word']  = $request->input('exact_word');
        $view_properties['from_date']   = $request->input('from_date');
        $view_properties['to_date']     = $request->input('to_date');

        $view_properties['min_price']   = $request->input('min_price');
        $view_properties['max_price']   = $request->input('max_price');
        
        $view_properties['app_sections']            = AppSections::all();
        $view_properties['logged_in_stylist_id']    = $user_data->id;
        $view_properties['popup_entity_type_ids']   = $entity_nav_tabs;
        $view_properties['entity_type_to_send']     = EntityType::LOOK;
        $view_properties['recommendation_type_id']  = RecommendationType::MANUAL;
        $view_properties['is_owner_or_admin']       = Auth::user()->hasRole('admin');
        
        return view('collection.list', $view_properties);
    }


    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getView()
    {
        $female_entities = $male_entities = [];
        $collection = Collection::find($this->resource_id);
        if($collection){
            $entity_ids = DB::table('collection_entities')
                ->where('collection_id', $collection->id)
                ->select('entity_id', 'entity_type_id')
                ->get();
            foreach($entity_ids as $data){
                $entity = '';
                if($data->entity_type_id == EntityType::LOOK){
                    $entity = array(EntityType::LOOK, Look::find($data->entity_id));
                }
                else if($data->entity_type_id == EntityType::PRODUCT){
                    $entity = array(EntityType::PRODUCT, Product::find($data->entity_id));
                }
                else{
                    continue;
                }
                if(isset($entity[1]->id)){
                    if($entity[1]->gender_id == Gender::Female)
                        $female_entities[] = $entity;
                    else if($entity[1]->gender_id == Gender::Male)
                        $male_entities[] = $entity;
                }
            }
            $status = Status::find($collection->status_id);
            //var_dump($collection, $collection->stylist, $product_ids, $products);
            $view_properties = array('collection' => $collection,
                'female_entities' => $female_entities,
                'male_entities' => $male_entities,
                'status' => $status);
            $view_properties['is_owner_or_admin'] = Auth::user()->hasRole('admin') || $collection->stylist_id == Auth::user()->id;
        }
        else{
            return view('404', array('title' => 'collection not found'));
        }

        return view('collection.view', $view_properties);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEdit(Request $request)
    {
        if ( empty($this->resource_id) ) {
            Redirect::back()->withError('Collection Not Found');
        }
        
        $collection = Collection::find($this->resource_id);
        $view_properties = null;
        
        if ($collection) {
            
            $this->base_table = 'collections';
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
            
            $view_properties['collection']      = $collection;
            $view_properties['gender_id']       = intval($collection->gender_id);
            $view_properties['status_id']       = intval($collection->status_id);
            $view_properties['occasion_id']     = intval($collection->occasion_id);
            $view_properties['age_group_id']    = intval($collection->age_group_id);
            $view_properties['budget_id']       = intval($collection->budget_id);
            $view_properties['body_type_id']    = intval($collection->body_type_id);
            
        } 
        else {
            return view('404', array('title' => 'Collection not found'));
        }
        
        return view('collection.edit', $view_properties);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postUpdate(Request $request)
    {
        $validator = $this->validator($request->all());
        
        if($validator->fails()) {
            return redirect('collection/edit/' . $this->resource_id)
                   ->withErrors($validator)
                   ->withInput();
        }

        $collection               = Collection::find($this->resource_id);
        $collection->name         = isset($request->name) && $request->name != '' ? $request->name : '';
        $collection->description  = isset($request->description) && $request->description != '' ? $request->description : '';
        $collection->age_group_id = isset($request->age_group_id) && $request->age_group_id != '' ? $request->age_group_id : '';
        $collection->body_type_id = isset($request->body_type_id) && $request->body_type_id != '' ? $request->body_type_id : '';
        $collection->budget_id    = isset($request->budget_id) && $request->budget_id != '' ? $request->budget_id : '';
        $collection->gender_id    = isset($request->gender_id) && $request->gender_id != '' ? $request->gender_id : '';
        $collection->occasion_id  = isset($request->occasion_id) && $request->occasion_id != '' ? $request->occasion_id : '';

        if ($collection->save()) {
            return redirect('collection/view/' . $this->resource_id);
        } 
        else {
            return Redirect::back()->withError('Error occur while updating the collection');
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
