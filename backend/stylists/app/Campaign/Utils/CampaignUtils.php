<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 01/06/16
 * Time: 10:35 PM
 */

namespace App\Campaign\Utils;
use App\Campaign\Entities\Enums\Placeholder;
use URL;

class CampaignUtils {

    const LINK_REGEX_EXP = '<a\s[^>]*href=([\"\']??)([^\">]*?)\\1[^>]*>(.*)<\/a>';
    const CLOSE_BODY_TAG  = '</body>';
    const OPEN_TRACKER_TAG = '<p style="text-align: center;"><img src="%s" border="0" /></p>';

    private static $imageExtensions = ['jpg', 'png', 'gif', 'jpeg'];
    private static $excludeLinks = ['#', '/'];
    private static $redirectURI = '/cr?c=%d&e=[EMAIL]&u=%s';
    private static $unsubscribeURI = '/unsubscribe?e=[EMAIL]';
    private static $openTrackerURI = '/image_open/%s.op';
    private static $openTrackerVariableFormat = '%s_*%d';


    public static function prepareMessage($message, $campaignId) {
        //$message = self::replacePlaceholders($message, [Placeholder::UNSUBSCRIBE_LINK => self::getUnsubscribeLink()]);
        $links = self::findLinks($message);

        if(!empty($links) && is_array($links)) {
            foreach ($links as $link) {
                if (self::isValidLink($link)) {
                    $preparedURL = self::addTrackerToLink($link, $campaignId);
                    $message  = self::replacePlaceholders($message , [$link => $preparedURL ]);
                }
            }
        }
        return self::addOpenTrackerPixel($message);
    }

    public static function replacePlaceholders($message, array $values){
        foreach($values as $key => $value)
            $message = str_replace($key, $value, $message);
        return $message;
    }

    public static function getOpenTrackerVariableValue($email, $campaignId){
       return sprintf(self::$openTrackerVariableFormat, $email, $campaignId);
    }

    public static function getUnsubscribeLinkWithTracker($email, $campaignId){
        return self::addTrackerToLink(sprintf(URL::to(self::$unsubscribeURI), $email), $campaignId);
    }

    private static function addTrackerToLink($link, $campaignId){
        return URL::to(sprintf(self::$redirectURI, $campaignId, urlencode($link)));
    }

    private static function findLinks($html){
        preg_match_all("/".self::LINK_REGEX_EXP."/siU", $html, $matches);
        return (!is_null($matches) && is_array($matches) && isset($matches[2]))?$matches[2]:null;
    }

    private static function getExtension($link){
        $parts = explode('.', $link);
        return strtolower(end($parts));
    }

    private static function isValidLink($link){
        return (!in_array(self::getExtension($link), self::$imageExtensions) &&
            !in_array($link, self::$excludeLinks)) ? true : false;
    }

    private static function getUnsubscribeLink(){
        return URL::to(self::$unsubscribeURI);
    }

    private static function addOpenTrackerPixel($message){
        if(self::isTrackerPresent($message))
            return self::replacePlaceholders($message, [Placeholder::OPEN_TRACKER => self::getOpenTrackerTag()]);
        else
            return self::addMissingOpenTracker($message);
    }

    private static function isTrackerPresent($message){
        return (strpos($message, Placeholder::OPEN_TRACKER) !== false);
    }

    private static function getOpenTrackerTag(){
        return sprintf(self::OPEN_TRACKER_TAG, self::getOpenTrackerUrl());
    }

    private static function getOpenTrackerUrl(){
        return URL::to(sprintf(self::$openTrackerURI, Placeholder::OPEN_TRACKER_VAR));
    }

    private static function addMissingOpenTracker($message){
        $foundPosition = stripos($message, self::CLOSE_BODY_TAG);

        if ($foundPosition !== false) { /** First try add tracker just before  </body>, if it exists. */
            $actualText = substr($message, $foundPosition, strlen(self::CLOSE_BODY_TAG));
            $message = substr_replace($message,
                                        self::getOpenTrackerTag().$actualText,
                                        $foundPosition,
                                        strlen(self::CLOSE_BODY_TAG));
        }else { /** if </body> tag doesn't exist then add at end of message. **/
            $message =$message . self::getOpenTrackerTag();
        }

        return $message;
    }

} 