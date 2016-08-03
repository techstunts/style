<?php
include("db_config.php");
include_once("Emailer.php");
file_put_contents('userdetails-data', "\n" . var_export($_REQUEST, true), FILE_APPEND);

if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}
$user_signup_ip_address = ip2long($ip);
$current_date_time = date("Y-m-d H:i:s");
//The $user_signup_ip_address would look something like: 1073732954

$signup_successful = false;

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_REQUEST['client_id']) && !empty($_REQUEST['client_id']) && isset($_REQUEST['email']) && !empty($_REQUEST['email'])) {

    $data = map_guest_with_current_client($_REQUEST['client_id'], $_REQUEST['email']);
    if($data['result'] == 'fail'){
        mysql_close($conn);
        /* JSON Response */
        header("Content-type: application/json");
        echo json_encode($data);
        exit;
    }
}


if ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_REQUEST['gender']) && !empty($_REQUEST['regid']) && !empty($_REQUEST['login_skipped']) && $_REQUEST['login_skipped'] == true) {
    $name = 'Guest';
    $regId = $_REQUEST['regid'];

    $gender = "";
    $gender_id = 3;
    if (!empty($_REQUEST['gender']) && isset($_REQUEST['gender'])) {
        $gender = strtolower($_REQUEST['gender']);
        $gender_id = $gender == 'male' ? 2 : 1;
    }
    if ($gender_id == 1) {
        $image = 'http://d36o0t9p57q98i.cloudfront.net/resources/images/android/female-v2.png';
    } else {
        $image = 'http://d36o0t9p57q98i.cloudfront.net/resources/images/android/male-v2.png';
    }

    $stylishid = getStylistId();
    $sql = "INSERT INTO clients(gender,gender_id,stylist_id,name,image,regId,signup_ip_address,created_at) VALUES('$gender','$gender_id','$stylishid','$name','$image','$regId','$user_signup_ip_address','$current_date_time')";
    $insert = mysql_query($sql);
    $lastid = mysql_insert_id();
    if ($lastid) {
        saveDeviceDetails($lastid, $regId, $user_signup_ip_address, $current_date_time);

        $sql = "SELECT id as stylist_id,name as stylist_name,code as stylist_code, image as stylist_image, icon as stylist_icon FROM stylists where id='$stylishid'";
        $query = mysql_query($sql);
        $stylist_data = mysql_fetch_array($query, MYSQL_ASSOC);
        $data = array('result' => 'success', 'message' => 'Login Success ', 'response body' => array("id" => $lastid, "user_id" => $lastid, "name" => $name, "username" => $name, "image" => $image, "stylist_name" => $stylist_data['stylist_name'], "body_type" => '', "body_shape" => '', "height" => '', "age" => '', "skin_type" => '', 'price range' => array("club" => '', "ethic" => '', "denim" => '', "footwear" => ''), 'styletype' => '', 'stylecode' => $stylist_data['stylist_code'], 'styleimage' => $stylist_data['stylist_image'], 'stylist_id' => $stylist_data['stylist_id'], 'stylish_id' => $stylist_data['stylist_id'], 'stylist_icon' => $stylist_data['stylist_icon']));
    } else {
        $data = array('result' => 'fail', 'message' => 'Something went wrong');
    }
}
elseif ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_REQUEST['email']) && !empty($_REQUEST['email']) && !empty($_REQUEST['password']) && isset($_REQUEST['password'])) {

    $email = $_REQUEST['email'];
    $password = trim($_REQUEST['password']);
    if(!isValidEmail($email))
    {
        $data = array('result' => 'fail', 'message' => 'Invalid email address');
    }
    else if (strlen($password) < 6)
    {
        $data = array('result' => 'fail', 'message' => 'Invalid password');
    }
    else
    {
        $gender = "";
        $gender_id = 3;
        if (!empty($_REQUEST['gender']) && isset($_REQUEST['gender'])) {
            $gender = strtolower($_REQUEST['gender']);
            $gender_id = $gender == 'male' ? 2 : 1;
        }
        if ($gender_id == 1) {
            $image = 'http://d36o0t9p57q98i.cloudfront.net/resources/images/android/female.png';
        } else {
            $image = 'http://d36o0t9p57q98i.cloudfront.net/resources/images/android/male.png';
        }
        $bodytype = "";
        $bodyshape = "";
        $height = "";
        $age = 18;
        $skintype = "";
        $clubprice = 100;
        $ethicprice = 100;
        $denimprice = 100;
        $footwearprice = 100;
        $pricerange = $clubprice + $ethicprice + $denimprice + $footwearprice;
        $name = isset($_REQUEST['username']) ? $_REQUEST['username'] : substr($email, 0, strpos($email, '@'));
        $regId = isset($_REQUEST['regid']) ? $_REQUEST['regid'] : "";
        $client = exec_sql("SELECT * from clients where clients.account_id=1 and email='$email'");

        if ($client) {
            saveDeviceDetails($client['id'], $regId, $user_signup_ip_address, $current_date_time);
            $data = login($email, $password, $gender, $gender_id);
        } else {
            $password_hashed = password_hash($_REQUEST['password'], PASSWORD_BCRYPT);
            $stylishid = getStylistId();
            $sql = "INSERT INTO clients(email,password,gender,gender_id,stylist_id,name,image,bodytype,bodyshape,height,age,skintype,pricerange,clubprice,ethicprice,denimprice,footwearprice,regId,signup_ip_address,created_at) VALUES('$email','$password_hashed','$gender','$gender_id','$stylishid','$name','$image','$bodytype', '$bodyshape','$height','$age','$skintype','$pricerange','$clubprice','$ethicprice','$denimprice','$footwearprice','$regId','$user_signup_ip_address', '$current_date_time')";
            $insert = mysql_query($sql);
            $lastid = mysql_insert_id();
            if ($lastid) {
                saveDeviceDetails($lastid, $regId, $user_signup_ip_address, $current_date_time);
                $data = login($email, $password, $gender, $gender_id);
            } else {
                $data = array('result' => 'fail', 'message' => 'Error in adding user');
            }
        }
        $signup_successful = true;
    }
}
elseif ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_REQUEST['email']) && !empty($_REQUEST['email']) && isset($_REQUEST['facebook_id']) && !empty($_REQUEST['facebook_id']) && isset($_REQUEST['username']) && !empty($_REQUEST['username'])) {
    $email = $_REQUEST['email'];
    $facebookid = $_REQUEST['facebook_id'];
    $gender = "";
    $gender_id = 3;
    if (!empty($_REQUEST['gender']) && isset($_REQUEST['gender'])) {
        $gender = strtolower($_REQUEST['gender']);
        $gender_id = $gender == 'male' ? 2 : 1;
    }
    $name = $_REQUEST['username'];
    $regId = $_REQUEST['regid'];
    $image = 'http://graph.facebook.com/' . $facebookid . '/picture?type=square';
    $bodytype = "";
    $bodyshape = "";
    $height = "";
    $age = 18;
    $skintype = "";
    $styletype = "";
    $clubprice = 100;
    $ethicprice = 100;
    $denimprice = 100;
    $footwearprice = 100;
    $pricerange = $clubprice + $ethicprice + $denimprice + $footwearprice;

    $password = "";
    $googleid = "";
    $linkedid = "";

    $checkuser = "SELECT * from clients where clients.account_id=1 and email='$email'";
    $result1 = mysql_query($checkuser);
    $rows = mysql_num_rows($result1);
    $checkfb = "SELECT * from clients where clients.account_id=1 and facebook_id='$facebookid'";
    $result2 = mysql_query($checkfb);

    $rows1 = mysql_num_rows($result2);
    $client_data = mysql_fetch_array($result1, MYSQL_ASSOC);
    if ($rows != 0) {
        if ($rows1 == 0) {
            $sql = "Update clients set facebook_id='$facebookid',gender='$gender', gender_id=$gender_id where clients.account_id=1 and email='$email' limit 1";
            mysql_query($sql);

        }
        //$data = array('result' => 0, 'message' => 'User already registered with the given email');
        $sql = "Update clients set regId='$regId', device_status=TRUE  where clients.account_id=1 and email='$email' limit 1";
        mysql_query($sql);
        saveDeviceDetails($client_data['id'], $regId, $user_signup_ip_address, $current_date_time);
        $data = FacebookLogin($email, $facebookid, $gender, $gender_id);
    } else {
        $stylishid = getStylistId();
        $sql = "INSERT INTO clients(facebook_id,google_id,linked_id,email,password,gender,gender_id,stylist_id,name,image,bodyshape,bodytype,skintype,styletype,age,pricerange,clubprice,ethicprice,denimprice,footwearprice,height,regId,signup_ip_address,created_at) VALUES('$facebookid','$googleid','$linkedid','$email','$password','$gender','$gender_id','$stylishid','$name','$image','$bodyshape','$bodytype','$skintype','$styletype','$age','$pricerange','$clubprice','$ethicprice','$denimprice','$footwearprice','$height','$regId','$user_signup_ip_address', '$current_date_time')";

        $insert = mysql_query($sql);
        $lastid = mysql_insert_id();
        saveDeviceDetails($lastid, $regId, $user_signup_ip_address, $current_date_time);

        if ($lastid) {

            $sql = "SELECT id,name,image,stylist_id,bodytype,bodyshape,height,age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice FROM clients where clients.account_id=1 and id='$lastid'";
            $select = mysql_query($sql);
            $result = array();
            while ($data = mysql_fetch_assoc($select)) {

                $result[0] = $data['id'];
                $result[1] = $data['name'];
                $result[2] = $data['image'];
                $result[3] = $data['stylist_id'];
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
            $data = FacebookLogin($email, $facebookid, $gender, $gender_id);
            $signup_successful = true;
        } else {
            $data = array('result' => 'fail', 'message' => 'Error in adding user');
        }
    }
}
elseif ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_REQUEST['email']) && !empty($_REQUEST['email']) && isset($_REQUEST['google_id']) && !empty($_REQUEST['google_id']) && isset($_REQUEST['username']) && !empty($_REQUEST['username'])) {

    $email = $_REQUEST['email'];
    $googleid = $_REQUEST['google_id'];
    $gender = strtolower($_REQUEST['gender']);
    $gender = "";
    $gender_id = 3;
    if (!empty($_REQUEST['gender']) && isset($_REQUEST['gender'])) {
        $gender = strtolower($_REQUEST['gender']);
        $gender_id = $gender == 'male' ? 2 : 1;
    }
    $name = $_REQUEST['username'];
    $bodytype = "";
    $bodyshape = "";
    $height = "";
    $age = 18;
    $skintype = "";
    $styletype = "";
    $clubprice = 100;
    $ethicprice = 100;
    $denimprice = 100;
    $footwearprice = 100;
    $regId = $_REQUEST['regid'];
    $pricerange = $clubprice + $ethicprice + $denimprice + $footwearprice;

    $facebookid = "";
    $password = "";
    $linkedid = "";
    $image = $_REQUEST['userimage'];
    $checkuser = "SELECT * from clients where clients.account_id=1 and email='$email'";
    $result1 = mysql_query($checkuser);

    $rows = mysql_num_rows($result1);
    $checkg = "SELECT * from clients where clients.account_id=1 and google_id='$googleid'";
    $result2 = mysql_query($checkg);

    $rows1 = mysql_num_rows($result2);
    $client_data = mysql_fetch_array($result1, MYSQL_ASSOC);
    if ($rows != 0) {
        if ($rows1 == 0) {
            $sql = "Update clients set google_id='$googleid',gender='$gender',gender_id='$gender_id' where clients.account_id=1 and email='$email'";
            mysql_query($sql);
        }
        //$data = array('result' => 'fail', 'message' => 'User already registered with the given email');
        $sql = "Update clients set regId='$regId', device_status=TRUE  where clients.account_id=1 and email='$email'";
        mysql_query($sql);
        saveDeviceDetails($client_data['id'], $regId, $user_signup_ip_address, $current_date_time);
        $data = GoogleLogin($email, $googleid, $gender, $gender_id);
    } else {
        $stylishid = getStylistId();
        $sql = "INSERT INTO clients(facebook_id,google_id,linked_id,email,password,gender,gender_id,stylist_id,name,image,bodyshape,bodytype,skintype,styletype,age,pricerange,clubprice,ethicprice,denimprice,footwearprice,height,regId,signup_ip_address,created_at) VALUES('$facebookid','$googleid','$linkedid','$email','$password','$gender','$gender_id','$stylishid','$name','$image','$bodyshape','$bodytype','$skintype','$styletype','$age','$pricerange','$clubprice','$ethicprice','$denimprice','$footwearprice','$height','$regId','$user_signup_ip_address', '$current_date_time')";
        $insert = mysql_query($sql);
        $lastid = mysql_insert_id();
        saveDeviceDetails($lastid, $regId, $user_signup_ip_address, $current_date_time);
        if ($lastid) {

            $sql = "SELECT id,name,image,stylist_id,bodytype,bodyshape,height,age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice FROM clients where clients.account_id=1 and id='$lastid'";
            $select = mysql_query($sql);
            $result = array();
            while ($data = mysql_fetch_assoc($select)) {

                $result[0] = $data['id'];
                $result[1] = $data['name'];
                $result[2] = $data['image'];
                $result[3] = $data['stylist_id'];
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
            $data = GoogleLogin($email, $googleid, $gender, $gender_id);
            $signup_successful = true;
        } else {
            $data = array('result' => 'fail', 'message' => 'Error in adding user');
        }
    }
} else {
    $data = array('result' => 'fail', 'message' => 'Request method is wrong or some parameters missing!');

}
if ($signup_successful) {
    if ($data['result'] == 'success' && isset($data['response body']) && $data['response body']['id'] != '') {
        $mailer = new Emailer();
        $mailer->enqueue(1, $data['response body']['id']);
        $mailer->enqueue(2, $data['response body']['id']);
    }
}


function login($email, $password, $gender, $gender_id)
{
    $client_data = exec_sql("SELECT clients.id,clients.name,clients.gender, clients.gender_id,clients.email,clients.password,clients.image,stylists.name as stylist_name,bodytype,bodyshape,height,clients.age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice,stylists.code as stylist_code, stylists.image as stylist_image, stylists.icon as stylist_icon, stylists.id as stylist_id FROM clients Join stylists on stylists.id=clients.stylist_id where clients.account_id=1 and clients.email='$email'");

    $password_verified = password_verify($password, $client_data['password']);
    if ($client_data['email'] == $email && $password_verified && (($client_data['gender'] == $gender) || ($client_data['gender_id'] == $gender_id))) {
        $data = array('result' => 'success', 'message' => 'Login Success ', 'response body' => array("id" => $client_data['id'], "user_id" => $client_data['id'], "name" => $client_data['name'], "username" => $client_data['name'], "image" => $client_data['image'], "stylist_name" => $client_data['stylist_name'], "body_type" => $client_data['bodytype'], "body_shape" => $client_data['bodytype'], "height" => $client_data['height'], "age" => $client_data['age'], "skin_type" => $client_data['skintype'], 'price range' => array("club" => $client_data['clubprice'], "ethic" => $client_data['ethicprice'], "denim" => $client_data['denimprice'], "footwear" => $client_data['footwearprice']), 'styletype' => $client_data['styletype'], 'stylecode' => $client_data['stylist_code'], 'styleimage' => $client_data['stylist_image'], "stylist_id" => $client_data['stylist_id'], "stylish_id" => $client_data['stylist_id'], "stylist_icon" => $client_data['stylist_icon'], ));
    } else {
        if ($client_data['email'] == $email && !$password_verified && (($client_data['gender'] == $gender) || ($client_data['gender_id'] == $gender_id))) {
            $data = array('result' => 'fail', 'message' => 'Incorrect Password');
        } elseif ($client_data['email'] == $email && $password_verified && (($client_data['gender'] != $gender) || ($client_data['gender_id'] != $gender_id))) {
            $data = array('result' => 'fail', 'message' => 'Incorrect gender for the registered email');
        } else {
            $data = array('result' => 'fail', 'message' => 'The given Email is not registered yet ');
        }
    }
    return $data;
}

function FacebookLogin($email, $facebookid, $gender, $gender_id)
{
    $sql = "SELECT email,facebook_id,gender,gender_id from clients where clients.account_id=1 and email='$email'";
    $res = mysql_query($sql);
    $login = array();
    $rows = mysql_num_rows($res);
    if ($rows == 1) {
        while ($data = mysql_fetch_array($res)) {
            $login[0] = $data['email'];
            $login[1] = $data['facebook_id'];
            $login[2] = $data['gender'];
            $login[3] = $data['gender_id'];
        }
    } else {

        $login[0] = "";
        $login[1] = "";
        $login[2] = "";
        $login[3] = "";

    }
    if ($login[0] == $email && $login[1] == $facebookid) {
        $sql = "SELECT id,stylist_id from clients where clients.account_id=1 and email='$email' AND facebook_id='$facebookid'";
        $result = array();
        $res = mysql_query($sql);
        while ($data = mysql_fetch_assoc($res)) {

            $userid = $data['id'];
            $result[1] = $data['stylist_id'];
        }
        if ($result[1] != 0) {
            $sql = "SELECT clients.id as user_id,clients.name,clients.gender_id,clients.gender,clients.image,stylists.name as stylist_name,bodytype,bodyshape,height,clients.age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice, stylists.code as stylist_code, stylists.image as stylist_image, stylists.id as stylist_id, stylists.icon as stylist_icon FROM clients Join stylists on stylists.id=clients.stylist_id where clients.account_id=1 and clients.id='$userid'";

            $select = mysql_query($sql);
            $result = array();

            while ($data = mysql_fetch_assoc($select)) {

                $result[0] = $data['user_id'];
                $result[1] = $data['name'];
                $result[2] = $data['image'];
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
                $result[17] = $data['stylist_icon'];
            }
            $data = array('result' => 'success', 'message' => 'Login Success ', 'response body' => array("id" => $result[0], "user_id" => $result[0], "name" => $result[1], "username" => $result[1], "image" => $result[2], "stylish_name" => $result[3], "body_type" => $result[4], "body_shape" => $result[5], "height" => $result[6], "age" => $result[7], "skin_type" => $result[8], 'price range' => array("club" => $result[10], "ethic" => $result[11], "denim" => $result[12], "footwear" => $result[13]), 'styletype' => $result[9], 'stylecode' => $result[14], 'styleimage' => $result[15], 'stylist_id' => $result[16], 'stylish_id' => $result[16], 'stylist_icon' => $result[17], "stylist_name" => $result[3] ));
        } else {
            $data = array('result' => 'fail', 'message' => 'User not assign to any stylish,provide stylish code for that user');
        }
    } else {
        if ($login[0] == $email && $login[1] != $facebookid) {
            $data = array('result' => 'fail', 'message' => 'Incorrect facebook id');
        } else {
            $data = array('result' => 'fail', 'message' => 'The given Email is not registered yet ');
        }
    }
    return $data;
}

function GoogleLogin($email, $googleid, $gender, $gender_id)
{
    $sql = "SELECT email,google_id,gender,gender_id from clients where clients.account_id=1 and email='$email'";
    $res = mysql_query($sql);
    $login = array();
    $rows = mysql_num_rows($res);
    if ($rows == 1) {
        while ($data = mysql_fetch_array($res)) {
            $login[0] = $data['email'];
            $login[1] = $data['google_id'];
            $login[2] = $data['gender'];
            $login[3] = $data['gender_id'];
        }
    } else {

        $login[0] = "";
        $login[1] = "";
        $login[2] = "";
        $login[3] = "";

    }
    if ($login[0] == $email && $login[1] == $googleid) {
        $sql = "SELECT id,stylist_id from clients where clients.account_id=1 and email='$email' AND google_id='$googleid'";
        $result = array();
        $res = mysql_query($sql);
        while ($data = mysql_fetch_assoc($res)) {

            $userid = $data['id'];
            $result[1] = $data['stylist_id'];

        }


        if ($result[1] != 0) {
            $sql = "SELECT clients.id as user_id,clients.name,clients.gender_id,clients.gender,clients.image,stylists.name as stylist_name,bodytype,bodyshape,height,clients.age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice, stylists.code as stylist_code, stylists.image as  stylist_image, stylists.id as stylist_id, stylists.icon as stylist_icon FROM clients Join stylists on stylists.id=clients.stylist_id where clients.account_id=1 and clients.id='$userid'";
            $select = mysql_query($sql);
            $result = array();


            while ($data = mysql_fetch_assoc($select)) {

                $result[0] = $data['user_id'];
                $result[1] = $data['name'];
                $result[2] = $data['image'];
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
                $result[17] = $data['stylist_icon'];
            }
            $data = array('result' => 'success', 'message' => 'Login Success ', 'response body' => array("id" => $result[0], "user_id" => $result[0], "name" => $result[1], "username" => $result[1], "image" => "http://istyleyou.in/istyleyouapi/profileimage/" . $result[2], "stylish_name" => $result[3], "body_type" => $result[4], "body_shape" => $result[5], "height" => $result[6], "age" => $result[7], "skin_type" => $result[8], 'price range' => array("club" => $result[10], "ethic" => $result[11], "denim" => $result[12], "footwear" => $result[13]), 'styletype' => $result[9], 'stylecode' => $result[14], 'styleimage' => $result[15], 'stylist_id' => $result[16], 'stylish_id' => $result[16], 'stylist_icon' => $result[17],  "stylist_name" => $result[3]));
        } else {
            $data = array('result' => 'fail', 'message' => 'User not assign to any stylish,provide stylish code for that user');
        }
    } else {
        if ($login[0] == $email && $login[1] != $googleid) {
            $data = array('result' => 'fail', 'message' => 'Incorrect google id');
        } else {
            $data = array('result' => 'fail', 'message' => 'The given Email is not registered yet ');
        }
    }
    return $data;
}

function getStylistId()
{
    $sql = "SELECT id from stylists where status_id='1'";
    $res = mysql_query($sql);
    $stylist_array = array();
    while ($row = mysql_fetch_assoc($res)) {
        $stylist_array[] = $row['id'];
    }
    return $stylist_array[mt_rand() % (count($stylist_array))];
}

function saveDeviceDetails($client_id, $regId, $ip, $current_date_time)
{
    $checkRegId = "SELECT id from client_device_registration_details where regId='$regId' AND client_id='$client_id'";
    $result = mysql_query($checkRegId);
    $rows = mysql_num_rows($result);
    if ($rows == 0) {
        $sql = "INSERT INTO client_device_registration_details(client_id, regId, os, os_version, ip, created_at, regId_status) VALUES('$client_id', '$regId', 'android', '', '$ip', '$current_date_time', TRUE )";
        mysql_query($sql);
    }
}

function exec_sql($sql){
    $result = mysql_query($sql);
    if(mysql_num_rows($result) != 0){
        return mysql_fetch_array($result, MYSQL_ASSOC);
    }
}

function map_guest_with_current_client($client_id, $email){

    if(!(is_numeric($client_id) && ctype_digit($client_id))){
        return array('result' => 'fail', 'message' => 'Invalid client id ' . $client_id);
    }

    $client_exists = exec_sql("SELECT * from clients where clients.account_id=1 and id='$client_id'");
    if(!$client_exists){
        return array('result' => 'fail', 'message' => 'Client id ' . $client_id . ' not found.');
    }

    if(isset($client_exists['email']) && $client_exists['email'] != ''){
        if($client_exists['email'] != $email){
            return array('result' => 'fail', 'message' => 'Client id ' . $client_id . ' is already associated with another email id.');
        }
        else if($client_exists['email'] == $email){
            return array('result' => 'fail', 'message' => 'Client id ' . $client_id . ' is already associated with same email id.');
        }
    }

    $email_exists = exec_sql("SELECT * from clients where clients.account_id=1 and email='$email'");
    if($email_exists){
        if($email_exists['id'] != $client_id){
            $mapping_exists = exec_sql("SELECT * FROM GUEST_CLIENT_MAPPING WHERE guest_id={$client_id}");
            if($mapping_exists){
                return array('result' => 'fail', 'message' => 'Guest client id ' . $client_id . ' is already mapped with a client ');
            }
            $mapping_sql = "insert into GUEST_CLIENT_MAPPING (guest_id, client_id)
                            values ({$client_id}, {$email_exists['id']})";
            if(!mysql_query($mapping_sql)){
                return array('result' => 'fail', 'message' => 'Error in mapping guest client id ' . $client_id . ' with any existing client record.');
            }
        }
    }
    else{
        $update_email_id_sql = "update CLIENTS set email='$email' where id='{$client_id}'";
        if(!mysql_query($update_email_id_sql)){
            return array('result' => 'fail', 'message' => 'Error in updating email id into guest client ' . $client_id . ' record.');
        }
    }

}

function isValidEmail($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

mysql_close($conn);
/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
//file_put_contents('clients-data', "\n" . var_export($data, true), FILE_APPEND);

?>
