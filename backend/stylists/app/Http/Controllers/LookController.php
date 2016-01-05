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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LookController extends Controller
{
    protected $filter_ids = ['stylish_id', 'status_id', 'gender_id'];
    protected $filters = ['stylists', 'statuses', 'genders'];

    protected $status_rules;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $action, $id = null, $action_id = null)
    {
        $method = strtolower($request->method()) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
        if($id){
            $this->resource_id = $id;
        }
        if($action_id){
            $this->action_resource_id = $action_id;
        }

        return $this->$method($request);
    }

    protected function initStatusRules(){
        $rules_file = app_path() . '/Models/Rules/look.xml';
        $data = implode("", file($rules_file));

        $xml = simplexml_load_string($data);
        $json = json_encode($xml);
        $status_rules = json_decode($json,TRUE);

        foreach($status_rules['statuses']['status'] as $status){
            $this->status_rules[$status['id']] = $status;
        }

        //var_export($this->status_rules);die;

    }

    public function getList(Request $request){
        $this->base_table = 'looks';
        $this->initWhereConditions($request);
        $this->initFilters();

        $view_properties = array(
            'stylists' => $this->stylists,
            'statuses' => $this->statuses,
            'genders' => $this->genders
        );

        foreach($this->filter_ids as $filter){
            $view_properties[$filter] = $request->input($filter) ? $request->input($filter) : "";
        }

        $paginate_qs = $request->query();
        unset($paginate_qs['page']);
        $this->initStatusRules();

        if(Auth::user()->hasRole('admin')){
            $view_properties['user_role'] = 'admin';
        }
        else if(Auth::user()->hasRole('stylist')){
            $view_properties['user_role'] = 'stylist';
        }


        $remove_deleted_looks = '1=1';
        if(!$request->has('status_id') || $request->input('status_id') != LookupStatus::Deleted){
            $remove_deleted_looks = 'looks.status_id != ' . LookupStatus::Deleted;
        }

        $looks  =
            Look::where($this->where_conditions)
                ->whereRaw($remove_deleted_looks)
                ->orderBy('id', 'desc')
                ->simplePaginate($this->records_per_page)
                ->appends($paginate_qs);

        $view_properties['looks'] = $looks;
        $view_properties['status_rules'] = $this->status_rules;
        return view('look.list', $view_properties);
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

    public function getChangestatus(Request $request)
    {
        $look = Look::find($this->resource_id);
        if($look) {
            $this->initStatusRules();
            if (isset($this->status_rules[$look->status->id]['edit_status']['new_status'])) {

                $new_statuses = $this->status_rules[$look->status->id]['edit_status']['new_status'];
                if (isset($new_statuses['id'])) {
                    $new_statuses = array($new_statuses);
                }
                foreach($new_statuses as $new_status) {
                    if ($new_status['id'] == $this->action_resource_id) {
                        $look->status_id = $new_status['id'];
                        if($look->save()){
                            return Redirect::back()->withSuccess('Look status changed successfully!');
                        }
                        else{
                            return Redirect::back()->withError('Error! Look save error.');
                        }
                    }
                }
                return Redirect::back()->withError('Error! Look status change from status ' . $look->status->name . ' to status ' . $this->action_resource_id . ' is not allowed.');
            }
            else{
                return Redirect::back()->withError('Error! Look is in its last status. Further status changes not allowed.');
            }
        }
        else{
            return Redirect::back()->withError('Error! Look not found.');
        }
        return Redirect::back()->withError('Error! Status cant be changed.');
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
        $look->status_id = LookupStatus::Submitted;

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
