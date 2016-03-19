<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 12/03/16
 * Time: 6:00 PM
 */

namespace App\Report\Repository;
use App\Report\Constants\ReportConstant;
use App\Report\Entities\ReportEntity;
use App\Report\Repository\Contrats\ReportRepositoryContract;
use App\Report\Utils\ReportUtils;
use DB;
use App\Report\Builders\QueryBuilder;
use Illuminate\Support\Facades\Config;

class ReportRepository implements ReportRepositoryContract {

    private $queryBuilder;
    private $enableQueryLogger;

    /**
     * ReportRepository constructor.
     * @param $queryBuilder
     */
    public function __construct(QueryBuilder $queryBuilder) {
        $this->enableQueryLogger =  Config::get('report.enableQueryLogger');
        $this->queryBuilder = $queryBuilder;
    }

    public function getFilterValues($table, $columnId, $columName) {
        if(empty($columName)) return DB::table($table)->select($columnId)->distinct()->get();
        else return DB::table($table)->select($columnId, $columName)->get();
    }

    public function getReportData(ReportEntity $reportEntity, $userInput) {
        $table = DB::table($reportEntity->getTable());
        $this->queryBuilder->build($reportEntity, $table, $userInput);
        return $this->getGroupData($reportEntity, $table, $userInput);
    }

    private function getGroupData(ReportEntity $reportEntity, $table, $userInput) {
        $groupValues = array();
        foreach ($reportEntity->getAttributes() as $attributeKey => $attribute) {
            if (!$this->isShowAttributeInReport($attributeKey, $attribute, $userInput)) continue;
            $tmpTable = clone $table;
            $groupByColumn = $attribute->getGroupByColumn();
            $query = $tmpTable->select(DB::raw("count(*) as ".ReportConstant::TOTAL_COUNT.", $groupByColumn as ".ReportConstant::ATTRIBUTE_ID))->groupBy($groupByColumn);
            $groupValues[ReportConstant::DATA][$attributeKey] = $query->get();
            $groupValues[ReportConstant::QUERY][$attributeKey] = $this->queryLogger($query->toSql(), $query->getBindings());
            unset($tmpTable);
        }
        return $groupValues;
    }

    private function isShowAttributeInReport($attributeKey, $attribute, $userInput) {
        $showOnlyAttribute = ReportUtils::getValueFromArray($userInput, ReportConstant::SHOW_ONLY_ATTRIBUTES);
        if (!$attribute->getShowInReport()) return false;
        if (!empty($showOnlyAttribute) && $attributeKey != $showOnlyAttribute) return false;
        return true;
    }

    private function queryLogger($query, $bindings) {
        if(!$this->enableQueryLogger) return null;
        if (!empty($bindings) && is_array($bindings)){
            foreach ($bindings as $i => $binding) {
                if ($binding instanceof \DateTime) {
                    $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                } else if (is_string($binding)) {
                    $bindings[$i] = "'$binding'";
                }
            }
            $query = str_replace(array('%', '?'), array('%%', '%s'), $query);
            $query = vsprintf($query, $bindings);
        }
        return $query;
    }

}

