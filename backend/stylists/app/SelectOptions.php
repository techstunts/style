<?php
namespace App;
use Illuminate\Support\Facades\DB;

class SelectOptions{
    protected $table = "";
    protected $whereClauses = [];
    protected $whereRawClauses = "";

    public function __construct($table, $where_conditions, $where_raw){
        $this->table = $table;
        $this->whereClauses = $where_conditions;
        $this->whereRawClauses = $where_raw;
    }

    //To be cached
    public function merchants(){
        $whereClauses = $this->whereClauses;
        unset($whereClauses['merchant_id']);
        $merchants = DB::table($this->table)
            ->join('merchants', $this->table . '.merchant_id', '=', 'merchants.id')
            ->where($whereClauses)
            ->whereRaw($this->whereRawClauses)
            ->distinct()
            ->select('merchants.id', 'merchants.name', DB::raw('COUNT(' . $this->table . '.id) as product_count'))
            ->groupBy('merchants.id', 'merchants.name')
            ->orderBy('merchants.name')
            ->get();
        return $merchants;
    }

    //To be cached
    public function brands(){
        $whereClauses = $this->whereClauses;
        unset($whereClauses['brand_id']);
        $brands = DB::table($this->table)
            ->join('brands', $this->table . '.brand_id', '=', 'brands.id')
            ->where($whereClauses)
            ->whereRaw($this->whereRawClauses)
            ->distinct()
            ->select('brands.id', 'brands.name', DB::raw('COUNT(' . $this->table . '.id) as product_count'))
            ->groupBy('brands.id', 'brands.name')
            ->orderBy('brands.name')
            ->get();
        return $brands;
    }

    //To be cached
    public function categories(){
        $whereClauses = $this->whereClauses;
        unset($whereClauses['category_id']);
        $categories = DB::table($this->table)
            ->join('categories', $this->table . '.category_id', '=', 'categories.id')
            ->where($whereClauses)
            ->whereRaw($this->whereRawClauses)
            //->distinct()
            ->select('categories.id', 'categories.name', DB::raw('COUNT(' . $this->table . '.id) as product_count'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('categories.name')
            ->get();
        return $categories;
    }

    public function genders(){
        return $this->get_lookup_data_with_count('gender');
    }

    public function statuses(){
        return $this->get_lookup_data_with_count('status');
    }

    public function occasions(){
        if (env('IS_NICOBAR'))
            return $this->get_lookup_data_with_count('occasion', '', 'label');
        else
            return $this->get_lookup_data_with_count('occasion');
    }

    public function body_types(){
        return $this->get_lookup_data_with_count('body_type');
    }

    public function budgets(){
        return $this->get_lookup_data_with_count('budget');
    }

    public function age_groups(){
        return $this->get_lookup_data_with_count('age_group');
    }

    public function colors(){
        return $this->get_lookup_data_with_count('color', 'primary_color_id');
    }

    public function ratings(){
        return $this->get_lookup_data_with_count('rating', 'rating_id');
    }

    public function devicesStatuses(){
        return $this->get_lookup_data_with_count('device_status', 'device_status');
    }

    public function bookingStatuses(){
        return $this->get_lookup_data_with_count('booking_status', 'status_id');
    }

    public function lookStatuses(){
        return $this->get_lookup_data_with_count('look_status','status_id');
    }

    public function styles(){
        return $this->get_lookup_data_with_count('style');
    }
    //To be cached
    protected function get_lookup_data_with_count($lookup_type, $count_table_fk="", $label = 'name'){
        $whereClauses = $this->whereClauses;

        $lookup_table = 'lu_' . $lookup_type;
        $lookup_table_pk_col = $lookup_table. '.id';
        $lookup_table_name_col = $lookup_table. '.'. $label;
        $count_table_id_col = $this->table . '.id';
        $count_table_fk_col = $this->table . '.' . ($count_table_fk!="" ? $count_table_fk : $lookup_type . '_id');
        $count_table_fk = $lookup_type . '_id';

        unset($whereClauses[$count_table_fk]);

        $data = DB::table($lookup_table)
            ->leftjoin($this->table, function ($join) use($lookup_table_pk_col, $count_table_fk_col, $whereClauses) {
               $join->on($lookup_table_pk_col, '=', $count_table_fk_col);
                foreach($whereClauses as $k => $v) {
                    $join->where($k, '=', $v);
                }

            })
            ->whereRaw($this->whereRawClauses)
            ->select($lookup_table_pk_col, $lookup_table_name_col, DB::raw('COUNT(' . $count_table_id_col . ') as product_count'))
            ->groupBy($lookup_table_pk_col, $lookup_table_name_col)
            ->orderBy($lookup_table_name_col, 'ASC')
            ->get();
        return $data;
    }

    //To be cached
    public function stylists(){
       
        return $this->getStylistsList('stylist_id');
    }
    
    public function createdBy(){
       
        return $this->getStylistsList('created_by');
    }
    public function approvedBy(){

        return $this->getStylistsList('approved_by');
    }

    public function getStylistsList($columnName) {
         $whereClauses = $this->whereClauses;
        unset($whereClauses[$columnName]);
        $stylists = DB::table($this->table)
            ->join('stylists', $this->table . '.'.$columnName, '=', 'stylists.id')
            ->where($whereClauses)
            ->whereRaw($this->whereRawClauses)
            ->select('stylists.id', 'stylists.name', DB::raw('COUNT(' . $this->table . '.'.$columnName.') as product_count'))
            ->groupBy('stylists.id', 'stylists.name')
            ->orderBy('stylists.name')
            ->get();
        return $stylists;        
    }

}