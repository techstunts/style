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
        $queryBuilder = DB::table($reportEntity->getTable());
        $this->queryBuilder->build($reportEntity, $queryBuilder, $userInput);
        return $this->getGroupData($reportEntity, $queryBuilder, $userInput);
    }

    private function getGroupData(ReportEntity $reportEntity, $queryBuilder, $userInput) {
        $groupValues = array();
        foreach ($reportEntity->getAttributes() as $attributeKey => $attribute) {
            if (!$this->isShowAttributeInReport($attributeKey, $attribute, $userInput)) continue;
            $tmpQueryBuilder = clone $queryBuilder;
            $groupByColumn = $attribute->getGroupByColumn();
            $query = $tmpQueryBuilder->select(DB::raw("count(*) as ".ReportConstant::TOTAL_COUNT.", $groupByColumn as ".ReportConstant::ATTRIBUTE_ID))->groupBy($groupByColumn);
            $groupValues[ReportConstant::DATA][$attributeKey] = $query->get();
            $groupValues[ReportConstant::QUERY][$attributeKey] = $this->queryLogger($query);
            unset($tmpQueryBuilder);
        }
        return $groupValues;
    }

    private function isShowAttributeInReport($attributeKey, $attribute, $userInput) {
        $showOnlyAttribute = ReportUtils::getValueFromArray($userInput, ReportConstant::SHOW_ONLY_ATTRIBUTES);
        if (!$attribute->getShowInReport()) return false;
        if (!empty($showOnlyAttribute) && $attributeKey != $showOnlyAttribute) return false;
        return true;
    }

    private function queryLogger($queryBuilder) {
        if(!$this->enableQueryLogger) return null;

        $query = $queryBuilder->toSql();
        $bindings = $queryBuilder->getBindings();

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

