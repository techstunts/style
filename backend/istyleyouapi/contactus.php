<?php
include("db_config.php");
if($_SERVER['REQUEST_METHOD']=="POST" &&  isset($_REQUEST['userid']) && !empty($_REQUEST['userid']) && isset($_REQUEST['username']) && !empty($_REQUEST['username']) && !empty($_REQUEST['email']) && isset($_REQUEST['email']) && isset($_REQUEST['message']) && !empty($_REQUEST['message'])){
		$userid=$_REQUEST['userid'];
		$username=$_REQUEST['username'];
		$email=$_REQUEST['email'];
		$message=$_REQUEST['message'];	 
		$query="SELECT * from userdetails where user_id='$userid' AND user_email='$email'";
		$res=mysql_query($query);
		$row=mysql_num_rows($res);
		if($row==1){
						$data = array('result' => 'success', 'response_message' => 'Message Send Successfully');	
						}
						else{
						$data = array('result' => 'fail', 'response_message' => 'Given userid/email is not registered in our record');	
						}	
}else{
	$data = array('result' => 'fail', 'response_message' => 'you have not added your complete details!');
}
mysql_close($conn);
/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
?>