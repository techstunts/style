<?php
include("db_config.php");
if($_SERVER['REQUEST_METHOD']=="POST" &&  !empty($_POST['userid']) && !empty($_POST['productid'])){
	$userid=$_POST['userid'];
	$productid=$_POST['productid'];
	$sql="SELECT * from userslike where user_id='$userid' AND product_id='$productid'";
	$res=mysql_query($sql);
	$row=mysql_num_rows($res);
	if($row==0){
	$sql="INSERT INTO userslike(user_id,product_id) Values('$userid','$productid')";
	$res=mysql_query($sql);
	$query="SELECT product_like from products where id='$productid'";
	$res=mysql_query($query);
	$data=mysql_fetch_array($res);
	$productlike=$data['product_like'] +1;
	$update="Update products SET product_like='$productlike' where id='$productid'";
	$res=mysql_query($update);

	$data = array('result' => 'success', 'message' => 'product id no. '.$productid.' like successfully by '.$userid);
	}else{
		$data = array('result' => 'fail', 'message' => 'product id no. '.$productid.' already like by '.$userid);
	}
}else{
	$data = array('result' => 'fail','Message'=>'userid or productid wrong');
}
mysql_close($conn);


/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
?>