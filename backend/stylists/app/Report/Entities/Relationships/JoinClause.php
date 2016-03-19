<?php
/**
 *  Table Join Clause  
 *
 * @author hrishikesh.mishra
 */
namespace App\Report\Entities\Relationships;

use App\Report\Constants\ReportConstant;
use App\Report\Exceptions\JoinClauseException;
use App\Report\Entities\Enums\ComparisonOperators;

class JoinClause {

    private $left; 
    private $operator;     
    private $right; 
    
    function __construct($left, $operator, $right) {
        $this->validate($left, $operator, $right);
        $this->left = $left;
        $this->operator = $operator;
        $this->right = $right;
    }
    
    private function validate($left, $operator, $right) { 
        if(empty($left) || !is_string($left)) throw new JoinClauseException("JoinClause \"".ReportConstant::LEFT."\" must not empty.");
        if(empty($operator) || !is_string($operator)) throw new JoinClauseException("JoinClause \"".ReportConstant::OPERATOR."\"must not empty.");
        if(!ComparisonOperators::isValidValue($operator)) throw new JoinClauseException("JoinClause\"".ReportConstant::OPERATOR."\" is not valid.");
        if(empty($right) || !is_string($right)) throw new JoinClauseException("JoinClause \"".ReportConstant::RIGHT."\" must not empty.");
        return true; 
    }

    /**
     * @return mixed
     */
    public function getLeft() {
        return $this->left;
    }

    /**
     * @return mixed
     */
    public function getOperator() {
        return $this->operator;
    }

    /**
     * @return mixed
     */
    public function getRight() {
        return $this->right;
    }


}


