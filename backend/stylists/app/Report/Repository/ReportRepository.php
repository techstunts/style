<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 12/03/16
 * Time: 6:00 PM
 */

namespace App\Report\Repository;
use App\Report\Entities\ReportEntity;
use App\Report\Repository\Contrats\ReportRepositoryContract;
use App\Report\Utils\ReportUtils;
use DB;
use App\Report\Builders\QueryBuilder;

class ReportRepository implements ReportRepositoryContract{

    private $queryBuilder;
    const SHOW_ONLY_ATTRIBUTES = "show-only-attributes";

    /**
     * ReportRepository constructor.
     * @param $queryBuilder
     */
    public function __construct(QueryBuilder $queryBuilder) {
        $this->queryBuilder = $queryBuilder;
    }

    public function getFilterValues($table, $columnId, $columName) {
        return DB::table($table)->select($columnId, $columName)->get();
    }

    public function getReportData(ReportEntity $reportEntity, $userInput) {
        //$this->__debugQuery();
        $table = DB::table($reportEntity->getTable());
        $this->queryBuilder->build($reportEntity, $table, $userInput);
        return $this->getGroupData($reportEntity, $table, $userInput);
    }

    private function getGroupData(ReportEntity $reportEntity, $table, $userInput){
        $groupValues = array();
        //@todo add check to attribute type on show in repot
        foreach($reportEntity->getAttributes() as $attributeKey => $attribute){
            if(!$this->isShowAttributeInReport($attributeKey, $attribute, $userInput)) continue;
            $tmpTable = clone $table;
            $groupByColumn =  $attribute->getParentTableIdColumn();
            $groupValues[$attributeKey] = $tmpTable->select(DB::raw("count(*) as total_count, $groupByColumn as attribute_id"))->groupBy($groupByColumn)->get();
            unset($tmpTable);
        }
        return $groupValues;
    }

    private function isShowAttributeInReport($attributeKey, $attribute, $userInput){
        $showOnlyAttribute = ReportUtils::getValueFromArray($userInput, self::SHOW_ONLY_ATTRIBUTES);
        if(!$attribute->getShowInReport()) return false;
        if(!empty($showOnlyAttribute) && $attributeKey != $showOnlyAttribute) return false;
        return true;
    }

    /**
     *
     * Call when you want to debug db queries
     *
     */
    private function __debugQuery(){

        DB::listen(function($query, $bindings, $time) {
            foreach ($bindings as $i => $binding) {
                if ($binding instanceof \DateTime) {
                    $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                } else if (is_string($binding)) {
                    $bindings[$i] = "'$binding'";//`enter code here`
                }
            }

            // Insert bindings into query
            $query = str_replace(array('%', '?'), array('%%', '%s'), $query);
            $query = vsprintf($query, $bindings);

            // Debug SQL queries
            echo '<br /><br/>SQL: [' . $query . ']';

        });

    }
}