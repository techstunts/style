<?php

/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 6:53 PM
 */
namespace App\Report\Exceptions;

class AttributeException extends \Exception{

    public function __construct($message = "", $code = 0){
        parent::__construct($message, $code);
    }
}