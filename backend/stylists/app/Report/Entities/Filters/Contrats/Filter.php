<?php

/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 13/03/16
 * Time: 4:25 PM
 */
namespace App\Report\Entities\Filters\Contrats;
use App\Report\Entities\Enums\FilterType;

abstract class Filter {

    private $type;

    /**
     * Filter constructor.
     * @param $type
     */
    public function __construct(FilterType $type) {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }
}