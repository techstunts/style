<?php
include("db_config.php");
include("common.php");
include("Lookup.php");

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_REQUEST['userid']) && !empty($_REQUEST['userid'])) {
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
        $body_type = $user_data[2];
        $body_type_id = Lookup::getId('body_type', $body_type);
        $body_type_condition = $gender == 'female' ? " AND cl.body_type_id = '{$body_type_id}'" : ""; //$gender_id=1 for 'female'
        $body_type_condition = "";//5-Jan-2015 : Temporarily commented out this condition as we do not have much looks in all body types.

        //Get 4 latest looks for 4 occasions which are not unliked by current user
        $latest_looks = array();

        $page = isset($_GET['page']) && $_GET['page'] != '' ? mysql_real_escape_string($_GET['page']) : 0;
        $occasions = array('Wine & Dine', 'Casuals', 'Ethnic/Festive', 'Work Wear');
        if (isset($_GET['occasion']) && $_GET['occasion'] != '' && strtolower($_GET['occasion']) != 'all') {
            $occasions = array_intersect($occasions, array(mysql_real_escape_string($_GET['occasion'])));
            $records_count = 16;
        }
        else{
            $records_count = 4;
        }

        $record_start = intval($page * $records_count);
        $gender_id = !empty($user_data[3]) ? $user_data[3] : Lookup::getId('gender', $gender);
        $look_ids = array();

        foreach ($occasions as $occasion) {
            $occasion_id = Lookup::getId('occasion', $occasion);
            $latest_looks_sql =
                "Select cl.id as look_id, cl.description, cl.image, o.name as occasion, cl.name, uf.fav_id, s.id as stylist_id,
                  s.name as stylist_name, s.image as stylist_image, s.icon as stylist_icon, cl.is_collage, cl.updated_at
			from looks cl
		  	join lu_occasion o on cl.occasion_id = o.id
			LEFT JOIN usersfav uf ON cl.id = uf.look_id and uf.user_id = '$userid'
			JOIN stylists s ON s.id = cl.stylist_id
			where cl.gender_id = '$gender_id'
				$body_type_condition
				AND cl.occasion_id = '$occasion_id'
				AND cl.status_id = 1
				AND cl.id NOT IN
					(Select look_id
					from users_unlike
					where user_id='$userid')
			ORDER BY cl.updated_at DESC
			LIMIT $record_start, $records_count ";
            //echo $latest_looks_sql . "<br /><br />";

            $latest_looks_res = mysql_query($latest_looks_sql);

            while ($data = mysql_fetch_array($latest_looks_res)) {
                $latest_looks[$data[11]] = $data;
            }
            unset($latest_looks_res);
        }

        foreach ($latest_looks as $look) {
            $look_ids[] = $look['look_id'];
        }
        $prices = getINRPrice(2, $look_ids);

        foreach ($latest_looks as $look) {
            $latest_looks[$look['updated_at']]['price'] = !empty($prices[$look['look_id']]) ? $prices[$look['look_id']] : 0;
        }

        if (count($latest_looks) > 0) {
            krsort($latest_looks);
            $looks_and_products = getLooksDetails(array_values($latest_looks), $userid);

            $response = array('result' => 'success', 'latest_looks' => $looks_and_products);
        } else {
            $response = array('result' => 'fail', 'response_message' => 'No records');
        }
    } else {
        $response = array('result' => 'fail', 'response_message' => 'userid doesnt exist in db');
    }
} else {
    $response = array('result' => 'fail', 'response_message' => 'userid empty');
}

mysql_close($conn);

/* JSON Response */
header("Content-type: application/json");
echo json_encode($response);
?>
