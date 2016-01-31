<?php

namespace App\Http\Controllers;

use App\Collection;
use App\Look;
use App\Models\Enums\EntityType;
use App\Models\Enums\Gender;
use App\Product;
use App\Models\Lookups\Status;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class CollectionController extends Controller
{
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

    public function getList(Request $request){
        $paginate_qs = $request->query();
        unset($paginate_qs['page']);

        $collections =
            Collection::with('gender','status','body_type','budget','occasion','age_group')
                ->orderBy('id', 'desc')
                ->simplePaginate($this->records_per_page)
                ->appends($paginate_qs);

        $view_properties['collections'] = $collections;
        return view('collection.list', $view_properties);
    }


    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getView()
    {
        $female_entities = $male_entities = [];
        $collection = Collection::find($this->resource_id);
        if($collection){
            $entity_ids = DB::table('collection_entities')
                ->where('collection_id', $collection->id)
                ->select('entity_id', 'entity_type_id')
                ->get();
            foreach($entity_ids as $data){
                $entity = '';
                if($data->entity_type_id == EntityType::Look){
                    $entity = array(EntityType::Look, Look::find($data->entity_id));
                }
                else if($data->entity_type_id == EntityType::Product){
                    $entity = array(EntityType::Product, Product::find($data->entity_id));
                }
                else{
                    continue;
                }
                if(isset($entity[1]->id)){
                    if($entity[1]->gender_id == Gender::Female)
                        $female_entities[] = $entity;
                    else if($entity[1]->gender_id == Gender::Male)
                        $male_entities[] = $entity;
                }
            }
            $status = Status::find($collection->status_id);
            //var_dump($collection, $collection->stylist, $product_ids, $products);
            $view_properties = array('collection' => $collection,
                'female_entities' => $female_entities,
                'male_entities' => $male_entities,
                'status' => $status);
        }
        else{
            return view('404', array('title' => 'collection not found'));
        }

        return view('collection.view', $view_properties);
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
