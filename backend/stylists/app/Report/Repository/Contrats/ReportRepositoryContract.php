<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 12/03/16
 * Time: 6:02 PM
 */

namespace App\Report\Repository\Contrats;


use App\Report\Entities\ReportEntity;

interface ReportRepositoryContract {

    public function getFilterValues($table, $columnId, $columName);

    public function getReportData(ReportEntity $reportEntity, $inputParam);
}