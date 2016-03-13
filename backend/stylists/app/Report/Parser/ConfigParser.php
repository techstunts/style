<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 10:03 PM
 */

namespace App\Report\Parser;

use App\Report\Entities\Conditions\Condition;
use App\Report\Entities\Relationships\Relationship;
use App\Report\Exceptions\AttributeException;
use App\Report\Entities\ReportEntity;
use App\Report\Entities\Attributes\ReferenceAttribute;
use App\Report\Entities\Attributes\Contracts\Attribute;
use App\Report\Entities\Enums\AttributeType;

class ConfigParser {

    const CONFIG_DIR = "config";
    const CONFIG_FILE = "report.json";
    const PARENT_DIR = "..";

    private static $reportConfig;

    private static function getReportConfig(){
        if(is_null(self::$reportConfig)){
            self::$reportConfig = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.self::PARENT_DIR.DIRECTORY_SEPARATOR.self::CONFIG_DIR.DIRECTORY_SEPARATOR.self::CONFIG_FILE), true);
        }
        return self::$reportConfig;
    }

    public function parseReportConfig(){
        $reportEntities = array();
        foreach(self::getReportConfig() as $reportKey => $reportConfig){
            $reportEntities[$reportKey] = new ReportEntity( $reportConfig[ReportEntity::DISPLAY_NAME], $reportConfig[ReportEntity::TABLE_NAME],
                                                            $this->parseAttributeConfig($reportConfig[ReportEntity::ATTRIBUTES]),
                                                            $this->parseRelationshipConfig($reportConfig[ReportEntity::RELATIONSHIPS]),
                                                            $this->parseConditionsConfig($reportConfig[ReportEntity::CONDITIONS]) );
        }
        return $reportEntities;
    }

    private function parseRelationshipConfig(array $relationships){
        $relationshipObjects = array();
        foreach($relationships as $relationshipKey => $relationship){
            $relationshipObjects[$relationshipKey] = new Relationship( isset($relationship[Relationship::JOIN_TYPE])?$relationship[Relationship::JOIN_TYPE]:null,  isset($relationship[Relationship::TABLE])?$relationship[Relationship::TABLE]:null, isset($relationship[Relationship::JOIN_CLAUSE])?$relationship[Relationship::JOIN_CLAUSE]:null);
        }
        return $relationshipObjects;
    }

    private function parseConditionsConfig(array $conditions){
        $conditionObjects = array();
        foreach($conditions as $conditionKey => $condition){
            $conditionObjects[$conditionKey] = new Condition(isset($condition[Condition::WHERE_TYPE])?$condition[Condition::WHERE_TYPE]:null,    isset($condition[Condition::COLUMN])?$condition[Condition::COLUMN]:null,
                                                                isset($condition[Condition::VALUE])?$condition[Condition::VALUE]:null, isset($condition[Condition::OPERATOR])?$condition[Condition::OPERATOR]:null);
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
            case AttributeType::SELF :
                return $this->getSelfAttributeObject($attributeConfig);
            default:
                throw new AttributeException("Invalid attribute type");
        }
    }

    private function getSelfAttributeObject(array $attributeConfig){

    }

    private function getReferenceAttributeObject(array $attributeConfig){
        return new ReferenceAttribute($attributeConfig[Attribute::FILTER_TYPE], $attributeConfig[ReferenceAttribute::SHOW_IN_REPORT], $attributeConfig[ReferenceAttribute::DISPLAY_NAME],
                                        $attributeConfig[ReferenceAttribute::COLUMN_ID], $attributeConfig[ReferenceAttribute::COLUMN_NAME], $attributeConfig[ReferenceAttribute::TABLE_NAME], $attributeConfig[ReferenceAttribute::PARENT_TABLE_COLUMN_ID]);
    }


}