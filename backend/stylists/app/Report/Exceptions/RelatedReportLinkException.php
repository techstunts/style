<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 19/03/16
 * Time: 10:40 AM
 */

namespace App\Report\Exceptions;


class RelatedReportLinkException extends \Exception{

    public function __construct($message = "", $code = 0){
        parent::__construct($message, $code);
    }
}