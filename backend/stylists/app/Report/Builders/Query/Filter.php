<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 17/03/16
 * Time: 10:44 PM
 */

namespace App\Report\Builders\Query;

use App\Report\Constants\ReportConstant;
use App\Report\Entities\ReportEntity;
use App\Report\Entities\Enums\AttributeType;
use App\Report\Filters\Factories\FilterFactory;
use App\Report\Utils\ReportUtils;

/**
 * Class Filter
 * @package App\Report\Builders\Query
 */
class Filter {

    const DB_DATE_FORMAT = "Y-m-d";
    const USER_INPUT_DATE_FORMAT = "d M Y";

    public function build(ReportEntity $reportEntity, $tableBuilder, $userInput){
        $filterValues = ReportUtils::getValueFromArray($userInput, ReportConstant::ATTRIBUTES);
        $attributes = $reportEntity->getAttributes();

        if($this->isEmptyFilter($attributes, $filterValues)) return;

        foreach($filterValues as $param => $values){
            $values = is_array($values)? array_filter($values): $values;

            if(empty($attributes[$param]) || ReportUtils::isEmpty($values)) continue;

            $attribute = $attributes[$param];
            $filterBuilder = FilterFactory::getInstance($attribute->getFilterType());


            switch($attributes[$param]->getType()){
                case AttributeType::REF:
                    $filterBuilder->buildQueryForRefAttribute($tableBuilder, $attribute->getTableName(), $attribute->getIdColumn(), $attribute->getParentTableIdColumn(), $values);
                    break;
                case AttributeType::NON_REF:
                    $filterBuilder->buildQueryForNonRefAttribute($tableBuilder, $attribute->getIdColumn(), $values);
                    break;
            }
        }
    }

    private function isEmptyFilter($attributes, $filterValues){
        return (empty($attributes) || empty($filterValues) ||
                !is_array($attributes) && !is_array($filterValues));
    }
}