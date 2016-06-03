<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 03/06/16
 * Time: 8:18 PM
 */

namespace App\Campaign\Entities\Enums;

class Placeholder {

    const USER_NAME = "{{user_name}}";
    const EMAIL = "{{email}}";
    const UNSUBSCRIBE_LINK = "{{unsubscribe_link}}";
    const OPEN_TRACKER = "{{open_tracker}}";

    public static function getHolder(){
        return [self::EMAIL, self::USER_NAME, self::UNSUBSCRIBE_LINK];
    }

} 