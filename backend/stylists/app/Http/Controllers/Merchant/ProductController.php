<?php

namespace App\Http\Controllers\Merchant;

use App\Brand;
use App\Category;
use App\Merchant;
use App\MerchantProduct;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    protected $filters = ['merchant_id', 'brand_id', 'category_id', 'gender_id'];
    protected $merchants = [];
    protected $brands = [];
    protected $categories = [];

    protected $where_conditions = [];

    protected $records_per_page=10;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $action)
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
        return $this->$method($_SERVER['REQUEST_METHOD'] == 'POST' ? $request : $request);
    }

    public function initWhereConditions(){
        $this->where_conditions['is_moderated'] = false;

        foreach($this->filters as $filter){
            if(isset($_GET[$filter]) && $_GET[$filter]!=""){
                $this->where_conditions[$filter] = $_GET[$filter];
            }
        }
    }

    public function getList(Request $request){
        $this->initWhereConditions();
        $this->initFilters();

        $view_properties = array(
            'merchants' => $this->merchants,
            'brands' => $this->brands,
            'categories' => $this->categories
        );


        foreach($this->filters as $filter){
            $view_properties[$filter] = isset($_GET[$filter]) ? $_GET[$filter] : "";
        }

        $paginate_qs = $request->query();
        unset($paginate_qs['page']);

        $merchant_products =
            MerchantProduct::
                where($this->where_conditions)
                ->simplePaginate($this->records_per_page)
                ->appends($paginate_qs);

        $view_properties['merchant_products'] = $merchant_products;

        return view('merchant.product.list', $view_properties);
    }

    public function initFilters(){
        $this->brands = $this->brands($this->where_conditions);
        $this->categories = $this->categories($this->where_conditions);
        $this->merchants = $this->merchants($this->where_conditions);
    }

    //To be cached
    public function merchants($whereClauses){
        unset($whereClauses['merchant_id']);
        $merchants = DB::table('merchant_products')
            ->join('merchants', 'merchant_products.merchant_id', '=', 'merchants.id')
            ->where($whereClauses)
            ->distinct()
            ->select('merchants.id', 'merchants.name', DB::raw('COUNT(merchant_products.id) as product_count'))
            ->groupBy('merchants.id', 'merchants.name')
            ->orderBy('merchants.name')
            ->get();
        return $merchants;
    }

    //To be cached
    public function brands($whereClauses){
        unset($whereClauses['brand_id']);
        $brands = DB::table('merchant_products')
            ->join('brands', 'merchant_products.brand_id', '=', 'brands.id')
            ->where($whereClauses)
            ->distinct()
            ->select('brands.id', 'brands.name', DB::raw('COUNT(merchant_products.id) as product_count'))
            ->groupBy('brands.id', 'brands.name')
            ->orderBy('brands.name')
            ->get();
        return $brands;
    }

    //To be cached
    public function categories($whereClauses){
        unset($whereClauses['category_id']);
        $categories = DB::table('merchant_products')
            ->join('categories', 'merchant_products.category_id', '=', 'categories.id')
            ->where($whereClauses)
            //->distinct()
            ->select('categories.id', 'categories.name', DB::raw('COUNT(merchant_products.id) as product_count'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('categories.name')
            ->get();
        return $categories;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
