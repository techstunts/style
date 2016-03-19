<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 13/03/16
 * Time: 4:27 PM
 */

namespace App\Report\Filters;


use App\Report\Filters\Contracts\FilterConstract;

class SingleSelect extends FilterConstract{

    public function buildQueryForRefAttribute($tableBuilder, $conditionTable, $idColumn, $parentTableIdColumn, $filterValues) {
        $tableBuilder->where($parentTableIdColumn, $filterValues);
    }

    public function buildQueryForNonRefAttribute($tableBuilder, $idColumn, $filterValue) {
        $tableBuilder->where($idColumn, $filterValue);
    }

}
