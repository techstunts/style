<?php

/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 6:20 PM
 */
namespace App\Report\Entities\Attributes\Contracts;

use App\Report\Entities\Enums\AttributeFieldType;
use App\Report\Entities\Enums\AttributeType;
use App\Report\Entities\Attributes\ContractsAttribute;
use App\Report\Exceptions\AttributeException;

abstract class ReferenceAttributeContract extends Attribute{

    const FIELD_TYPE = "field_type";
    const SHOW_IN_REPORT = "show_in_report";
    const DISPLAY_NAME = "display_name";
    const TABLE_ID = "table_id";
    const TABLE_NAME = "table_name";
    const PARENT_TABLE_ID = "parent_table_id";

    private $fieldType;
    private $showInReport;
    private $displayName;
    private $tableId;
    private $tableName;
    private $parentTableId;

    /**
     * ReferenceAttributeContract constructor.
     * @param $fieldType
     * @param $showInReport
     * @param $displayName
     * @param $tableId
     * @param $tableName
     * @param $parentTableId
     */
    public function __construct($fieldType, $showInReport, $displayName, $tableId, $tableName, $parentTableId){
        parent::__construct(AttributeType::REF);
        $this->validate($fieldType, $showInReport, $displayName, $tableId, $tableName, $parentTableId);
        $this->fieldType = AttributeFieldType::getType($fieldType);
        $this->showInReport = $showInReport;
        $this->displayName = $displayName;
        $this->tableId = $tableId;
        $this->tableName = $tableName;
        $this->parentTableId = $parentTableId;
    }


    protected function validate($fieldType, $showInReport, $displayName, $tableId, $tableName, $parentTableId){
        if(!AttributeFieldType::isValidValue($fieldType)) throw new AttributeException("Attribute \"field_type\" is not valid, value must in [" . AttributeFieldType::getAllowedValues() . "]");
        if(!is_bool($showInReport)) throw new AttributeException("Attribute \"show_in_report\" is not valid, value must boolean.");
        if(empty($displayName) || !is_string($displayName)) throw new AttributeException("Attribute \"display_name\" not not empty.");
        if(empty($tableId) || !is_string($tableId)) throw new AttributeException("Attribute \"table_id\" not not empty.");
        if(empty($tableName) || !is_string($tableName)) throw new AttributeException("Attribute \"table_name\" not not empty.");
        if(empty($parentTableId) || !is_string($parentTableId)) throw new AttributeException("Attribute \"parent_table_id\" not not empty.");
        return true;
    }

    public function __call ($method, $params) {
        $property = substr($method, 3);
        if (substr($method, 0, 3) == 'get')
            return $this->$property;
        else
            return null;
    }

}