<?php

namespace App\Http\Controllers;


use App\SelectOptions;
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
    protected $records_per_page=25;
    protected $where_conditions = [];
    protected $where_raw = "1=1";
    protected $brands = [];
    protected $categories = [];
    protected $merchants = [];
    protected $genders = [];
    protected $stylists = [];
    protected $statuses = [];
    protected $occasions = [];
    protected $body_types = [];
    protected $budgets = [];
    protected $age_groups = [];

    protected $resource_id;
    protected $action_resource_id;

    public function initWhereConditions(Request $request){
        foreach($this->filter_ids as $filter_id){
            if($request->input($filter_id) != ""){
                $this->where_conditions[$this->base_table . '.' . $filter_id] = $request->input($filter_id);
            }
        }
        if($request->input('search') != "" and strlen(trim($request->input('search')))>0){
            $search_term  = trim($request->input('search'));
            if($request->input('exact_word') == "search exact word"){
                $this->where_raw = "({$this->base_table}.name REGEXP '[[:<:]]{$search_term}[[:>:]]' OR {$this->base_table}.description REGEXP '[[:<:]]{$search_term}[[:>:]]')";
            }
            else{
                $this->where_raw = "({$this->base_table}.name like '%{$search_term}%' OR {$this->base_table}.description like '%{$search_term}%')";
            }
        }

    }

    public function initFilters(){
        $select_options = new SelectOptions($this->base_table, $this->where_conditions, $this->where_raw);
        foreach($this->filters as $filter){
            $this->$filter = $select_options->$filter();
        }
    }

}
