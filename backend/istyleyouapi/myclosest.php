<?php
include("db_config.php");
if($_SERVER['REQUEST_METHOD']=="GET" && isset($_REQUEST['userid']) && !empty($_REQUEST['userid'])){
	$userid=$_REQUEST['userid'];
			$sql="Select distinct createdlook.look_id,look_description,look_image,lookprice,occasion,look_name from sendlook join createdlook on sendlook.look_id=createdlook.look_id where sendlook.look_id NOT IN (Select look_id from users_unlike where user_id='$userid') AND  sendlook.look_id NOT IN (Select look_id from usersfav where user_id='$userid') AND sendlook.user_id='$userid' ORDER BY sendlook.send_id DESC";
			$res=mysql_query($sql);
			$row=mysql_num_rows($res);
			if($row!=0){
			
	$ids=array();
	$abc=array();
	$list=array();
	$i=0;
	while($data=mysql_fetch_array($res)){
				$ids[]=$data;
				$id=$ids[$i][0];

				$query="select id,product_name,upload_image,product_price,product_type,product_link from lookdescrip join createdlook on createdlook.product_id1=lookdescrip.id or createdlook.product_id2=lookdescrip.id or createdlook.product_id3=lookdescrip.id or createdlook.product_id4=lookdescrip.id where look_id='$id'";
				$res1=mysql_query($query);
				$rows=mysql_num_rows($res);
				while($data1=mysql_fetch_array($res1)){
					$list[]=$data1;
				}
				$i++;
	}
			$k=$row*4;	
	$productarray=array();
	for($j=0;$j<$k;$j++){
		$sql="Select product_id from usersfav join lookdescrip on usersfav.product_id=lookdescrip.id where user_id='$userid'";
		$res=mysql_query($sql);
		$tr=mysql_num_rows($res);
		while($data=mysql_fetch_array($res)){
						$productid[]=$data['product_id'];
					}
					if($tr==0){
						$fav='No';
					}
					for($m=0;$m<$tr;$m++){	
						if($list[$j][0]==$productid[$m]){
							$fav='yes';
							break;
						}else{
							$fav='No';
						}
					}	
		$product[]=array('fav'=>$fav,'productid'=>$list[$j][0],'productname'=>$list[$j][1],'productimage'=>$list[$j][2],'productprice'=>$list[$j][3],'producttype'=>$list[$j][4],'productlink'=>$list[$j][5]);
		
	}

	
	$data=array('result'=>'success','productdetails'=>$product);
	}else{
		$product=array();
		$data=array('result'=>'success','productdetails'=>$product);
	}

}else{
	$data=array('result'=>'fail','response_message'=>'userid empty');
	
}
mysql_close($conn);

/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);

?>