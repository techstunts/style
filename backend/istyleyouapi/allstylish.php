<?php       
include("db_config.php");
$sql="SELECT stylish_id,stylish_image FROM `stylish_details`";
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