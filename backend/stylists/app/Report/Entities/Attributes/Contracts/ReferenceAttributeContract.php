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
use App\Report\Entities\Enums\FilterType;
use App\Report\Exceptions\AttributeException;

abstract class ReferenceAttributeContract extends Attribute{

    const SHOW_IN_REPORT = "show_in_report";
    const DISPLAY_NAME = "display_name";
    const COLUMN_ID = "column_id";
    const COLUMN_NAME = "column_name";
    const TABLE_NAME = "table_name";
    const PARENT_TABLE_COLUMN_ID = "parent_table_column_id";

    private $showInReport;
    private $displayName;
    private $columnId;
    private $columnName;
    private $tableName;
    private $parentTableColumnId;


    /**
     * ReferenceAttributeContract constructor.
     * @param $filterType
     * @param $showInReport
     * @param $displayName
     * @param $columnId
     * @param $columnName
     * @param $tableName
     * @param $parentTableColumnId
     */
    public function __construct($filterType, $showInReport, $displayName, $columnId, $columnName, $tableName, $parentTableColumnId){
        parent::__construct(AttributeType::REF, $filterType);
        $this->validate($filterType, $showInReport, $displayName, $columnId, $columnName, $tableName, $parentTableColumnId);
        $this->showInReport = $showInReport;
        $this->displayName = $displayName;
        $this->columnId = $columnId;
        $this->columnName = $columnName;
        $this->tableName = $tableName;
        $this->parentTableColumnId = $parentTableColumnId;
    }


    protected function validate($filterType, $showInReport, $displayName, $columnId, $columnName, $tableName, $parentTableColumnId){
        if(!is_bool($showInReport)) throw new AttributeException("Attribute \"".self::SHOW_IN_REPORT."\" is not valid, value must boolean.");
        if(empty($displayName) || !is_string($displayName)) throw new AttributeException("Attribute \"".self::DISPLAY_NAME."\" must not empty.");
        if(empty($columnId) || !is_string($columnId)) throw new AttributeException("Attribute \"".self::COLUMN_ID."\" must not empty.");
        if(empty($tableName) || !is_string($tableName)) throw new AttributeException("Attribute \"".self::TABLE_NAME."\" must not empty.");
        if(empty($parentTableColumnId) || !is_string($parentTableColumnId)) throw new AttributeException("Attribute \"".self::PARENT_TABLE_COLUMN_ID."\" must not empty.");
        $this->isValidColumnName($filterType, $columnName);
        return true;
    }

    private function isValidColumnName($filterType, $columnName){
        if(FilterType::isFilterWithMultiValue($filterType) && (empty($columnName) || !is_string($columnName)))
            throw new AttributeException("Attribute \"".self::COLUMN_NAME."\" must not empty, if filter type is [.".$this->filterTypeForColumnName."]");
        return true;
    }

    /**
     * @return mixed
     */
    public function getShowInReport()
    {
        return $this->showInReport;
    }

    /**
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @return mixed
     */
    public function getColumnId()
    {
        return $this->columnId;
    }

    /**
     * @return mixed
     */
    public function getColumnName() {
        return $this->columnName;
    }


    /**
     * @return mixed
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @return mixed
     */
    public function getParentTableColumnId()
    {
        return $this->parentTableColumnId;
    }

}