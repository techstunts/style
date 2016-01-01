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


    $sql = "Update userdetails SET bodyshape='$bodyshape',bodytype='$bodytype',skintype='$skintype',styletype='$styletype',age='$age',pricerange='$pricerange',clubprice='$clubprice',ethicprice='$ethicprice',denimprice='$denimprice',footwearprice='$footwearprice',height='$height' where user_id='$userid'";

    $select = mysql_query($sql);
    $sql = "SELECT user_id,username,userimage,stylists.name as stylist_name,bodytype,bodyshape,height,age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice FROM userdetails Join stylists on stylists.stylish_id=userdetails.stylish_id  where userdetails.user_id='$userid'";

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

    }
    $data = array('result' => 'success', 'message' => 'questions updated ', 'response body' => array("user_id" => $result[0], "username" => $result[1], "userimage" => $result[2], "stylish_name" => $result[3], "body_type" => $result[4], "body_shape" => $result[5], "height" => $result[6], "age" => $result[7], "skin_type" => $result[8], 'price range' => array("club" => $result[10], "ethic" => $result[11], "denim" => $result[12], "footwear" => $result[13]), 'styletype' => $result[9]));


} else {
    $data = array('result' => 'fail', 'message' => 'you have not added your complete details!');
}
mysql_close($conn);


/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
?>