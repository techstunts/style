<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 20/03/16
 * Time: 12:49 AM
 */

namespace App\Report\Exceptions;


class JsonException extends \Exception{

    public function __construct($message = "", $code = 0){
        parent::__construct($message, $code);
    }
}