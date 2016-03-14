<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 8:50 PM
 */
namespace App\Report;
use App\Report\Parser\Parser;
use App\Report\Exceptions\ReportEntityException;
use App\Report\Builders\Filter;
use App\Report\Entities\ReportEntity;
use App\Report\Repository\ReportRepository;
class Reporter {

    private $reportEntities;
    private $filter;
    private $parser;

    public function __construct(Parser $parser, Filter $filter ){
        $this->parser = $parser;
        $this->filter = $filter;
        $this->reportEntities = $parser->getReportEntities();
    }

    public function report($reportId){
        $reportEntity = $this->getReportEntity($reportId);
        $this->updateFilterValues($reportEntity);
        return $reportEntity;
    }
	//@todo refactor 
    public function collectReport($reportId, $inputParams){
        $reportEntity = $this->getReportEntity($reportId);
        $repo = new ReportRepository();
        return $repo->getReportData($reportEntity, $inputParams);
    }

    private function getReportEntity($reportId){
        if(empty($this->reportEntities[$reportId])) throw new ReportEntityException("Invalid report!!");
        return $this->reportEntities[$reportId];
    }

    private function updateFilterValues(ReportEntity $reportEntity){
        $this->filter->build($reportEntity);
    }
}
