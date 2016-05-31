<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    /** Campaign states  */
    const CREATED_STATE = 'CREATED';
    const PUBLISHED_STATE = 'PUBLISHED';
    const QUEUING_STATE = 'QUEUING';
    const QUEUED_STATE = 'QUEUED';

    protected $table = 'campaigns';

    public function isEditable(){
        $editableState = [self::CREATED_STATE];
        if(in_array($this->status, $editableState))
            return true;
        else
            return false;
    }

}
