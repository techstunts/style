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
    protected $records_per_page=24;
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
        $to_date = '';
        $from_date = '';
        foreach($this->filter_ids as $filter_id){
            if($request->input($filter_id) != ""){
                $this->where_conditions[$this->base_table . '.' . $filter_id] = $request->input($filter_id);
            }
        }
        if($request->input('search') != "" and strlen(trim($request->input('search')))>0){
            $search_term  = trim($request->input('search'));
            $this->where_raw = "({$this->base_table}.name like '%{$search_term}%' OR {$this->base_table}.description like '%{$search_term}%')";
        }

        if($request->input('from_date') != ""){
            $time  = strtotime($request->input('from_date'));
            $from_date = date("Y-m-d",$time);
        }

        if($request->input('to_date') != ""){
            $time  = strtotime($request->input('to_date'));
            $to_date = date("Y-m-d",$time);
        }

        if($request->input('from_date') != "" and $request->input('to_date') != ""){
            $this->where_raw = "({$this->base_table}.created_at BETWEEN '{$from_date}' AND '{$to_date}')";
        }elseif($from_date != ""){
            $this->where_raw = "({$this->base_table}.created_at > '{$from_date}')";
        }elseif($to_date != ""){
            $this->where_raw = "({$this->base_table}.created_at < '{$to_date}')";
        }

        if($request->input('from_rs') != "" and $request->input('to_rs') != ""){
            $this->where_raw = "({$this->base_table}.price BETWEEN '{$request->input('from_rs')}' AND '{$request->input('to_rs')}')";
        }elseif($request->input('from_rs') != ""){
            $this->where_raw = "({$this->base_table}.price > '{$request->input('from_rs')}')";
        }elseif($request->input('to_rs') != ""){
            $this->where_raw = "({$this->base_table}.price < '{$request->input('to_rs')}')";
        }

    }

    public function initFilters(){
        $select_options = new SelectOptions($this->base_table, $this->where_conditions, $this->where_raw);
        foreach($this->filters as $filter){
            $this->$filter = $select_options->$filter();
        }
    }

}
