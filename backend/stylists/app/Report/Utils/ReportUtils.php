<?php

/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 19/03/16
 * Time: 12:25 AM
 */

namespace App\Report\Utils;

class ReportUtils {

    /**
     * This will prevent "Undefined index error exception"
     * @param array $array
     * @param $index
     * @return null
     */
    public static function getValueFromArray(array $array, $index){
        return isset($array[$index])? $array[$index]:null;
    }
}