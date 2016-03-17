<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 8:50 PM
 */
namespace App\Report;
use App\Report\Builders\FilterValue;
use App\Report\Parser\Parser;
use App\Report\Exceptions\ReportEntityException;
use App\Report\Entities\ReportEntity;
use App\Report\Repository\Contrats\ReportRepositoryContract;
class Reporter {

    private $reportEntities;
    private $filter;
    private $parser;
    private $reportRepository;

    public function __construct(Parser $parser, FilterValue $filter, ReportRepositoryContract $reportRepository ){
        $this->parser = $parser;
        $this->filter = $filter;
        $this->reportRepository = $reportRepository;
        $this->reportEntities = $parser->getReportEntities();
    }

    public function report($reportId){
        $reportEntity = $this->getReportEntity($reportId);
        $this->updateFilterValues($reportEntity);
        return $reportEntity;
    }

    public function collectReport($reportId, $inputParams){
        $reportEntity = $this->getReportEntity($reportId);
        return $this->reportRepository->getReportData($reportEntity, $inputParams);
    }

    private function getReportEntity($reportId){
        if(empty($this->reportEntities[$reportId])) throw new ReportEntityException("Invalid report!!");
        return $this->reportEntities[$reportId];
    }

    private function updateFilterValues(ReportEntity $reportEntity){
        $this->filter->build($reportEntity);
    }
}
