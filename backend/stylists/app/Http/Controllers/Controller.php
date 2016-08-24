<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
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
    protected $colors = [];
    protected $ratings = [];
    protected $approvedBy = [];

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
            $search_query = $desc_condition = $tag_condition = "";

            if ($this->base_table == 'products') {
                $tag_condition = $this->setTagCondition($search_term);
            }

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
            $search_query .= $tag_condition;
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

    public function setInStockCondition($in_stock)
    {
        $columnName = 'in_stock';
        if ($this->base_table == 'merchant_products') {
            $columnName = 'm_in_stock';
        }
        $this->where_conditions[$this->base_table . '.' . $columnName] = $in_stock;
    }

    public function setTagCondition($tags)
    {
        $tags_array = explode(',', $tags);
        $tags_query = '';

        foreach ($tags_array as $value) {
            $value = trim($value);
            $tags_query .= " OR (name LIKE '$value')";
        }

        $lu_tags = DB::table('lu_tags')->select('id')->whereRaw(substr($tags_query, 4))->get();
        if (count($lu_tags) <= 0) {
            return '';
        }
        $tag_ids = array();
        foreach ($lu_tags as $lu_tag) {
            array_push($tag_ids, $lu_tag->id);
        }
        $tagged_products = DB::table('product_tags')->select('product_id')->whereIn('tag_id', $tag_ids)->get();
        if (count($tagged_products) <= 0) {
           return '';
        }
        $product_ids = array();
        foreach ($tagged_products as $tagged_product) {
            array_push($product_ids, $tagged_product->product_id);
        }
        return " OR {$this->base_table}.id IN(". implode(", ", $product_ids) . ")";
    }
}
