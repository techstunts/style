<?php
include("db_config.php");
include("ProductLink.php");

if($_SERVER['REQUEST_METHOD']=="GET" && isset($_REQUEST['userid']) && !empty($_REQUEST['userid'])){
	$userid=$_REQUEST['userid'];
			$sql="Select distinct l.id as look_id, l.description, l.image, l.price, o.name as occasion, l.name
				  from recommendations r
				  join looks l on r.entity_id=l.id and r.entity_type_id = 2
				  join lu_occasion o on l.occasion_id = o.id
				  where r.user_id='$userid'
				  and l.status_id = 1
				  ORDER BY r.id DESC";
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

		$query="select p.id,p.name,upload_image,p.price,product_type,product_link, p.agency_id, p.merchant_id,
					   m.name merchant_name, b.name brand_name, b.id as brand_id
				from looks l
				join looks_products lp ON l.id = lp.look_id
				join products p ON lp.product_id = p.id
				join merchants m ON p.merchant_id = m.id
				join brands b ON p.brand_id = b.id
				where l.id='$id'
				and l.status_id = 1
				";
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
										$list[$j][5]),
			'merchant' => $list[$j]['merchant_name'],
			'brand' => $list[$j]['brand_name'],
			'brand_id' => $list[$j]['brand_id'],
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
