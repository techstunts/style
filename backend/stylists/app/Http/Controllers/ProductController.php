<?php

namespace App\Http\Controllers;

use App\Brand;
use App\Category;
use App\Models\Lookups\Gender;
use App\Models\Lookups\Color;
use App\Merchant;
use App\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\SelectOptions;

class ProductController extends Controller
{
    protected $filter_ids = ['merchant_id', 'brand_id', 'category_id', 'gender_id'];
    protected $filters = ['merchants', 'brands', 'categories', 'genders'];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $action, $id = null)
    {
        $method = strtolower($request->method()) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
        if($id){
            $this->resource_id = $id;
        }
        return $this->$method($request);
    }

    public function getList(Request $request){
        $this->base_table = 'products';
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

        $products =
            Product::
            where($this->where_conditions)
                ->orderBy('id', 'desc')
                ->simplePaginate($this->records_per_page)
                ->appends($paginate_qs);

        $view_properties['products'] = $products;
        $view_properties['genders_list'] = $genders_list;

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
	$primary_color = Color::where(['name' => $request->input('color1')])->first();
        $secondary_color = Color::where(['name' => $request->input('color2')])->first();

        if($merchant && $brand && $category && $request->input('name')) {
            $product = new Product();
            $product->merchant_id	= $merchant->id;
            $product->product_name	= $request->input('name');
            $product->description	= $request->input('desc');
            $product->product_price	= str_replace(array(","," "), "", $request->input('price'));
            $product->product_link	= $request->input('url');
            $product->upload_image	= $request->input('image0');
            $product->image_name	= $request->input('image0');
            $product->brand_id	    = $brand->id;
            $product->category_id	= $category->id;
            $product->gender_id	    = $gender ? $gender->id : "";
            $product->primary_color_id     = $primary_color ? $primary_color->id : "";
            $product->secondary_color_id     = $secondary_color ? $secondary_color->id : "";

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
    public function getView()
    {
        $product = Product::find($this->resource_id);
        $view_properties = null;
        if($product){
            $category = $product->category;
            $merchant = $product->merchant;
            $brand = $product->brand;
            $gender = $product->gender;
            $looks = $product->looks;

            $view_properties = array('product' => $product, 'looks' => $looks, 'merchant' => $merchant,
                'category' => $category, 'gender' => $gender, 'brand' => $brand, 'primary_color' => $product->primary_color, 'secondary_color' => 
$product->secondary_color);
        }
        else{
            return view('404', array('title' => 'Product not found'));
        }

        return view('product.view', $view_properties);
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
