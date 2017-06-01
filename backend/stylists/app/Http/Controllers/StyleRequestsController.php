<?php

namespace App\Http\Controllers;

use App\Error;
use App\Http\Mapper\BookingMapper;
use App\Http\Mapper\StyleRequestMapper;
use App\Models\Lookups\BookingStatus;
use App\Models\Lookups\Style;
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
    protected $filter_ids = [ 'occasion_id', 'budget_id', 'status_id', 'style_id', 'category_id'];
    protected $filters = [ 'occasions', 'budgets', 'bookingStatuses', 'styles', 'categories'];

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
            'categories' => $this->categories,
            'budgets' => $this->budgets,
            'bookingStatuses' => $this->bookingStatuses,
            'booking_statuses_list' => BookingStatus::get(),
            'styles_categories' => $this->styles(),
        );

        $entity_nav_tabs = array(
            EntityType::LOOK,
            EntityType::PRODUCT,
        );

        $view_properties['entity_type_names']= array(
            EntityTypeName::LOOK,
            EntityTypeName::PRODUCT,
        );
        if (!env('IS_NICOBAR')){
            array_push($entity_nav_tabs, EntityType::TIP, EntityType::COLLECTION);
            array_push($view_properties['entity_type_names'], EntityTypeName::TIP, EntityTypeName::COLLECTION);
        }
        $view_properties['nav_tab_index'] = '0';

        foreach($this->filter_ids as $filter){
            $view_properties[$filter] = $request->has($filter) && $request->input($filter) !== "" ? intval($request->input($filter)) : "";
        }

        $view_properties['from_date'] = $request->input('from_date');
        $view_properties['to_date'] = $request->input('to_date');
        $view_properties['min_discount'] = $request->input('min_discount');
        $view_properties['max_discount'] = $request->input('max_discount');
        $view_properties['show_discount_fields'] = false;
        $view_properties['in_stock'] = $request->input('in_stock');

        $paginate_qs = $request->query();
        unset($paginate_qs['page']);

        $client = function ($query) {
            $query->whereHas('stylist', function($subQuery){
                if(Auth::user()->hasRole('stylist')){
                    $subQuery->where('id', Auth::user()->id);
                }
            });
        };
        $requests  = StyleRequests::with(['budget', 'occasion', 'entity_type', 'status', 'requestBooking.booking'])
                ->whereHas('client', $client)
                ->where($this->where_conditions)
                ->whereRaw($this->where_raw)
                ->orderBy('id', 'DESC')
                ->simplePaginate($this->records_per_page)
                ->appends($paginate_qs);
        $view_properties['requests'] = $requests;
        $view_properties['app_sections'] = AppSections::all();
        $view_properties['popup_entity_type_ids'] = $entity_nav_tabs;
        $view_properties['recommendation_type_id'] = RecommendationType::STYLE_REQUEST;
        $view_properties['show_price_filters'] = 'YES';
        $view_properties['show_back_next_button'] = true;

        return view('requests.list', $view_properties);
    }

    public function styles()
    {
        $styles = Style::with('category')->get();
        $category_indexed_styles = array();
        foreach ($styles as $style) {
            if (!isset($category_indexed_styles[$style->category_id])){
                $category_indexed_styles[$style->category_id]['name'] = $style->category->name;
                $category_indexed_styles[$style->category_id]['styles'] = array();
            }
            unset($style->category);
            $category_indexed_styles[$style->category_id]['styles'][] = $style;
        }
        return $category_indexed_styles;
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

        $question_ans = function ($query) {
            $query->with(['question', 'option']);
        };

        $api_origin = env('API_ORIGIN');
        $uploadedStyleImage = function ($query) use ($api_origin) {
            $query->select('id', DB::raw("concat('$api_origin', '/',  path, '/', name) as url"));
        };

        $looks = function ($subquery) use ($api_origin) {
            $subquery->select('id', DB::raw("concat('$api_origin', '/uploads/images/looks/', image) as image"));
        };
        $products = function ($subquery) use ($api_origin) {
            $subquery->select('id', 'image_name as image');
        };

        $reco_looks = function ($query) use($looks) {
            $query->select('id', 'entity_type_id', 'entity_id', 'style_request_id', 'created_at');
            $query->with(['look' => $looks,]);
            $query->where(['entity_type_id' => EntityType::LOOK]);
        };
        $reco_products = function ($query) use($products) {
            $query->select('id', 'entity_type_id', 'entity_id', 'style_request_id', 'created_at');
            $query->with(['product' => $products,]);
            $query->where(['entity_type_id' => EntityType::PRODUCT]);
        };

        $styleRequest = StyleRequests::with(['client' => $client, 'requested_entity', 'question_ans' => $question_ans, 'reco_looks' => $reco_looks,
            'reco_products' => $reco_products, 'category', 'uploadedStyleImage' => $uploadedStyleImage, 'request_styling_element_texts'])
            ->where(['id' => $this->resource_id])
            ->first();
        if (!$styleRequest) {
            return Redirect::to('requests/list')->withError('Request Not Found');
        }
        $styleRequest = $this->formatRecommendations($styleRequest);
        $ans_arr = array();
        foreach ($styleRequest->question_ans as $question_ans) {
            if (!isset($ans_arr[$question_ans->question_id])) {
                $ans_arr[$question_ans->question_id] = array();
                $ans_arr[$question_ans->question_id]['question'] = $question_ans->question->title;
                $ans_arr[$question_ans->question_id]['ansType'] = '';
                $ans_arr[$question_ans->question_id]['ans'] = array();
            }
            if ($question_ans->option) {
                if (!empty($question_ans->option->text) && '' != $question_ans->option->text
                    && !empty($question_ans->option->image) && '' != $question_ans->option->image) {
                    if (empty($ans_arr[$question_ans->question_id]['ansType']))
                        $ans_arr[$question_ans->question_id]['ansType'] = 'both';
                } elseif (!empty($question_ans->option->image) && '' != $question_ans->option->image) {
                    if (empty($ans_arr[$question_ans->question_id]['ansType']))
                        $ans_arr[$question_ans->question_id]['ansType'] = 'image';
                } else {
                    if (empty($ans_arr[$question_ans->question_id]['ansType']))
                        $ans_arr[$question_ans->question_id]['ansType'] = 'text';
                }
                $ans_arr[$question_ans->question_id]['ans'][] = $question_ans->option;
            } else {
                $ans_arr[$question_ans->question_id]['ans'][] = (object) array('text' => $question_ans->text);
                if (empty($ans_arr[$question_ans->question_id]['ansType']))
                    $ans_arr[$question_ans->question_id]['ansType'] = 'text';
            }
        }
        unset($styleRequest->question_ans);
        $styleRequest->question_ans = $ans_arr;
        $view_properties = array();
        $view_properties['request'] = $styleRequest;
        $styleRequestMapperObj = new StyleRequestMapper();
        $view_properties = array_merge($view_properties, $styleRequestMapperObj->popupProperties($request));
        $view_properties['static_url'] = env('IS_NICOBAR') ? env('NICOBAR_STATIC_URL') : env('ALL_ASSETS');
        return view('requests.view', $view_properties);
    }

    public function postUpdateStatus(Request $request)
    {
        $request_id = $request->input('request_id');
        if (empty($request_id)) {
            return response()->json(
                array(
                    'success' => false,
                    'message' => 'Invalid request',
                ), 200
            );
        }

        $bookingsMapperObj = new BookingMapper();
        $booking_status_id_exists = $bookingsMapperObj->validStatus($request);
        if (!$booking_status_id_exists) {
            return response()->json(
                array(
                    'success' => false,
                    'message' => 'Invalid status',
                ), 200
            );
        }
        $styleRequestObj = new StyleRequestMapper();
        $styleRequest = $styleRequestObj->requestById($request_id);
        if (!$styleRequest){
            return response()->json(
                array(
                    'success' => false,
                    'message' => 'Request not found',
                ), 200
            );
        }
        if ($styleRequest->status_id == $request->input('status_id')){
            return response()->json(
                array(
                    'success' => false,
                    'message' => 'Request has already been updated',
                ), 200
            );
        } else {
            $response = $styleRequestObj->updateStatus($styleRequest, $request->input('status_id'));
            return response()->json($response, 200);
        }
    }

    public function formatRecommendations ($stylerequest) {
        $reco_arr = array();
        foreach ($stylerequest->reco_looks as $reco_look) {
            $date = date('Y-m-d H:i:s', strtotime($reco_look->created_at));
            if (!isset($reco_arr[$date])) {
                $reco_arr[$date] = array();
            }
            if (!isset($reco_arr[$date][$reco_look->entity_type_id])) {
                $reco_arr[$date][$reco_look->entity_type_id] = array();
            }
            $reco_arr[$date][$reco_look->entity_type_id][] = $reco_look->look;
        }
        foreach ($stylerequest->reco_products as $reco_look) {
            $date = date('Y-m-d H:i:s', strtotime($reco_look->created_at));
            if (!isset($reco_arr[$date])) {
                $reco_arr[$date] = array();
            }
            if (!isset($reco_arr[$date][$reco_look->entity_type_id])) {
                $reco_arr[$date][$reco_look->entity_type_id] = array();
            }
            $reco_arr[$date][$reco_look->entity_type_id][] = $reco_look->product;
        }
        krsort($reco_arr);
        $stylerequest->recommendations = $reco_arr;
        unset($stylerequest->reco_looks, $stylerequest->reco_products);
        return $stylerequest;
    }
}
