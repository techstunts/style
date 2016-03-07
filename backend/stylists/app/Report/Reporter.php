<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 8:50 PM
 */
namespace App\Report;
use App\Report\Parser\Parser;

class Reporter {

    private $reportEntities;

    public function __construct(Parser $parser){
        $this->reportEntities = $parser->getReportEntities();
    }

    public function report(){

    }
}