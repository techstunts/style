<?php

/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 6:20 PM
 */
namespace App\Report\Entities\Attributes;

use App\Report\Constants\ReportConstant;
use App\Report\Entities\Enums\AttributeFieldType;
use App\Report\Entities\Enums\AttributeType;
use App\Report\Entities\Enums\FilterType;
use App\Report\Exceptions\AttributeException;
use App\Report\Entities\Attributes\Contracts\Attribute;

class ReferenceAttribute extends Attribute {

    private $idColumn;
    private $nameColumn;
    private $tableName;
    private $parentTableIdColumn;


    /**
     * ReferenceAttributeContract constructor.
     * @param $filterType
     * @param $showInReport
     * @param $displayName
     * @param $idColumn
     * @param $nameColumn
     * @param $tableName
     * @param $parentTableIdColumn
     */
    public function __construct($filterType, $showInReport, $displayName, $idColumn, $nameColumn, $tableName, $parentTableIdColumn){
        parent::__construct(AttributeType::REF, $filterType, $showInReport, $displayName);
        $this->validateReferenceAttribute($filterType, $idColumn, $nameColumn, $tableName, $parentTableIdColumn);
        $this->idColumn = $idColumn;
        $this->nameColumn = $nameColumn;
        $this->tableName = $tableName;
        $this->parentTableIdColumn = $parentTableIdColumn;
    }


    protected function validateReferenceAttribute($filterType, $idColumn, $nameColumn, $tableName, $parentTableColumnId){
        if(empty($idColumn) || !is_string($idColumn)) throw new AttributeException("Attribute \"".ReportConstant::ID_COLUMN."\" must not empty.");
        if(empty($tableName) || !is_string($tableName)) throw new AttributeException("Attribute \"".ReportConstant::TABLE_NAME."\" must not empty.");
        if(empty($parentTableColumnId) || !is_string($parentTableColumnId)) throw new AttributeException("Attribute \"".ReportConstant::PARENT_TABLE_ID_COLUMN."\" must not empty.");
        $this->isNameColumnRequired($filterType, $nameColumn);
        return true;
    }

    private function isNameColumnRequired($filterType, $nameColumn){
        if(FilterType::isFilterWithMultiValue($filterType) && (empty($nameColumn) || !is_string($nameColumn)))
            throw new AttributeException("Attribute \"".ReportConstant::NAME_COLUMN."\" must not empty, if filter type is [.".implode(",", FilterType::getMultiValueFilters())."]");
        return true;
    }

    public function getGroupByColumn() {
        return $this->parentTableIdColumn;
    }


    /**
     * @return mixed
     */
    public function getIdColumn() {
        return $this->idColumn;
    }

    /**
     * @return mixed
     */
    public function getNameColumn() {
        return $this->nameColumn;
    }

    /**
     * @return mixed
     */
    public function getTableName() {
        return $this->tableName;
    }

    /**
     * @return mixed
     */
    public function getParentTableIdColumn() {
        return $this->parentTableIdColumn;
    }



}