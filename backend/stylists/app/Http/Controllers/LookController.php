<?php

namespace App\Http\Controllers;

use App\Look;
use App\Models\Enums\EntityType;
use App\Models\Enums\EntityTypeName;
use App\Models\Enums\ProfileImageStatus;
use App\Models\Enums\RecommendationType;
use App\Models\Enums\Status as LookupStatus;
use App\Models\Enums\StylistStatus;
use App\Models\Enums\ImageType;
use App\Models\Lookups\Status;
use App\Models\Lookups\AppSections;
use App\Http\Mapper\LookMapper;
use App\Http\Mapper\UploadMapper;
use App\Http\Mapper\Mapper;
use App\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Validator;

class LookController extends Controller
{
    protected $filter_ids = ['stylist_id', 'status_id', 'gender_id', 'occasion_id', 'body_type_id', 'budget_id', 'age_group_id'];
    protected $filters = ['stylists', 'statuses', 'genders', 'occasions', 'body_types', 'budgets', 'age_groups'];

    protected $status_rules;

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

    protected function initStatusRules()
    {
        $rules_file = app_path() . '/Models/Rules/look.xml';
        $data = implode("", file($rules_file));

        $xml = simplexml_load_string($data);
        $json = json_encode($xml);
        $status_rules = json_decode($json, TRUE);

        foreach ($status_rules['statuses']['status'] as $status) {
            $this->status_rules[$status['id']] = $status;
        }
    }

