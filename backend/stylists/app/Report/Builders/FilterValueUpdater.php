<?php

/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 13/03/16
 * Time: 5:32 PM
 */

namespace App\Report\Builders;

use App\Report\Entities\Enums\AttributeType;
use App\Report\Entities\ReportEntity;
use App\Report\Entities\Enums\FilterType;
use App\Report\Exceptions\FilterNotFoundException;
use App\Report\Filters\Factories\FilterFactory;
use App\Report\Repository\Contrats\ReportRepositoryContract;


class FilterValueUpdater {

    private $reportRepository;

    /**
     * Filter Value constructor.
     */
    public function __construct(ReportRepositoryContract $reportRepository) {
        $this->reportRepository = $reportRepository;
    }

    public function update(ReportEntity $reportEntity){
        foreach($reportEntity->getAttributes() as $attribute){
            if(FilterType::isFilterWithMultiValue($attribute->getFilterType())) {
                $this->setFilterValues($reportEntity, $attribute);
            }
        }
    }

    private function setFilterValues(ReportEntity $reportEntity, $attribute){
        $filterUpdater = FilterFactory::getInstance($attribute->getFilterType());
        switch($attribute->getType()){
            case AttributeType::REF:
                $filterUpdater->setReferenceFilterValues($this->reportRepository, $attribute);
                break;
            case AttributeType::NON_REF:
                $filterUpdater->setNonReferenceFilterValues($this->reportRepository, $attribute, $reportEntity->getTable());
                break;
            default:
                throw new FilterNotFoundException("Filter value updater method not found for attribute, " + $attribute->getType());
        }
    }
}