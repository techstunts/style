<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use URL;

class Campaign extends Model
{
    /** Campaign states  */
    const CREATED_STATE = 'CREATED';
    const PUBLISHED_STATE = 'PUBLISHED';
    const QUEUING_STATE = 'QUEUING';
    const QUEUED_STATE = 'QUEUED';
    const DB_DATE_FORMAT = "Y-m-d H:i:s";
    const LINK_REGEX_EXP = "<a\s[^>]*href=([\"\']??)([^\">]*?)\\1[^>]*>(.*)<\/a>";

    protected $table = 'campaigns';

    private static $imageExtensions = ['jpg', 'png', 'gif', 'jpeg'];
    private static $excludeLinks = ['#', '/'];
    private static $redirectURI = "/campaign/redirect?c=%d&e=[EMAIL]&u=%s";


    public function isEditable()
    {
        $editableState = [self::CREATED_STATE];
        return (in_array($this->status, $editableState)) ? true : false;
    }

    public function isPublishable()
    {
        return ($this->status === self::CREATED_STATE)? true: false;
    }

    public function isPublished()
    {
        $publishedState = [self::PUBLISHED_STATE, self::QUEUING_STATE, self::QUEUED_STATE];
        return (in_array($this->status, $publishedState)) ? true : false;
    }

    public function publish($publishDate, $dateFormat)
    {
        $this->status = Campaign::PUBLISHED_STATE;
        $this->published_on = Carbon::createFromFormat($dateFormat, $publishDate)->format(self::DB_DATE_FORMAT);
        $this->prepared_message = $this->prepareMessage();
        $this->updateTimestamps();
        $this->save();
    }

    public function queuing()
    {
        $this->status = self::QUEUING_STATE;
        $this->updateTimestamps();
        $this->save();
    }

    public function queued()
    {
        $this->status = self::QUEUED_STATE;
        $this->updateTimestamps();
        $this->save();
    }

    private function findLinks($html){
        preg_match_all("/".self::LINK_REGEX_EXP."/siU", $html, $matches);
        return $matches[2];
    }

    private function getExtension($link){
        $parts = explode('.', $link);
        return strtolower(end($parts));
    }

    private function prepareMessage() {
        $preparedMessage = $this->message ;
        $links = self::findLinks($preparedMessage );
        if(!empty($links) && is_array($links)) {
            foreach ($links as $link) {
                if (self::isValidLink($link)) {
                    $preparedURL = URL::to(sprintf(self::$redirectURI, $this->id, urlencode($link)));
                    $preparedMessage  = str_replace($link, $preparedURL, $preparedMessage);
                }
            }
        }
        return $preparedMessage;
    }

    private function isValidLink($link){
        return (!in_array(self::getExtension($link), self::$imageExtensions) &&
            !in_array($link, self::$excludeLinks)) ? true : false;
    }
}
