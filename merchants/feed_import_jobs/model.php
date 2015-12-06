<?php
function terminate($msg){
    echo "\n" . $msg;
    die;
}

function debug_message($msg, $type = __PROGRESS__){
    global $debug_on;
    if($type != __DEBUG__ || $debug_on == true)
        echo "\n" . $msg;
}

function db_connect($params){
    global $db_conn;
    $db_conn = mysql_connect($params['host'], $params['username'], $params['password']);

    if(! $db_conn )
    {
        die('Could not connect: ' . mysql_error());
    }

    mysql_select_db($params['database']);
}

function execute_query($sql){
    global $db_conn;
    $retval = mysql_query( $sql, $db_conn );

    if(! $retval )
    {
        die("\nCould not execute query - $sql \n" . mysql_error());
    }

    return $retval;
}

function last_insert_id(){
    global $db_conn;
    return mysql_insert_id($db_conn);
}
