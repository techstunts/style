<?php
include("db_config.php");
include("Lookup.php");
if ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_REQUEST['userid']) )
{
    $occasion = '';
    if(isset($_REQUEST['occasion']) && !empty($_REQUEST['occasion'])){
        $occasion = Lookup::getId('occasion', $_REQUEST['occasion']);

        $app_occasions['Casuals'] = 1;
        $app_occasions['Ethnic'] = 3;
        $app_occasions['Formals'] = 4;
        $app_occasions['Wine '] = 5;

        if($occasion == ''){
            if(isset($app_occasions[$_REQUEST['occasion']])){
                $occasion = $app_occasions[$_REQUEST['occasion']];
            }
        }
    }

    $budget = '';
    if(isset($_REQUEST['occasion']) && !empty($_REQUEST['budget'])){
        $budget = Lookup::getId('budget', $_REQUEST['budget']);

        $app_budgets['Low'] = 1;
        $app_budgets['Medium'] = 2;
        $app_budgets['High'] = 3;
        $app_budgets['Premium'] = 4;

        if($budget == ''){
            if(isset($app_budgets[$_REQUEST['budget']])){
                $budget = $app_budgets[$_REQUEST['budget']];
            }
        }
    }

    if(!isset($entity_type)){
        $entity_type = 0;
        if(isset($_REQUEST['style_request_type']) && !empty($_REQUEST['style_request_type'])){
            $entity_type = Lookup::getId('entity_type', $_REQUEST['style_request_type']);
        }
    }

    $description = '';
    if(isset($_POST['description']) && !empty($_POST['description'])){
        $description = mysql_real_escape_string(trim($_POST['description']));
    }

    if(($entity_type != 0 && $occasion == '' && $budget == '') || ($entity_type == 0 && $description == '')){
        $data = array('result' => 'fail', 'message' => 'Some parameter missing');
    }
    else {
        $userid = $_REQUEST['userid'];
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

        $sql = "INSERT INTO style_requests(user_id, occasion_id, budget_id, entity_type_id, image, description, created_at)
                  VALUES('$userid','$occasion','$budget','$entity_type','$asklookimage','$description','$date') ";
        mysql_query($sql);
        $data = array('result' => 'success', 'message' => 'Ask successful');
    }

} else {
    $data = array('result' => 'fail', 'message' => 'Some parameter missing');

}
mysql_close($conn);


/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
?>