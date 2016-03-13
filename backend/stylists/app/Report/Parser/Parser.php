<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 10:00 PM
 */

namespace App\Report\Parser;
use App\Report\Parser\QueryParser;
use App\Report\Parser\ConfigParser;
use App\Report\Builder\Query\ViewFilter;

class Parser {

    private $configParser;
    private $queryParser;

    /**
     * Parser constructor.
     */
    public function __construct(ConfigParser $configParser, QueryParser $queryParser) {
        $this->configParser = $configParser;
        $this->queryParser = $queryParser;
    }

    public function getReportEntities(){
        return $this->configParser->parseReportConfig();
    }

}