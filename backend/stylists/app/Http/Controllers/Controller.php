<?php

namespace App\Http\Controllers;


use App\SelectOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $base_table;
    protected $filters = [];
    protected $records_per_page=24;
    protected $where_conditions = [];
    protected $where_raw = "1=1";
    protected $brands = [];
    protected $categories = [];
    protected $merchants = [];
    protected $genders = [];
    protected $stylists = [];
    protected $statuses = [];
    protected $bookingStatuses = [];
    protected $occasions = [];
    protected $body_types = [];
    protected $budgets = [];
    protected $age_groups = [];

    protected $stylist_condition = false;
    protected $resource_id;
    protected $action_resource_id;

    public function initWhereConditions(Request $request){
        foreach($this->filter_ids as $filter_id){
            if($request->input($filter_id) != ""){
                $this->where_conditions[$this->base_table . '.' . $filter_id] = $request->input($filter_id);
            }
        }
        $where_raw = [];

        if($this->stylist_condition){
            $where_raw[] = "({$this->base_table}.stylist_id = '" . Auth::user()->id . "')";
        }

        if ($this->base_table == 'merchant_products') {
            $name = $this->base_table . '.m_product_name';
            $description = $this->base_table . '.m_product_description';
        }else{
            $name = $this->base_table . '.name';
            $description = $this->base_table . '.description';
        }

        if($request->input('search') != "" and strlen(trim($request->input('search')))>0){
            $search_term  = trim($request->input('search'));
            $search_query = $desc_condition = "";
            if($request->input('exact_word') == "search exact word"){
                $search_query = "({$name} REGEXP '[[:<:]]{$search_term}[[:>:]]' {{desc}} )";
                if($this->base_table != 'clients'){
                    $desc_condition = " OR {$description} REGEXP '[[:<:]]{$search_term}[[:>:]]' ";
                }
            }
            else{
                $search_query = "({$name} like '%{$search_term}%' {{desc}} )";
                if($this->base_table != 'clients'){
                    $desc_condition = " OR {$description} like '%{$search_term}%' ";
                }
            }
            $where_raw[] = str_replace("{{desc}}", $desc_condition, $search_query);
        }

        if($request->input('from_date') != ""){
            $where_raw[] = "({$this->base_table}.created_at >= '" . date("Y-m-d 00:00:00",strtotime($request->input('from_date'))) . "')";
        }

        if($request->input('to_date') != ""){
            $where_raw[] = "({$this->base_table}.created_at <= '" . date("Y-m-d 23:59:59",strtotime($request->input('to_date'))) . "')";
        }

        if ($request->has('book_date') && !empty($request->input('book_date'))) {
            $where_raw[] = "({$this->base_table}.date = '" . date("Y-m-d",strtotime($request->input('book_date'))) . "')";
        }

        if($request->input('min_price') != ""){
            $where_raw[] = "({$this->base_table}.price >= '{$request->input('min_price')}')";
        }

        if($request->input('max_price') != ""){
            $where_raw[] = "({$this->base_table}.price <= '{$request->input('max_price')}')";
        }

        $discounted_price_condition = " AND {$this->base_table}.discounted_price > 0";
        $decimal_points = 3;

        if($request->has('min_discount') != "" && intval($request->input('min_discount')) > 0){
            $min_discount = intval($request->input('min_discount')) / 100;
            $where_raw[] = "(TRUNCATE({$this->base_table}.discounted_price / {$this->base_table}.price, $decimal_points) >= {$min_discount} $discounted_price_condition)";
        }

        if($request->has('max_discount') != "" && intval($request->input('max_discount')) > 0){
            $max_discount = intval($request->input('max_discount')) / 100;
            $where_raw[] = "(TRUNCATE({$this->base_table}.discounted_price / {$this->base_table}.price, $decimal_points) <= {$max_discount} $discounted_price_condition)";
        }

        if($request->input('product_id') != ""){
            $ids_arr = [] ;
            foreach($request->input('product_id') as $product){
                if($product)
                $ids_arr[] = $product;
            }
            $where_raw[] = "{$this->base_table}.id IN(". implode(", ", $ids_arr) . ")";
        }
        if($where_raw){
            $this->where_raw = implode(" AND ", $where_raw);
        }
    }

    public function initFilters(){
        $select_options = new SelectOptions($this->base_table, $this->where_conditions, $this->where_raw);
        foreach($this->filters as $filter){
            $this->$filter = $select_options->$filter();
        }
    }

    public function setStylistCondition(){
        $this->stylist_condition = Auth::user()->hasRole('admin') ? false : true;
    }
}
