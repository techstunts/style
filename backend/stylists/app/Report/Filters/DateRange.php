<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 13/03/16
 * Time: 4:27 PM
 */

namespace App\Report\Filters;

use App\Report\Constants\ReportConstant;
use App\Report\Filters\Contracts\FilterConstract;
use Carbon\Carbon;

class DateRange extends FilterConstract {

    const DB_DATE_FORMAT = "Y-m-d";
    const USER_INPUT_DATE_FORMAT = "d M Y";

    public function buildQueryForRefAttribute($tableBuilder, $conditionTable, $idColumn, $parentTableIdColumn, $filterValues) {
        $fromDate = Carbon::createFromFormat(self::USER_INPUT_DATE_FORMAT,  $filterValues[ReportConstant::FROM_DATE])->format(self::DB_DATE_FORMAT);
        $toDate = Carbon::createFromFormat(self::USER_INPUT_DATE_FORMAT,  $filterValues[ReportConstant::TO_DATE])->format(self::DB_DATE_FORMAT);
        $tableBuilder->whereBetween($parentTableIdColumn, array($fromDate, $toDate));
    }

    public function buildQueryForNonRefAttribute($tableBuilder, $idColumn, $filterValues) {
        $fromDate = Carbon::createFromFormat(self::USER_INPUT_DATE_FORMAT,  $filterValues[ReportConstant::FROM_DATE])->format(self::DB_DATE_FORMAT);
        $toDate = Carbon::createFromFormat(self::USER_INPUT_DATE_FORMAT,  $filterValues[ReportConstant::TO_DATE])->format(self::DB_DATE_FORMAT);
        $tableBuilder->whereBetween($idColumn, array($fromDate, $toDate));
    }

}