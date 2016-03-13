<?php
/**
 * Relationship Exceptioin
 *
 * @author hrishikesh.mishra
 */
namespace App\Report\Exceptions;

class RelationshipException extends \Exception{

    public function __construct($message = "", $code = 0){
        parent::__construct($message, $code);
    }
} 