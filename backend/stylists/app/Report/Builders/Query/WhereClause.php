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

    public function build(ReportEntity $reportEntity, $queryBuilder){
        if(empty($reportEntity->getConditions())) return;

        foreach($reportEntity->getConditions() as $condition){
            switch($condition->getWhereType()){
                case WhereType::OR_WHERE: $this->buildOrWhereClause($queryBuilder, $condition); break;
                case WhereType::WHERE_BETWEEN: $this->buildWhereBetween($queryBuilder, $condition); break;
                case WhereType::WHERE_NOT_BETWEEN:  $this->buildWhereNotBetween($queryBuilder, $condition); break;
                case WhereType::WHERE_IN: $this->buildWhereIn($queryBuilder, $condition); break;
                case WhereType::WHERE_NOT_IN: $this->buildWhereNotIn($queryBuilder, $condition); break;
                case WhereType::WHERE_NOT_NULL: $this->buildWhereNotNull($queryBuilder, $condition); break;
                case WhereType::WHERE_NULL: $this->buildWhereNull($queryBuilder, $condition); break;
                default : $this->buildAndWhereClause($queryBuilder, $condition);
            }
        }
    }

    private function buildAndWhereClause($queryBuilder, Condition $condition){
        if(!empty($condition->getOperator()))
            $queryBuilder->where($condition->getColumn(), $condition->getOperator(), $condition->getValue());
        else
            $queryBuilder->where($condition->getColumn(), $condition->getValue());
    }

    private function buildOrWhereClause($queryBuilder, Condition $condition){
        if(!empty($condition->getOperator()))
            $queryBuilder->orWhere($condition->getColumn(), $condition->getOperator(), $condition->getValue());
        else
            $queryBuilder->orWhere($condition->getColumn(), $condition->getValue());
    }

    private function buildWhereBetween($queryBuilder, Condition $condition){
        $queryBuilder->whereBetween($condition->getColumn(), $condition->getValue());
    }

    private function buildWhereNotBetween($table, Condition $condition){
        $table->whereNotBetween($condition->getColumn(), $condition->getValue());
    }

    private function buildWhereIn($queryBuilder, Condition $condition){
        $queryBuilder->whereIn($condition->getColumn(), $condition->getValue());
    }

    private function buildWhereNotIn($queryBuilder, Condition $condition){
        $queryBuilder->whereNotIn($condition->getColumn(), $condition->getValue());
    }

    private function buildWhereNotNull($queryBuilder, Condition $condition){
        $queryBuilder->whereNotNull($condition->getColumn());
    }

    private function buildWhereNull($queryBuider, Condition $condition){
        $queryBuider->whereNull($condition->getColumn());
    }

}