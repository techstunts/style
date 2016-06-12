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
        $view_properties = array_merge($view_properties, $tipMapperObj->getViewProperties($request));
        return view('tip.create', $view_properties);
    }

    public function postCreate(Request $request)
    {
        $tipMapperObj = new TipMapper();

        $validator = $tipMapperObj->inputValidator($request);
        if ($validator->fails()) {
            $view_properties = $tipMapperObj->getViewProperties($request);

            return Redirect::back()
                ->withErrors($validator)
                ->withInput($view_properties);
        }

        $tip = new Tip();

        $tip = $tipMapperObj->setObjectProperties($tip, $request);
        $tip->created_by = $request->user()->id != '' ? $request->user()->id : '';
        $tip->created_at = date('Y-m-d H:i:s');

        try {
            $tip->save();
            return redirect('tip/view/' . $tip->id);
        } catch (\Exception $e) {
            return Redirect::back()->withError('Error occur while creating a tip '.PHP_EOL . $e->getMessage());
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

        $view_properties['entity_type_names'] = array(
            EntityTypeName::CLIENT
        );

        $paginate_qs = $request->query();
        unset($paginate_qs['page']);

        $tips = Tip::where($this->where_conditions)
            ->whereRaw($this->where_raw)
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
            'is_owner_or_admin' => Auth::user()->hasRole('admin') || $tip->stylist_id == Auth::user()->id,
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

            $view_properties['tip'] = $tip;
            $view_properties['gender_id'] = intval($tip->gender_id);
            $view_properties['status_id'] = intval($tip->status_id);
            $view_properties['occasion_id'] = intval($tip->occasion_id);
            $view_properties['age_group_id'] = intval($tip->age_group_id);
            $view_properties['budget_id'] = intval($tip->budget_id);
            $view_properties['body_type_id'] = intval($tip->body_type_id);

        } else {
            return view('404', array('title' => 'Tip not found'));
        }

        return view('tip.edit', $view_properties);
    }

    public function postUpdate(Request $request)
    {
        if (empty($this->resource_id)) {
            Redirect::back()->withError('Tip Not Found');
        }
        $tipMapperObj = new TipMapper();
        $validator = $tipMapperObj->inputValidator($request);

        if ($validator->fails()) {
            return redirect('tip/edit/' . $this->resource_id)
                ->withErrors($validator)
                ->withInput();
        }

        $tip = Tip::find($this->resource_id);

        $tip = $tipMapperObj->setObjectProperties($tip, $request);
        $tip->updated_by = $request->user()->id != '' ? $request->user()->id : '';

        try{
            $tip->save();
            return redirect('tip/view/' . $this->resource_id);
        } catch(\Exception $e){
            return Redirect::back()->withError('Error occur while updating the tip'. PHP_EOL. $e->getMessage());
        }
    }

}
