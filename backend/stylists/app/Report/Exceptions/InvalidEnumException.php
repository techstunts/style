<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 11:29 PM
 */

namespace App\Report\Exceptions;


class InvalidEnumException extends \Exception{

    public function __construct($message = "", $code = 0){
        parent::__construct($message, $code);
    }
}