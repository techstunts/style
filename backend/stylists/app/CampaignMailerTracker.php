<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignMailerTracker extends Model
{
    const TABLE_NAME = 'campaign_mailer_trackers';

    protected $table = self::TABLE_NAME;
}