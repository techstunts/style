<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 8:30 PM
 */
namespace App\Report\Entities\Attributes\Contracts;

use App\Report\Entities\Enums\AttributeType;
use App\Report\Entities\Enums\FilterType;

abstract class Attribute {

    const TYPE = "type";
    const FILTER_TYPE = "filter_type";

    private $type;
    private $filterType;
    private $filterValues;
    
    /**
     * Attribute constructor.
     * @param $type
     */
    public function __construct($type, $filterType){
        $this->validateFilterType($filterType);
        $this->type = $type;
        $this->filterType = $filterType;
    }

    private function validateFilterType($filterType) {
        if(!FilterType::isValidValue($filterType)) throw new AttributeException("Attribute \"field_type\" is not valid, value must in [" . AttributeFieldType::getAllowedValues() . "]");
        return true;
    }

    /**
     * @return AttributeType
     */
    public function getType(){
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getFilterType() {
        return $this->filterType;
    }

    /**
     * @return mixed
     */
    public function getFilterValues() {
        return $this->filterValues;
    }

    /**
     * @param mixed $filterValues
     */
    public function setFilterValues($filterValues) {
        $this->filterValues = $filterValues;
    }

}