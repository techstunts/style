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
use DB;
use App\Report\Builders\QueryBuilder;

class ReportRepository implements ReportRepositoryContract{

    private $queryBuilder;

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

    public function getReportData(ReportEntity $reportEntity, $filterValues) {
        $table = DB::table($reportEntity->getTable());
        $this->queryBuilder->build($reportEntity, $table, $filterValues);
        return $this->getGroupData($reportEntity, $table);
    }

    private function getGroupData(ReportEntity $reportEntity, $table){
        $groupValues = array();
        foreach($reportEntity->getAttributes() as $attributeKey => $attribute){
            //@todo, move this attribute to Attribute
            if(!$attribute->getShowInReport()) continue;
            $tmpTable = clone $table;
            $groupByColumn =  $attribute->getParentTableColumnId();
            $groupValues[$attributeKey] = $tmpTable->select(DB::raw("count(*) as total_count, $groupByColumn as attribute_id"))->groupBy($attribute->getParentTableColumnId())->get();
            unset($tmpTable);
        }

        return $groupValues;
    }


    /**
     *
     * Call when you want to debug db queries
     *
     */
    private function __debugQuery(){
        DB::listen(function($sql, $bindings, $time) {
            var_dump($sql);
            var_dump($bindings);
            var_dump($time);
        });
    }
}