<?php
include("db_config.php");
if($_SERVER['REQUEST_METHOD']=="POST" &&  !empty($_POST['userid']) && !empty($_POST['lookid'])){
	$userid=$_POST['userid'];
	$lookid=$_POST['lookid'];
	$query="SELECT * from looks where look_id='$lookid'";
	$res=mysql_query($query);
	$row=mysql_num_rows($res);
	if($row==1){
			$check="Select * from usersfav where user_id='$userid' AND look_id='$lookid'";
			$res=mysql_query($check);
			$row=mysql_num_rows($res);
			if($row==0){
			$sql="INSERT INTO usersfav(user_id,look_id) Values('$userid','$lookid')";
			$res=mysql_query($sql);
			$query="SELECT look_fav from looks where look_id='$lookid'";
			$res=mysql_query($query);
			$data=mysql_fetch_array($res);
			$lookfav=$data['look_fav'] + 1;
			$update="Update looks SET look_fav='$lookfav' where look_id='$lookid'";
			$res=mysql_query($update);
			$data = array('result' => 'success', 'response_message' => 'Look id no. '.$lookid.' fav successfully by userid'.$userid);
			}else{
				$sql="Delete From usersfav where user_id='$userid' AND look_id='$lookid'";
				$res=mysql_query($sql);
				$query="SELECT look_fav from looks where look_id='$lookid'";
				$res=mysql_query($query);
				$data=mysql_fetch_array($res);
				$lookfav=$data['look_fav'] - 1;
				$update="Update looks SET look_fav='$lookfav' where look_id='$lookid'";
				$res=mysql_query($update);
				$data = array('result' => 'success', 'response_message' => 'Look id no. '.$lookid.' Unfav successfully by userid'.$userid);
			}
	}else{
		$data = array('result' => 'fail','response_message'=>'Look ID does not exist');
	}
}else{
	$data = array('result' => 'fail','response_message'=>'userid or lookid empty');
}
mysql_close($conn);


/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
?>