<?php
namespace App;


class Error
{
    function error($code, $json_encode=true, $custom_message="")
    {
        switch ($code) {
            case 10:
                $message = "Exception Occured";
                break;

            case 11:
                $message = "Invalid id";
                break;

            case 401:
                $message = 'No product found';
                break;
            case 402:
                $message = 'Some Error(s) found';
                break;
            case 403:
                $message = 'Products created successfully';
                break;
            case 404:
                $message = 'Error creating new products';
                break;
            case 405:
                $message = 'No product selected';
                break;
            case 406:
                $message = 'Error in creating data in rejected products table';
                break;
            case 407:
                $message = 'Error deleting data from Merchant Products';
                break;
            case 408:
                $message = 'Products rejected';
                break;

            default :
                $message = "";
        }

        if(strlen($custom_message) > 0){
            $message .= PHP_EOL . $custom_message;
        }

        $error = array(
            'error' => array(
                'code' => $code,
                'message' => $message
            )
        );


        if($json_encode){
            return json_encode($error);
        }

        return $error;
    }
}