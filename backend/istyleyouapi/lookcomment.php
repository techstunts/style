<?php
include("db_config.php");
if($_SERVER['REQUEST_METHOD']=="POST" &&  !empty($_POST['userid']) && !empty($_POST['lookid']) && !empty($_POST['lookcomment'])){
	$userid=$_POST['userid'];
	$lookid=$_POST['lookid'];
	$lookcomment=$_POST['lookcomment'];
	$sql="INSERT INTO users_comments(user_id,look_id,look_comment) Values('$userid','$lookid','$lookcomment')";
	$res=mysql_query($sql);
	$query="SELECT look_comment from looks where look_id='$lookid'";
	$res=mysql_query($query);
	$data=mysql_fetch_array($res);
	$totallookcomment=$data['look_comment'] +1;
	$update="Update looks SET look_comment='$totallookcomment' where look_id='$lookid'";
	$res=mysql_query($update);
	$data = array('result' => 'success', 'message' => 'User ID no. '.$userid.' commented on look ID no '.$lookid);
	
}else{
	$data = array('result' => 'fail','Message'=>'userid/lookid/comment cannot be empty');
}
mysql_close($conn);


/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
?>