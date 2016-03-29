<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 13/03/16
 * Time: 2:15 PM
 */

namespace App\Report\Entities\Enums;
use App\Report\Exceptions\InvalidEnumException;

class WhereType {

    const OR_WHERE = "or_where";
    const WHERE_BETWEEN = "where_between";
    const WHERE_NOT_BETWEEN = "where_not_between";
    const WHERE_IN = "where_in";
    const WHERE_NOT_IN = "where_not_in";
    const WHERE_NOT_NULL = "where_not_null";
    const WHERE_NULL = "where_null";

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
}