<?php
include("db_config.php");
if($_SERVER['REQUEST_METHOD']=="POST" && !empty($_REQUEST['userid']) && !empty($_REQUEST['occasion']) && isset($_REQUEST['occasion']) && !empty($_REQUEST['budget']) && !empty($_REQUEST['datetime'])){
	$userid=$_REQUEST['userid'];
	$occasion=$_REQUEST['occasion'];
	$budget=$_REQUEST['budget'];
	$date=$_REQUEST['datetime'];
	$asklookimage="";
	if(!empty($_FILES['askimage']['name'])){
	$ext=explode('.',$_FILES['askimage']['name']);
	$ext=$ext[count($ext)-1];
	$fname=rand(000000000,999999999).'_'.rand(000000000,999999999).'.'.$ext;

	if(move_uploaded_file($_FILES['askimage']['tmp_name'],'asklookimage/'.$fname))
		{
			$asklookimage=$fname;
		}
			 
	else{
			$asklookimage="";
 		}
 	}

 		$sql="INSERT INTO asklook(user_id,occasion,budget,asklookimage,datetime) VALUES('$userid','$occasion','$budget','$asklookimage','$date') ";
 		mysql_query($sql);
 		$data = array('result' => 'success', 'message' => 'Ask Look successfully');
}elseif($_SERVER['REQUEST_METHOD']=="POST" && !empty($_REQUEST['userid']) && !empty($_REQUEST['occasion']) && !empty($_REQUEST['datetime'])){
	$userid=$_REQUEST['userid'];
	$occasion=$_REQUEST['occasion'];
	$budget="";
	$date=$_REQUEST['datetime'];
	$asklookimage="";
	$sql="INSERT INTO asklook(user_id,occasion,budget,asklookimage,datetime) VALUES('$userid','$occasion','$budget','$asklookimage','$date') ";
 	mysql_query($sql);
 	$data = array('result' => 'success', 'message' => 'Ask Look successfully');
}else{
	$data = array('result' => 'fail', 'message' => 'Some parameter missing');
			
}
mysql_close($conn);


/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
?>