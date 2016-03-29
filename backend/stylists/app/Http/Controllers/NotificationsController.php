<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Recommendation;
use App\Push;
use App\Client;
use App\Stylist;
use App\Look;
use App\Product;

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
        if ($id) {
            $this->resource_id = $id;
        }
        if ($action_id) {
            $this->action_resource_id = $action_id;
        }
        return $this->$method($request);
    }

    public function postPushNotifications(Request $request)
    {
        $app_section = isset($request->app_section) && $request->app_section != "" ? $request->app_section : "3";
        $entity_type_id = isset($request->entity_type_id) ? $request->entity_type_id : '';
        $client_ids = isset($request->client_ids) ? $request->client_ids : '';
        $entity_ids = isset($request->entity_ids) ? $request->entity_ids : '';

        $stylish_id = Auth::user()->stylish_id;

        foreach ($client_ids as $client) {
            $message_pushed = 0;
            foreach ($entity_ids as $entity_id) {
                $recommends_arr[] = array(
                    'user_id' => $client,
                    'recommendation_type_id' => '3',
                    'created_by' => $stylish_id,
                    'entity_type_id' => $entity_type_id,
                    'entity_id' => $entity_id
                );

                if ($message_pushed == 0) {
                    $regId = Client::where('user_id', $client)->first()->regId;
                    $stylish_name = Stylist::where('stylish_id', $stylish_id)->first()->name;
                    $entity_url = '';
                    if ($entity_type_id == 1) {
                        $entity_url = Product::where('id', $entity_id)->first()->upload_image;
                    } elseif ($entity_type_id == 2) {
                        $entity_url = Look::where('id', $entity_id)->first()->image;
                    }

                    $push = new Push();
                    $params = array(
                        "pushtype" => "android",
                        "registration_id" => $regId,
                        "message" => $stylish_name . " has sent you looks",
                        "message_summery" => $stylish_name . " has sent you looks",
                        "look_url" => $entity_url,
                        'app_section' => $app_section,
                    );
                    $rtn = $push->sendMessage($params);
                    $message_pushed++;
                }
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
