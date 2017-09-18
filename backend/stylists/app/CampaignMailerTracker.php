<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignMailerTracker extends Model
{
    const TABLE_NAME = 'isy_campaign_mailer_trackers';
    const OPEN_EVENT = "OPENED";
    const CLICK_EVENT = "CLICKED";

    protected $table = self::TABLE_NAME;
    protected $fillable = ['campaign_id', 'email', 'url', 'event'];

}

