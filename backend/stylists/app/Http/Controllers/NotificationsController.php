<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Recommendation;

class NotificationsController extends Controller
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

    public function postPushNotifications(Request $request){

        $entity_type_id = isset($request->entity_type_id) ? $request->entity_type_id : '';
        $client_ids = isset($request->client_ids) ? $request->client_ids : '';
        $entity_ids = isset($request->entity_ids) ? $request->entity_ids : '';

        $stylish_id = Auth::user()->stylish_id;

        foreach($client_ids as $client){
            foreach($entity_ids as $entity_id){
                $recommends_arr[] = array(
                    'user_id'                   => $client,
                    'recommendation_type_id'    => '3',
                    'created_by'                => $stylish_id,
                    'entity_type_id'            => $entity_type_id,
                    'entity_id'                 => $entity_id
                );
            }
            Recommendation::insert($recommends_arr);
            $recommends_arr = [];
        }

        return response()->json(
            array('success' => true,
            ), 200
        );
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
