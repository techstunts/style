<?php
namespace App;


class Success
{
    function success($code, $json_encode=true, $custom_message="")
    {
        switch ($code) {
            case 400:
                $message = "Done successfully";
                break;
        }

        if(strlen($custom_message) > 0){
            $message .= PHP_EOL . $custom_message;
        }

        $result = array(
            'success' => array(
                'code' => $code,
                'message' => $message
            )
        );

        if($json_encode){
            return json_encode($result);
        }

        return $result;
    }
}