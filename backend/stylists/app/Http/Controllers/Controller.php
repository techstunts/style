<?php

namespace App\Http\Controllers;


use App\Models\Lookups\Tag;
use App\ProductTag;
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
            $search_term  = strtolower(trim($request->input('search')));
            $search_query = $desc_condition = $tag_condition = "";

            if ($this->base_table == 'products') {
                $tag_condition = $this->setTagCondition($search_term, 'product', $request->input('tags_only'));
            } elseif ($this->base_table == 'looks') {
                $tag_condition = $this->setTagCondition($search_term, 'look');
            }

            if($request->input('exact_word') == "search exact word"){
                $search_query = "(LOWER({$name}) REGEXP '[[:<:]]{$search_term}[[:>:]]' {{desc}} )";
                if($this->base_table != 'clients'){
                    $desc_condition = " OR LOWER({$description}) REGEXP '[[:<:]]{$search_term}[[:>:]]' ";
                }
            }
            else{
                $search_query = "(LOWER({$name}) like '%{$search_term}%' {{desc}} )";
                if($this->base_table != 'clients'){
                    $desc_condition = " OR LOWER({$description}) like '%{$search_term}%' ";
                }
            }
            if ($this->base_table == 'products' and $request->input('tags_only')) {
                $search_query = '';
                $search_query .= substr($tag_condition, 4);
            } else {
                 $search_query .= $tag_condition;
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

    public function setTagCondition($tags, $table, $tags_only = false)
    {
        $tags_array = array();
        foreach (explode(',', $tags) as $value) {
            $tags_array[] = trim($value);
        }
        if (!$tags_only) {
        $tagged_products = DB::table($table.'_tags')
            ->select(DB::raw("DISTINCT {$table}_id"))
            ->join('lu_tags', 'lu_tags.id', '=', $table.'_tags.tag_id')
            ->whereIn(DB::raw("LOWER(lu_tags.name)"), $tags_array)
            ->get();
        } else {
            $tags = Tag::whereIn('name', $tags_array)->get();
            $tag_ids = array();
            foreach ($tags as $tag) {
                array_push($tag_ids, $tag->id);
            }
            $tags_count = count($tag_ids);
            $tagged_products = ProductTag::whereIn('tag_id', $tag_ids)
                ->select('product_id', DB::raw('COUNT(tag_id) as tag_ids_count'))
                ->groupBy('product_id')
                ->havingRaw("tag_ids_count >= $tags_count")
                ->get();
        }
        if (count($tagged_products) <= 0) {
           return '';
        }
        $product_ids = array();
        $paramName = $table.'_id';
        foreach ($tagged_products as $tagged_product) {
            array_push($product_ids, $tagged_product->$paramName);
        }
        return " OR {$this->base_table}.id IN(". implode(", ", $product_ids) . ")";
    }
}
