<?php

/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 13/03/16
 * Time: 5:32 PM
 */

namespace App\Report\Builders;

use App\Report\Entities\Attributes\NonReferenceAttribute;
use App\Report\Entities\Attributes\ReferenceAttribute;
use App\Report\Entities\Enums\AttributeType;
use App\Report\Entities\ReportEntity;
use App\Report\Entities\Attributes\Contracts\Attribute;
use App\Report\Entities\Enums\FilterType;
use App\Report\Exceptions\AttributeException;
use App\Report\Repository\Contrats\ReportRepositoryContract;


class FilterValue {

    private $reportRepository;

    /**
     * Filter Value constructor.
     */
    public function __construct(ReportRepositoryContract $reportRepository) {
        $this->reportRepository = $reportRepository;
    }

    public function update(ReportEntity $reportEntity){
        foreach($reportEntity->getAttributes() as $attribute){
            if(FilterType::isFilterWithMultiValue($attribute->getFilterType())) $this->setFilterValues($attribute);
        }
    }

    private function setFilterValues($attribute){
        switch($attribute->getType()){
            case AttributeType::REF: return $this->setReferenceFilterValues($attribute);
            case AttributeType::NON_REF: return $this->setSelfFilterValues($attribute);
            default: throw new AttributeException("Invalid attribute for setting filter value");
        }
    }

    private function setReferenceFilterValues(ReferenceAttribute $attribute){
        $filterValues = $this->reportRepository->getFilterValues($attribute->getTableName(), $attribute->getIdColumn(), $attribute->getNameColumn());
        //@todo, do we really need this one, why not we directly assign filterValue to attribute
        $sortedFilterValues = array();
        if(!empty($filterValues)) {
            foreach ($filterValues as $value) {
                $sortedFilterValues [$value->{$attribute->getIdColumn()}] = $value->{$attribute->getNameColumn()};
            }
        }
        $attribute->setFilterValues($sortedFilterValues);
    }

    //@todo can we combine these both functions
    private function setSelfFilterValues(NonReferenceAttribute $attribute){
        $filterValues = $this->reportRepository->getFilterValues($attribute->getTableName(), $attribute->getIdColumn(), $attribute->getNameColumn());
        //@todo, do we really need this one, why not we directly assign filterValue to attribute
        $sortedFilterValues = array();
        if(!empty($filterValues)) {
            foreach ($filterValues as $value) {
                $sortedFilterValues [$value->{$attribute->getIdColumn()}] = $value->{$attribute->getNameColumn()};
            }
        }
        $attribute->setFilterValues($sortedFilterValues);
    }
}