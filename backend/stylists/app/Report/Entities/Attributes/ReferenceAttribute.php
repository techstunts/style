<?php
namespace App\Report\Entities\Attributes;

use App\Report\Entities\Attributes\Contracts\ReferenceAttributeContract;

/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 7:32 PM
 */
class ReferenceAttribute extends ReferenceAttributeContract {


    /**
     * ReferenceAttribute constructor.
     * @param $filterType
     * @param $showInReport
     * @param $displayName
     * @param $columnId
     * @param $columnName
     * @param $tableName
     * @param $parentTableColumnId
     */
    public function __construct($filterType, $showInReport, $displayName, $columnId, $columnName, $tableName, $parentTableColumnId) {
        parent::__construct($filterType, $showInReport, $displayName, $columnId, $columnName, $tableName, $parentTableColumnId);
    }
}