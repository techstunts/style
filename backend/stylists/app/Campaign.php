<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use URL;
use App\Campaign\Utils\CampaignUtils;

class Campaign extends Model
{
    /** Campaign states  */
    const CREATED_STATE = 'CREATED';
    const PUBLISHED_STATE = 'PUBLISHED';
    const QUEUING_STATE = 'QUEUING';
    const QUEUED_STATE = 'QUEUED';
    const DB_DATE_FORMAT = "Y-m-d H:i:s";


    protected $table = 'isy_campaigns';



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
        $this->updateTimestamps();
        $this->save();
    }

    public function queuing()
    {
        $this->status = self::QUEUING_STATE;
        $this->prepared_message = CampaignUtils::prepareMessage($this->message, $this->id);
        $this->updateTimestamps();
        $this->save();
    }

    public function queued()
    {
        $this->status = self::QUEUED_STATE;
        $this->updateTimestamps();
        $this->save();
    }


}
