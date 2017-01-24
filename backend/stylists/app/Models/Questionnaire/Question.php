<?php

namespace App\Models\Questionnaire;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'questions';

    protected $primaryKey = 'id';
    public $timestamps=false;
}
