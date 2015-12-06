<?php

namespace App\Http\Controllers\Merchant;

use App\Brand;
use App\Category;
use App\Merchant;
use App\MerchantProduct;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    protected $filters = ['merchant_id', 'brand_id', 'category_id', 'gender_id'];
    protected $merchants = [];
    protected $brands = [];
    protected $categories = [];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $action)
    {
        $method = strtolower($_SERVER['REQUEST_METHOD']) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
        return $this->$method($_SERVER['REQUEST_METHOD'] == 'POST' ? $request : null);
    }

    public function getList(){
        $this->initFilters();

        $view_properties = array(
            'merchants' => $this->merchants,
            'brands' => $this->brands,
            'categories' => $this->categories
        );

        $where_conditions = array(
            'is_moderated' => false,
        );

        foreach($this->filters as $filter){
            $view_properties[$filter] = isset($_GET[$filter]) ? $_GET[$filter] : "";
            if(isset($_GET[$filter]) && $_GET[$filter]!=""){
                $where_conditions[$filter] = $_GET[$filter];
            }
        }

        $merchant_products =
            MerchantProduct::
                where($where_conditions)
                ->take(100)
                ->get();

        $view_properties['merchant_products'] = $merchant_products;

        return view('merchant.product.list', $view_properties);
    }

    public function initFilters(){
        $this->merchants = Merchant::all();
        $this->brands = Brand::all();
        $this->categories = Category::all();
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
