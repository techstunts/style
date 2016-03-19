<?php

namespace App\Report\Entities\Relationships;


use App\Report\Constants\ReportConstant;
use App\Report\Entities\Enums\JoinType;
use App\Report\Exceptions\RelationshipException;

/**
 * Description of Relationship
 *
 * @author hrishikesh.mishra
 */
class Relationship {

    private $joinType;
    private $table; 
    private $joinClause; 
    
    public function __construct($joinType, $table, array $joinCondition) {
        $this->joinType = $joinType;
        $this->table = $table;
        $this->joinClause = new JoinClause($joinCondition[ReportConstant::LEFT], $joinCondition[ReportConstant::OPERATOR], $joinCondition[ReportConstant::RIGHT]) ;
    }
    
    public function validate($joinType, $table, array $joinCondition){
        if(!empty($joinType) || !is_string($joinType)) throw new RelationshipException("Relationship \"".ReportConstant::JOIN_TYPE."\" must not empty.");
        if(!JoinType::isValidValue($joinType)) throw new RelationshipException("Relationship \"".ReportConstant::JOIN_TYPE."\" is valid value");
        if(!empty($table) || !is_string($table)) throw new RelationshipException("Relationship \"".ReportConstant::TABLE."\" must not empty.");
        if(!empty($joinCondition) || !is_array($joinCondition)) throw new RelationshipException("Relationship \"".ReportConstant::JOIN_CLAUSE."\" must not empty.");
        return true;
    }

    /**
     * @return mixed
     */
    public function getJoinType() {
        return $this->joinType;
    }

    /**
     * @return mixed
     */
    public function getTable() {
        return $this->table;
    }

    /**
     * @return JoinClause
     */
    public function getJoinClause() {
        return $this->joinClause;
    }


}
