<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 17/03/16
 * Time: 10:39 PM
 */

namespace App\Report\Builders\Query;

use App\Report\Entities\ReportEntity;
use App\Report\Entities\Enums\JoinType;
use App\Report\Entities\Relationships\Relationship as RelationshipEntity;

class Relationship {

    public function build(ReportEntity $reportEntity, $queryBuilder){
        if(empty($reportEntity->getRelationships())) return;
        foreach($reportEntity->getRelationships() as $relationship){
            switch($relationship->getJoinType()){
                case JoinType::LEFT_JOIN: $this->buildLeftJoin($relationship, $queryBuilder); break;
            }
        }
    }

    private function buildLeftJoin(RelationshipEntity $relationship, $table){
        $table->leftJoin($relationship->getTable(),
                            $relationship->getJoinClause()->getLeft(),
                            $relationship->getJoinClause()->getOperator(),
                            $relationship->getJoinClause()->getRight());
    }

}