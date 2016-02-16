<?php
include("db_config.php");
include("ProductLink.php");
if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['userid']) && !empty($_GET['userid'])) {
    $userid = $_GET['userid'];

    $page = isset($_GET['page']) && $_GET['page'] != '' ? mysql_real_escape_string($_GET['page']) : 0;
    $records_per_page = 20;
    $record_start = intval($page * $records_per_page);

    $sql = "select p.id, p.name, upload_image, p.price, product_type, product_link, p.agency_id, p.merchant_id,
                    m.name merchant_name, b.name brand_name, b.id as brand_id
            from products p
            join usersfav on usersfav.product_id = p.id
            join merchants m ON p.merchant_id = m.id
            join brands b ON p.brand_id = b.id
            where user_id='$userid'
            ORDER BY usersfav.fav_id DESC
            LIMIT $record_start, $records_per_page ";
    $res = mysql_query($sql);
    $row = mysql_num_rows($res);
    $list = array();
    while ($data1 = mysql_fetch_array($res)) {
        $list[] = $data1;
    }
    $productarray = array();
    for ($j = 0; $j < $row; $j++) {
        $product = array(
            'fav' => 'yes',
            'productid'=>$list[$j][0],
            'productname'=>$list[$j][1],
            'productimage'=>$list[$j][2],
            'productprice'=>$list[$j][3],
            'producttype'=>$list[$j][4],
            'productlink' => ProductLink::getDeepLink($list[$j][6],
                $list[$j][7],
                $list[$j][5]),
            'merchant' => $list[$j]['merchant_name'],
            'brand' => $list[$j]['brand_name'],
            'brand_id' => $list[$j]['brand_id'],
        );
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