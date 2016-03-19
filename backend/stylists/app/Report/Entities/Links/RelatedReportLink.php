<?php

/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 19/03/16
 * Time: 10:37 AM
 */
namespace App\Report\Entities\Links;

use App\Report\Exceptions\RelatedReportLinkException;

class RelatedReportLink {

    const LINK = "link";
    const DISPLAY_NAME = "display_name";

    private $link;
    private $displayName;

    /**
     * RelatedReportLink constructor.
     * @param $link
     */
    public function __construct($link, $displayName) {
        $this->validateLink($link, $displayName);
        $this->link = $link;
        $this->displayName = $displayName;
    }

    private function validateLink($link, $displayName){
        if(empty($link) || !is_string($link) || trim($link) === "") throw new RelatedReportLinkException("RelatedReportLink \"".self::LINK."\" should not be empty.");
        if(empty($displayName) || !is_string($displayName) || trim($displayName) === "") throw new RelatedReportLinkException("RelatedReportLink \"".self::LINK."\" should not be empty.");
        return true;
    }

    /**
     * @return mixed
     */
    public function getLink() {
        return $this->link;
    }

    /**
     * @return mixed
     */
    public function getDisplayName() {
        return $this->displayName;
    }

}