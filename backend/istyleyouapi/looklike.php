<?php
include("db_config.php");
if($_SERVER['REQUEST_METHOD']=="POST" &&  !empty($_POST['userid']) && !empty($_POST['lookid'])){
	$userid=$_POST['userid'];
	$lookid=$_POST['lookid'];
	$sql="SELECT * from userslike where user_id='$userid' AND look_id='$lookid'";
	$res=mysql_query($sql);
	$row=mysql_num_rows($res);
	if($row==0){
	$sql="INSERT INTO userslike(user_id,look_id) Values('$userid','$lookid')";
	$res=mysql_query($sql);
	$query="SELECT look_like from looks where looks.id='$lookid'";
	$res=mysql_query($query);
	$data=mysql_fetch_array($res);
	$looklike=$data['look_like'] +1;
	$update="Update looks SET look_like='$looklike' where looks.id='$lookid'";
	$res=mysql_query($update);

	$data = array('result' => 'success', 'message' => 'look id no. '.$lookid.' like successfully by '.$userid);
	}else{
		$data = array('result' => 'fail', 'message' => 'look id no. '.$lookid.' already like by '.$userid);
	}
}else{
	$data = array('result' => 'fail','Message'=>'userid or lookid wrong');
}
mysql_close($conn);


/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
?>