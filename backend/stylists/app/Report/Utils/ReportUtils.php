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

    private static $JSON_ERRORS = array(
        JSON_ERROR_NONE => 'No error',
        JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
        JSON_ERROR_STATE_MISMATCH => 'State mismatch (invalid or malformed JSON)',
        JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
        JSON_ERROR_SYNTAX => 'Syntax error',
        JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
    );

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

    public static function getJsonLastErrorMsg($error) {
        return isset(self::$JSON_ERRORS[$error]) ? self::$JSON_ERRORS[$error] : 'Unknown error';
    }

}