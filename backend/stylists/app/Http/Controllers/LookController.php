<?php

namespace App\Http\Controllers;

use App\CombineImages;
use App\Look;
use App\LookProduct;
use App\Models\Enums\Status as LookupStatus;
use App\Product;
use App\Models\Lookups\Status;
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
            Look::orderBy('id', 'desc')
                ->simplePaginate($this->records_per_page)
                ->appends($paginate_qs);


        //$looks = Look::where('id','<=',8000)->get()->slice(0,10)->all();
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
        $look->name = isset($request->name) && $request->name != '' ? $request->name : '';
        $look->description = isset($request->description) && $request->description != '' ? $request->description : '';
        $look->body_type_id = isset($request->body_type_id) && $request->body_type_id != '' ? $request->body_type_id : '';
        $look->budget_id = isset($request->budget_id) && $request->budget_id != '' ? $request->budget_id : '';
        $look->age_group_id = isset($request->age_group_id) && $request->age_group_id != '' ? $request->age_group_id : '';
        $look->occasion_id = isset($request->occasion_id) && $request->occasion_id != '' ? $request->occasion_id : '';
        $look->gender_id = isset($request->gender_id) && $request->gender_id != '' ? $request->gender_id : '';
        $look->stylish_id = $request->user()->stylish_id != '' ? $request->user()->stylish_id : '';
        $look->created_at = date('Y-m-d H:i:s');
        $look->status_id = LookupStatus::Inactive;

        $look_products  = array();
        $look_price = 0;
        $src_image_paths = Array();
        $cnt = 1;
        foreach($request->input('product_id') as $product_id){
            if($product_id != ''){
                $product = null;
                $product = Product::find($product_id);
                if($product && $product->id){
                    $look_products[] = array('product_id' => $product_id);
                    $look_price += $product->product_price;
                    $src_image_paths[] = $product->upload_image;
                    $cnt++;
                }
            }
        }

        $look->price = $look_price;

        $lookImage = new CombineImages();
        if($lookImage->createLook($src_image_paths, $look->name)){
            $look->image = $lookImage->targetImage;
            if($look->save()){
                foreach($look_products as &$lp){
                    $lp['look_id'] = $look->id;
                }
                LookProduct::insert($look_products);

                return response()->json(
                    array('success' => true,
                        'look_id' => $look->id,
                        'look_url' => url('look/view/' . $look->id),
                        'look_name' => $look->name
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
        $view_properties = [];
        if($look){
            $products = $look->products;
            $status = Status::find($look->status_id);
            //var_dump($look, $look->stylist, $product_ids, $products);
            $view_properties = array('look' => $look, 'products' => $products, 'stylist' => $look->stylist,
                'status' => $status);
        }
        else{
            return view('404', array('title' => 'Look not found'));
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
