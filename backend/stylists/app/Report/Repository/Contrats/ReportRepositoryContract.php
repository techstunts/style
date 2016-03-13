<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 12/03/16
 * Time: 6:02 PM
 */

namespace App\Report\Repository\Contrats;


interface ReportRepositoryContract {

    public function getFilterValues($table, $columnId, $columName);
}