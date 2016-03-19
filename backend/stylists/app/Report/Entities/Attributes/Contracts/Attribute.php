<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 8:30 PM
 */
namespace App\Report\Entities\Attributes\Contracts;

use App\Report\Constants\ReportConstant;
use App\Report\Entities\Enums\FilterType;
use App\Report\Exceptions\AttributeException;
use App\Report\Utils\ReportUtils;

abstract class Attribute {

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
        if(!FilterType::isValidValue($filterType)) throw new AttributeException("Attribute \"".ReportConstant::FILTER_TYPE."\" is not valid, value must in [" .ReportUtils::convertArrayToString(FilterType::getAllowedValues()). "]");
        if(empty($displayName) || !is_string($displayName)) throw new AttributeException("Attribute \"".ReportConstant::DISPLAY_NAME."\" must not be empty.");
        if(is_null($showInReport) || !is_bool($showInReport)) throw new AttributeException("Attribute \"".ReportConstant::SHOW_IN_REPORT."\" must not be empty.");
        if($this->filterType === FilterType::DATE_RANGE && $showInReport) throw new AttributeException("Attribute \"".ReportConstant::SHOW_IN_REPORT."\" must be false for filter type \"". FilterType::DATE_RANGE."\".");
        return true;
    }


    public abstract function getGroupByColumn();

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