<?php
include("db_config.php");
if($_SERVER['REQUEST_METHOD']=="GET" && isset($_GET['userid']) && !empty($_GET['userid'])){
	$userid=$_GET['userid'];
	$sql="Select looks.id as look_id,looks.description, looks.image, looks.price, looks.name
		  from looks
		  where looks.id NOT IN (Select look_id from users_unlike where user_id='$userid')
		  AND looks.id IN (Select look_id from usersfav where user_id='$userid')
		  ORDER BY looks.id DESC LIMIT 5 ";
			$res=mysql_query($sql);
			$row=mysql_num_rows($res);
		
			$ids=array();
			$stylish_details=array();
			$list=array();
			if($row!=0){
			while($data=mysql_fetch_array($res)){
				$ids[]=$data;
			}
				for($i=0;$i<$row;$i++){
				$id=$ids[$i][0];
                                $stylish="select stylists.stylish_id, stylists.name as stylish_name, stylists.image as stylish_image  from stylists join looks on stylists.stylish_id=looks.stylish_id where looks.id='$id'";
				$res2=mysql_query($stylish);
				while($data2=mysql_fetch_array($res2)){
					$stylish_details[]=$data2;
				}

				$query="select p.id,product_name,upload_image,product_price,product_type,product_link, p.agency_id, p.merchant_id
                        from looks l
                        join looks_products lp ON l.id = lp.look_id
                        join products p ON lp.product_id = p.id
                        where l.id='$id'";

				$res1=mysql_query($query);
				while($data1=mysql_fetch_array($res1)){
					$list[]=$data1;
				}
					$sql="Select product_id from usersfav join products on usersfav.product_id=products.id where user_id='$userid'";
					$res=mysql_query($sql);
					$tr=mysql_num_rows($res);
				$productarray=array();
				$produtid=array();
				for($j=0;$j<4;$j++){
					while($data=mysql_fetch_array($res)){
						$productid[]=$data['product_id'];
					}
					if($tr==0){
						$fav='No';
					}
					for($k=0;$k<$tr;$k++){	
						if($list[$j][0]==$productid[$k]){
							$fav='yes';
							break;
						}else{
							$fav='No';
						}
					}	
					
				
					$product=array('fav'=>$fav,'productid'=>$list[$j][0],'productname'=>$list[$j][1],'productimage'=>$list[$j][2],'productprice'=>$list[$j][3],'producttype'=>$list[$j][4],'productlink'=>$list[$j][5]);
					$productarray[]=$product;
				}
				$data= array('lookdetails'=>array('fav'=>'yes','lookid'=>$ids[$i][0],'lookdescription'=>$ids[$i][1],'lookimage'=>$ids[$i][2],'lookprice'=>$ids[$i][3],'lookname'=>$ids[$i][4],'productdetails' => $productarray,'stylish_details'=>$stylish_details));
				$abc[]=$data;
				//$total[]=$abc;
				unset($list);
                                unset($stylish_details);
				
			}
		$data=array('result'=>'success','myfav'=>$abc);
	}else{
		$data=array('result'=>'success','myfav'=>'No fav looks');

	}

}else{
	$data=array('result'=>'fail','response_message'=>'userid empty');
}
mysql_close($conn);

/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
?>