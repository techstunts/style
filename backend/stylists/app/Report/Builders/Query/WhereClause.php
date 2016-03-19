<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 17/03/16
 * Time: 10:33 PM
 */

namespace App\Report\Builders\Query;

use App\Report\Entities\ReportEntity;
use App\Report\Entities\Enums\WhereType;
use App\Report\Entities\Conditions\Condition;

class WhereClause {

    public function build(ReportEntity $reportEntity, $table){
        if(empty($reportEntity->getConditions())) return;

        foreach($reportEntity->getConditions() as $condition){
            switch($condition->getWhereType()){
                case WhereType::OR_WHERE: $this->buildOrWhereClause($table, $condition); break;
                case WhereType::WHERE_BETWEEN: $this->buildWhereBetween($table, $condition); break;
                case WhereType::WHERE_NOT_BETWEEN:  $this->buildWhereNotBetween($table, $condition); break;
                case WhereType::WHERE_IN: $this->buildWhereIn($table, $condition); break;
                case WhereType::WHERE_NOT_IN: $this->buildWhereNotIn($table, $condition); break;
                case WhereType::WHERE_NOT_NULL: $this->buildWhereNotNull($table, $condition); break;
                case WhereType::WHERE_NULL: $this->buildWhereNull($table, $condition); break;
                default : $this->buildAndWhereClause($table, $condition);
            }
        }
    }

    private function buildAndWhereClause($table, Condition $condition){
        if(!empty($condition->getOperator()))
            $table->where($condition->getColumn(), $condition->getOperator(), $condition->getValue());
        else
            $table->where($condition->getColumn(), $condition->getValue());
    }

    private function buildOrWhereClause($table, Condition $condition){
        if(!empty($condition->getOperator()))
            $table->orWhere($condition->getColumn(), $condition->getOperator(), $condition->getValue());
        else
            $table->orWhere($condition->getColumn(), $condition->getValue());
    }

    private function buildWhereBetween($table, Condition $condition){
        $table->whereBetween($condition->getColumn(), $condition->getValue());
    }

    private function buildWhereNotBetween($table, Condition $condition){
        $table->whereNotBetween($condition->getColumn(), $condition->getValue());
    }

    private function buildWhereIn($table, Condition $condition){
        $table->whereIn($condition->getColumn(), $condition->getValue());
    }

    private function buildWhereNotIn($table, Condition $condition){
        $table->whereNotIn($condition->getColumn(), $condition->getValue());
    }

    private function buildWhereNotNull($table, Condition $condition){
        $table->whereNotNull($condition->getColumn());
    }

    private function buildWhereNull($table, Condition $condition){
        $table->whereNull($condition->getColumn());
    }

}