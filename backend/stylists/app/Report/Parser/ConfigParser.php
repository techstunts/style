<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 10:03 PM
 */

namespace App\Report\Parser;

use App\Report\Constants\ReportConstant;
use App\Report\Entities\Attributes\NonReferenceAttribute;
use App\Report\Entities\Conditions\Condition;
use App\Report\Entities\Relationships\Relationship;
use App\Report\Exceptions\AttributeException;
use App\Report\Entities\ReportEntity;
use App\Report\Entities\Attributes\ReferenceAttribute;
use App\Report\Entities\Attributes\Contracts\Attribute;
use App\Report\Entities\Enums\AttributeType;
use App\Report\Exceptions\JsonException;
use App\Report\Exceptions\ReportEntityException;
use App\Report\Utils\ReportUtils;


class ConfigParser {

    const CONFIG_DIR = "Config";
    const PARENT_DIR = "..";
    const FILE_EXTENSION =".json";

    public function getReportEntity($reportId){
        return $this->parseReportConfig($reportId, $this->getReportConfig($reportId));
    }

    private function parseReportConfig($reportId, array $reportConfig){
        return new ReportEntity( $reportId,
                                    ReportUtils::getValueFromArray($reportConfig, ReportConstant::DISPLAY_NAME),
                                    ReportUtils::getValueFromArray($reportConfig, ReportConstant::TABLE_NAME),
                                    ReportUtils::getValueFromArray($reportConfig, ReportConstant::RELATED_REPORT_LINKS),
                                    $this->parseAttributeConfig(ReportUtils::getValueFromArray($reportConfig, ReportConstant::ATTRIBUTES)),
                                    $this->parseRelationshipConfig(ReportUtils::getValueFromArray($reportConfig, ReportConstant::RELATIONSHIPS)),
                                    $this->parseConditionsConfig(ReportUtils::getValueFromArray($reportConfig, ReportConstant::CONDITIONS)));
    }

    private function parseRelationshipConfig($relationships){
        if(empty($relationships)) return;
        $relationshipObjects = array();
        foreach($relationships as $relationshipKey => $relationship){
            $relationshipObjects[$relationshipKey] = new Relationship(  ReportUtils::getValueFromArray($relationship, ReportConstant::JOIN_TYPE),
                                                                        ReportUtils::getValueFromArray($relationship, ReportConstant::TABLE),
                                                                        ReportUtils::getValueFromArray($relationship, ReportConstant::JOIN_CLAUSE));
        }
        return $relationshipObjects;
    }

    private function parseConditionsConfig($conditions){
        if(empty($conditions)) return;
        $conditionObjects = array();
        foreach($conditions as $conditionKey => $condition){
            $conditionObjects[$conditionKey] = new Condition(   ReportUtils::getValueFromArray($condition, ReportConstant::WHERE_TYPE),
                                                                ReportUtils::getValueFromArray($condition, ReportConstant::COLUMN),
                                                                ReportUtils::getValueFromArray($condition, ReportConstant::VALUE),
                                                                ReportUtils::getValueFromArray($condition, ReportConstant::OPERATOR));
        }
        return $conditionObjects;
    }

    private function parseAttributeConfig(array $attributes){
        $attributeObjects = array();
        foreach($attributes as $attributeName => $attributeConfig){
            $attributeObjects[$attributeName] = $this->getAttributeObject($attributeConfig);
        }        
        return $attributeObjects;
    }

    private function getAttributeObject(array $attributeConfig){
        switch($attributeConfig[ReportConstant::TYPE]){
            case AttributeType::REF:
                return $this->getReferenceAttributeObject($attributeConfig);
            case AttributeType::NON_REF :
                return $this->getNonReferenceAttributeObject($attributeConfig);
            default:
                throw new AttributeException("Invalid attribute type");
        }
    }

    private function getNonReferenceAttributeObject(array $attributeConfig){
        return new NonReferenceAttribute(   ReportUtils::getValueFromArray($attributeConfig, ReportConstant::FILTER_TYPE),
                                            ReportUtils::getValueFromArray($attributeConfig, ReportConstant::SHOW_IN_REPORT),
                                            ReportUtils::getValueFromArray($attributeConfig, ReportConstant::DISPLAY_NAME),
                                            ReportUtils::getValueFromArray($attributeConfig, ReportConstant::ID_COLUMN),
                                            ReportUtils::getValueFromArray($attributeConfig, ReportConstant::NAME_COLUMN));
    }

    private function getReferenceAttributeObject(array $attributeConfig){
        return new ReferenceAttribute(  ReportUtils::getValueFromArray($attributeConfig, ReportConstant::FILTER_TYPE),
                                        ReportUtils::getValueFromArray($attributeConfig, ReportConstant::SHOW_IN_REPORT),
                                        ReportUtils::getValueFromArray($attributeConfig, ReportConstant::DISPLAY_NAME),
                                        ReportUtils::getValueFromArray($attributeConfig, ReportConstant::ID_COLUMN),
                                        ReportUtils::getValueFromArray($attributeConfig, ReportConstant::NAME_COLUMN),
                                        ReportUtils::getValueFromArray($attributeConfig, ReportConstant::TABLE_NAME),
                                        ReportUtils::getValueFromArray($attributeConfig, ReportConstant::PARENT_TABLE_ID_COLUMN));
    }

    private function getReportConfig($reportId){
        $configFile = $this->getConfigFile($reportId);
        if(!$this->isConfigExist($configFile)) throw new ReportEntityException($reportId ." Report Entity not exist. ");
        $configData = json_decode(file_get_contents($configFile), true);
        $jsonError = json_last_error();
        if($jsonError) throw new JsonException("Got json exception during config parsing, exception is [".ReportUtils::getJsonLastErrorMsg($jsonError) ."]" );
        return $configData;
    }

    private function isConfigExist($configFile){
        return file_exists($configFile);
    }

    private function getConfigFile($reportId){
        return implode(DIRECTORY_SEPARATOR, array(
                                                    __DIR__ ,
                                                    self::PARENT_DIR,
                                                    self::CONFIG_DIR,
                                                    $reportId
                                                )
                ).self::FILE_EXTENSION;
    }

}
