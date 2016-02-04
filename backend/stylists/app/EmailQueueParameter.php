<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmailQueueParameter extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'email_queue_parameter';

}
