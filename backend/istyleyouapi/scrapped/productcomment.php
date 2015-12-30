<?php
include("db_config.php");
if($_SERVER['REQUEST_METHOD']=="POST" &&  !empty($_POST['userid']) && !empty($_POST['productid']) && !empty($_POST['productcomment'])){
	$userid=$_POST['userid'];
	$productid=$_POST['productid'];
	$productcomment=$_POST['productcomment'];
	$sql="INSERT INTO users_comments(user_id,product_id,product_comment) Values('$userid','$productid','$productcomment')";
	$res=mysql_query($sql);
	$query="SELECT product_comment from products where id='$productid'";
	$res=mysql_query($query);
	$data=mysql_fetch_array($res);
	$totalproductcomment=$data['product_comment'] +1;
	$update="Update products SET product_comment='$totalproductcomment' where id='$productid'";
	$res=mysql_query($update);
	$data = array('result' => 'success', 'message' => 'User ID no. '.$userid.' commented on product ID no '.$productid);
	
}else{
	$data = array('result' => 'fail','Message'=>'userid/productid/comment cannot be empty');
}
mysql_close($conn);


/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
?>