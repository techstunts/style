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


}
