<?php
include("db_config.php");
include("ProductLink.php");
if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_REQUEST['userid']) && !empty($_REQUEST['userid'])) {
    $userid = $_REQUEST['userid'];
    $sql = "Select distinct l.id as look_id, l.description, l.image, l.price, o.name as occasion, l.name
				  from recommendations r
				  join looks l on r.entity_id=l.id and r.entity_type_id = 2
				  join lu_occasion o on l.occasion_id = o.id
				  where r.user_id='$userid'
				  ORDER BY r.id DESC limit 5";
    $res = mysql_query($sql);
    $row = mysql_num_rows($res);

    if ($row != 0) {


        $ids = array();
        $abc = array();
        $list = array();
        $total = array();
        $stylish_details = array();
        $i = 0;
        while ($data = mysql_fetch_array($res)) {
            $ids[] = $data;
        }
        for ($i = 0; $i < $row; $i++) {
            $id = $ids[$i][0];
            $stylish = "select stylists.stylish_id, stylists.name as stylish_name, stylists.image as stylish_image
						  from stylists join looks on stylists.stylish_id=looks.stylish_id
						  where looks.id='$id'";
            $res2 = mysql_query($stylish);
            while ($data2 = mysql_fetch_array($res2)) {
                $stylish_details[] = $data2;
            }
            $query = "select p.id,product_name,upload_image,product_price,product_type,product_link, p.agency_id, p.merchant_id
                        from looks l
                        join looks_products lp ON l.id = lp.look_id
                        join products p ON lp.product_id = p.id
                        where l.id='$id'";

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

                $product = array('fav' => $fav,
                    'productid' => $list[$j][0],
                    'productname' => $list[$j][1],
                    'productimage' => $list[$j][2],
                    'productprice' => $list[$j][3],
                    'producttype' => $list[$j][4],
                    'productlink' => ProductLink::getDeepLink($list[$j][6],
                        $list[$j][7],
                        $list[$j][5]));
                $productarray[] = $product;
            }
            $data = array('lookdetails' => array('fav' => 'No', 'lookid' => $ids[$i][0], 'lookdescription' => $ids[$i][1], 'lookimage' => $ids[$i][2], 'lookprice' => $ids[$i][3], 'occasion' => $ids[$i][4], 'lookname' => $ids[$i][5], 'productdetails' => $productarray, 'stylish_details' => $stylish_details));
            $abc[] = $data;
            //$total[]=$abc;
            unset($list);
            unset($stylish_details);


        }
    } else {
        $abc = array();
    }
    //fav
    $sql = "Select l.id as look_id, l.description, l.image, l.price, o.name as occasion, l.name
					  from looks l
					  join lu_occasion o on l.occasion_id = o.id
					  where l.id NOT IN (Select look_id from users_unlike where user_id='$userid')
					  AND l.id IN (Select look_id from usersfav where user_id='$userid')
					  ORDER BY l.id DESC  ";
    $res = mysql_query($sql);
    $row = mysql_num_rows($res);

    $ids = array();

    $list = array();
    while ($data = mysql_fetch_array($res)) {
        $ids[] = $data;
    }
    for ($i = 0; $i < $row; $i++) {
        $id = $ids[$i][0];
        $stylish = "select stylists.stylish_id, stylists.name as stylish_name, stylists.image as stylish_image from stylists join looks on stylists.stylish_id=looks.stylish_id where looks.id='$id'";
        $res2 = mysql_query($stylish);
        while ($data2 = mysql_fetch_array($res2)) {
            $stylish_details[] = $data2;
        }

        $query = "select p.id,product_name,upload_image,product_price,product_type,product_link, p.agency_id, p.merchant_id
                        from looks l
                        join looks_products lp ON l.id = lp.look_id
                        join products p ON lp.product_id = p.id
                        where l.id='$id'";

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

            $product = array('fav' => $fav,
                'productid' => $list[$j][0],
                'productname' => $list[$j][1],
                'productimage' => $list[$j][2],
                'productprice' => $list[$j][3],
                'producttype' => $list[$j][4],
                'productlink' => ProductLink::getDeepLink($list[$j][6],
                    $list[$j][7],
                    $list[$j][5]));
            $productarray[] = $product;
        }
        $data = array('lookdetails' => array('fav' => 'yes', 'lookid' => $ids[$i][0], 'lookdescription' => $ids[$i][1], 'lookimage' => $ids[$i][2], 'lookprice' => $ids[$i][3], 'occasion' => $ids[$i][4], 'lookname' => $ids[$i][5], 'productdetails' => $productarray, 'stylish_details' => $stylish_details));
        $abc[] = $data;
        //$total[]=$abc;
        unset($list);

    }

    //fav
    $data = array('result' => 'success', 'myfeed' => $abc);

} else {
    $data = array('result' => 'fail', 'response_message' => 'userid empty');

}
mysql_close($conn);

/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
?>