    public function getList(Request $request)
    {
        $this->base_table = 'looks';
        $this->filter_ids = ['stylist_id', 'status_id', 'category_id', 'occasion_id'];
        $this->filters = ['stylists', 'statuses', 'categories', 'occasions'];

        $this->initWhereConditions($request);
        $this->initFilters();

        $view_properties = array(
            'stylists' => $this->stylists,
            'statuses' => $this->statuses,
            'categories' => $this->categories,
            'genders' => $this->genders,
            'occasions' => $this->occasions,
            'body_types' => $this->body_types,
            'budgets' => $this->budgets,
            'age_groups' => $this->age_groups
        );

        $entity_nav_tabs = array(
            EntityType::CLIENT
        );

        $view_properties['entity_type_names'] = array(
            EntityTypeName::CLIENT
        );
        $view_properties['nav_tab_index'] = '0';

        foreach ($this->filter_ids as $filter) {
            $view_properties[$filter] = $request->has($filter) && $request->input($filter) !== "" ? intval($request->input($filter)) : "";
        }
        $view_properties['search'] = $request->input('search');
        $view_properties['exact_word'] = $request->input('exact_word');
        $view_properties['from_date'] = $request->input('from_date');
        $view_properties['to_date'] = $request->input('to_date');

        $view_properties['min_price'] = $request->input('min_price');
        $view_properties['max_price'] = $request->input('max_price');

        $paginate_qs = $request->query();
        unset($paginate_qs['page']);
        $this->initStatusRules();

        $user_data = Auth::user();
        if ($user_data->hasRole('admin')) {
            $view_properties['user_role'] = 'admin';
        } else if ($user_data->hasRole('stylist')) {
            $view_properties['user_role'] = 'stylist';
        }

        $remove_deleted_looks = '1=1';
        if (!$request->has('status_id') || $request->input('status_id') != LookupStatus::Deleted) {
            $remove_deleted_looks = 'looks.status_id != ' . LookupStatus::Deleted;
        }
        $mapperObj = new Mapper();
        $look_prices = $mapperObj->getPriceClosure();
        $looks =
            Look::with(['gender', 'status', 'body_type', 'budget', 'occasion', 'age_group', 'tags.tag', 'prices' => $look_prices])
                ->where($this->where_conditions)
                ->whereRaw($this->where_raw)
                ->whereRaw($remove_deleted_looks)
                ->orderBy('updated_at', 'desc')
                ->simplePaginate($this->records_per_page)
                ->appends($paginate_qs);

        $lookMapperObj = new LookMapper();
        foreach ($looks as $look) {
            $look->price = !empty($look->prices) ? $lookMapperObj->getPrice($look->prices) : 0;
            unset($look->prices);
        }
        $view_properties['looks'] = $looks;
        $view_properties['status_rules'] = $this->status_rules;
        $view_properties['app_sections'] = AppSections::all();
        $view_properties['logged_in_stylist_id'] = $user_data->id;
        $view_properties['popup_entity_type_ids'] = $entity_nav_tabs;
        $view_properties['entity_type_to_send'] = EntityType::LOOK;
        $view_properties['recommendation_type_id'] = RecommendationType::MANUAL;
        $view_properties['is_owner_or_admin'] = Auth::user()->hasRole('admin');
        $view_properties['entity'] = 'look';
        return view('look.list', $view_properties);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCreate(Request $request)
    {
        $lookMapperObj = new LookMapper();
        $view_properties = $lookMapperObj->getDropDowns();
        $view_properties = array_merge($view_properties, $lookMapperObj->getViewProperties($request->old()));
        $view_properties = array_merge($view_properties, $lookMapperObj->getPopupProperties($request));
        $view_properties['products'] = !empty($request->old('product_ids')) ? Mapper::productsByIds($request->old('product_ids')) : '';

        return view('look.create', $view_properties);
    }

    public function getChangestatus(Request $request)
    {
        $look = Look::find($this->resource_id);
        if ($look) {
            if ($look->status_id !== LookupStatus::Active && $this->action_resource_id == LookupStatus::Active && empty($look->image)) {
                return Redirect::back()->withError('Error! Please upload the image for this look.');
            }
            $this->initStatusRules();
            if (isset($this->status_rules[$look->status->id]['edit_status']['new_status'])) {

                $new_statuses = $this->status_rules[$look->status->id]['edit_status']['new_status'];
                if (isset($new_statuses['id'])) {
                    $new_statuses = array($new_statuses);
                }
                foreach ($new_statuses as $new_status) {
                    if ($new_status['id'] == $this->action_resource_id) {
                        $look->status_id = $new_status['id'];
                        if ($look->save()) {
                            return Redirect::back()->withSuccess('Look status changed successfully!');
                        } else {
                            return Redirect::back()->withError('Error! Look save error.');
                        }
                    }
                }
                return Redirect::back()->withError('Error! Look status change from status ' . $look->status->name . ' to status ' . $this->action_resource_id . ' is not allowed.');
            } else {
                return Redirect::back()->withError('Error! Look is in its last status. Further status changes not allowed.');
            }
        } else {
            return Redirect::back()->withError('Error! Look not found.');
        }
        return Redirect::back()->withError('Error! Status cant be changed.');
    }

    public function postCreate(Request $request)
    {
        $lookMapperObj = new LookMapper();
        $uploadMapperObj = new UploadMapper();

        $validator = $lookMapperObj->inputValidator($request);
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

        $look = new Look();
        if ($request->file('image')) {
            $result = $lookMapperObj->saveLookDetails($look, $request, $uploadMapperObj);
        } else {
            $result = $lookMapperObj->saveLookDetails($look, $request);
        }

        if ($result['status'] == false) {
            return Redirect::back()->withError($result['message'])->withInput($request->all());
        }
        return redirect('look/view/' . $look->id);
    }

    public function getView()
    {
        $looks_products = function ($query) {
            $query->with('product');
        };
        $images = function ($query) {
            $query->where(['uploaded_by_entity_type_id' => EntityType::LOOK, 'status_id' => ProfileImageStatus::Active]);
            $query->whereIn('image_type_id', [ImageType::PDP_Image, ImageType::PLP_Image]);
        };

        $look = Look::with(['look_products' => $looks_products, 'stylist', 'prices', 'category', 'otherImages' => $images])
            ->where('id', $this->resource_id)
            ->first();
        if ($look) {
            $lookMapperObj = new LookMapper();
            $look = $lookMapperObj->setLookImages($look);
            $view_properties = array(
                'look' => $look,
                'status' => Status::find($look->status_id),
                'is_owner_or_admin' => Auth::user()->hasRole('admin') || $look->stylist_id == Auth::user()->id,
            );
            return view('look.view', $view_properties);
        } else {
            return view('404', array('title' => 'Look not found'));
        }
    }

    public function getEdit($request)
    {
        if (empty($this->resource_id)) {
            Redirect::back()->withError('Look Not Found');
        }
        $lookMapperObj = new LookMapper();
        $look = $lookMapperObj->getLookById($this->resource_id);
        $view_properties = null;

        if ($look) {
            if (!empty($request->old('product_ids'))) {
                $look->look_products = Mapper::productsByIds($request->old('product_ids'));
            }
            $look = $lookMapperObj->setLookImages($look);
            $look->price = count($look->prices) ? $lookMapperObj->getPrice($look->prices) : 0;
            $view_properties = $lookMapperObj->getDropDowns();

            $view_properties = array_merge($view_properties, $lookMapperObj->getViewProperties($request->old(), $look));
            $view_properties = array_merge($view_properties, $lookMapperObj->getPopupProperties($request));

            $view_properties = array_merge($view_properties, ['look' => $look]);

        } else {
            return view('404', array('title' => 'Look not found'));
        }

        return view('look.edit', $view_properties);
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
            'price' => 'required|min:2',
        ]);
    }

    public function postUpdate(Request $request)
    {
        if (empty($this->resource_id)) {
            Redirect::back()->withError('Look Not Found');
        }
        $lookMapperObj = new LookMapper();

        if (!empty($request->input('is_recommended'))) {
            $lookMapperObj->updateStatus($this->resource_id, $request->input('status_id'));
            return redirect('look/view/' . $this->resource_id);
        }
        $validator = $lookMapperObj->inputValidator($request);

        if ($validator->fails()) {
            return redirect('look/edit/' . $this->resource_id)
                ->withErrors($validator)
                ->withInput($request->all());
        }
        $look = Look::find($this->resource_id);

        $uploadMapperObj = new UploadMapper();
        if ($request->file('image')) {
            $validator = $uploadMapperObj->inputValidator($request);
            if ($validator->fails()) {

                return Redirect::back()
                    ->withErrors($validator)
                    ->withInput($request->all());
            }
        }
        if ($request->file('image')) {
            $result = $lookMapperObj->saveLookDetails($look, $request, $uploadMapperObj);
        } else {
            $result = $lookMapperObj->saveLookDetails($look, $request);
        }

        if ($result['status'] == false) {
            return Redirect::back()->withError($result['message'])->withInput($request->all());
        }
        return redirect('look/view/' . $look->id);
    }

    public function getCollage(Request $request)
    {
        if (!Auth::user()->hasRole('admin') &&
            !in_array(Auth::user()->status_id, [StylistStatus::Active, StylistStatus::Inactive])
        ) {
            return redirect('look/list')->withError('Collage access denied!');
        }
        return view('look/collage');
    }
}
