<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\Gender;
use App\Merchant;
use App\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\SelectOptions;

class ProductController extends Controller
{
    protected $filters = ['merchant_id', 'brand_id', 'category_id'];

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

    public function getList(Request $request){
        $this->initWhereConditions($request);
        $this->initFilters('lookdescrip');

        $view_properties = array(
            'merchants' => $this->merchants,
            'brands' => $this->brands,
            'categories' => $this->categories
        );

        foreach($this->filters as $filter){
            $view_properties[$filter] = $request->input($filter) ? $request->input($filter) : "";
        }

        $paginate_qs = $request->query();
        unset($paginate_qs['page']);

        $products =
            Product::
            where($this->where_conditions)
                ->orderBy('id', 'desc')
                ->simplePaginate($this->records_per_page)
                ->appends($paginate_qs);

        $view_properties['products'] = $products;

        return view('product.list', $view_properties);
    }

    public function initWhereConditions(Request $request){
        parent::initWhereConditions($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCreate(Request $request)
    {
        $merchant = Merchant::where('name', $request->input('merchant'))->first();

        $brand = Brand::firstOrCreate(['name' => $request->input('brand')]);
        $category = Category::firstOrCreate(['name' => $request->input('category')]);
        $gender = Gender::where(['name' => $request->input('gender')])->first();

        if($merchant && $brand && $category && $request->input('name')) {
            $product = new Product();
            $product->merchant_id	= $merchant->id;
            $product->product_name	= $request->input('name');
            $product->product_price	= str_replace(array(","," "), "", $request->input('price'));
            $product->product_link	= $request->input('url');
            $product->upload_image	= $request->input('image0');
            $product->image_name	= $request->input('image0');
            $product->brand_id	    = $brand->id;
            $product->category_id	= $category->id;
            $product->gender_id	    = $gender ? $gender->id : "";

            if($product->save()) {
                $product_url = url('product/view/' . $product->id);
                echo json_encode([true, $product_url]);
            }
            else{
                echo json_encode([false, "Product cant be stored"]);
            }
        }
        else{
            echo json_encode([false, "Please enter product name"]);
        }
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
