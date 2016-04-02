<?php
namespace App;

class Push
{



    private function sendMessageAndroid($registration_id, $params)
    {

        ## data is different from what your app is programmed
        $data = array(
            'registration_ids' => array($registration_id),

            'data' => array(
                'message' => $params["message"],
                'message_summery' => $params["message_summery"],
                'look_url' => $params["look_url"],
                'url' => $params["url"],
                'app_section' => ((isset($params["app_section"]) && $params["app_section"] != "") ? $params["app_section"] : "3")
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
        $rtn["result"] = $result;
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

                    $this->sendMessageAndroid($params["registration_id"], $params);
                    break;
            }
        }

    }
}

?>