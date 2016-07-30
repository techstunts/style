<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CampaignMailerRepository extends Model
{
    const TABLE_NAME = 'campaign_mailer_list';

    protected $table = self::TABLE_NAME;
    protected $fillable = ['email', 'name', 'campaign_id'];


}
