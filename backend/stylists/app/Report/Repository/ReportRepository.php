<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 12/03/16
 * Time: 6:00 PM
 */

namespace App\Report\Repository;

use App\Report\Entities\Enums\AttributeType;
use App\Report\Entities\Enums\JoinType;
use App\Report\Entities\ReportEntity;
use App\Report\Repository\Contrats\ReportRepositoryContract;
use DB;
use App\Report\Entities\Conditions\Condition;
use App\Report\Entities\Relationships\Relationship;
use App\Report\Entities\Attributes\Contracts\ReferenceAttributeContract;
use App\Report\Entities\Enums\FilterType;
use App\Report\Entities\Enums\WhereType;

/**
 *
 *
 * -------------------------------------------------------------------------------
 *
 *
 *              REFACTOR THIS CLASS ASAP
 *
 *
 * -------------------------------------------------------------------------------
 *
 */

class ReportRepository implements ReportRepositoryContract{

    public function getFilterValues($table, $columnId, $columName) {
        return DB::table($table)->select($columnId, $columName)->get();
    }

    public function getReportData(ReportEntity $reportEntity, $inputParam) {
        $table = DB::table($reportEntity->getTable());

        echo "<pre>";
        DB::listen(function($sql, $bindings, $time) {
            var_dump($sql);
            var_dump($bindings);
            var_dump($time);
        });


        $this->buildRelationship($reportEntity, $table);
        $this->buildCondition($reportEntity, $table);
        $this->buildUserClause($reportEntity->getAttributes(), $table, $inputParam);

        $data = $this->getGroupData($reportEntity, $table);


        dd($data);
    }

    private function getGroupData(ReportEntity $reportEntity, $table){
        $groupValues = array();
        foreach($reportEntity->getAttributes() as $attributeKey => $attribute){

            //@todo, move this attribute to Attribute
            if(!$attribute->getShowInReport()) continue;
            $tmpTable = clone $table;
            $groupByColumn =  $attribute->getParentTableColumnId();
            $groupValues[$attributeKey] = $tmpTable->select(DB::raw("count(*) as count_$groupByColumn, $groupByColumn"))->groupBy($attribute->getParentTableColumnId())->get();
            unset($tmpTable);
        }

        return $groupValues;
    }

    private function buildRelationship(ReportEntity $reportEntity, $table){
        if(empty($reportEntity->getRelationships())) return;

        foreach($reportEntity->getRelationships() as $relationship){
            switch($relationship->getJoinType()){
                case JoinType::LEFT_JOIN: $this->buildLeftJoin($relationship, $table); break;
            }
        }
    }

    private function buildUserClause($attributes, $table, $inputParams){
        if(empty($attributes) || empty($inputParams) || !is_array($attributes) && !is_array($inputParams)) return;
        foreach($inputParams as $param => $values){
            if(is_array($values)) $values = array_filter($values);
            if(empty($attributes[$param]) || empty($values)) continue;
            switch($attributes[$param]->getType()){
                case AttributeType::REF: $this->buildReferenceUserClause($attributes[$param], $table, $values); break;
                case AttributeType::SELF: $this->buildSelfUserClause($attributes[$param], $table, $values); break;
            }
        }
    }

    private function buildReferenceUserClause(ReferenceAttributeContract $referenceAttribute, $table, $values){
        switch($referenceAttribute->getFilterType()){
            case FilterType::MULTI_SELECT: $this->buildMultiSelectClause($table, $referenceAttribute->getTableName(), $referenceAttribute->getParentTableColumnId(), $values); break;
            case FilterType::SINGLE_SELECT: $this->buildSingleSelectClause($table, $referenceAttribute->getTableName(), $referenceAttribute->getParentTableColumnId(), $values); break;
            case FilterType::DATE_RANGE: break;
        }
    }

    public function buildMultiSelectClause($table, $conditionTable, $column, $value){
         $table->whereIn($column, $value);
    }

    public function buildSingleSelectClause($table, $conditionTable, $column, $value){
        //$table->where($conditionTable, $column, $value);
    }

    public function buildDateRangeClause(){

    }


    private function buildSelfUserClause($selfAttribute, $table, $value){

    }


    private function buildLeftJoin(Relationship $relationship, $table){
        $table->leftJoin($relationship->getTable(), $relationship->getJoinClause()->getLeft(), $relationship->getJoinClause()->getOperator(), $relationship->getJoinClause()->getRight());
    }

    //@todo move all these method to proper class
    private function buildCondition(ReportEntity $reportEntity, $table){
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



    //@todo move all these method to proper class
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
        $table->whereNotNull($condition->getColumn(), $condition->getValue());
    }

    private function buildWhereNull($table, Condition $condition){
        $table->whereNull($condition->getColumn(), $condition->getValue());
    }
}