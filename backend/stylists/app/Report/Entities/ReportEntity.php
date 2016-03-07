<?php

/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 7:48 PM
 */
namespace App\Report\Entities;

use App\Report\Exceptions\ReportEntityException;
use App\Report\Entities\Attributes\Attribute;

class ReportEntity {

    const DISPLAY_NAME = "display_name";
    const TABLE_NAME = "table_name";
    const ATTRIBUTE = "attribute";

    private $displayName;
    private $table;
    private $attributes = array();

    /**
     * ReportEntity constructor.
     * @param $displayName
     * @param $table
     * @param array $attributes
     */
    public function __construct($displayName, $table, array $attributes) {
        $this->validate($displayName, $table, $attributes);
        $this->displayName = $displayName;
        $this->table = $table;
        $this->attributes = $attributes;
    }

    private function validate($displayName, $table, array $attributes) {
        if(empty($displayName) || !is_string($displayName)) throw new ReportEntityException("Report entity \"display_name\" must not be empty.");
        if(empty($table) || !is_string($table)) throw new ReportEntityException("Report entity \"table\" must not be empty.");
        if(empty($attributes) || !is_array($attributes)) throw new ReportEntityException("Report entity \"attributes\" must not be empty.");
        if(!$this->validateAttributes($attributes)) throw new ReportEntityException("Report entity \"attributes\" value is not valid.");
        return true;
    }

    private function validateAttributes(array $attributes){
        foreach($attributes as $attribute){
            if(! $attribute instanceof Attribute) return false;
        }
        return true;
    }

}