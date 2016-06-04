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
    const OPEN_TRACKER_VAR = "{{open_tracker_var}}";

    public static function getHolder(){
        return [self::EMAIL, self::USER_NAME, self::UNSUBSCRIBE_LINK];
    }

} 