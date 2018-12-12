<?php

namespace App\Http\Controllers;

use App\Http\Mapper\ProductMapper;
use App\Models\Enums\Currency;
use App\Models\Enums\EntityTypeName;
use App\Models\Enums\ImageType;
use App\Models\Enums\PriceType;
use App\Models\Enums\ProfileImageStatus;
use App\Models\Enums\Status;
use App\UploadImages;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        $client_ids = $request->input('client_ids') ? $request->input('client_ids') : '';
        $entity_ids = $request->input('entity_ids') ? $request->input('entity_ids') : '';

        $product_ids = !empty($request->input('entity_ids')[strtolower(EntityTypeName::PRODUCT)]) ? $request->input('entity_ids')[strtolower(EntityTypeName::PRODUCT)] : [];
        $look_ids = !empty($request->input('entity_ids')[strtolower(EntityTypeName::LOOK)]) ? $request->input('entity_ids')[strtolower(EntityTypeName::LOOK)] : [];
        $tip_ids = !empty($request->input('entity_ids')[strtolower(EntityTypeName::TIP)]) ? $request->input('entity_ids')[strtolower(EntityTypeName::TIP)] : [];
        $collection_ids = !empty($request->input('entity_ids')[strtolower(EntityTypeName::COLLECTION)]) ? $request->input('entity_ids')[strtolower(EntityTypeName::COLLECTION)] : [];

        $client_reg_details = function($subQuery){
            $subQuery->where('regid_status', true);
        };

        $client = function($query) use ($client_reg_details) {
            $query->with(['client_reg_details' => function($subQuery){
                $subQuery->where('regid_status', true);
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
        $upload_image = function ($query) {
            $query->where([
                'uploaded_by_entity_type_id' => EntityTypeId::LOOK,
                'image_type_id' => ImageType::PDP_Image,
                'status_id' => ProfileImageStatus::Active,
            ]);
        };

        $api_origin = env('IMAGES_ORIGIN');
        $entity_data = array();
        if (count($product_ids) > 0) {
            $products = Product::with(['category', 'merchant'])->whereIn('id', $product_ids)
                ->select('id', 'name', 'image_name as image', 'product_link', 'merchant_id')->get();

            if (count($products)) {
                foreach ($products as $product) {
                    if ($product->product_prices){
                        foreach ($product->product_prices as $product_price) {
                            if ($product_price->price_type_id == PriceType::RETAIL && $product_price->currency_id == Currency::INR)
                                $product->price = $product_price->value;
                        }
                        unset($product->product_prices);
                    }
                }
            }
            $entity_data[strtolower(EntityTypeName::PRODUCT)] = $products;
        }
        if (count($look_ids) > 0) {
            $looks = Look::whereIn('id', $look_ids)->with(['look_products.product', 'images' => $upload_image])
                ->select('id', 'name', DB::raw("concat('$api_origin', '/uploads/images/looks/', image) as image"))->get();
            foreach ($looks as $look) {
                if (count($look->images) > 0) {
                    $look->image = env('IMAGES_ORIGIN') . '/' . $look->images[0]['path'] . '/' . $look->images[0]['name'];
                    unset($look->images);
                }
                if (count($look->look_products)) {
                    foreach ($look->look_products as $look_product) {
                        if ($look_product->product && $look_product->product->product_prices){
                            foreach ($look_product->product->product_prices as $product_price) {
                                if ($product_price->price_type_id == PriceType::RETAIL && $product_price->currency_id == Currency::INR)
                                    $look_product->product->price = $product_price->value;
                            }
                            unset($look_product->product->product_prices);
                        }
                    }
                }
            }
            $entity_data[strtolower(EntityTypeName::LOOK)] = $looks;
        }
        if (count($tip_ids) > 0) {
            $entity_data[strtolower(EntityTypeName::TIP)] = Tip::whereIn('id', $tip_ids)
                ->select('id', 'name', DB::raw("concat('$api_origin', '/uploads/images/tips/', image) as image"))->get();
        }
        if (count($collection_ids) > 0) {
            $entity_data[strtolower(EntityTypeName::COLLECTION)] = Collection::whereIn('id', $collection_ids)
                ->select('id', 'name', DB::raw("concat('$api_origin', '/uploads/images/collections/', image) as image"))->get();
        }
        $clients_count = count($client_data);

        $stylists = [];
        $recommends_arr = array();
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
                $stylist_data = Auth::user();
                $reg_ids = $client_data[$i]->client_reg_details;
                $client = $client_data[$i];
            }

            if(!$stylist_data){
                $stylist_data = Auth::user();
            }

            $this->sendMail($request, $client, $stylist_data, $entity_data);

            $regIdsAndroid = array();
            $regIdsIOS = array();
            $android_flag = false;
            $ios_flag = false;
            foreach ($reg_ids as $reg_id) {
                if ($reg_id->os == 'android') {
                    array_push($regIdsAndroid, $reg_id->regid);
                    $android_flag = true;
                } elseif ($reg_id->os == 'ios') {
                    array_push($regIdsIOS, $reg_id->regid);
                    $ios_flag = true;
                }
            }

            $message_pushed = 0;
            $client_id = $recommendation_type_id == RecommendationType::STYLE_REQUEST ? $client_data[$i]->client->id : $client_data[$i]->id;
            $style_request_id = $recommendation_type_id == RecommendationType::STYLE_REQUEST ? $client_data[$i]->id : 0;
            $recommends_arr = $this->dataToBeSaved($recommends_arr, $entity_data, $client_id, $recommendation_type_id, $style_request_id);

            if (!env('IS_NICOBAR') && count($reg_ids) > 0 && $message_pushed == 0) {
                $params = array(
                    "message" => $stylist_data->name . " has sent you recommendation",
                    "message_summery" => $stylist_data->name . " has sent you recommendation against your style request",
                    "look_url" => !empty($entity_data[strtolower(EntityTypeName::LOOK)]) ? $entity_data[strtolower(EntityTypeName::LOOK)][0]->image : '',
                    "url" => !empty($entity_data[strtolower(EntityTypeName::LOOK)]) ? $entity_data[strtolower(EntityTypeName::LOOK)][0]->image : '',
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
            }
        }
        $error_message = '';
        $success_message = '';

        DB::beginTransaction();
        try{
            Recommendation::insert($recommends_arr);
            if (!empty($inactive_reg_ids_query)) {
                DeviceDetails::whereRaw(substr($inactive_reg_ids_query, 4))->update(['regid_status' => false]);
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

    public function dataToBeSaved($recommends_arr, $entity_data, $client_id, $recommendation_type_id, $style_request_id) {
        if (!empty($entity_data[strtolower(EntityTypeName::PRODUCT)]) && count($entity_data[strtolower(EntityTypeName::PRODUCT)]) > 0) {
            $recommends_arr = $this->formatData($recommends_arr, $entity_data[strtolower(EntityTypeName::PRODUCT)], EntityTypeId::PRODUCT, $client_id, $recommendation_type_id, $style_request_id);
        }
        if (!empty($entity_data[strtolower(EntityTypeName::LOOK)]) && count($entity_data[strtolower(EntityTypeName::LOOK)]) > 0) {
            $recommends_arr = $this->formatData($recommends_arr, $entity_data[strtolower(EntityTypeName::LOOK)], EntityTypeId::LOOK, $client_id, $recommendation_type_id, $style_request_id);
        }
        if (!empty($entity_data[strtolower(EntityTypeName::TIP)]) && count($entity_data[strtolower(EntityTypeName::TIP)]) > 0) {
            $recommends_arr = $this->formatData($recommends_arr, $entity_data[strtolower(EntityTypeName::TIP)], EntityTypeId::TIP, $client_id, $recommendation_type_id, $style_request_id);
        }
        if (!empty($entity_data[strtolower(EntityTypeName::COLLECTION)]) && count($entity_data[strtolower(EntityTypeName::COLLECTION)]) > 0) {
            $recommends_arr = $this->formatData($recommends_arr, $entity_data[strtolower(EntityTypeName::COLLECTION)], EntityTypeId::COLLECTION, $client_id, $recommendation_type_id, $style_request_id);
        }
        return $recommends_arr;
    }

    public function formatData($recommends_arr, $entity_data, $entity_type_id, $client_id, $recommendation_type_id, $style_request_id) {
        foreach ($entity_data as $item) {
            $recommends_arr[] = array(
                'user_id' => $client_id,
                'recommendation_type_id' => $recommendation_type_id,
                'created_by' => Auth::user()->id,
                'entity_type_id' => $entity_type_id,
                'entity_id' => $item->id,
                'style_request_id' => $style_request_id,
                'created_at' => date("Y-m-d H:i:s")
            );
        }
        return $recommends_arr;
    }

    public function getQueryForInactiveRegIds($reg_ids, $reg_status)
    {
        $query = '';
        $client_id = $reg_ids[0]->client_id;
        $index = 0;
        foreach ($reg_status as $status) {
            if (!empty($status->error) &&
                ($status->error == "NotRegistered" || $status->error == "InvalidRegistration" || $status->error == "MissingRegistration")) {
                $regId = $reg_ids[$index++]->regid;
                $query = $query . " OR (client_id = '{$client_id}' AND regid = '{$regId}')";
            }
        }
        return $query;
    }

    public function sendMail(Request $request, $client, $stylist, $entity_data){
        $banner_image = UploadImages::where('uploaded_by_entity_id', $stylist->id)
            ->where('uploaded_by_entity_type_id', \App\Models\Enums\EntityType::STYLIST)
            ->where('image_type_id', ImageType::Banner)
            ->where('status_id', Status::Active)->first();

        $banner_image_path = $banner_image ? env('IMAGES_ORIGIN') .'/'. $banner_image->path . '/' . $banner_image->name : "";

        $product_mapper = new ProductMapper();
        if (!empty($entity_data[strtolower(EntityTypeName::PRODUCT)]))
            foreach ($entity_data[strtolower(EntityTypeName::PRODUCT)] as $product) {
                $product->product_link = $product_mapper->getDeepLink($product->merchant_id, $product->product_link);
        }

        $words = explode(" ", $stylist->name);
        $stylist_first_name = $words[0];

        $words = explode(" ", $client->name);
        $client_first_name = $words[0];

        $custom_message = str_replace("{stylist_name}", $stylist_first_name, env('RECOMMENDATION_EMAIL_MESSAGE'));
        $custom_message = $request->input('custom_message') && trim($request->input('custom_message')) != "" ? $request->input('custom_message') : $custom_message;

        $product_list_heading = env('RECOMMENDATION_EMAIL_PRODUCTLIST_HEADING');
        $product_list_heading = $request->input('product_list_heading') && trim($request->input('product_list_heading')) != "" ? $request->input('product_list_heading') : $product_list_heading;

        $recommendation_template = env('IS_NICOBAR') ? ('emails.nico_recommendations') : ('emails.recommendations');
        $recommendation_template = env('AUTO_RECO_MAIL') ? ('emails.auto_recommendation') : $recommendation_template;
        $from_email = env('FROM_EMAIL') ? env('FROM_EMAIL') : 'stylists@istyleyou.in';
        $bcc_email = env('BCC_EMAIL') ? explode(';',env('BCC_EMAIL')) : [];
        $static_url = env('IS_NICOBAR') ? env('NICOBAR_STATIC_URL') : env('ALL_ASSETS');

        try {
            Mail::send($recommendation_template,

                ['client' => $client, 'stylist' => $stylist, 'entity_data' => $entity_data,
                    'banner_image_path' => $banner_image_path, 'stylist_first_name' => $stylist_first_name,
                    'client_first_name' => $client_first_name, 'custom_message' => $custom_message,
                    'product_list_heading' => $product_list_heading,
                    'nicobar_website' => env('NICOBAR_WEBSITE'),
                    'static_url' => $static_url
                ],
                function ($mail) use ($client, $stylist, $client_first_name, $from_email, $bcc_email) {
                    $mail->from($from_email, (env('IS_NICOBAR') ? 'Nicobar' : 'IStyleYou'));
                    $mail->to($client->email, $client->name)
                        ->bcc($bcc_email)
                        ->subject($client_first_name . ', your stylist has sent you recommendations!');
                });
        } catch (\Exception $e) {
            Log::info('Exception sending mail : '. $e->getMessage());
            return false;
        }
    }
//below function is not in use right now as structure of sending the recommendatoin has been changed
    public function getEntityProducts($entity_type_id, $entity_data) {
        $entity_products = array();
        if ($entity_type_id == EntityTypeId::PRODUCT) {
            foreach ($entity_data as $product) {
                $entity_products[] = $product;
            }
        } elseif ($entity_type_id == EntityTypeId::LOOK) {
            foreach ($entity_data as $look) {
                if ($look->products) {
                    foreach ($look->products as $product) {
                        $entity_products[] = $product;
                    }
                }
            }
        } elseif ($entity_type_id == EntityTypeId::TIP) {
            foreach ($entity_data as $tips) {
                if ($tips->product_entities) {
                    foreach ($tips->product_entities as $tipEntity) {
                        if ($tipEntity->product) {
                            $entity_products[] = $tipEntity->product;
                        }
                    }
                }
            }
        } elseif ($entity_type_id == EntityTypeId::COLLECTION) {
            foreach ($entity_data as $collection) {
                if ($collection->product_entities) {
                    foreach ($collection->product_entities as $collectionEntity) {
                        if ($collectionEntity->product) {
                            $entity_products[] = $collectionEntity->product;
                        }
                    }
                }
            }
        }
        return $entity_products;
    }

}
