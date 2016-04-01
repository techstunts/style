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
use App\Models\Lookups\EntityType;
use App\Models\Enums\AppSections;
use App\Models\Enums\RecommendationType;
use App\Models\Enums\EntityType as EntityTypeId;

class RecommendationController extends Controller
{
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

    public function postSend(Request $request)
    {
        $app_section = isset($request->app_section) && $request->app_section != "" ? $request->app_section : AppSections::MY_REQUESTS;

        $errormsg = '';
        $entity_type_id = '';
        if(isset($request->entity_type_id)){
            $entity_type_id = $request->entity_type_id;
        }else{
            $errormsg = 'Entity type undefined';
        }

        $client_ids = isset($request->client_ids) ? $request->client_ids : '';
        $entity_ids = isset($request->entity_ids) ? $request->entity_ids : '';

        $stylish_id = Auth::user()->stylish_id;
        $entity_type_name = EntityType::where('id', $entity_type_id)->first()->name;
        if($entity_type_id) {
            foreach ($client_ids as $client) {
                $message_pushed = 0;
                foreach ($entity_ids as $entity_id) {
                    $recommends_arr[] = array(
                        'user_id' => $client,
                        'recommendation_type_id' => RecommendationType::MANUAL,
                        'created_by' => $stylish_id,
                        'entity_type_id' => $entity_type_id,
                        'entity_id' => $entity_id
                    );

                    if ($message_pushed == 0) {
                        $client_data = Client::where('user_id', $client)->first();
                        $stylist_data = Stylist::where('stylish_id', $stylish_id)->first();

                        if (empty($client_data) || empty($stylist_data)) {
                            break;
                        }
                        $regId = $client_data->regId;
                        $stylish_name = $stylist_data->name;
                        $entity_url = '';
                        if ($entity_type_id == EntityTypeId::PRODUCT) {
                            $entity_data = Product::where('id', $entity_id)->first();
                            if (empty($entity_data)) {
                                break;
                            }
                            $entity_url = $entity_data->upload_image;

                        } elseif ($entity_type_id == EntityTypeId::LOOK) {
                            $entity_data = Look::where('id', $entity_id)->first();
                            if (empty($entity_data)) {
                                break;
                            }
                            $entity_url = $entity_data->image;
                        }

                        $push = new Push();
                        $params = array(
                            "pushtype" => "android",
                            "registration_id" => $regId,
                            "message" => $stylish_name . " has sent you" . $entity_type_name,
                            "message_summery" => $stylish_name . " has sent you" . $entity_type_name,
                            "look_url" => $entity_url,
                            "url" => $entity_url,
                            'app_section' => $app_section,
                        );
                    $rtn = $push->sendMessage($params);
                        $message_pushed++;
                    }
                }
                Recommendation::insert($recommends_arr);
                $recommends_arr = [];
            }
        }

        return response()->json(
            array(
                'success' => true,
                'error_message' => $errormsg
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
