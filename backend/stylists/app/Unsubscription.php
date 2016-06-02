<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unsubscription extends Model
{
    const TABLE_NAME = 'unsubscriptions';

    protected $table = self::TABLE_NAME;
}
