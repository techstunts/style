<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 10:03 PM
 */

namespace App\Report\Parser;

use App\Report\Entities\Attributes\NonReferenceAttribute;
use App\Report\Entities\Conditions\Condition;
use App\Report\Entities\Relationships\Relationship;
use App\Report\Exceptions\AttributeException;
use App\Report\Entities\ReportEntity;
use App\Report\Entities\Attributes\ReferenceAttribute;
use App\Report\Entities\Attributes\Contracts\Attribute;
use App\Report\Entities\Enums\AttributeType;
use App\Report\Exceptions\ReportEntityException;
use App\Report\Utils\ReportUtils;


class ConfigParser {

    const CONFIG_DIR = "config";
    const PARENT_DIR = "..";
    const FILE_EXTENSION =".json";

    public function getReportEntity($reportId){
        return $this->parseReportConfig($this->getReportConfig($reportId));
    }

    private function parseReportConfig(array $reportConfig){
        return new ReportEntity(    ReportUtils::getValueFromArray($reportConfig, ReportEntity::DISPLAY_NAME),
                                    ReportUtils::getValueFromArray($reportConfig, ReportEntity::TABLE_NAME),
                                    $this->parseAttributeConfig(ReportUtils::getValueFromArray($reportConfig, ReportEntity::ATTRIBUTES)),
                                    $this->parseRelationshipConfig(ReportUtils::getValueFromArray($reportConfig, ReportEntity::RELATIONSHIPS)),
                                    $this->parseConditionsConfig(ReportUtils::getValueFromArray($reportConfig, ReportEntity::CONDITIONS)));
    }

    private function parseRelationshipConfig(array $relationships){
        $relationshipObjects = array();
        foreach($relationships as $relationshipKey => $relationship){
            $relationshipObjects[$relationshipKey] = new Relationship(  ReportUtils::getValueFromArray($relationship, Relationship::JOIN_TYPE),
                                                                        ReportUtils::getValueFromArray($relationship, Relationship::TABLE),
                                                                        ReportUtils::getValueFromArray($relationship, Relationship::JOIN_CLAUSE));
        }
        return $relationshipObjects;
    }

    private function parseConditionsConfig(array $conditions){
        $conditionObjects = array();
        foreach($conditions as $conditionKey => $condition){
            $conditionObjects[$conditionKey] = new Condition(   ReportUtils::getValueFromArray($condition, Condition::WHERE_TYPE),
                                                                ReportUtils::getValueFromArray($condition, Condition::COLUMN),
                                                                ReportUtils::getValueFromArray($condition, Condition::VALUE),
                                                                ReportUtils::getValueFromArray($condition, Condition::OPERATOR));
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
        switch($attributeConfig[Attribute::TYPE]){
            case AttributeType::REF:
                return $this->getReferenceAttributeObject($attributeConfig);
            case AttributeType::NON_REF :
                return $this->getNonReferenceAttributeObject($attributeConfig);
            default:
                throw new AttributeException("Invalid attribute type");
        }
    }

    private function getNonReferenceAttributeObject(array $attributeConfig){
        return new NonReferenceAttribute(   ReportUtils::getValueFromArray($attributeConfig, Attribute::FILTER_TYPE),
                                            ReportUtils::getValueFromArray($attributeConfig, Attribute::SHOW_IN_REPORT),
                                            ReportUtils::getValueFromArray($attributeConfig, Attribute::DISPLAY_NAME),
                                            ReportUtils::getValueFromArray($attributeConfig, NonReferenceAttribute::ID_COLUMN),
                                            ReportUtils::getValueFromArray($attributeConfig, NonReferenceAttribute::NAME_COLUMN));
    }

    private function getReferenceAttributeObject(array $attributeConfig){
        return new ReferenceAttribute(  ReportUtils::getValueFromArray($attributeConfig, Attribute::FILTER_TYPE),
                                        ReportUtils::getValueFromArray($attributeConfig, Attribute::SHOW_IN_REPORT),
                                        ReportUtils::getValueFromArray($attributeConfig, Attribute::DISPLAY_NAME),
                                        ReportUtils::getValueFromArray($attributeConfig, ReferenceAttribute::ID_COLUMN),
                                        ReportUtils::getValueFromArray($attributeConfig, ReferenceAttribute::NAME_COLUMN),
                                        ReportUtils::getValueFromArray($attributeConfig, ReferenceAttribute::TABLE_NAME),
                                        ReportUtils::getValueFromArray($attributeConfig, ReferenceAttribute::PARENT_TABLE_ID_COLUMN));
    }

    private function getReportConfig($reportId){
        $configFile = $this->getConfigFile($reportId);
        if(!$this->isConfigExist($configFile)) throw new ReportEntityException($reportId ." Report Entity not exist. ");
        return json_decode(file_get_contents($configFile), true);

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