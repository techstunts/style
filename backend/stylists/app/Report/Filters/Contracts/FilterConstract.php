<?php

/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 19/03/16
 * Time: 9:08 PM
 */

namespace App\Report\Filters\Contracts;

use App\Report\Entities\Attributes\NonReferenceAttribute;
use App\Report\Entities\Attributes\ReferenceAttribute;
use App\Report\Repository\Contrats\ReportRepositoryContract;

abstract class FilterConstract {

    public abstract function buildQueryForRefAttribute($tableBuilder, $conditionTable, $idColumn, $parentTableIdColumn, $filterValues);

    public abstract function buildQueryForNonRefAttribute($tableBuilder, $idColumn, $filterValues);

    public function setReferenceFilterValues(ReportRepositoryContract $reportRepository, ReferenceAttribute $attribute){
        $filterValues = $reportRepository ->getFilterValues($attribute->getTableName(), $attribute->getIdColumn(), $attribute->getNameColumn());
        $sortedFilterValues = array();
        if(!empty($filterValues)) {
            foreach ($filterValues as $value) {
                $sortedFilterValues [$value->{$attribute->getIdColumn()}] = $value->{$attribute->getNameColumn()};
            }
        }
        $attribute->setFilterValues($sortedFilterValues);
    }

    public function setNonReferenceFilterValues(ReportRepositoryContract $reportRepository, NonReferenceAttribute $attribute, $tableName){
        $filterValues = $reportRepository->getFilterValues($tableName, $attribute->getIdColumn(), null);
        $sortedFilterValues = array();
        if(!empty($filterValues)) {
            foreach ($filterValues as $value) {
                $sortedFilterValues [$value->{$attribute->getIdColumn()}] = $value->{$attribute->getIdColumn()};
            }
        }
        $attribute->setFilterValues($sortedFilterValues);
    }
}