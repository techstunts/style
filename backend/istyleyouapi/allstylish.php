<?php       
include("db_config.php");
$sql="SELECT id,image as stylish_image FROM `stylists`";
 $res=mysql_query($sql);
 while($data=mysql_fetch_assoc($res)){
$stylishinfo[]=$data;
}
$data=array("stylishinfo"=>$stylishinfo);
mysql_close($conn);
/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);

?>