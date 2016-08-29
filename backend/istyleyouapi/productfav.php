<?php
include("db_config.php");
if ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['userid']) && !empty($_POST['productid'])) {
    $userid = mysql_real_escape_string($_POST['userid']);
    $productid = mysql_real_escape_string($_POST['productid']);
    $query = "SELECT * from products where id='$productid'";
    $res = mysql_query($query);
    $row = mysql_num_rows($res);
    if ($row == 1) {
        $check = "Select * from usersfav where user_id='$userid' AND product_id='$productid'";
        $res = mysql_query($check);
        $row = mysql_num_rows($res);
        if ($row == 0) {
            $sql = "INSERT INTO usersfav(user_id,product_id) Values('$userid','$productid')";
            $res = mysql_query($sql);
            $query = "SELECT product_fav from products where id='$productid'";
            $res = mysql_query($query);
            $data = mysql_fetch_array($res);
            $productfav = $data['product_fav'] + 1;
            $update = "Update products SET product_fav='$productfav' where id='$productid'";
            $res = mysql_query($update);
            $data = array('result' => 'success', 'response_message' => 'Product id no. ' . $productid . ' fav successfully by userid' . $userid);
        } else {
            $sql = "Delete From usersfav where user_id='$userid' AND product_id='$productid'";
            $res = mysql_query($sql);
            $query = "SELECT product_fav from products where id='$productid'";
            $res = mysql_query($query);
            $data = mysql_fetch_array($res);
            $productfav = $data['product_fav'] - 1;
            $update = "Update products SET product_fav='$productfav' where id='$productid'";
            $res = mysql_query($update);
            $data = array('result' => 'success', 'response_message' => 'Product id no. ' . $productid . ' Unfav successfully by userid' . $userid);
        }
    } else {
        $data = array('result' => 'fail', 'response_message' => 'Product ID does not exist');
    }
} else {
    $data = array('result' => 'fail', 'response_message' => 'userid or productid empty');
}
mysql_close($conn);


/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
?>