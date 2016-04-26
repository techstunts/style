<?php

namespace App\Http\Controllers\Merchant;

use App\Models\Lookups\Gender;
use App\MerchantProduct;
use App\MerchantProductRejected;
use App\Product;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class ProductController extends Controller
{
    protected $filter_ids = ['merchant_id', 'brand_id', 'category_id', 'gender_id'];
    protected $filters = ['merchants', 'brands', 'categories', 'genders'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $action)
    {
        $method = strtolower($request->method()) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
        return $this->$method($request);
    }

    public function initWhereConditions(Request $request){
        $this->where_conditions['is_moderated'] = false;
        parent::initWhereConditions($request);
    }

    public function postList(Request $request){
        $this->initWhereConditions($request);
        if($request->input('approve_all')){
            $this->approveAllProducts();
        }
        elseif($request->input('reject_all')){
            $this->rejectAllProducts();
        }

        return Redirect::to($request->fullUrl());
    }

    public function getList(Request $request){
        $this->base_table = 'merchant_products';
        $this->initWhereConditions($request);
        $this->initFilters();

        $view_properties = array(
            'merchants' => $this->merchants,
            'brands' => $this->brands,
            'categories' => $this->categories,
            'genders' => $this->genders
        );

        foreach($this->filter_ids as $filter){
            $view_properties[$filter] = $request->input($filter) ? $request->input($filter) : "";
        }

        $paginate_qs = $request->query();
        unset($paginate_qs['page']);

        $genders_list = Gender::all()->keyBy('id');
        $genders_list[0] = new Gender();

        $merchant_products =
            MerchantProduct::
                where($this->where_conditions)
                ->simplePaginate($this->records_per_page)
                ->appends($paginate_qs);

        $view_properties['merchant_products'] = $merchant_products;
        $view_properties['genders_list'] = $genders_list;

        return view('merchant.product.list', $view_properties);
    }

    public function approveAllProducts(){
        $merchant_products =
            MerchantProduct::
            where($this->where_conditions)
                ->simplePaginate($this->records_per_page);

        foreach($merchant_products as $m_product){
            $product = new Product();
            $product->agency_id	= $m_product->agency_id;
            $product->merchant_id	= $m_product->merchant_id;
            $product->name	= $m_product->m_product_name;
            $product->price	= $m_product->m_product_price;
            $product->product_link	= $m_product->m_product_url;
            $product->upload_image	= $m_product->product_image_url;
            $product->image_name	= $m_product->product_image_url;
            $product->merchant_product_id	= $m_product->id;
            $product->brand_id	= $m_product->brand_id;
            $product->category_id	= $m_product->category_id;
            $product->gender_id	= $m_product->gender_id;
            if($product->save()){
                $m_product->is_moderated = true;
                $m_product->save();
            }
        }
    }

    public function rejectAllProducts()
    {
        $merchant_products =
            MerchantProduct::
            where($this->where_conditions)
                ->simplePaginate($this->records_per_page);

        foreach($merchant_products as $m_product){
            $rejected_product = new MerchantProductRejected();
            $rejected_product->agency_id	= $m_product->agency_id;
            $rejected_product->merchant_id	= $m_product->merchant_id;
            $rejected_product->m_product_id	= $m_product->m_product_id;
            $rejected_product->m_product_sku	= $m_product->m_product_sku;
            if($rejected_product->save()){
                $m_product->is_moderated = true;
                $m_product->save();
            }
        }
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
