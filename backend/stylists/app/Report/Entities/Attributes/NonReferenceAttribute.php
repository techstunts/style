<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 18/03/16
 * Time: 11:54 PM
 */

namespace App\Report\Entities\Attributes;

use App\Report\Constants\ReportConstant;
use App\Report\Entities\Attributes\Contracts\Attribute;
use App\Report\Entities\Enums\AttributeType;
use App\Report\Entities\Enums\FilterType;
use App\Report\Exceptions\AttributeException;
use App\Report\Utils\ReportUtils;

class NonReferenceAttribute extends Attribute {

    private $idColumn;

    public function __construct($filterType, $showInReport, $displayName, $idColumn, $nameColumn){
        parent::__construct(AttributeType::NON_REF, $filterType, $showInReport, $displayName);
        $this->validateNonReferenceAttribute($filterType, $idColumn, $nameColumn);
        $this->idColumn = $idColumn;
    }

    protected function validateNonReferenceAttribute($filterType, $idColumn, $nameColumn){
        if(empty($idColumn) || !is_string($idColumn)) throw new AttributeException("Attribute \"".ReportConstant::ID_COLUMN."\" must not empty.");
        return true;
    }

    public function getGroupByColumn() {
        return $this->idColumn;
    }


    /**
     * @return mixed
     */
    public function getIdColumn() {
        return $this->idColumn;
    }

}