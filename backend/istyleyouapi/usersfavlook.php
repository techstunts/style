<?php
include("db_config.php");
if($_SERVER['REQUEST_METHOD']=="GET" && isset($_GET['userid']) && !empty($_GET['userid'])){
	$userid=$_GET['userid'];
	$sql="Select createdlook.look_id,look_description,look_image,lookprice,look_name from createdlook where createdlook.look_id NOT IN (Select look_id from users_unlike where user_id='$userid') AND createdlook.look_id IN (Select look_id from usersfav where user_id='$userid') ORDER BY createdlook.look_id DESC LIMIT 5 ";
			$res=mysql_query($sql);
			$row=mysql_num_rows($res);
		
			$ids=array();
			
			$list=array();
			if($row!=0){
			while($data=mysql_fetch_array($res)){
				$ids[]=$data;
			}
				for($i=0;$i<$row;$i++){
				$id=$ids[$i][0];
                               
				$query="select id,product_name,upload_image,product_price,product_type,product_link from lookdescrip join createdlook on createdlook.product_id1=lookdescrip.id or createdlook.product_id2=lookdescrip.id or createdlook.product_id3=lookdescrip.id or createdlook.product_id4=lookdescrip.id where look_id='$id'";
				$res1=mysql_query($query);
				while($data1=mysql_fetch_array($res1)){
					$list[]=$data1;
				}
					$sql="Select product_id from usersfav join lookdescrip on usersfav.product_id=lookdescrip.id where user_id='$userid'";
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
				$data= array('lookdetails'=>array('fav'=>'yes','lookid'=>$ids[$i][0],'lookdescription'=>$ids[$i][1],'lookimage'=>$ids[$i][2],'lookprice'=>$ids[$i][3],'lookname'=>$ids[$i][4],'productdetails' => $productarray));
				$abc[]=$data;
				//$total[]=$abc;
				unset($list);
                                
				
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