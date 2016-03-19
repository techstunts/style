<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 19/03/16
 * Time: 9:05 PM
 */

namespace App\Report\Exceptions;


class FilterNotFoundException extends \Exception{

    public function __construct($message = "", $code = 0){
        parent::__construct($message, $code);
    }
}