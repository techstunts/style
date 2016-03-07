<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 8:30 PM
 */

namespace App\Report\Entities\Attributes\Contracts;


abstract class Attribute {

    const TYPE = "type";

    private $type;

    /**
     * Attribute constructor.
     * @param $type
     */
    public function __construct($type){
        $this->type = $type;
    }

    /**
     * @return AttributeType
     */
    public function getType(){
        return $this->type;
    }
}