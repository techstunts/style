<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Enums\EntityType;
use App\Models\Enums\EntityTypeName;
use App\Models\Enums\RecommendationType;
use App\Models\Lookups\AppSections;
use App\Tip;
use App\Http\Mapper\TipMapper;
use App\Http\Mapper\UploadMapper;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class TipController extends Controller
{

    protected $filter_ids = ['age_group_id', 'gender_id', 'created_by', 'body_type_id', 'budget_id', 'occasion_id', 'status_id'];
    protected $filters = ['age_groups', 'genders', 'createdBy', 'body_types', 'budgets', 'occasions', 'statuses'];


    public function index(Request $request, $action, $id = null, $action_id = null)
    {
        $method = strtolower($request->method()) . strtoupper(substr($action, 0, 1)) . substr($action, 1);

        if ($id) {
            $this->resource_id = $id;
        }

        if ($action_id) {
            $this->action_resource_id = $action_id;
        }

        return $this->$method($request);
    }

    public function getCreate(Request $request)
    {
        $tipMapperObj = new TipMapper();
        $view_properties = $tipMapperObj->getDropDowns();
        $view_properties = array_merge($view_properties, $tipMapperObj->getViewProperties($request->old()));
        $view_properties = array_merge($view_properties, $tipMapperObj->getPopupProperties($request));

        return view('tip.create', $view_properties);
    }

    public function postCreate(Request $request)
    {
        $tipMapperObj = new TipMapper();
        $uploadMapperObj = new UploadMapper();

        $validator = $tipMapperObj->inputValidator($request);
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

        $tip = new Tip();
        if ($request->file('image')) {
            $result = $tipMapperObj->saveTipDetails($tip, $request, $uploadMapperObj);
        } else {
            $result = $tipMapperObj->saveTipDetails($tip, $request);
        }

        if ($result['status'] == false) {
            return Redirect::back()->withError($result['message'])->withInput($request->all());
        }
        return redirect('tip/view/' . $tip->id);

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

        $view_properties['stylist_id'] = '';
        if ($request->has('stylist_id') && $request->input('stylist_id') !== "") {
            $view_properties['stylist_id'] = intval($request->input('stylist_id'));
            $this->where_conditions[$this->base_table . '.created_by'] = $request->input('stylist_id');
        }

        $entity_nav_tabs = array(
            EntityType::CLIENT
        );

        $view_properties['entity_type_names'] = array(
            EntityTypeName::CLIENT
        );

        $paginate_qs = $request->query();
        unset($paginate_qs['page']);

        $created_by = "1=1";
        if (!empty($this->resource_id)) {
            $created_by = " created_by = " . $this->resource_id;
        }

        $tips = Tip::with(['createdBy' => function ($query) {
            $query->select('id', 'name');
        }])
            ->where($this->where_conditions)
            ->whereRaw($this->where_raw)
            ->whereRaw($created_by)
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
        $view_properties['entity_type_to_send'] = EntityType::TIP;
        $view_properties['recommendation_type_id'] = RecommendationType::MANUAL;
        $view_properties['is_owner_or_admin'] = Auth::user()->hasRole('admin');

        return view('tip.list', $view_properties);

    }

    public function getView()
    {
        if (empty($this->resource_id)) {
            return view('404', array('title' => 'Tip id not provided'));
        }
        $tipMapperObj = new TipMapper();
        $tip = $tipMapperObj->getTipById($this->resource_id);

        if (empty($tip)) {
            return view('404', array('title' => 'Tip not found'));
        }
        $view_properties = array(
            'tip' => $tip,
            'is_owner_or_admin' => Auth::user()->hasRole('admin') || $tip->created_by == Auth::user()->id,
        );

        return view('tip.view', $view_properties);
    }

    public function getEdit(Request $request)
    {
        if (empty($this->resource_id)) {
            Redirect::back()->withError('Tip Not Found');
        }
        $tipMapperObj = new TipMapper();
        $tip = $tipMapperObj->getTipById($this->resource_id);
        $view_properties = null;

        if ($tip) {
            $view_properties = $tipMapperObj->getDropDowns();

            $view_properties = array_merge($view_properties, $tipMapperObj->getViewProperties($request->old(), $tip));
            $view_properties = array_merge($view_properties, $tipMapperObj->getPopupProperties($request));

            $view_properties = array_merge($view_properties, ['tip' => $tip]);

        } else {
            return view('404', array('title' => 'Tip not found'));
        }

        return view('tip.edit', $view_properties);
    }

    public function postUpdate(Request $request)
    {
        $uploadMapperObj = new UploadMapper();
        if (empty($this->resource_id)) {
            Redirect::back()->withError('Tip Not Found');
        }
        $tipMapperObj = new TipMapper();
        $validator = $tipMapperObj->inputValidator($request);

        if ($validator->fails()) {
            return redirect('tip/edit/' . $this->resource_id)
                ->withErrors($validator)
                ->withInput($request->all());
        }
        $validator = $uploadMapperObj->inputValidator($request);
        if ($validator->fails()) {

            return Redirect::back()
                ->withErrors($validator)
                ->withInput($request->all());
        }

        $tip = Tip::find($this->resource_id);


        if ($request->file('image')) {
            $result = $tipMapperObj->saveTipDetails($tip, $request, $uploadMapperObj);
        } else {
            $result = $tipMapperObj->saveTipDetails($tip, $request);
        }

        if ($result['status'] == false) {
            return Redirect::back()->withError($result['message'])->withInput($request->all());
        }
        return redirect('tip/view/' . $tip->id);
    }

}
