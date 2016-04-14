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
use App\StyleRequests;
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
        return $this->$method($request);
    }

    public function postSend(Request $request)
    {
        $app_section = $request->input('app_section') && $request->input('app_section') != "" ? $request->input('app_section') : AppSections::MY_REQUESTS;
        $recommendation_type_id = $request->input('recommendation_type_id') ? $request->input('recommendation_type_id') : RecommendationType::MANUAL;
        $style_request_ids = $request->input('style_request_ids');

        $entity_type_id = '';
        if ($request->input('entity_type_id')) {
            $entity_type_id = $request->input('entity_type_id');
        } else {
            return response()->json(
                array(
                    'error_message' => 'Entity type undefined'
                ), 200
            );
        }

        $client_ids = $request->input('client_ids') ? $request->input('client_ids') : '';
        $entity_ids = $request->input('entity_ids') ? $request->input('entity_ids') : '';

        $entity_type_data = EntityType::where('id', $entity_type_id)->first();
        if (empty($entity_type_data)) {
            return response()->json(
                array(
                    'error_message' => 'Entity type name not found'
                ), 200
            );
        }


        $stylish_data = Auth::user();
        if (empty($stylish_data)) {
            return response()->json(
                array(
                    'error_message' => 'Invalid stylist'
                ), 200
            );
        }
        $stylist_id = $stylish_data->id;

        $client_data = '';
        if ($recommendation_type_id == RecommendationType::STYLE_REQUEST) {
            $client_data = StyleRequests::with('client')
                ->whereIn('id', $style_request_ids)->get();
        }else{
            $client_data = Client::whereIn('id', $client_ids)->get();
        }

        if (empty($client_data)) {
            return response()->json(
                array(
                    'error_message' => 'Client not found'
                ), 200
            );
        }
        $entity_data = '';
        if ($entity_type_id == EntityTypeId::PRODUCT) {
            $entity_data = Product::whereIn('id', $entity_ids)->get();
        } elseif ($entity_type_id == EntityTypeId::LOOK) {
            $entity_data = Look::whereIn('id', $entity_ids)->get();
        }
        if (empty($entity_data)) {
            return response()->json(
                array(
                    'error_message' => 'Entity not found'
                ), 200
            );
        }
        $clients_count = count($client_data);
        $entity_count = count($entity_data);

        $recommends_arr = array();
        $query_count = 0;
        $push = new Push();
        for ($i = 0; $i < $clients_count; $i++) {
            $message_pushed = 0;
            for ($j = 0; $j < $entity_count; $j++) {
                $recommends_arr[$query_count] = array(
                    'user_id' => $client_data[$i]->id,
                    'recommendation_type_id' => $recommendation_type_id,
                    'created_by' => $stylist_id,
                    'entity_type_id' => $entity_type_id,
                    'entity_id' => $entity_data[$j]->id,
                    'style_request_id' => $client_data[$i]->id ? $client_data[$i]->id : 0,
                    'created_at' => date("Y-m-d H:i:s")
                );
                $query_count++;
                if ($message_pushed == 0) {
                    $params = array(
                        "pushtype" => "android",
                        "registration_id" => $client_data[$i]->regId,
                        "message" => $stylish_data->name . " has sent you " . $entity_type_data->name,
                        "message_summery" => $stylish_data->name . " has sent you " . $entity_type_data->name,
                        "look_url" => env('IMAGE_BASE_URL') . $entity_data[$j]->image,
                        "url" => env('IMAGE_BASE_URL') . $entity_data[$j]->image,
                        'app_section' => $app_section,
                    );
                    $push->sendMessage($params);
                    $message_pushed++;
                }
            }
        }
        Recommendation::insert($recommends_arr);

        return response()->json(
            array(
                'success' => true,
                'error_message' => '',
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
