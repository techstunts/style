<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 13/03/16
 * Time: 4:28 PM
 */

namespace App\Report\Entities\Enums;

use App\Report\Exceptions\InvalidEnumException;

class FilterType {

    const SINGLE_SELECT = "single_select";
    const MULTI_SELECT = "multi_select";
    const DATE_RANGE = "date_range";

    private static $multiValuesFilter = array(self::SINGLE_SELECT, self::MULTI_SELECT);

    private static $constants = array();

    private static function getConstants(){
        if (null == self::$constants) {
            $class = new \ReflectionClass(get_called_class());
            self::$constants = $class->getConstants();
        }
        return self::$constants;
    }

    public static function getAllowedValues(){
        return array_values(self::getConstants());
    }

    public static function isValidType($fieldType){
        return array_key_exists($fieldType, self::getConstants()) ? true : false;
    }

    public static function isValidValue($value){
        return array_search($value, self::getConstants()) === false ? false :true;
    }

    public static function getType($type){
        $key = array_search($type, self::getConstants());
        if($key == false) throw new InvalidEnumException("Invalid enum : $type");
        return $key;
    }

    public static function isFilterWithMultiValue($filterType){
        return in_array($filterType, self::$multiValuesFilter);
    }

    public static function getMultiValueFilters(){
        return self::$multiValuesFilter;
    }

}