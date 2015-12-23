<?php
include("db_config.php");
include("ProductLink.php");

if($_SERVER['REQUEST_METHOD']=="GET" && isset($_REQUEST['userid']) && !empty($_REQUEST['userid'])){
	$userid=$_REQUEST['userid'];
			$sql="Select distinct looks.id as look_id,look_description,look_image,lookprice,occasion,look_name from sendlook join looks on sendlook.look_id=looks.id where sendlook.user_id='$userid' ORDER BY sendlook.send_id DESC";
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

		$query="select ld.id,product_name,upload_image,product_price,product_type,product_link, ld.agency_id, ld.merchant_id
				from products ld join looks
				on looks.product_id1=ld.id
				or looks.product_id2=ld.id
				or looks.product_id3=ld.id
				or looks.product_id4=ld.id
				where looks.id='$id'";
		$res1=mysql_query($query);
		$rows=mysql_num_rows($res);
		while($data1=mysql_fetch_array($res1)){
			$list[]=$data1;
		}
		$i++;
	}
			$k=$row*4;

	$sql="Select product_id from usersfav join products on usersfav.product_id=products.id where user_id='$userid'";
	$res=mysql_query($sql);
	$tr=mysql_num_rows($res);
	while($data=mysql_fetch_array($res)){
		$productid[]=$data['product_id'];
	}

	for($j=0;$j<$k;$j++){
		if(!isset($list[$j])){
			continue;
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
		$product[]=array(
			'fav'=>$fav,
			'productid'=>$list[$j][0],
			'productname'=>$list[$j][1],
			'productimage'=>$list[$j][2],
			'productprice'=>$list[$j][3],
			'producttype'=>$list[$j][4],
			'productlink'=>ProductLink::getDeepLink($list[$j][6],
										$list[$j][7],
										$list[$j][5])
		);
		
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
