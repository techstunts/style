<?php
include("db_config.php");
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_REQUEST['userid']) && isset($_REQUEST['bodytype']) && isset($_REQUEST['bodyshape']) && isset($_REQUEST['height']) && isset($_REQUEST['age']) && isset($_REQUEST['skintype']) && isset($_REQUEST['styletype']) && isset($_REQUEST['clubprice']) && isset($_REQUEST['ethicprice']) && isset($_REQUEST['denimprice']) && isset($_REQUEST['footwearprice'])) {
    $userid = $_REQUEST['userid'];
    $bodytype = $_REQUEST['bodytype'];
    $bodyshape = $_REQUEST['bodyshape'];
    $height = $_REQUEST['height'];
    $age = $_REQUEST['age'];
    $skintype = $_REQUEST['skintype'];
    $styletype = $_REQUEST['styletype'];
    $clubprice = $_REQUEST['clubprice'];
    $ethicprice = $_REQUEST['ethicprice'];
    $denimprice = $_REQUEST['denimprice'];
    $footwearprice = $_REQUEST['footwearprice'];
    $pricerange = $clubprice + $ethicprice + $denimprice + $footwearprice;
    $update_stylist_id = "";

    if (isset($_REQUEST['stylist_code']) && !empty($_REQUEST['stylist_code'])) {
        $stylist_code = $_REQUEST['stylist_code'];
        $query = "SELECT stylist.id as stylist_id FROM stylists WHERE code='$stylist_code'";
        $res = mysql_query($query);
        $row = mysql_num_rows($res);
        if ($row == 1) {
            while ($data = mysql_fetch_array($res)) {
                $stylist_id = $data['stylist_id'];
                $update_stylist_id = ",stylist_id='{$stylist_id}'";
                break;
            }
        }
    }


    $sql = "Update userdetails
            SET bodyshape='$bodyshape', bodytype='$bodytype', skintype='$skintype', styletype='$styletype',
                age='$age', pricerange='$pricerange', clubprice='$clubprice', ethicprice='$ethicprice',
                denimprice='$denimprice', footwearprice='$footwearprice', height='$height' {$update_stylist_id}
            where id='$userid'";

    $select = mysql_query($sql);
    $sql = "SELECT u.id as user_id, username, userimage, s.name as stylist_name, bodytype, bodyshape, height, u.age, skintype, styletype,
                        clubprice, ethicprice, denimprice, footwearprice,
                        s.code as stylist_code, s.image as stylist_image, s.id as stylist_id
                FROM userdetails u
                Join stylists s on s.id = u.stylist_id
                where u.id='$userid'";

    $select = mysql_query($sql);
    $result = array();


    while ($data = mysql_fetch_assoc($select)) {

        $result[0] = $data['user_id'];
        $result[1] = $data['username'];
        $result[2] = $data['userimage'];
        $result[3] = $data['stylist_name'];
        $result[4] = $data['bodytype'];
        $result[5] = $data['bodyshape'];
        $result[6] = $data['height'];
        $result[7] = $data['age'];
        $result[8] = $data['skintype'];
        $result[9] = $data['styletype'];
        $result[10] = $data['clubprice'];
        $result[11] = $data['ethicprice'];
        $result[12] = $data['denimprice'];
        $result[13] = $data['footwearprice'];
        $result[14] = $data['stylist_code'];
        $result[15] = $data['stylist_image'];
        $result[16] = $data['stylist_id'];

    }
    $data = array(
        'result' => 'success',
        'message' => 'questions updated ',
        'response body' => array(
            "id" => $result[0],
            "user_id" => $result[0],
            "username" => $result[1],
            "userimage" => $result[2],
            "stylish_name" => $result[3],
            "body_type" => $result[4],
            "body_shape" => $result[5],
            "height" => $result[6],
            "age" => $result[7],
            "skin_type" => $result[8],
            'price range' => array(
                "club" => $result[10],
                "ethic" => $result[11],
                "denim" => $result[12],
                "footwear" => $result[13]
            ),
            'styletype' => $result[9],
            'stylish_code' => $result[14],
            'stylish_image' => $result[15],
            'stylish_id' => $result[16],
            'stylist_id' => $result[16]
        )
    );


} else {
    $data = array('result' => 'fail', 'message' => 'you have not added your complete details!');
}
mysql_close($conn);


/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
?>

