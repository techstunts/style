<?php

namespace App\Http\Controllers;

use App\Collection;
use App\Models\Enums\EntityType;
use App\Models\Enums\EntityTypeName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lookups\AppSections;
use App\Models\Enums\RecommendationType;
use App\Http\Mapper\CollectionMapper;
use App\Http\Mapper\UploadMapper;

use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use Validator;

class CollectionController extends Controller
{
    
    protected $filter_ids   = ['age_group_id', 'gender_id','created_by', 'body_type_id', 'budget_id', 'occasion_id', 'status_id'];
    protected $filters      = ['age_groups', 'genders','createdBy', 'body_types', 'budgets', 'occasions', 'statuses'];

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

        $view_properties['stylist_id'] = '';
        if ($request->has('stylist_id') && $request->input('stylist_id') !== "" ) {
            $view_properties['stylist_id'] = intval($request->input('stylist_id'));
            $this->where_conditions[$this->base_table.'.created_by'] = $request->input('stylist_id');
        }

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
                ->whereRaw($this->where_raw)
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
        $view_properties['entity_type_to_send']     = EntityType::COLLECTION;
        $view_properties['recommendation_type_id']  = RecommendationType::MANUAL;
        $view_properties['is_owner_or_admin']       = Auth::user()->hasRole('admin');
        
        return view('collection.list', $view_properties);
    }

    public function getView()
    {
        if (empty($this->resource_id)) {
            return view('404', array('title' => 'Collection id not provided'));
        }
        $collectionMapperObj = new CollectionMapper();
        $collection = $collectionMapperObj->getCollectionById($this->resource_id);

        if (empty($collection)) {
            return view('404', array('title' => 'Collection not found'));
        }
        $view_properties = array(
            'collection' => $collection,
            'is_owner_or_admin' => Auth::user()->hasRole('admin') || $collection->created_by == Auth::user()->id,
        );

        return view('collection.view', $view_properties);
    }

    public function getEdit(Request $request)
    {
        if (empty($this->resource_id)) {
            Redirect::back()->withError('Collection Not Found');
        }
        $collectionMapperObj = new CollectionMapper();
        $collection = $collectionMapperObj->getCollectionById($this->resource_id);
        $view_properties = null;

        if ($collection) {
            $view_properties = $collectionMapperObj->getDropDowns();

            $view_properties = array_merge($view_properties, $collectionMapperObj->getViewProperties($request->old(), $collection));
            $view_properties = array_merge($view_properties, $collectionMapperObj->getPopupProperties($request));

            $view_properties = array_merge($view_properties, ['collection' => $collection]);

        } else {
            return view('404', array('title' => 'Collection not found'));
        }

        return view('collection.edit', $view_properties);
    }

    public function postUpdate(Request $request)
    {
        $uploadMapperObj = new UploadMapper();
        if (empty($this->resource_id)) {
            Redirect::back()->withError('Collection Not Found');
        }

        $collectionMapperObj = new CollectionMapper();
        if (!empty($request->input('is_recommended'))) {
            $collectionMapperObj->updateStatus($this->resource_id, $request->input('status_id'));
            return redirect('collection/view/' . $this->resource_id);
        }
        $validator = $collectionMapperObj->inputValidator($request);

        if ($validator->fails()) {
            return redirect('collection/edit/' . $this->resource_id)
                ->withErrors($validator)
                ->withInput($request->all());
        }

        $validator = $uploadMapperObj->inputValidator($request);
        if ($validator->fails()) {

            return Redirect::back()
                ->withErrors($validator)
                ->withInput($request->all());
        }

        $collection = Collection::find($this->resource_id);

        if ($request->file('image')) {
            $result = $collectionMapperObj->saveCollectionDetails($collection, $request, $uploadMapperObj);
        } else {
            $result = $collectionMapperObj->saveCollectionDetails($collection, $request);
        }

        if ($result['status'] == false) {
            return Redirect::back()->withError($result['message'])->withInput($request->all());
        }
        return redirect('collection/view/' . $collection->id);

    }


    public function getCreate(Request $request)
    {
        $collectionMapperObj = new CollectionMapper();
        $view_properties = $collectionMapperObj->getDropDowns();
        $view_properties = array_merge($view_properties, $collectionMapperObj->getViewProperties($request->old()));
        $view_properties = array_merge($view_properties, $collectionMapperObj->getPopupProperties($request));

        return view('collection.create', $view_properties);
    }

    public function postCreate(Request $request)
    {
        $collectionMapperObj = new CollectionMapper();
        $uploadMapperObj = new UploadMapper();

        $validator = $collectionMapperObj->inputValidator($request);
        if ($validator->fails()) {

            return Redirect::back()
                ->withErrors($validator)
                ->withInput($request->all());
        }
        $validator = $uploadMapperObj->inputValidator($request);
        if ($validator->fails()) {

            return Redirect::back()
                ->withErrors($validator)
                ->withInput($request->all());
        }

        $collection = new Collection();

        if ($request->file('image')) {
            $result = $collectionMapperObj->saveCollectionDetails($collection, $request, $uploadMapperObj);
        } else {
            $result = $collectionMapperObj->saveCollectionDetails($collection, $request);
        }

        if ($result['status'] == false) {
            return Redirect::back()->withError($result['message'])->withInput($request->all());
        }
        return redirect('collection/view/' . $collection->id);
    }
}
