<?php
namespace App;
use Illuminate\Support\Facades\DB;

class SelectOptions{
    protected $table = "";

    public function __construct($table){
        $this->table = $table;
    }

    //To be cached
    public function merchants($whereClauses){
        unset($whereClauses['merchant_id']);
        $merchants = DB::table($this->table)
            ->join('merchants', $this->table . '.merchant_id', '=', 'merchants.id')
            ->where($whereClauses)
            ->distinct()
            ->select('merchants.id', 'merchants.name', DB::raw('COUNT(' . $this->table . '.id) as product_count'))
            ->groupBy('merchants.id', 'merchants.name')
            ->orderBy('merchants.name')
            ->get();
        return $merchants;
    }

    //To be cached
    public function brands($whereClauses){
        unset($whereClauses['brand_id']);
        $brands = DB::table($this->table)
            ->join('brands', $this->table . '.brand_id', '=', 'brands.id')
            ->where($whereClauses)
            ->distinct()
            ->select('brands.id', 'brands.name', DB::raw('COUNT(' . $this->table . '.id) as product_count'))
            ->groupBy('brands.id', 'brands.name')
            ->orderBy('brands.name')
            ->get();
        return $brands;
    }

    //To be cached
    public function categories($whereClauses){
        unset($whereClauses['category_id']);
        $categories = DB::table($this->table)
            ->join('categories', $this->table . '.category_id', '=', 'categories.id')
            ->where($whereClauses)
            //->distinct()
            ->select('categories.id', 'categories.name', DB::raw('COUNT(' . $this->table . '.id) as product_count'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('categories.name')
            ->get();
        return $categories;
    }

    public function genders($whereClauses){
        return $this->get_lookup_data_with_count('gender', $whereClauses);
    }

    public function statuses($whereClauses){
        return $this->get_lookup_data_with_count('status', $whereClauses);
    }

    public function occasions($whereClauses){
        return $this->get_lookup_data_with_count('occasion', $whereClauses);
    }

    public function body_types($whereClauses){
        return $this->get_lookup_data_with_count('body_type', $whereClauses);
    }

    public function budgets($whereClauses){
        return $this->get_lookup_data_with_count('budget', $whereClauses);
    }

    public function age_groups($whereClauses){
        return $this->get_lookup_data_with_count('age_group', $whereClauses);
    }

    //To be cached
    protected function get_lookup_data_with_count($lookup_type, $whereClauses){
        $lookup_table = 'lu_' . $lookup_type;
        $lookup_table_pk_col = $lookup_table. '.id';
        $lookup_table_name_col = $lookup_table. '.name';
        $count_table_id_col = $this->table . '.id';
        $count_table_fk_col = $this->table . '.' . $lookup_type . '_id';
        $count_table_fk = $lookup_type . '_id';

        unset($whereClauses[$count_table_fk]);
        $data = DB::table($this->table)
            ->join($lookup_table, $count_table_fk_col, '=', $lookup_table_pk_col)
            ->where($whereClauses)
            ->select($lookup_table_pk_col, $lookup_table_name_col, DB::raw('COUNT(' . $count_table_id_col . ') as product_count'))
            ->groupBy($lookup_table_pk_col, $lookup_table_name_col)
            ->get();
        return $data;
    }

    //To be cached
    public function stylists($whereClauses){
        unset($whereClauses['stylish_id']);
        $stylists = DB::table($this->table)
            ->join('stylists', $this->table . '.stylish_id', '=', 'stylists.stylish_id')
            ->where($whereClauses)
            ->select('stylists.stylish_id', 'stylists.name', DB::raw('COUNT(' . $this->table . '.stylish_id) as product_count'))
            ->groupBy('stylists.stylish_id', 'stylists.name')
            ->orderBy('stylists.name')
            ->get();
        return $stylists;
    }

}