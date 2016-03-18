<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 17/03/16
 * Time: 10:44 PM
 */

namespace App\Report\Builders\Query;

use App\Report\Entities\Attributes\ReferenceAttribute;
use App\Report\Entities\ReportEntity;
use App\Report\Entities\Enums\AttributeType;
use App\Report\Entities\Enums\FilterType;
use Carbon\Carbon;


/**
 *
 * -----------------------------------------
 * @todo, do refactor for attribute type
 * ------------------------------------------
 *
 * Class Filter
 * @package App\Report\Builders\Query
 *
 */
class Filter {
    const DB_DATE_FORMAT = "Y-m-d";
    const USER_INPUT_DATE_FORMAT = "d M Y";



    public function build(ReportEntity $reportEntity, $table, $filterValues){

        $attributes = $reportEntity->getAttributes();
        if(empty($attributes) || empty($filterValues) || !is_array($attributes) && !is_array($filterValues)) return;
        foreach($filterValues as $param => $values){
            if(is_array($values)) $values = array_filter($values);
            if(empty($attributes[$param]) || empty($values)) continue;
            switch($attributes[$param]->getType()){
                case AttributeType::REF: $this->buildReferenceUserClause($attributes[$param], $table, $values); break;
                case AttributeType::NON_REF: $this->buildSelfUserClause($attributes[$param], $table, $attributes[$param] ->getIdColumn(), $values); break;
            }
        }
    }

    private function buildReferenceUserClause(ReferenceAttribute $referenceAttribute, $table, $values){
        switch($referenceAttribute->getFilterType()){
            case FilterType::MULTI_SELECT: $this->buildMultiSelectClause($table, $referenceAttribute->getTableName(), $referenceAttribute->getParentTableIdColumn(), $values); break;
            case FilterType::SINGLE_SELECT: $this->buildSingleSelectClause($table, $referenceAttribute->getTableName(), $referenceAttribute->getParentTableIdColumn(), $values); break;
            case FilterType::DATE_RANGE: $this->buildDateRangeClause($table, $referenceAttribute->getTableName(), $referenceAttribute->getParentTableIdColumn(), $values); break;;
        }
    }

    public function buildMultiSelectClause($table, $conditionTable, $column, $value){
        $table->whereIn($column, $value);
    }

    public function buildSingleSelectClause($table, $conditionTable, $column, $value){
        //$table->where($conditionTable, $column, $value);
    }

    public function buildDateRangeClause($table, $idColumn, $value){

        $fromDate = Carbon::createFromFormat(self::USER_INPUT_DATE_FORMAT,  $value["from_date"])->format(self::DB_DATE_FORMAT);
        $toDate = Carbon::createFromFormat(self::USER_INPUT_DATE_FORMAT,  $value["to_date"])->format(self::DB_DATE_FORMAT);
        $table->whereBetween($idColumn, array($fromDate, $toDate));
    }


    //@todo fix this.
    private function buildSelfUserClause($referenceAttribute, $table, $idColumn, $values){
        switch($referenceAttribute->getFilterType()){
            case FilterType::MULTI_SELECT: $this->buildMultiSelectClause($table, $referenceAttribute->getTableName(), $referenceAttribute->getParentTableIdColumn(), $values); break;
            case FilterType::SINGLE_SELECT: $this->buildSingleSelectClause($table, $referenceAttribute->getTableName(), $referenceAttribute->getParentTableIdColumn(), $values); break;
            case FilterType::DATE_RANGE: $this->buildDateRangeClause($table,  $idColumn, $values); break;
        }
    }
}