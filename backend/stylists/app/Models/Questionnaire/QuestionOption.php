<?php

namespace App\Models\Questionnaire;

use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'question_options';

    protected $primaryKey = 'id';
    public $timestamps=false;

}
