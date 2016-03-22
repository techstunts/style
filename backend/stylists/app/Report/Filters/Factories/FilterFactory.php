<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 19/03/16
 * Time: 8:58 PM
 */

namespace App\Report\Filters\Factories;

use App\Report\Entities\Enums\FilterType;
use App\Report\Exceptions\FilterNotFoundException;
use App\Report\Filters\DateRange;
use App\Report\Filters\MultiSelect;
use App\Report\Filters\SingleSelect;

class FilterFactory {

    private static $INSTANCES = array();

    public static function getInstance($filterType){
        if(empty(self::$INSTANCES)) self::createInstances();
        if(empty(self::$INSTANCES[$filterType])) throw new FilterNotFoundException("Requested filter not regiseted. ["+$filterType+"]");
        return self::$INSTANCES[$filterType];
    }

    private static function createInstances(){
        self::$INSTANCES = array(
            FilterType::SINGLE_SELECT  => new SingleSelect(),
            FilterType::MULTI_SELECT => new MultiSelect(),
            FilterType::DATE_RANGE => new DateRange()
        );
    }

}