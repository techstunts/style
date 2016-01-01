<?php
include("db_config.php");
if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['userid']) && !empty($_GET['userid'])) {
    $userid = $_GET['userid'];
    $sql = "select * from products join usersfav on usersfav.product_id=products.id where user_id='$userid'";
    $res = mysql_query($sql);
    $row = mysql_num_rows($res);
    $list = array();
    while ($data1 = mysql_fetch_array($res)) {
        $list[] = $data1;
    }
    $productarray = array();
    for ($j = 0; $j < $row; $j++) {
        $product = array('fav' => 'yes', 'productid' => $list[$j][0], 'productname' => $list[$j][1], 'producttype' => $list[$j][2], 'productprice' => $list[$j][3], 'productlink' => $list[$j][4], 'productimage' => $list[$j][5]);
        $productarray[] = $product;
    }
    $data = array('result' => 'success', 'myfav' => $productarray);

} else {
    $data = array('result' => 'fail', 'response_message' => 'userid empty');
}
mysql_close($conn);

/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
?>