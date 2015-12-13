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

    //To be cached
    public function genders($whereClauses){
        unset($whereClauses['gender_id']);
        $genders = DB::table($this->table)
            ->join('genders', $this->table . '.gender_id', '=', 'genders.id')
            ->where($whereClauses)
            //->distinct()
            ->select('genders.id', 'genders.name', DB::raw('COUNT(' . $this->table . '.id) as product_count'))
            ->groupBy('genders.id', 'genders.name')
            ->orderBy('genders.name')
            ->get();
        return $genders;
    }


}