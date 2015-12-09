<?php

namespace App\Http\Controllers;

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
        $method = strtolower($_SERVER['REQUEST_METHOD']) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
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
