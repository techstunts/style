<?php
include("db_config.php");
include("Lookup.php");

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['stylish_id']) && !empty($_GET['stylish_id']) && !empty($_GET['gender'])) {
    $userid = $_GET['stylish_id'];
    $gender = $_GET['gender'];
    $sql = "SELECT stylish_id, name, description, image, code FROM stylists where stylish_id='$userid'";
    $stylishinfo = array();
    $res = mysql_query($sql);
    $rows = mysql_num_rows($res);

    $gender_id = Lookup::getId('gender', $gender);

    if ($rows == 1) {
        while ($data = mysql_fetch_assoc($res)) {
            $stylishimage = array();
            $stylishid = $data['stylish_id'];
            $stylishname = $data['name'];
            $description = $data['description'];
            $stylishimage[] = $data['image'];
            $stylishcode = $data['code'];
        }

        $lookinfo = array();
        $looks = array();
        $sql = "Select l.id as look_id, l.description, l.image, l.price, l.name, o.name as occasion
              FROM looks l join lu_occasion o on l.occasion_id = o.id
              where gender_id= '$gender_id' AND stylish_id='$userid'
              order by l.id ASC LIMIT 5";

        $res = mysql_query($sql);
        $row = mysql_num_rows($res);
        if ($row != 0) {
            while ($data = mysql_fetch_array($res)) {
                $ids[] = $data;
            }
            for ($i = 0; $i < $row; $i++) {
                $id = $ids[$i][0];
                $sql1 = "select usersfav.look_id from usersfav join looks on looks.id=usersfav.look_id where user_id='$userid'";

                $res = mysql_query($sql1);
                $r = mysql_num_rows($res);
                while ($data = mysql_fetch_array($res)) {
                    $looks[] = $data['look_id'];
                }
                if ($r == 0) {
                    $fav1 = 'No';
                }

                for ($k = 0; $k < $r; $k++) {
                    if ($id == $looks[$k]) {
                        $fav1 = 'yes';
                        break;
                    } else {
                        $fav1 = 'No';
                    }
                }
                $query = "select p.id, p.name, upload_image, p.price, product_type, product_link, p.agency_id, p.merchant_id
                  from looks l
                  join looks_products lp ON l.id = lp.look_id
                  join products p ON lp.product_id = p.id
                  where l.id='$id'";

                $res1 = mysql_query($query);
                $list = array();
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


                    $product = array('fav' => $fav, 'productid' => $list[$j][0], 'productname' => $list[$j][1], 'productimage' => $list[$j][2], 'productprice' => $list[$j][3], 'producttype' => $list[$j][4], 'productlink' => $list[$j][5]);
                    $productarray[] = $product;
                }
                $data = array('fav' => $fav1, 'lookid' => $ids[$i][0], 'lookdescription' => $ids[$i][1], 'lookimage' => $ids[$i][2], 'lookprice' => $ids[$i][3], 'lookname' => $ids[$i][4], 'occasion' => $ids[$i][5], 'productdetails' => $productarray);
                $abc[] = $data;
                //$total[]=$abc;
                unset($list);

            }

            $data = array('result' => 'success', 'Stylish Images' => $stylishimage, 'stylish_id' => $stylishid, 'stylish_name' => $stylishname, 'description' => $description, 'stylish_code' => $stylishcode, 'Look Details' => $abc);
        } else {
            $abc = array();

            $data = array('result' => 'success', 'Stylish Images' => $stylishimage, 'stylish_id' => $stylishid, 'stylish_name' => $stylishname, 'description' => $description, 'stylish_code' => $stylishcode, 'Look Details' => $abc);
        }
    } else {
        $data = array('result' => 'fail', 'message' => 'User ID is wrong');
    }
} else {
    $data = array('result' => 'fail', 'message' => 'Request Method is wrong or userid wrong/empty');
}

mysql_close($conn);
/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);

?>
