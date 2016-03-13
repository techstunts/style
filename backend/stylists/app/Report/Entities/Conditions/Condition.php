<?php

/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 13/03/16
 * Time: 2:07 PM
 */

namespace App\Report\Entities\Conditions;
use App\Report\Entities\Enums\WhereType;
use App\Report\Exceptions\ConditionException;
use App\Report\Entities\Enums\ComparisonOperators;

class Condition {

    const WHERE_TYPE = "where_type";
    const COLUMN = "column";
    const VALUE = "value";
    const OPERATOR = "operator";

    private $whereType;
    private $column;
    private $value;
    private $operator;

    /**
     * Condition constructor.
     * @param $conditionType
     * @param $column
     * @param $value
     * @param $operator
     */
    public function __construct($whereType, $column, $value, $operator) {
        $this->validator($whereType, $column, $value, $operator);
        $this->whereType = $whereType;
        $this->column = $column;
        $this->value = $value;
        $this->operator = $operator;
    }

    private function validator($whereType, $column, $value, $operator){
        if(empty($column) || !is_string($column)) throw new ConditionException("Condition \"".self::COLUMN."\" must not empty.");
        $this->validateCondition($whereType, $column, $value, $operator);
        return true;

    }

    private function validateCondition($whereType, $column, $value, $operator){
        if(!empty($whereType)){
            if(!WhereType::isValidValue($whereType)) throw new ConditionException("Condition \"".self::WHERE_TYPE."\" is not valid, for \"$column\".");

            switch($whereType){
                case WhereType::OR_WHERE: return $this->validateOrWhere($column, $value, $operator);
                case WhereType::WHERE_BETWEEN: return $this->validateWhereBetween($column, $value, $operator);
                case WhereType::WHERE_NOT_BETWEEN: return $this->validateWhereNotBetween($column, $value, $operator);
                case WhereType::WHERE_IN:  return $this->validateWhereIn($column, $value, $operator);
                case WhereType::WHERE_NOT_IN: return $this->validateWhereNotIn($column, $value, $operator);
                case WhereType::WHERE_NOT_NULL: return $this->validateWhereNotNull($column, $value, $operator);
                case WhereType::WHERE_NULL: return $this->validateWhereNull($column, $value, $operator);
                default : throw new ConditionException("Condition \"".self::WHERE_TYPE."\" is not valid, for column \"$column\".");
            }
        }
        return $this->validateAndWhere($column, $value, $operator);
    }

    private function validateAndWhere($column, $value, $operator){
        if(!isset($value) || strlen($value) === 0 || !is_string($value)) throw new ConditionException("Condition \"".self::VALUE."\" must not empty.");
        if(!empty($operator) && !ComparisonOperators::isValidValue($operator)) throw new ConditionException("Condition \"".self::OPERATOR."\" is not valid, for column \"$column\".");
        return true;
    }

    private function validateOrWhere($column, $value, $operator){
        if(!isset($value) || strlen($value) === 0 || !is_string($value)) throw new ConditionException("Condition \"".self::VALUE."\" must not empty.");
        if(!empty($operator) && !ComparisonOperators::isValidValue($operator)) throw new ConditionException("Condition \"".self::OPERATOR."\" is not valid, for column \"$column\".");
        return true;
    }

    private function validateWhereBetween($column, $value, $operator){
        if(!isset($value) || !is_array($value)) throw new ConditionException("Condition \"".self::VALUE."\" must be not empty and array only, for column \"$column\".");
        if(!empty($operator)) throw new ConditionException("Condition \"".self::OPERATOR."\" operator should be empty, for column \"$column\".");
        return true;
    }

    private function validateWhereNotBetween($column, $value, $operator){
        if(!isset($value) ||  !is_array($value)) throw new ConditionException("Condition \"".self::VALUE."\" must be not empty and array only, for column \"$column\".");
        if(!empty($operator)) throw new ConditionException("Condition \"".self::OPERATOR."\" operator should be empty, for column \"$column\".");
        return true;
    }

    private function validateWhereIn($column, $value, $operator){
        if(!isset($value) ||  !is_array($value)) throw new ConditionException("Condition \"".self::VALUE."\" must be not empty and array only, for column \"$column\".");
        if(!empty($operator)) throw new ConditionException("Condition \"".self::OPERATOR."\" operator should be empty, for column \"$column\".");
        return true;
    }
    private function validateWhereNotIn($column, $value, $operator){
        if(!isset($value) ||  !is_array($value)) throw new ConditionException("Condition \"".self::VALUE."\" must be not empty and array only, for column \"$column\".");
        if(!empty($operator)) throw new ConditionException("Condition \"".self::OPERATOR."\" operator should be empty, for column \"$column\".");
        return true;
    }
    private function validateWhereNotNull($column, $value, $operator){
        if(!empty($value)) throw new ConditionException("Condition \"".self::VALUE."\" value should be empty, for column \"$column\".");
        if(!empty($operator)) throw new ConditionException("Condition \"".self::OPERATOR."\" operator should be empty, for column \"$column\".");
        return true;
    }
    private function validateWhereNull($column, $value, $operator){
        if(!empty($value)) throw new ConditionException("Condition \"".self::VALUE."\" value should be empty, for column \"$column\".");
        if(!empty($operator)) throw new ConditionException("Condition \"".self::OPERATOR."\" operator should be empty, for column \"$column\".");
        return true;
    }

    /**
     * @return mixed|null
     */
    public function getWhereType() {
        return $this->whereType;
    }

    /**
     * @return mixed
     */
    public function getColumn() {
        return $this->column;
    }

    /**
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @return mixed|null
     */
    public function getOperator() {
        return $this->operator;
    }

}