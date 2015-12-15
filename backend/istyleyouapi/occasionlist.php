<?php
include("db_config.php");
$category=['Select Occasion','Work Wear','Wine & Dine','Ethnic/Festive','Casuals'];
$data=array('category'=>$category);
mysql_close($conn);

/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
?>