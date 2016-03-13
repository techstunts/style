<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 13/03/16
 * Time: 4:27 PM
 */

namespace App\Report\Entities\Filters;

use App\Report\Entities\Enums\FilterType;
use App\Report\Entities\Filters\Contrats\Filter;

class SingleSelect extends Filter{


    /**
     * SingleSelect constructor.
     */
    public function __construct() {
        parent::__construct(FilterType::SINGLE_SELECT);
    }
}
