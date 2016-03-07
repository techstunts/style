<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 10:03 PM
 */

namespace App\Report\Parser;

use App\Report\Exceptions\AttributeException;
use App\Report\Entities\ReportEntity;
use App\Report\Entities\Attributes\ReferenceAttribute;
use App\Report\Entities\Attributes\Contracts\Attribute;
use App\Report\Entities\Enums\AttributeType;

class ConfigParser {

    //@todo, Move this config to some config file, or some yml file
    //@todo, Config is in draft mode, will change very frequently
    private $config = '{
              "looks": {
                "display_name": "Looks Report",
                "table_name": "looks",
                "relationship": {
                  "reco" :{
                    "join_type": "left",
                    "table_id":"entity_id",
                    "table_name" : "recommendations",
                    "parent_table_id": "id",
                    "condition": {
                      "entity_type": {
                        "attribute" : "entity_type_id",
                        "value" : ["2"]
                      }
                    }
                  }
                },
                "attribute": {
                  "budget": {
                    "type": "ref",
                    "field_type": "multiselect",
                    "show_in_report": true,
                    "display_name": "Budget",
                    "table_id": "id",
                    "table_name": "lu_budget",
                    "parent_table_id": "budget_id"
                  },
                  "occasion": {
                    "type": "ref",
                    "field_type": "multiselect",
                    "show_in_report": true,
                    "display_name": "Occasion",
                    "table_id": "id",
                    "table_name": "lu_occasion",
                    "parent_table_id": "occasion_id"
                  },
                  "body_type": {
                    "type": "ref",
                    "field_type": "singleselect",
                    "show_in_report": true,
                    "display_name": "Body Type",
                    "table_id": "id",
                    "table_name": "lu_body_type",
                    "parent_table_id": "body_type_id"
                  }
                }
              }

        }';

    public function parseReportConfig(){
        $reportEntities = array();
        foreach(json_decode($this->config, true) as $reportKey => $reportConfig){
            $reportEntities[$reportKey] = new ReportEntity( $reportConfig[ReportEntity::DISPLAY_NAME],
                                                            $reportConfig[ReportEntity::TABLE_NAME],
                                                            $this->parseAttributeConfig($reportConfig[ReportEntity::ATTRIBUTE]));
        }
        return $reportEntities;
    }

    private function parseAttributeConfig(array $attributes){
        $attributeObjects = array();
        foreach($attributes as $attributeName => $attributeConfig){
            $attributeObjects[$attributeName] = $this->getAttributeObject($attributeConfig);
        }
        dd($attributeObjects);
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
        return new ReferenceAttribute(  $attributeConfig[ReferenceAttribute::FIELD_TYPE],
                                        $attributeConfig[ReferenceAttribute::SHOW_IN_REPORT],
                                        $attributeConfig[ReferenceAttribute::DISPLAY_NAME],
                                        $attributeConfig[ReferenceAttribute::TABLE_ID],
                                        $attributeConfig[ReferenceAttribute::TABLE_NAME],
                                        $attributeConfig[ReferenceAttribute::PARENT_TABLE_ID]);
    }
}