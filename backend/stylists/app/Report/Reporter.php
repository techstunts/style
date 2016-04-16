<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 8:50 PM
 */
namespace App\Report;
use App\Report\Builders\FilterValueUpdater;
use App\Report\Parser\ConfigParser;
use App\Report\Parser\Parser;
use App\Report\Entities\ReportEntity;
use App\Report\Repository\Contrats\ReportRepositoryContract;
class Reporter {

    private $filterValue;
    private $configParser;

    public function __construct(ConfigParser $configParser, FilterValueUpdater $filterValue, ReportRepositoryContract $reportRepository ){
        $this->configParser = $configParser;
        $this->filterValue = $filterValue;
        $this->reportRepository = $reportRepository;
    }


    public function getReportEntity($reportId){
        $reportEntity = $this->configParser->getReportEntity($reportId);
        $this->updateFilterValues($reportEntity);
        return $reportEntity;
    }

    public function report($reportId, $inputParams){
        $reportEntity =  $this->configParser->getReportEntity($reportId);
        return $this->reportRepository->getReportData($reportEntity, $inputParams);
    }

    private function updateFilterValues(ReportEntity $reportEntity){
        $this->filterValue->update($reportEntity);
    }
}
