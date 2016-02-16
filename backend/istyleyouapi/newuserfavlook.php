<?php
include("db_config.php");
include("ProductLink.php");

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['userid']) && !empty($_GET['userid'])) {
    $userid = $_GET['userid'];

    $page = isset($_GET['page']) && $_GET['page'] != '' ? mysql_real_escape_string($_GET['page']) : 0;
    $records_per_page = 20;
    $record_start = intval($page * $records_per_page);

    $sql = "Select l.id as look_id, l.description, l.image, l.price, l.name
		  from looks l
		  where l.id NOT IN (Select look_id from users_unlike where user_id='$userid')
		  AND l.id IN (Select look_id from usersfav where user_id='$userid')
		  and l.status_id = 1
		  ORDER BY l.id DESC
		  LIMIT $record_start, $records_per_page ";
    $res = mysql_query($sql);
    $row = mysql_num_rows($res);

    $ids = array();
    $stylish_details = array();
    $list = array();
    if ($row != 0) {
        while ($data = mysql_fetch_array($res)) {
            $ids[] = $data;
        }
        for ($i = 0; $i < $row; $i++) {
            $id = $ids[$i][0];
            $stylish = "select s.stylish_id, s.name as stylish_name, s.image as stylish_image
                        from stylists s
                        join looks l on s.stylish_id = l.stylish_id
                        where l.id='$id'
				        and l.status_id = 1
                        ";
            $res2 = mysql_query($stylish);
            while ($data2 = mysql_fetch_array($res2)) {
                $stylish_details[] = $data2;
            }

            $query = "select p.id, p.name, upload_image, p.price, product_type, product_link, p.agency_id, p.merchant_id,
                            m.name merchant_name, b.name brand_name, b.id as brand_id
                        from looks l
                        join looks_products lp ON l.id = lp.look_id
                        join products p ON lp.product_id = p.id
						join merchants m ON p.merchant_id = m.id
						join brands b ON p.brand_id = b.id
                        where l.id='$id'
				        and l.status_id = 1
                        ";

            $res1 = mysql_query($query);
            if(mysql_num_rows($res1)<=0){
                continue;
            }

            while ($data1 = mysql_fetch_array($res1)) {
                $list[] = $data1;
            }
            $sql = "Select product_id from usersfav join products on usersfav.product_id=products.id where user_id='$userid'";
            $res = mysql_query($sql);
            $tr = mysql_num_rows($res);
            $productarray = array();
            $produtid = array();
            for ($j = 0; $j < 4; $j++) {
                while ($data = mysql_fetch_array($res)) {
                    $productid[] = $data['product_id'];
                }
                if ($tr == 0) {
                    $fav = 'No';
                }
                for ($k = 0; $k < $tr; $k++) {
                    if ($list[$j][0] == $productid[$k]) {
                        $fav = 'yes';
                        break;
                    } else {
                        $fav = 'No';
                    }
                }


                $product = array(
                    'fav' => $fav,
                    'productid' => $list[$j][0],
                    'productname' => $list[$j][1],
                    'productimage' => $list[$j][2],
                    'productprice' => $list[$j][3],
                    'producttype' => $list[$j][4],
                    'productlink'=>ProductLink::getDeepLink($list[$j][6],
                            $list[$j][7],
                            $list[$j][5]),
                    'merchant' => $list[$j]['merchant_name'],
                    'brand' => $list[$j]['brand_name'],
                    'brand_id' => $list[$j]['brand_id'],
                );
                $productarray[] = $product;
            }
            $data = array('lookdetails' => array('fav' => 'yes', 'lookid' => $ids[$i][0], 'lookdescription' => $ids[$i][1], 'lookimage' => $ids[$i][2], 'lookprice' => $ids[$i][3], 'lookname' => $ids[$i][4], 'productdetails' => $productarray, 'stylish_details' => $stylish_details));
            $abc[] = $data;
            //$total[]=$abc;
            unset($list);
            unset($stylish_details);

        }
        $data = array('result' => 'success', 'myfav' => $abc);
    } else {
        $data = array('result' => 'success', 'myfav' => 'No fav looks');

    }

} else {
    $data = array('result' => 'fail', 'response_message' => 'userid empty');
}
mysql_close($conn);

/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
?>