<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 17/03/16
 * Time: 10:44 PM
 */

namespace App\Report\Builders\Query;

use App\Report\Constants\ReportConstant;
use App\Report\Entities\Attributes\NonReferenceAttribute;
use App\Report\Entities\Attributes\ReferenceAttribute;
use App\Report\Entities\ReportEntity;
use App\Report\Entities\Enums\AttributeType;
use App\Report\Entities\Enums\FilterType;
use App\Report\Utils\ReportUtils;
use Carbon\Carbon;

/**
 * Class Filter
 * @package App\Report\Builders\Query
 */
class Filter {

    const DB_DATE_FORMAT = "Y-m-d";
    const USER_INPUT_DATE_FORMAT = "d M Y";

    public function build(ReportEntity $reportEntity, $table, $userInput){
        $filterValues = ReportUtils::getValueFromArray($userInput, ReportConstant::ATTRIBUTES);
        $attributes = $reportEntity->getAttributes();
        if($this->isEmptyFilter($attributes, $filterValues)) return;
        foreach($filterValues as $param => $values){
            $values = is_array($values)? array_filter($values): $values;
            if(empty($attributes[$param]) || ReportUtils::isEmpty($values)) continue;
            switch($attributes[$param]->getType()){
                case AttributeType::REF: $this->buildReferenceFilter($attributes[$param], $table, $values); break;
                case AttributeType::NON_REF: $this->buildNonReferenceFilter($attributes[$param], $table, $values); break;
            }
        }
    }

    private function buildReferenceFilter(ReferenceAttribute $referenceAttribute, $table, $values){
        switch($referenceAttribute->getFilterType()){
            case FilterType::MULTI_SELECT: $this->buildMultiSelectClauseForRefAttribute($table, $referenceAttribute->getTableName(), $referenceAttribute->getIdColumn(), $referenceAttribute->getParentTableIdColumn(), $values); break;
            case FilterType::SINGLE_SELECT: $this->buildSingleSelectClauseForRefAttribute($table, $referenceAttribute->getTableName(), $referenceAttribute->getIdColumn(), $referenceAttribute->getParentTableIdColumn(), $values); break;
            case FilterType::DATE_RANGE: $this->buildDateRangeClauseForRefAttribute($table, $referenceAttribute->getTableName(), $referenceAttribute->getIdColumn(), $referenceAttribute->getParentTableIdColumn(), $values); break;;
        }
    }

    private function buildNonReferenceFilter(NonReferenceAttribute $attribute, $table, $values){
        switch($attribute->getFilterType()){
            case FilterType::MULTI_SELECT: $this->buildMultiSelectClauseForNonRefAttribute($table,  $attribute->getIdColumn(), $values); break;
            case FilterType::SINGLE_SELECT: $this->buildSingleSelectClauseForNonRefAttribute($table,  $attribute->getIdColumn(), $values); break;
            case FilterType::DATE_RANGE: $this->buildDateRangeClauseForNonRefAttribute($table,  $attribute->getIdColumn(), $values); break;
        }
    }

    private function buildMultiSelectClauseForRefAttribute($table, $conditionTable, $idColumn, $parentTableIdColumn, $values){
        $table->whereIn($parentTableIdColumn, $values);
    }

    private function buildMultiSelectClauseForNonRefAttribute($table, $idColumn, $value){
        $table->whereIn($idColumn, $value);
    }

    private function buildSingleSelectClauseForRefAttribute($table, $conditionTable, $idColumn, $parentTableIdColumn, $values){
        $table->where($parentTableIdColumn, $values);
    }

    private function buildSingleSelectClauseForNonRefAttribute($table, $idColumn, $value){
        $table->where($idColumn, $value);
    }

    private function buildDateRangeClauseForRefAttribute($table, $conditionTable, $idColumn, $parentTableIdColumn, $values){
        $fromDate = Carbon::createFromFormat(self::USER_INPUT_DATE_FORMAT,  $values[ReportConstant::FROM_DATE])->format(self::DB_DATE_FORMAT);
        $toDate = Carbon::createFromFormat(self::USER_INPUT_DATE_FORMAT,  $values[ReportConstant::TO_DATE])->format(self::DB_DATE_FORMAT);
        $table->whereBetween($parentTableIdColumn, array($fromDate, $toDate));
    }

    private function buildDateRangeClauseForNonRefAttribute($table, $idColumn, $values){
        $fromDate = Carbon::createFromFormat(self::USER_INPUT_DATE_FORMAT,  $values[ReportConstant::FROM_DATE])->format(self::DB_DATE_FORMAT);
        $toDate = Carbon::createFromFormat(self::USER_INPUT_DATE_FORMAT,  $values[ReportConstant::TO_DATE])->format(self::DB_DATE_FORMAT);
        $table->whereBetween($idColumn, array($fromDate, $toDate));
    }

    private function isEmptyFilter($attributes, $filterValues){
        return (empty($attributes) || empty($filterValues) ||
                !is_array($attributes) && !is_array($filterValues));
    }
}