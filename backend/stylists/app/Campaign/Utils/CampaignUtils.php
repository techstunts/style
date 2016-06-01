<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 01/06/16
 * Time: 10:35 PM
 */

namespace App\Campaign\Utils;
use URL;

class CampaignUtils {

    const LINK_REGEX_EXP = "<a\s[^>]*href=([\"\']??)([^\">]*?)\\1[^>]*>(.*)<\/a>";

    private static $imageExtensions = ['jpg', 'png', 'gif', 'jpeg'];
    private static $excludeLinks = ['#', '/'];
    private static $redirectURI = "/campaign/redirect?c=%d&e=[EMAIL]&u=%s";

    public static function findLinks($html){
        preg_match_all("/".self::LINK_REGEX_EXP."/siU", $html, $matches);
        return $matches[2];
    }

    public static function getExtension($link){
        $parts = explode('.', $link);
        return strtolower(end($parts));
    }

    public static function prepareMessage($message, $campaignId) {
        $links = self::findLinks($message);
        if(!empty($links) && is_array($links)) {
            foreach ($links as $link) {
                if (self::isValidLink($link)) {
                    $preparedURL = URL::to(sprintf(self::$redirectURI, $campaignId, urlencode($link)));
                    $message = str_replace($link, $preparedURL, $message);
                }
            }
        }
        return $message;
    }

    private static function isValidLink($link){
        return (!in_array(self::getExtension($link), self::$imageExtensions) &&
            !in_array($link, self::$excludeLinks)) ? true : false;
    }

} 