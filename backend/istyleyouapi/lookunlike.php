<?php
include("db_config.php");
if($_SERVER['REQUEST_METHOD']=="POST" &&  !empty($_POST['userid']) && !empty($_POST['lookid'])){
	$userid=$_POST['userid'];
	$lookid=$_POST['lookid'];
	$check="Select * from users_unlike where user_id='$userid' AND look_id='$lookid'";
	$res=mysql_query($check);
	$row=mysql_num_rows($res);
	if($row==0){
	$sql="INSERT INTO users_unlike(user_id,look_id) Values('$userid','$lookid')";
	$res=mysql_query($sql);
	$query="SELECT look_unlike from createdlook where look_id='$lookid'";
	$res=mysql_query($query);
	$data=mysql_fetch_array($res);
	$looklike=$data['look_unlike'] +1;
	$update="Update createdlook SET look_unlike='$looklike' where look_id='$lookid'";
	$res=mysql_query($update);
	$data = array('result' => 'success', 'response_message' => 'look id no. '.$lookid.' unlike successfully by userid'.$userid);
	}else{
		$data = array('result' => 'fail', 'response_message' => 'look id no. '.$lookid.' already unlike by userid'.$userid);
	}
}else{
	$data = array('result' => 'fail','response_message'=>'userid or lookid empty');
}
mysql_close($conn);


/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
?>