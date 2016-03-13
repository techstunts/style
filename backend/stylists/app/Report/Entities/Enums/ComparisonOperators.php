<?php

namespace App\Report\Entities\Enums;
use App\Report\Exceptions\InvalidEnumException;
/**
 * Comparison operators
 *
 * @author hrishikesh.mishra
 */
class ComparisonOperators {

    const EQUAL = "=";   
    const NOT_EQUAL = "<>";   
    const GREATER_THAN = ">";   
    const GREATER_THAN_OR_EQUAL = ">=";   
    const LESS_THAN = "<";   
    const LESS_THAN_OR_EQUAL = "<=";       
    
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
