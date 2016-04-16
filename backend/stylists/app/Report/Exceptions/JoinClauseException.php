<?php

/**
 * Join Clause Exception 
 *
 * @author hrishikesh.mishra
 */
namespace App\Report\Exceptions;

class JoinClauseException extends \Exception{

    public function __construct($message = "", $code = 0){
        parent::__construct($message, $code);
    }
}