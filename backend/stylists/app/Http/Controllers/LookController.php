<?php

namespace App\Http\Controllers;

use App\CombineImages;
use App\Look;
use App\Product;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class LookController extends Controller
{
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
        $paginate_qs = $request->query();
        unset($paginate_qs['page']);

        $looks  =
            Look::orderBy('look_id', 'desc')
                ->simplePaginate($this->records_per_page)
                ->appends($paginate_qs);


        //$looks = Look::where('look_id','<=',8000)->get()->slice(0,10)->all();
        return view('look.list',['looks'=> $looks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCreate(Request $request)
    {
        echo "getCreate";
    }

    /**
     * Store a newly created look in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postCreate(Request $request)
    {

        $look = new Look();
        $look->look_name = isset($request->look_name) && $request->look_name != '' ? $request->look_name : '';
        $look->look_description = isset($request->look_description) && $request->look_description != '' ? $request->look_description : '';
        $look->body_type = isset($request->body_type) && $request->body_type != '' ? $request->body_type : '';
        $look->budget = isset($request->budget) && $request->budget != '' ? $request->budget : '';
        $look->age = isset($request->age) && $request->age != '' ? $request->age : '';
        $look->occasion = isset($request->occasion) && $request->occasion != '' ? $request->occasion : '';
        $look->gender = isset($request->gender) && $request->gender != '' ? $request->gender : '';
        $look->stylish_id = isset($request->stylish_id) && $request->stylish_id != '' ? $request->stylish_id : '';
        $look->date = date('Y-m-d H:i:s');

        $look_price = 0;
        $src_image_paths = Array();
        $cnt = 1;
        foreach($request->input('product_id') as $product_id){
            if($product_id != ''){
                $product = null;
                $product = Product::find($product_id);
                if($product && $product->id){
                    $property = "product_id" . $cnt;
                    $look->$property = $product_id;
                    $look_price += $product->product_price;
                    $src_image_paths[] = $product->upload_image;
                    $cnt++;
                }
            }
        }

        $look->lookprice = $look_price;

        $lookImage = new CombineImages();
        if($lookImage->createLook($src_image_paths, $look->look_name)){
            $look->look_image = $lookImage->targetImage;
            if($look->save()){
                $domain = str_replace("stylist.", "", $_SERVER['HTTP_HOST']);
                return response()->json(
                    array('success' => true,
                        'look_id' => $look->look_id,
                        'look_url' => url('look/view/' . $look->look_id),
                        'look_name' => $look->look_name
                    ), 200);
            }
        }

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getView()
    {
        $look = Look::find($this->resource_id);
        $view_properties = null;
        if($look){
            $product_ids[] = $look->product_id1;
            $product_ids[] = $look->product_id2;
            $product_ids[] = $look->product_id3;
            $product_ids[] = $look->product_id4;

            $products = Product::find($product_ids);
            //var_dump($look, $look->stylist, $product_ids, $products);
            $view_properties = array('look' => $look, 'products' => $products, 'stylist' => $look->stylist);
        }

        return view('look.view', $view_properties);
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
