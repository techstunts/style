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
use App\Report\Exceptions\ReportEntityException;
use Mockery\CountValidator\Exception;

class ConfigParser {

    const CONFIG_DIR = "config";
    const PARENT_DIR = "..";
    const FILE_EXTENSION =".json";

    public function getReportEntity($reportId){
        return $this->parseReportConfig($this->getReportConfig($reportId));
    }

    private function parseReportConfig(array $reportConfig){
        return new ReportEntity($reportConfig[ReportEntity::DISPLAY_NAME], $reportConfig[ReportEntity::TABLE_NAME],
                                    $this->parseAttributeConfig($reportConfig[ReportEntity::ATTRIBUTES]),
                                    $this->parseRelationshipConfig($reportConfig[ReportEntity::RELATIONSHIPS]),
                                    $this->parseConditionsConfig($reportConfig[ReportEntity::CONDITIONS]) );
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

    private function getReportConfig($reportId){
        $configFile = $this->getConfigFile($reportId);
        if(!$this->isConfigExist($configFile)) throw new ReportEntityException($reportId ." Report Entity not exist. ");
        return json_decode(file_get_contents($configFile), true);

    }

    private function isConfigExist($configFile){
        return file_exists($configFile);
    }

    private function getConfigFile($reportId){
        return implode(DIRECTORY_SEPARATOR, array(__DIR__ , self::PARENT_DIR, self::CONFIG_DIR, $reportId)).self::FILE_EXTENSION;
    }

}