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
    $db_conn = mysqli_connect($params['host'], $params['username'], $params['password']);

    if(! $db_conn )
    {
        die('Could not connect: ' . mysqli_error());
    }

    mysqli_select_db($db_conn, $params['database']);
}

function execute_query($sql){
    global $db_conn;
    $retval = mysqli_query($db_conn,$sql );

    if(! $retval )
    {
        die("\nCould not execute query - $sql \n" . mysqli_error());
    }

    return $retval;
}

function last_insert_id(){
    global $db_conn;
    return mysqli_insert_id($db_conn);
}
