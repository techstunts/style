<?php
namespace App;

use App\Models\Enums\AppSections;
use Illuminate\Support\Facades\Log;

class Push
{
    protected $timeout = 60;

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
                'app_section' => ((isset($params["app_section"]) && $params["app_section"] != "") ? $params["app_section"] : AppSections::MY_REQUESTS),
                'stylist' => $params['stylist']
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

    private function sendMessageIos($registration_ids, $params) {

        $ssl_url = 'ssl://gateway.push.apple.com:2195';

        $payload = array();
        $payload['aps'] = array('alert' => $params["message"], 'badge' => 0, 'sound' => 'default');

        $payload['extra_info'] = array('apns_msg' => $params["message"]);
        $push = json_encode($payload);

        $streamContext = stream_context_create();
        stream_context_set_option($streamContext, 'ssl', 'local_cert', env('IOS_CERTIFICATE'));

        $apns = stream_socket_client($ssl_url, $error, $errorString, $this->timeout, STREAM_CLIENT_CONNECT, $streamContext);
        if (!$apns) {
            $rtn["code"] = "001";
            $rtn["message"] = "Failed to connect ".$error." ".$errorString;
            return $rtn;
        }
        foreach ($registration_ids as $registration_id) {
            $t_registration_id = str_replace('%20', '', $registration_id);
            $t_registration_id = str_replace(' ', '', $t_registration_id);
            $apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $t_registration_id)) . chr(0) . chr(strlen($push)) . $push;
            $writeResult = fwrite($apns, $apnsMessage, strlen($apnsMessage));
        }
        fclose($apns);

        $rtn["code"] = "000";//means result OK
        $rtn["message"] = "OK";
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
                case "ios":

                    return $this->sendMessageIos($params["registration_id"], $params);
                    break;
            }
        }

    }
}

?>