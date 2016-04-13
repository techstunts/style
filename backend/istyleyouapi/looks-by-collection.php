<?php
include("db_config.php");
include("common.php");
include("Lookup.php");

$request_valid = false;
if($_SERVER['REQUEST_METHOD'] != "GET"){
    $response = array('result' => 'fail', 'response_message' => 'Supported GET request only');
}
else if(!isset($_REQUEST['userid']) || empty($_REQUEST['userid'])) {
    $response = array('result' => 'fail', 'response_message' => 'userid empty');
}
else if(!isset($_REQUEST['collection_id']) || empty($_REQUEST['collection_id'])) {
    $response = array('result' => 'fail', 'response_message' => 'collection_id empty');
}
else{
    $request_valid = true;
}

if ($request_valid === true) {
    $collection_id = mysql_real_escape_string($_REQUEST['collection_id']);

    $userid = mysql_real_escape_string($_REQUEST['userid']);

    $user_details_query = "SELECT user_id, gender, bodytype
							FROM userdetails
							WHERE user_id = $userid
							LIMIT 0,1";
    $user_res = mysql_query($user_details_query);
    $user_rows = mysql_num_rows($user_res);

    if ($user_rows > 0) {
        $user_data = mysql_fetch_array($user_res);
        $gender = $user_data[1];

        $looks = array();

        $page = isset($_GET['page']) && $_GET['page'] != '' ? mysql_real_escape_string($_GET['page']) : 0;

        $record_start = intval($page * 10);
        $records_count = 10;

        $gender_id = Lookup::getId('gender', $gender);

        $looks_sql =
            "Select l.id as look_id, l.description, l.image, l.price, o.name as occasion, l.name, uf.fav_id,
					sd.id as stylist_id, sd.name as stylist_name, sd.image as stylist_image
        from collections cl
        join collection_entities ce ON cl.id = ce.collection_id
        join looks l on ce.entity_id = l.id and ce.entity_type_id = 2
        join lu_occasion o on l.occasion_id = o.id
        LEFT JOIN usersfav uf ON l.id = uf.look_id and uf.user_id = '$userid'
        JOIN stylists sd on sd.id = l.stylist_id
        where cl.id = '$collection_id'
            AND l.gender_id = '$gender_id'
            AND l.id NOT IN
                (Select look_id
                from users_unlike
                where user_id='$userid')
        ORDER BY l.created_at DESC
        LIMIT $record_start, $records_count ";
        //echo $looks_sql . "<br /><br />";

        $looks_res = mysql_query($looks_sql);

        while ($data = mysql_fetch_array($looks_res)) {
            $looks[] = $data;
        }
        unset($looks_res);

        if (count($looks) > 0) {
            $looks_and_products = getLooksDetails($looks, $userid);

            $response = array('result' => 'success', 'looks_and_products' => $looks_and_products);
        } else {
            $response = array('result' => 'fail', 'response_message' => 'No records');
        }
    } else {
        $response = array('result' => 'fail', 'response_message' => 'userid doesnt exist in db');
    }
}

mysql_close($conn);

/* JSON Response */
header("Content-type: application/json");
echo json_encode($response);
?>
