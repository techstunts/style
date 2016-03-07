<?php
namespace App\Report\Entities\Attributes;

use App\Report\Entities\Attributes\Contracts\ReferenceAttributeContract;

/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 7:32 PM
 */
class ReferenceAttribute extends ReferenceAttributeContract
{

    /**
     * ReferenceAttribute constructor.
     * @param AttributeFieldType $fieldType
     * @param $showInReport
     * @param $displayName
     * @param $tableId
     * @param $tableName
     * @param $parentTableId
     */
    public function __construct($fieldType, $showInReport, $displayName, $tableId, $tableName, $parentTableId) {
        parent::__construct($fieldType, $showInReport, $displayName, $tableId, $tableName, $parentTableId);
    }
}