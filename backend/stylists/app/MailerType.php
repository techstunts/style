<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailerType extends Model
{
    const CAMPAIGN_MAILER_TYPE_ID = 1;
    const TABLE_NAME = 'mailer_types';

    protected $table = self::TABLE_NAME;

}
