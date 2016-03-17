<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 17/03/16
 * Time: 10:44 PM
 */

namespace App\Report\Builders\Query;

use App\Report\Entities\ReportEntity;
use App\Report\Entities\Enums\AttributeType;
use App\Report\Entities\Attributes\Contracts\ReferenceAttributeContract;
use App\Report\Entities\Enums\FilterType;
class Filter {

    public function build(ReportEntity $reportEntity, $table, $filterValues){
        $attributes = $reportEntity->getAttributes();

        if(empty($attributes) || empty($filterValues) || !is_array($attributes) && !is_array($filterValues)) return;

        foreach($filterValues as $param => $values){
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
}