<?php
include("db_config.php");
include("Lookup.php");

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_REQUEST['userid']) && !empty($_REQUEST['userid'])) {
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
        $gender_id = Lookup::getId('gender', $gender);

        $page = isset($_GET['page']) && $_GET['page'] != '' ? mysql_real_escape_string($_GET['page']) : 0;

        $record_start = intval($page * 10);
        $records_count = 10;

        $collections = array();

        $collections_sql =
                "Select cl.id, cl.name, cl.description, cl.image
			from collections cl
			where cl.gender_id = '$gender_id'
				AND cl.status_id = 1
			ORDER BY cl.created_at DESC
			LIMIT $record_start, $records_count ";
            //echo $collections_sql . "<br /><br />";

        $collections_res = mysql_query($collections_sql);

        while ($data = mysql_fetch_array($collections_res)) {
            $collections[] = $data;
        }
        unset($collections_res);

        $collections_count = count($collections);

        if ($collections_count > 0) {

            for ($i = 0; $i < $collections_count; $i++) {

                $current_collection =
                    array(
                        'lookdetails' =>
                            array(
                                'id' => $collections[$i][0],
                                'name' => mb_convert_encoding($collections[$i][1], "UTF-8", "Windows-1252"),
                                'description' => mb_convert_encoding($collections[$i][2], "UTF-8", "Windows-1252"),
                                'image' => "http://istyleyou.in/backend/images/" . $collections[$i][3],
                            )
                    );
                $trending_collections[] = $current_collection;
                unset($current_collection);
            }

            $response = array('result' => 'success', 'trending_collections' => $trending_collections);
        } else {
            $response = array('result' => 'fail', 'response_message' => 'No records');
        }
    } else {
        $response = array('result' => 'fail', 'response_message' => 'userid doesnt exist in db');
    }
} else {
    $response = array('result' => 'fail', 'response_message' => 'userid empty');
}

//var_dump($response);
//var_dump($response['latest_looks'][0]['lookdetails']);

mysql_close($conn);

/* JSON Response */
header("Content-type: application/json");
echo json_encode($response);
?>
