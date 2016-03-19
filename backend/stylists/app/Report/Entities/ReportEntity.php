<?php

/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 7:48 PM
 */
namespace App\Report\Entities;

use App\Report\Constants\ReportConstant;
use App\Report\Exceptions\ReportEntityException;
use App\Report\Entities\Attributes\Contracts\Attribute;
use App\Report\Entities\Conditions\Condition;
use App\Report\Entities\Relationships\Relationship;
use App\Report\Entities\Links\RelatedReportLink;
use App\Report\Utils\ReportUtils;

class ReportEntity {

    private $displayName;
    private $table;
    private $relatedReportLink;
    private $relationships = array();
    private $conditions = array();
    private $attributes = array();
       
    /**
     * ReportEntity constructor.
     * @param $displayName
     * @param $table
     * @param array $attributes
     */
    public function __construct($displayName, $table, $relatedReportLinks, array $attributes, array $relationships = null, array $conditions = null ) {
        $this->validate($displayName, $table, $attributes, $relationships, $conditions);
        $this->displayName = $displayName;
        $this->relatedReportLink = $this->createRelatedReportLink($relatedReportLinks);
        $this->relationships = $relationships;
        $this->conditions = $conditions;
        $this->table = $table;
        $this->attributes = $attributes;
    }

    private function validate($displayName, $table, array $attributes, array $relationships = null, array $conditions = null) {
        if(empty($displayName) || !is_string($displayName)) throw new ReportEntityException("Report entity \"".ReportConstant::DISPLAY_NAME."\" must not be empty.");
        if(empty($table) || !is_string($table)) throw new ReportEntityException("Report entity \"".ReportConstant::TABLE_NAME."\" must not be empty.");
        if(!$this->validateRelationships($relationships)) throw new ReportEntityException("Report entity\"".ReportConstant::RELATIONSHIPS."\" value is not valid.");
        if(!$this->validateConditions($conditions)) throw new ReportEntityException("Report entity\"".ReportConstant::CONDITIONS."\" value is not valid.");
        if(empty($attributes) || !is_array($attributes)) throw new ReportEntityException("Report entity \"".ReportConstant::ATTRIBUTES."\" must not be empty.");
        if(!$this->validateAttributes($attributes)) throw new ReportEntityException("Report entity\"".ReportConstant::ATTRIBUTES."\" value is not valid.");
        return true;
    }

    private function validateRelationships($relationships){
        if(empty($relationships))return true;
        foreach($relationships as $relationship){
            if(!$relationship instanceof Relationship) return false;
        }
        return true;
    }

    private function validateConditions($conditions){
        if(empty($conditions)) return true;
        foreach($conditions as $attribute){
            if(! $attribute instanceof Condition) return false;
        }
        return true;
    }

    private function validateAttributes(array $attributes){     
        foreach($attributes as $attribute){                
            if(! $attribute instanceof Attribute) return false;
        }
        return true;
    }

    private function createRelatedReportLink($relatedReportLinks){
        if(empty($relatedReportLinks)) return null;
        if(!is_array($relatedReportLinks) || count($relatedReportLinks) === 0) throw new ReportEntityException("Report entity \"".ReportConstant::RELATED_REPORT_LINKS."\" must be array.");
        $relatedReportLinkList = array();
        foreach($relatedReportLinks as $relatedReportLink){
            $relatedReportLinkList[] =  new RelatedReportLink(  ReportUtils::getValueFromArray($relatedReportLink, ReportConstant::LINK),
                                                                ReportUtils::getValueFromArray($relatedReportLink, ReportConstant::DISPLAY_NAME));
        }
        return $relatedReportLinkList;
    }
    /**
     * @return mixed
     */
    public function getDisplayName() {
        return $this->displayName;
    }

    /**
     * @return array|null
     */
    public function getRelatedReportLink() {
        return $this->relatedReportLink;
    }

    /**
     * @return mixed
     */
    public function getTable() {
        return $this->table;
    }

    /**
     * @return array
     */
    public function getRelationships() {
        return $this->relationships;
    }

    /**
     * @return array
     */
    public function getConditions() {
        return $this->conditions;
    }

    /**
     * @return array
     */
    public function getAttributes() {
        return $this->attributes;
    }


}