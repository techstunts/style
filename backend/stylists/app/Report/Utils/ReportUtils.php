<?php

/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 19/03/16
 * Time: 12:25 AM
 */

namespace App\Report\Utils;

class ReportUtils {

    const SEPARATOR = ", ";
    /**
     * This will prevent "Undefined index error exception"
     * @param array $array
     * @param $index
     * @return null
     */
    public static function getValueFromArray(array $array, $index){
        return isset($array[$index])? $array[$index]:null;
    }

    /**
     * @param array $array
     * @return string
     */
    public static function convertArrayToString(array $array = array()){
        return implode(self::SEPARATOR, $array);
    }

    /**
     * @param $values
     * @return bool
     */
    public static function isEmpty($values){
        if(is_string($values) && trim($values) == "") return true;
        if(is_array($values) && count($values) === 0) return true;
        return false;
    }

}