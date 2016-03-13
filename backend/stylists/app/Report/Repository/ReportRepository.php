<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 12/03/16
 * Time: 6:00 PM
 */

namespace App\Report\Repository;

use App\Report\Repository\Contrats\ReportRepositoryContract;
use DB;

class ReportRepository implements ReportRepositoryContract{


    public function getFilterValues($table, $columnId, $columName) {
        return DB::table($table)->select($columnId, $columName)->get();
    }

}