<?php

/**
 * <p>
 * Blocked Stylist Validator based on status_id
 * in database
 *</p>
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 29/03/16
 * Time: 12:41 PM
 */
namespace App\Models\Validator;

use App\Stylist;

class BlockedStylistsValidator {

    const BLOCKED_STATUS_ID = 4;

    public function validate($attribute, $value, $parameters, $validator){
        $isBlocked = Stylist::where('email', $value)->where('status_id', static::BLOCKED_STATUS_ID)->first();
        return is_null($isBlocked);
    }
}