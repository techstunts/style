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

    const LINK_REGEX_EXP = "<a\s[^>]*href=([\"\']??)([^\">]*?)\\1[^>]*>(.*)<\/a>";

    private static $imageExtensions = ['jpg', 'png', 'gif', 'jpeg'];
    private static $excludeLinks = ['#', '/'];
    private static $redirectURI = "/cr?c=%d&e=[EMAIL]&u=%s";
    private static $unsubscribeURI = "/unsubscribe?e=[EMAIL]";

    public static function prepareMessage($message, $campaignId) {
        $message = self::replacePlaceholders($message, [Placeholder::UNSUBSCRIBE_LINK => self::getUnsubscribeLink()]);
        $links = self::findLinks($message);

        if(!empty($links) && is_array($links)) {
            foreach ($links as $link) {
                if (self::isValidLink($link)) {
                    $preparedURL = URL::to(sprintf(self::$redirectURI, $campaignId, urlencode($link)));
                    $message  = str_replace($link, $preparedURL, $message);
                }
            }
        }

        return $message;
    }

    public static function replacePlaceholders($message, array $values){
        foreach($values as $key => $value)
            $message = str_replace($key, $value, $message);
        return $message;
    }

    private static function findLinks($html){
        preg_match_all("/".self::LINK_REGEX_EXP."/siU", $html, $matches);
        return $matches[2];
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

    private static function addTrackerPixel($message){
        if(self::isTrackerPresent($message))
            return $message;

        if()

    }

    private static function isTrackerPresent($message){
        return true;
    }



} 