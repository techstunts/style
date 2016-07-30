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
    const VAR_SEPARATOR = '_*';

    private static $imageExtensions = ['jpg', 'png', 'gif', 'jpeg'];
    private static $excludeLinks = ['#', '/', Placeholder::UNSUBSCRIBE_LINK];
    private static $redirectURI = '/cr?c=%d&e=%s&u=%s';
    private static $unsubscribeURI = '/unsubscribe?e=%s';
    private static $openTrackerURI = '/image-open?tid=%s';

    public static function prepareMessage($message, $campaignId)
    {
        $links = self::findLinks($message);
        if(!empty($links) && is_array($links))
        {
            foreach ($links as $link)
            {
                if (self::isValidLink($link))
                {
                    $preparedURL = URL::to(sprintf(self::$redirectURI, $campaignId, Placeholder::EMAIL, base64_encode($link)));
                    $message  = self::replacePlaceholders($message , [$link => $preparedURL ]);
                }
            }
        }
        return self::addOpenTrackerPixel($message);
    }

    public static function replacePlaceholders($message, array $values)
    {
        foreach($values as $key => $value) $message = str_replace($key, $value, $message);
        return $message;
    }

    public static function getOpenTrackerVariableValue($email, $campaignId)
    {
       return base64_encode($email.self::VAR_SEPARATOR.$campaignId);
    }

    public static function getUnsubscribeLinkWithTracker($email, $campaignId)
    {
        return URL::to(sprintf(self::$redirectURI, $campaignId, $email, base64_encode(sprintf(URL::to(self::$unsubscribeURI), $email))));
    }

    public static function removeNonASCICharacter($string)
    {
        return preg_replace('/[^(\x20-\x7F)]*/','', $string);
    }

    public static function isUnsubcribeLink($link)
    {
        return (strpos($link, '/unsubscribe?e=') !== false)?true:false;
    }

    public static function getOpenTrackerData($trackerString)
    {
        $trackerValues = explode(self::VAR_SEPARATOR, $trackerString);
        $email = isset($trackerValues[0])?$trackerValues[0]:null;
        $campaignId = isset($trackerValues[1])?$trackerValues[1]:null;
        return compact('email', 'campaignId');
    }

    private static function findLinks($html)
    {
        preg_match_all("/".self::LINK_REGEX_EXP."/siU", $html, $matches);
        return (!is_null($matches) && is_array($matches) && isset($matches[2]))?$matches[2]:null;
    }

    private static function getExtension($link)
    {
        $parts = explode('.', $link);
        return strtolower(end($parts));
    }

    private static function isValidLink($link)
    {

        return (!in_array(self::getExtension($link), self::$imageExtensions) && !in_array($link, self::$excludeLinks) ) ? true : false;
    }

    private static function addOpenTrackerPixel($message)
    {
        if(self::isTrackerPresent($message))
            return self::replacePlaceholders($message, [Placeholder::OPEN_TRACKER => self::getOpenTrackerTag()]);
        else
            return self::addMissingOpenTracker($message);
    }

    private static function isTrackerPresent($message)
    {
        return (strpos($message, Placeholder::OPEN_TRACKER) !== false)?true:false;
    }

    private static function getOpenTrackerTag()
    {
        return sprintf(self::OPEN_TRACKER_TAG, self::getOpenTrackerUrl());
    }

    private static function getOpenTrackerUrl()
    {
        return URL::to(sprintf(self::$openTrackerURI, Placeholder::OPEN_TRACKER_VAR));
    }

    private static function addMissingOpenTracker($message)
    {
        $foundPosition = stripos($message, self::CLOSE_BODY_TAG);

        /** First try add tracker just before  </body>, if it exists. */
        if ($foundPosition !== false)
        {
            $actualText = substr($message, $foundPosition, strlen(self::CLOSE_BODY_TAG));
            $message = substr_replace($message,
                                        self::getOpenTrackerTag().$actualText,
                                        $foundPosition,
                                        strlen(self::CLOSE_BODY_TAG));

        /** if </body> tag doesn't exist then add at end of message. **/
        } else
        {
            $message =$message . self::getOpenTrackerTag();
        }
        return $message;
    }

} 