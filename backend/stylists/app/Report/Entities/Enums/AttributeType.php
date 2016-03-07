<?php

/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 6:03 PM
 */
namespace App\Report\Entities\Enums;

use App\Report\Exceptions\InvalidEnumException;

class AttributeType {

    /**
     * If attribute presents in different table
     * instead of primary table, we use Ref type.
     */
    const REF = "ref";

    /**
     * If attribute presents in same table,
     * we use Self type.
     */
    const SELF = "self";

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