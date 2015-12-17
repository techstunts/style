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

    protected $filters = [];
    protected $records_per_page=25;
    protected $where_conditions = [];
    protected $brands = [];
    protected $categories = [];
    protected $merchants = [];
    protected $genders = [];

    protected $resource_id;

    public function initWhereConditions(Request $request){
        foreach($this->filters as $filter){
            if($request->input($filter) != ""){
                $this->where_conditions[$filter] = $request->input($filter);
            }
        }
    }

    public function initFilters($base_table){
        $select_options = new SelectOptions($base_table);
        $this->brands = $select_options->brands($this->where_conditions);
        $this->categories = $select_options->categories($this->where_conditions);
        $this->merchants = $select_options->merchants($this->where_conditions);
        $this->genders = $select_options->genders($this->where_conditions);
    }

}
