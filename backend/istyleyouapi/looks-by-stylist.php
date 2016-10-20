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
else if(!isset($_REQUEST['stylist_id']) || empty($_REQUEST['stylist_id'])) {
    $response = array('result' => 'fail', 'response_message' => 'stylist_id empty');
}
else{
    $request_valid = true;
}

if ($request_valid === true) {
    $stylist_id = mysql_real_escape_string($_REQUEST['stylist_id']);

    $userid = mysql_real_escape_string($_REQUEST['userid']);

    $user_details_query = "SELECT id, gender, bodytype, gender_id
							FROM clients
							WHERE id = $userid
							LIMIT 0,1";
    $user_res = mysql_query($user_details_query);
    $user_rows = mysql_num_rows($user_res);

    if ($user_rows > 0) {
        $user_data = mysql_fetch_array($user_res);
        $gender = $user_data[1];

        $gender_id = !empty($user_data[3]) ? $user_data[3] : Lookup::getId('gender', $gender);

        $looks = array();

        $page = isset($_GET['page']) && $_GET['page'] != '' ? mysql_real_escape_string($_GET['page']) : 0;

        $records_per_page = 10;
        $record_start = intval($page * $records_per_page);

        $looks_sql =
            "Select l.id as look_id, l.description, concat('uploads/images/looks/', l.image) as image, l.price, o.name as occasion, l.name, uf.fav_id
        from looks l
        join lu_occasion o on l.occasion_id = o.id
        LEFT JOIN usersfav uf ON l.id = uf.look_id and uf.user_id = '$userid'
        where l.stylist_id = '$stylist_id'
            AND l.gender_id = '$gender_id'
            AND l.status_id = 1
            AND l.id NOT IN
                (Select look_id
                from users_unlike
                where user_id='$userid')
        ORDER BY l.created_at DESC
        LIMIT $record_start, $records_per_page ";
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
