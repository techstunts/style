<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MailerMasterRepository extends Model
{

    const TABLE_NAME = 'mailer_master_list';
    const FETCH_LIMIT = 1;

    protected $table = self::TABLE_NAME;

    public static function getUsers($start){

        /**
         * SELECT * FROM mailer_master_list as m left join unsubscriptions as u on
         * m.email=u.email and mailer_type_id=1 where mailer_type_id is null
         */
        $query = self::getUserQuery()
                    -> select(self::TABLE_NAME.".email", self::TABLE_NAME.".name")
                    ->skip($start)
                    ->take(self::FETCH_LIMIT);

        self::queryLogger($query);
        return $query->get();
    }



    public static function getUsersCount(){
        $query = self::getUserQuery()
                    -> select(DB::raw('count(*) as user_count'));

        self::queryLogger($query);
        return $query->count();
    }

    public static  function queryLogger($queryBuilder) {
        $query = $queryBuilder->toSql();
        $bindings = $queryBuilder->getBindings();

        if (!empty($bindings) && is_array($bindings)){
            foreach ($bindings as $i => $binding) {
                if ($binding instanceof \DateTime) {
                    $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                } else if (is_string($binding)) {
                    $bindings[$i] = "'$binding'";
                }
            }
            $query = str_replace(array('%', '?'), array('%%', '%s'), $query);
            $query = vsprintf($query, $bindings);
        }
        echo "\n\n---------------------------------\n$query\n--------------------------------\n\n";
    }

    private static function getUserQuery(){
        return DB::table(self::TABLE_NAME)
            ->leftJoin(Unsubscription::TABLE_NAME, function ($join) {
                $join->on(self::TABLE_NAME.'.email', '=', Unsubscription::TABLE_NAME.".email")
                    ->where(Unsubscription::TABLE_NAME.'.mailer_type_id', '=', MailerType::CAMPAIGN_MAILER_TYPE_ID);
            })
            ->whereNull(Unsubscription::TABLE_NAME.'.mailer_type_id');
    }
}
