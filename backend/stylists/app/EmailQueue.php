<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmailQueue extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'email_queue';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = true;

    public function emailTemplate(){
        return $this->belongsTo('App\EmailTemplate', 'email_template_id');
    }

    public function parameters(){
        return $this->hasMany('App\EmailQueueParameter', 'email_queue_id');
    }

}
