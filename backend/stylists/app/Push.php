<?php
namespace App;

use App\Models\Enums\AppSections;
use Illuminate\Support\Facades\Log;

class Push
{
    private function sendMessageAndroid($registration_id, $params)
    {

        ## data is different from what your app is programmed
        $data = array(
            'registration_ids' => $registration_id,

            'data' => array(
                'message' => $params["message"],
                'message_summery' => $params["message_summery"],
                'look_url' => $params["look_url"],
                'url' => $params["url"],
                'app_section' => ((isset($params["app_section"]) && $params["app_section"] != "") ? $params["app_section"] : AppSections::MY_REQUESTS)
            )
        );

        $headers = array(
            "Content-Type:application/json",
            "Authorization:key=" . env('ANDROID_AUTH_KEY')
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://android.googleapis.com/gcm/send");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $result = curl_exec($ch);

        curl_close($ch);

        $rtn["code"] = "000";//means result OK
        $rtn["message"] = "OK";
        $rtn["result"] = json_decode($result);

        Log::info('GCM send data: ', $data);
        Log::info('GCM response: ', $rtn);

        return $rtn;
    }

    /**
     * Send message to SmartPhone
     * $params [pushtype, msg, registration_id]
     */

    public function sendMessage($params)
    {

        //$parm = array("msg"=>$params["msg"]);
        if ($params["registration_id"] && $params["message"]) {
            switch ($params["pushtype"]) {
                case "android":

                    return $this->sendMessageAndroid($params["registration_id"], $params);
                    break;
            }
        }

    }
}

?>