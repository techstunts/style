<?php
include("db_config.php");
include("Lookup.php");
if ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_REQUEST['userid']) &&
        !empty($_REQUEST['occasion']) && isset($_REQUEST['occasion']) && !empty($_REQUEST['budget'])) {
    $userid = $_REQUEST['userid'];
    $occasion = Lookup::getId('occasion', $_REQUEST['occasion']);
    $budget = Lookup::getId('budget', $_REQUEST['budget']);
    $date = date('Y-m-d H:i:s');
    $asklookimage = "";
    if (!empty($_FILES['askimage']['name'])) {
        $ext = explode('.', $_FILES['askimage']['name']);
        $ext = $ext[count($ext) - 1];
        $fname = rand(000000000, 999999999) . '_' . rand(000000000, 999999999) . '.' . $ext;

        if (move_uploaded_file($_FILES['askimage']['tmp_name'], 'asklookimage/' . $fname)) {
            $asklookimage = $fname;
        } else {
            $asklookimage = "";
        }
    }

    $sql = "INSERT INTO style_requests(user_id, occasion_id, budget_id, image, created_at)
			  VALUES('$userid','$occasion','$budget','$asklookimage','$date') ";
    mysql_query($sql);
    $data = array('result' => 'success', 'message' => 'Ask Look successfully');
} elseif ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_REQUEST['userid']) && !empty($_REQUEST['occasion']) ) {
    $userid = $_REQUEST['userid'];
    $occasion = Lookup::getId('occasion', $_REQUEST['occasion']);
    $budget = "";
    $date = date('Y-m-d H:i:s');
    $asklookimage = "";
    $sql = "INSERT INTO style_requests(user_id, occasion_id, budget_id, image, created_at)
		  VALUES('$userid','$occasion','$budget','$asklookimage','$date') ";
    mysql_query($sql);
    $data = array('result' => 'success', 'message' => 'Ask Look successfully');
} else {
    $data = array('result' => 'fail', 'message' => 'Some parameter missing');

}
mysql_close($conn);


/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
?>