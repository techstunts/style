<?php
///**
// * Created by IntelliJ IDEA.
// * User: hrishikesh.mishra
// * Date: 07/03/16
// * Time: 6:10 PM
// */
//
//namespace App\Report\Entities\Enums;
//use App\Report\Exceptions\InvalidEnumException;
//
//class AttributeFieldType {
//
//    /**
//     * User can select multi-value for this attribute.
//     * In UI, it will checkbox
//     * and in query, It will use "In" clause.
//     */
//    const MultiSelect = "multiselect";
//
//    /**
//     * User can select only one value through dropdown.
//     */
//    const SingleSelect = "singleselect";
//
//    /**
//     * User can enter date range.
//     */
//    const DateRange = "daterange";
//
//    private static $constants = array();
//
//    private static function getConstants(){
//        if (null == self::$constants) {
//            $class = new \ReflectionClass(get_called_class());
//            self::$constants = $class->getConstants();
//        }
//        return self::$constants;
//    }
//
//    public static function getAllowedValues(){
//        return array_values(self::getConstants());
//    }
//
//    public static function isValidType($fieldType){
//        return array_key_exists($fieldType, self::getConstants()) ? true : false;
//    }
//
//    public static function isValidValue($value){
//        return array_search($value, self::getConstants()) === false ? false :true;
//    }
//
//    public static function getType($type){
//        $key = array_search($type, self::getConstants());
//        if($key == false) throw new InvalidEnumException("Invalid enum : $type");
//        return $key;
//    }
//}