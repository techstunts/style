<?php
include("db_config.php");
if($_GET['type']=='male'){
$category=['Select Product','Shirts','Ethnic Top','Top Wear','Winter Wear','Jeans','Pants','Footwear','Accessory','Lowers'];
$data=array('category'=>$category);
}
if($_GET['type']=='female'){
$category=['Select Product','Shirts','Saree','Ethnic Top','Ethnic Bottom','Top Wear','Winter Wear','Skirts','Jeans','Pants','Bags','Footwear','Jewelry','Accessory','Cosmetics','Lowers'];
$data=array('category'=>$category);
}
mysql_close($conn);

/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
?>