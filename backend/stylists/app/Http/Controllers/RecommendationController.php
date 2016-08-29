<?php

namespace App\Http\Controllers;

use App\Http\Mapper\ProductMapper;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\Recommendation;
use App\Push;
use App\Client;
use App\Stylist;
use App\Look;
use App\Tip;
use App\Collection;
use App\Product;
use App\StyleRequests;
use App\Models\Lookups\EntityType;
use App\Models\Enums\AppSections;
use App\Models\Enums\DeviceStatus;
use App\Models\Enums\RecommendationType;
use App\Models\Enums\EntityType as EntityTypeId;
use App\ClientDeviceRegDetails as DeviceDetails;

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
        $client_reg_details = function($subQuery){
            $subQuery->where('regId_status', true);
        };

        $client = function($query) use ($client_reg_details) {
            $query->with(['client_reg_details' => function($subQuery){
                $subQuery->where('regId_status', true);
            }]);
        };

        if ($recommendation_type_id == RecommendationType::STYLE_REQUEST) {
            $client_data = StyleRequests::with(['client' => $client])
                ->whereIn('id', $style_request_ids)->get();
        }else{
            $client_data = Client::with(['stylist', 'client_reg_details' => $client_reg_details])->whereIn('id', $client_ids)->get();
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
        } elseif ($entity_type_id == EntityTypeId::TIP) {
            $entity_data = Tip::whereIn('id', $entity_ids)->get();
        } elseif ($entity_type_id == EntityTypeId::COLLECTION) {
            $entity_data = Collection::whereIn('id', $entity_ids)->get();
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

        $stylists = [];
        $recommends_arr = array();
        $query_count = 0;
        $push = new Push();
        $inactive_reg_ids_query = '';
        $client_ids_inactive_device_status = array();

        for ($i = 0; $i < $clients_count; $i++) {
            if($recommendation_type_id == RecommendationType::STYLE_REQUEST ){
                if(!isset($stylists[$client_data[$i]->client->stylist_id])){
                    $stylists[$client_data[$i]->client->stylist_id] = Stylist::find($client_data[$i]->client->stylist_id);
                }
                $stylist_data = $stylists[$client_data[$i]->client->stylist_id];
                $reg_ids = $client_data[$i]->client->client_reg_details;
                $client = $client_data[$i]->client;
            }
            else{
                $stylist_data = $client_data[$i]->stylist;
                $reg_ids = $client_data[$i]->client_reg_details;
                $client = $client_data[$i];
            }

            if(!$stylist_data){
                $stylist_data = Auth::user();
            }

            if ($entity_type_id == EntityTypeId::PRODUCT) {
                $stylist_data = Stylist::find(52);
                $this->sendMail($client, $stylist_data, $entity_data);
            }

            $regIdsAndroid = array();
            $regIdsIOS = array();
            $android_flag = false;
            $ios_flag = false;
            foreach ($reg_ids as $reg_id) {
                if ($reg_id->os == 'android') {
                    array_push($regIdsAndroid, $reg_id->regId);
                    $android_flag = true;
                } elseif ($reg_id->os == 'ios') {
                    array_push($regIdsIOS, $reg_id->regId);
                    $ios_flag = true;
                }
            }

            if (count($reg_ids) > 0) {
                $message_pushed = 0;
                for ($j = 0; $j < $entity_count; $j++) {
                    $recommends_arr[$query_count] = array(
                        'user_id' => $recommendation_type_id == RecommendationType::STYLE_REQUEST ? $client_data[$i]->client->id : $client_data[$i]->id,
                        'recommendation_type_id' => $recommendation_type_id,
                        'created_by' => Auth::user()->id,
                        'entity_type_id' => $entity_type_id,
                        'entity_id' => $entity_data[$j]->id,
                        'style_request_id' => $recommendation_type_id == RecommendationType::STYLE_REQUEST ? $client_data[$i]->id : 0,
                        'created_at' => date("Y-m-d H:i:s")
                    );
                    $query_count++;
                    if ($message_pushed == 0) {
                        $params = array(
                            "message" => $stylist_data->name . " has sent you " . $entity_type_data->name,
                            "message_summery" => $stylist_data->name . " has sent you " . $entity_type_data->name,
                            "look_url" => $entity_type_id == EntityTypeId::PRODUCT ? $entity_data[$j]->image : env('IMAGE_BASE_URL') . $entity_data[$j]->image,
                            "url" => $entity_type_id == EntityTypeId::PRODUCT ? $entity_data[$j]->image : env('IMAGE_BASE_URL') . $entity_data[$j]->image,
                            'app_section' => $app_section,
                            "stylist" => Stylist::getExposableData($stylist_data)
                        );
                        if ($ios_flag) {
                            $params['pushtype'] = "ios";
                            $params['registration_id'] = $regIdsIOS;
                            $response = $push->sendMessage($params);
                        }
                        if ($android_flag) {
                            $params['pushtype'] = "android";
                            $params['registration_id'] = $regIdsAndroid;

                            $response = $push->sendMessage($params);
                            if ($response['result'] && $response['result']->failure > 0) {
                                $inactive_reg_ids_query = $inactive_reg_ids_query . $this->getQueryForInactiveRegIds($reg_ids, $response['result']->results);
                                if ($response['result']->failure == count($regIdsAndroid)) {
                                    array_push($client_ids_inactive_device_status, $recommendation_type_id == RecommendationType::STYLE_REQUEST ? $client_data[$i]->client->id : $client_data[$i]->id);
                                }
                            }
                        }
                        $message_pushed++;
                    }
                }
            }

        }
        $error_message = '';
        $success_message = '';
        $success = false;

        DB::beginTransaction();
        try{
            Recommendation::insert($recommends_arr);
            if (!empty($inactive_reg_ids_query)) {
                DeviceDetails::whereRaw(substr($inactive_reg_ids_query, 4))->update(['regId_status' => false]);
            }
            if (!empty($client_ids_inactive_device_status)) {
                Client::whereIn('id', $client_ids_inactive_device_status)->update(['device_status' => DeviceStatus::Inactive]);
            }
            $success_message = 'Sent successfully';
            $success = true;
            DB::commit();
        } catch (\Exception $e) {
            $error_message = 'Exception: '. $e->getMessage();
            $success = false;
            DB::rollback();
        }

        return response()->json(
            array(
                'success' => $success,
                'error_message' => $error_message,
                'success_message' => $success_message,
            ), 200
        );
    }

    public function getQueryForInactiveRegIds($reg_ids, $reg_status)
    {
        $query = '';
        $client_id = $reg_ids[0]->client_id;
        $index = 0;
        foreach ($reg_status as $status) {
            if (!empty($status->error) &&
                ($status->error == "NotRegistered" || $status->error == "InvalidRegistration" || $status->error == "MissingRegistration")) {
                $regId = $reg_ids[$index++]->regId;
                $query = $query . " OR (client_id = '{$client_id}' AND regId = '{$regId}')";
            }
        }
        return $query;
    }

    public function sendMail($client, $stylist, $entity_data){
        foreach ($entity_data as $product) {
            $product->product_link = ProductMapper::getDeepLink($product->merchant_id, $product->product_link);
        }

        Mail::send('emails.recommendations',
            ['client' => $client, 'stylist' => $stylist, 'products' => $entity_data],
            function ($mail) use ($client) {
                $mail->from('stylist@istyleyou.in', 'IStyleYou stylist');
                $mail->to($client->email, $client->name)->subject('IStyleYou : Style recommendations for you!');
        });
    }

}
