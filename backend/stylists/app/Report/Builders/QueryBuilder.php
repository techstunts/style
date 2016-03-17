<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 17/03/16
 * Time: 10:49 PM
 */

namespace App\Report\Builders;

use App\Report\Builders\Query\Relationship;
use App\Report\Builders\Query\Filter;
use App\Report\Builders\Query\WhereClause;
use App\Report\Entities\ReportEntity;

class QueryBuilder {

    private $relationship;
    private $filters;
    private $whereClause;

    public function __construct(Relationship $relationship, Filter $filter, WhereClause $whereClause){
        $this->relationship = $relationship;
        $this->filters = $filter;
        $this->whereClause = $whereClause;
    }

    public function build(ReportEntity $reportEntity, $table, $filterValues){
        $this->whereClause->build($reportEntity, $table);
        $this->relationship->build($reportEntity, $table);
        $this->filters->build($reportEntity, $table, $filterValues);
    }
}