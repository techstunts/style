<?php
include("db_config.php");
if($_SERVER['REQUEST_METHOD']=="POST" && !empty($_REQUEST['userid']) && !empty($_REQUEST['occasion']) && !empty($_REQUEST['category']) && isset($_REQUEST['occasion']) && !empty($_REQUEST['budget']) && !empty($_REQUEST['datetime'])){
	$userid=$_REQUEST['userid'];
	$occasion=$_REQUEST['occasion'];
        $category=$_REQUEST['category'];
	$budget=$_REQUEST['budget'];
	$date=$_REQUEST['datetime'];
	

 		$sql="INSERT INTO askproduct(user_id,occasion,category,budget,datetime) VALUES('$userid','$occasion','$category','$budget','$date') ";
 		mysql_query($sql);

 		$data = array('result' => 'success', 'message' => 'Ask Product successfully');
}else{
	$data = array('result' => 'fail', 'message' => 'Some parameter missing');
			
}
mysql_close($conn);


/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
?>