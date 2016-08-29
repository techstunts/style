<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unsubscription extends Model
{
    const REASON_NOT_RELEVANT =  'Your emails are not relevant to me';
    const REASON_TOO_FREQUENT = 'Your emails are too frequent';
    const REASON_NOT_SIGNUP = 'I don\'t remember signing up for this';
    const REASON_NOT_WANT_MORE = 'I no longer want to receive these emails';
    const REASON_SPAM = 'The emails are spam and should be reported';
    const REASON_OTHER = 'Others';

    const TABLE_NAME = 'unsubscriptions';

    protected $table = self::TABLE_NAME;
    protected $fillable = ['email', 'reason', 'mailer_type_id'];

    public static function getReasons(){
        return [self::REASON_NOT_RELEVANT, self::REASON_TOO_FREQUENT,
                self::REASON_NOT_SIGNUP, self::REASON_NOT_WANT_MORE,
                self::REASON_SPAM, self::REASON_OTHER];
    }
}
