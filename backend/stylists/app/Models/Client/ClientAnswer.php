<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class ClientAnswer extends Model
{
    protected $table = 'isy_client_answer';
    public function question ()
    {
        return $this->belongsTo('App\Models\Questionnaire\Question', 'question_id');
    }
    public function option ()
    {
        return $this->belongsTo('App\Models\Questionnaire\QuestionOption', 'option_id');
    }
}
