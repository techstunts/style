<?php
include("db_config.php");
if($_SERVER['REQUEST_METHOD']=="POST" &&  !empty($_POST['userid']) && !empty($_POST['productid'])){
	$userid=$_POST['userid'];
	$productid=$_POST['productid'];
	$check="Select * from users_unlike where user_id='$userid' AND product_id='$productid'";
	$res=mysql_query($check);
	$row=mysql_num_rows($res);
	if($row==0){
	$sql="INSERT INTO users_unlike(user_id,product_id) Values('$userid','$productid')";
	$res=mysql_query($sql);
	$query="SELECT product_unlike from lookdescrip where id='$productid'";
	$res=mysql_query($query);
	$data=mysql_fetch_array($res);
	$productunlike=$data['product_unlike'] +1;
	$update="Update lookdescrip SET product_unlike='$productunlike' where id='$productid'";
	$res=mysql_query($update);
	$data = array('result' => 'success', 'response_message' => 'Product id no. '.$productid.' unlike successfully by userid'.$userid);
	}else{
		$data = array('result' => 'fail', 'response_message' => 'Product id no. '.$productid.' already unlike by userid'.$userid);
	}
}else{
	$data = array('result' => 'fail','response_message'=>'userid or productid empty');
}
mysql_close($conn);


/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
?>