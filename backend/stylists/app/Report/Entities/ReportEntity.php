<?php

/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 7:48 PM
 */
namespace App\Report\Entities;

use App\Report\Exceptions\ReportEntityException;
use App\Report\Entities\Attributes\Contracts\Attribute;
use App\Report\Entities\Conditions\Condition;
use App\Report\Entities\Relationships\Relationship;

class ReportEntity {

    const DISPLAY_NAME = "display_name";
    const TABLE_NAME = "table_name";
    const RELATIONSHIPS = "relationships";    
    const CONDITIONS = "conditions";
    const ATTRIBUTES = "attributes";
        
    private $displayName;
    private $table;
    private $relationships = array();
    private $conditions = array();
    private $attributes = array();
       
    /**
     * ReportEntity constructor.
     * @param $displayName
     * @param $table
     * @param array $attributes
     */
    public function __construct($displayName, $table, array $attributes, array $relationships = null, array $conditions = null ) {
        $this->validate($displayName, $table, $attributes, $relationships, $conditions);
        $this->displayName = $displayName;
        $this->relationships = $relationships;
        $this->conditions = $conditions;
        $this->table = $table;
        $this->attributes = $attributes;
    }

    private function validate($displayName, $table, array $attributes, array $relationships = null, array $conditions = null) {
        if(empty($displayName) || !is_string($displayName)) throw new ReportEntityException("Report entity \"".self::DISPLAY_NAME."\" must not be empty.");
        if(empty($table) || !is_string($table)) throw new ReportEntityException("Report entity \"".self::TABLE_NAME."\" must not be empty.");
        if(empty($relationships) || !is_array($relationships)) throw new ReportEntityException("Report entity \"".self::RELATIONSHIPS."\" must not be empty.");
        if(!$this->validateRelationships($relationships)) throw new ReportEntityException("Report entity\"".self::RELATIONSHIPS."\" value is not valid.");
        if(empty($conditions) || !is_array($conditions)) throw new ReportEntityException("Report entity \"".self::CONDITIONS."\" must not be empty.");
        if(!$this->validateConditions($conditions)) throw new ReportEntityException("Report entity\"".self::CONDITIONS."\" value is not valid.");
        if(empty($attributes) || !is_array($attributes)) throw new ReportEntityException("Report entity \"".self::ATTRIBUTES."\" must not be empty.");
        if(!$this->validateAttributes($attributes)) throw new ReportEntityException("Report entity\"".self::ATTRIBUTES."\" value is not valid.");
        return true;
    }

    private function validateRelationships(array $relationships){
        foreach($relationships as $relationship){
            if(!$relationship instanceof Relationship) return false;
        }
        return true;
    }

    private function validateConditions(array $conditions){
        foreach($conditions as $attribute){
            if(! $attribute instanceof Condition) return false;
        }
        return true;
    }

    private function validateAttributes(array $attributes){     
        foreach($attributes as $attribute){                
            if(! $attribute instanceof Attribute) return false;
        }
        return true;
    }

    /**
     * @return mixed
     */
    public function getDisplayName() {
        return $this->displayName;
    }

    /**
     * @return mixed
     */
    public function getTable() {
        return $this->table;
    }

    /**
     * @return array
     */
    public function getRelationships() {
        return $this->relationships;
    }

    /**
     * @return array
     */
    public function getConditions() {
        return $this->conditions;
    }

    /**
     * @return array
     */
    public function getAttributes() {
        return $this->attributes;
    }


}