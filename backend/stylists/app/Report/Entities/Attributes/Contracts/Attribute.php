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
    const DISPLAY_NAME = "display_name";
    const SHOW_IN_REPORT = "show_in_report";

    private $type;
    private $filterType;
    private $filterValues;
    private $displayName;
    private $showInReport;

    /**
     * Attribute constructor.
     * @param $type
     * @param $filterType
     * @param $showInReport
     * @param $displayName
     */
    public function __construct($type, $filterType, $showInReport, $displayName ){
        $this->validateAttributes($filterType, $displayName, $showInReport);
        $this->type = $type;
        $this->filterType = $filterType;
        $this->displayName = $displayName;
        $this->showInReport = $showInReport;
    }

    private function validateAttributes($filterType, $displayName, $showInReport) {
        if(!FilterType::isValidValue($filterType)) throw new AttributeException("Attribute \"".self::DISPLAY_NAME."\" is not valid, value must in [" .implode(",", FilterType::getAllowedValues()) . "]");
        if(empty($displayName) || !is_string($displayName)) throw new AttributeException("Attribute \"".self::DISPLAY_NAME."\" must not be empty.");
        if(is_null($showInReport) || !is_bool($showInReport)) throw new AttributeException("Attribute \"".self::SHOW_IN_REPORT."\" must not be empty.");
        return true;
    }

    /**
     * @return mixed
     */
    public function getType() {
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
     * @return mixed
     */
    public function getDisplayName() {
        return $this->displayName;
    }

    /**
     * @return mixed
     */
    public function getShowInReport() {
        return $this->showInReport;
    }


    /**
     * @param mixed $filterValues
     */
    public function setFilterValues($filterValues) {
        $this->filterValues = $filterValues;
    }

}