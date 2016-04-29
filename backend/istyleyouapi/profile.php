<?php
include("db_config.php");
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_REQUEST['userid']) && isset($_REQUEST['name']) && isset($_REQUEST['bodytype']) && isset($_REQUEST['bodyshape']) && isset($_REQUEST['height']) && isset($_REQUEST['age']) && isset($_REQUEST['skintype']) && isset($_REQUEST['clubprice']) && isset($_REQUEST['ethicprice']) && isset($_REQUEST['denimprice']) && isset($_REQUEST['footwearprice']) ) {
    $userid = $_REQUEST['userid'];
    $name = $_REQUEST['name'];
    if (!empty($_FILES['image']['name'])) {

        $ext = explode('.', $_FILES['image']['name']);
        $ext = $ext[count($ext) - 1];
        $fname = rand(000000000, 999999999) . '_' . rand(000000000, 999999999) . '.' . $ext;
        if (move_uploaded_file($_FILES['image']['tmp_name'], 'profileimage/' . $fname)) {
            $image = $fname;
        }

    } else {

        $sql = "Select image from clients where id='$userid'";
        $res = mysql_query($sql);
        while ($data = mysql_fetch_array($res)) {
            $image = $data['image'];
        }
    }

    $bodytype = $_REQUEST['bodytype'];
    $bodyshape = $_REQUEST['bodyshape'];
    $height = $_REQUEST['height'];
    $age = $_REQUEST['age'];
    $skintype = $_REQUEST['skintype'];
    $clubprice = $_REQUEST['clubprice'];
    $ethicprice = $_REQUEST['ethicprice'];
    $denimprice = $_REQUEST['denimprice'];
    $footwearprice = $_REQUEST['footwearprice'];
    $pricerange = $clubprice + $ethicprice + $denimprice + $footwearprice;
    $sql = "Update clients SET name='$name',image='$image',bodyshape='$bodyshape',bodytype='$bodytype',skintype='$skintype',age='$age',pricerange='$pricerange',clubprice='$clubprice',ethicprice='$ethicprice',denimprice='$denimprice',footwearprice='$footwearprice',height='$height' where id='$userid'";

    $select = mysql_query($sql);
    $sql = "SELECT id, name, image, s.name as stylist_name, bodytype, bodyshape, height, u.age, skintype,
                    clubprice, ethicprice, denimprice, footwearprice
            FROM clients u
            Join stylists s on s.id = u.stylist_id
            where u.id='$userid'";

    $select = mysql_query($sql);
    $result = array();


    while ($data = mysql_fetch_assoc($select)) {

        $result[0] = $data['id'];
        $result[1] = $data['name'];
        $result[2] = $data['image'];
        $result[3] = $data['stylist_name'];
        $result[4] = $data['bodytype'];
        $result[5] = $data['bodyshape'];
        $result[6] = $data['height'];
        $result[7] = $data['age'];
        $result[8] = $data['skintype'];
        $result[9] = '';
        $result[10] = $data['clubprice'];
        $result[11] = $data['ethicprice'];
        $result[12] = $data['denimprice'];
        $result[13] = $data['footwearprice'];

    }
    $data = array('result' => 'success', 'message' => 'Profile updated ', 'response body' => array("id" => $result[0], "name" => $result[1], "image" => $result[2], "stylish_name" => $result[3], "stylist_name" => $result[3], "body_type" => $result[4], "body_shape" => $result[5], "height" => $result[6], "age" => $result[7], "skin_type" => $result[8], 'price range' => array("club" => $result[10], "ethic" => $result[11], "denim" => $result[12], "footwear" => $result[13]), 'styletype' => $result[9]));
} else {
    $data = array('result' => 'fail', 'message' => 'You have not added your complete details!');
}
mysql_close($conn);


/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
?>