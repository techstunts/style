<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 8:27 PM
 */

namespace App\Report\Exceptions;


class ReportEntityException extends \Exception{

    public function __construct($message = "", $code = 0){
        parent::__construct($message, $code);
    }
}