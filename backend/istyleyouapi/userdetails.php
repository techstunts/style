<?php
include("db_config.php");
include_once("Emailer.php");
//file_put_contents('userdetails-data', "\n" . var_export($_REQUEST, true), FILE_APPEND);

if (!empty($_SERVER['HTTP_CLIENT_IP'])){
    $ip=$_SERVER['HTTP_CLIENT_IP'];
}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
}else{
    $ip=$_SERVER['REMOTE_ADDR'];
}
$user_signup_ip_address = ip2long($ip);
$current_date_time = date("Y-m-d H:i:s");
//The $user_signup_ip_address would look something like: 1073732954

$signup_successful = false;

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_REQUEST['email']) && !empty($_REQUEST['email']) && !empty($_REQUEST['password']) && isset($_REQUEST['password']) && !empty($_REQUEST['gender']) && isset($_REQUEST['gender'])) {

    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];
    $gender = $_REQUEST['gender'];
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
    $valid = true;
    if (isset($_REQUEST['stylishcode']) && !empty($_REQUEST['stylishcode'])) {
        $stylishcode = $_REQUEST['stylishcode'];
        $query = "Select id from stylists where code='$stylishcode'";
        $res = mysql_query($query);
        $row = mysql_num_rows($res);
        if ($row == 1) {
            $res = mysql_fetch_array($res);
            $stylishid = $res['id'];
        } else {
            $valid = false;
        }
    } else {
        if ($gender == 'male') {
            $sql = "SELECT id from stylists where gender_id='1' and status_id=1 ORDER BY id  LIMIT 1";
            $res = mysql_query($sql);
            $data = mysql_fetch_array($res);
            $lastfemaleid = $data['id'];
            $sql = "SELECT id from stylists where gender_id='1' and status_id=1 ORDER BY id  LIMIT 1";
            $res = mysql_query($sql);
            $data = mysql_fetch_array($res);
            $firstfemaleid = $data['id'];
            $sql = "SELECT stylist_id from clients where gender='male' ORDER BY id DESC LIMIT 1";
            $res = mysql_query($sql);
            $data = mysql_fetch_array($res);
            $lastuserid = $data['stylist_id'];
            $sql = "select id from stylists where id = (select min(id) from stylists where gender_id='1' AND id > $lastuserid and status_id=1)";
            $res = mysql_query($sql);
            $data = mysql_fetch_array($res);
            $randomid = $data['id'];
            if ($lastuserid == $lastfemaleid) {
                $stylishid = $firstfemaleid;
            } else {
                $stylishid = $randomid;
            }

        } else {
            $sql = "SELECT stylist_id FROM clients ORDER BY id DESC LIMIT 1 ";
            $res = mysql_query($sql);
            $data = mysql_fetch_array($res);
            $lastuserstylish = $data['stylist_id'];
            $sql = "SELECT id from stylists WHERE status_id=1 ORDER BY id DESC LIMIT 1";
            $res = mysql_query($sql);
            $data = mysql_fetch_array($res);
            $laststylishid = $data['id'];

            if ($lastuserstylish == $laststylishid) {
                $stylishid = $lastuserstylish - ($laststylishid - 1);
            } else {
                $stylishid = $lastuserstylish + 1;
            }
        }
    }
    $facebookid = "";
    $googleid = "";
    $linkedid = "";
    $name = "";
    $image = "";
    $checkuser = "SELECT * from clients where email='$email'";
    $result1 = mysql_query($checkuser);

    $rows = mysql_num_rows($result1);
    if ($valid) {
        if ($rows != 0) {
            $data = array('result' => 0, 'message' => 'User already registered with the given email');
            $data = login($email, $password, $gender);
        } else {
            $sql = "INSERT INTO clients(facebook_id,google_id,linked_id,email,password,gender,stylist_id,name,image,bodyshape,bodytype,skintype,styletype,age,pricerange,clubprice,ethicprice,denimprice,footwearprice,height,signup_ip_address,created_at) VALUES('$facebookid','$googleid','$linkedid','$email','$password','$gender','$stylishid','$name','$image','$bodyshape','$bodytype','$skintype','$styletype','$age','$pricerange','$clubprice','$ethicprice','$denimprice','$footwearprice','$height','$user_signup_ip_address', '$current_date_time')";
            $insert = mysql_query($sql);
            $lastid = mysql_insert_id();


            if ($lastid) {
                if ($stylishid != 0) {
                    $sql = "SELECT id,name,image,stylists.name as stylist_name,bodytype,bodyshape,height,clients.age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice FROM userdetails Join stylists on stylists.id=userdetails.stylist_id  where userdetails.id='$lastid'";
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
                        $result[9] = $data['styletype'];
                        $result[10] = $data['clubprice'];
                        $result[11] = $data['ethicprice'];
                        $result[12] = $data['denimprice'];
                        $result[13] = $data['footwearprice'];

                    }

                    $data = login($email, $password, $gender);
                } else {
                    $sql = "SELECT id,name,image,stylist_id,bodytype,bodyshape,height,age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice FROM clients Where id='$lastid'";

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

                    $data = login($email, $password, $gender, $stylishid);
                }
                $signup_successful = true;

            } else {
                $data = array('result' => 'fail', 'message' => 'Error in adding user');
            }

        }
    } else {
        $data = array('result' => 'fail', 'message' => 'failed...You Entered Wrong Stylish Code');
    }
} elseif ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_REQUEST['email']) && !empty($_REQUEST['email']) && isset($_REQUEST['facebook_id']) && !empty($_REQUEST['facebook_id']) && isset($_REQUEST['gender']) && !empty($_REQUEST['gender']) && isset($_REQUEST['name']) && !empty($_REQUEST['name'])) {
    $email = $_REQUEST['email'];
    $facebookid = $_REQUEST['facebook_id'];
    $gender = $_REQUEST['gender'];
    $name = $_REQUEST['name'];
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
    if ($gender == 'male') {
        $sql = "SELECT id from stylists where gender_id='1' AND status_id=1 ORDER BY id DESC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $lastfemaleid = $data['id'];
        $sql = "SELECT id from stylists where gender_id='1' AND status_id=1 ORDER BY id ASC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $firstfemaleid = $data['id'];
        $sql = "SELECT stylist_id from clients where gender='male' ORDER BY id DESC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $lastuserid = $data['stylist_id'];
        $sql = "select id from stylists where id = (select min(id) from stylists where gender_id='1' AND id > $lastuserid AND status_id=1)";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $randomid = $data['id'];
        if ($lastuserid == $lastfemaleid) {
            $stylishid = $firstfemaleid;
        } else {
            $stylishid = $randomid;
        }

    } else {

        $sql = "SELECT id from stylists where gender_id='1' AND status_id=1 ORDER BY id DESC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $lastfemaleid = $data['id'];
        $sql = "SELECT id from stylists where gender_id='1' AND status_id=1 ORDER BY id ASC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $firstfemaleid = $data['id'];
        $sql = "SELECT stylist_id from clients where gender='female' ORDER BY id DESC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $lastuserid = $data['stylist_id'];
        $sql = "select id from stylists where id = (select min(id) from stylists where gender_id='1' AND id > $lastuserid AND status_id=1)";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $randomid = $data['id'];
        if ($lastuserid == $lastfemaleid) {
            $stylishid = $firstfemaleid;
        } else {
            $stylishid = $randomid;
        }
    }
    $password = "";
    $googleid = "";
    $linkedid = "";

    $checkuser = "SELECT * from clients where email='$email'";
    $result1 = mysql_query($checkuser);
    $rows = mysql_num_rows($result1);
    $checkfb = "SELECT * from clients where facebook_id='$facebookid'";
    $result2 = mysql_query($checkfb);

    $rows1 = mysql_num_rows($result2);
    if ($rows != 0) {
        if ($rows1 == 0) {
            $sql = "Update clients set facebook_id='$facebookid',gender='$gender'  where email='$email'";
            $res == mysql_query($sql);
        }
        //$data = array('result' => 0, 'message' => 'User already registered with the given email');
        $sql = "Update clients set regId='$regId' where email='$email'";
        $res == mysql_query($sql);
        $data = FacebookLogin($email, $facebookid, $gender, $name);

    } else {
        $sql = "INSERT INTO clients(facebook_id,google_id,linked_id,email,password,gender,stylist_id,name,image,bodyshape,bodytype,skintype,styletype,age,pricerange,clubprice,ethicprice,denimprice,footwearprice,height,regId,signup_ip_address,created_at) VALUES('$facebookid','$googleid','$linkedid','$email','$password','$gender','$stylishid','$name','$image','$bodyshape','$bodytype','$skintype','$styletype','$age','$pricerange','$clubprice','$ethicprice','$denimprice','$footwearprice','$height','$regId','$user_signup_ip_address', '$current_date_time')";
        $insert = mysql_query($sql);
        $lastid = mysql_insert_id();


        if ($lastid) {

            $sql = "SELECT id,name,image,stylist_id,bodytype,bodyshape,height,age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice FROM clients where id='$lastid'";
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
            $data = FacebookLogin($email, $facebookid, $gender, $name);
            $signup_successful = true;
        } else {
            $data = array('result' => 'fail', 'message' => 'Error in adding user');
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_REQUEST['email']) && !empty($_REQUEST['email']) && isset($_REQUEST['google_id']) && !empty($_REQUEST['google_id']) && isset($_REQUEST['gender']) && !empty($_REQUEST['gender']) && isset($_REQUEST['name']) && !empty($_REQUEST['name'])) {

    $email = $_REQUEST['email'];
    $googleid = $_REQUEST['google_id'];
    $gender = $_REQUEST['gender'];
    $name = $_REQUEST['name'];
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
    if ($gender == 'male') {
        $sql = "SELECT id from stylists where gender_id='1' AND status_id=1 ORDER BY id DESC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $lastfemaleid = $data['id'];
        $sql = "SELECT id from stylists where gender_id='1' AND status_id=1 ORDER BY id ASC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $firstfemaleid = $data['id'];
        $sql = "SELECT stylist_id from clients where gender='male' ORDER BY id DESC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $lastuserid = $data['stylist_id'];
        $sql = "select id from stylists where id = (select min(id) from stylists where gender_id='1' AND id > $lastuserid AND status_id=1)";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $randomid = $data['id'];
        if ($lastuserid == $lastfemaleid) {
            $stylishid = $firstfemaleid;
        } else {
            $stylishid = $randomid;
        }

    } else {

        $sql = "SELECT id from stylists where gender_id='1' AND status_id=1 ORDER BY id DESC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $lastfemaleid = $data['id'];
        $sql = "SELECT id from stylists where gender_id='1' AND status_id=1 ORDER BY id ASC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $firstfemaleid = $data['id'];
        $sql = "SELECT stylist_id from clients where gender='female' ORDER BY id DESC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $lastuserid = $data['stylist_id'];
        $sql = "select id from stylists where id = (select min(id) from stylists where gender_id='1' AND id > $lastuserid AND status_id=1)";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $randomid = $data['id'];
        if ($lastuserid == $lastfemaleid) {
            $stylishid = $firstfemaleid;
        } else {
            $stylishid = $randomid;
        }
    }
    $facebookid = "";
    $password = "";
    $linkedid = "";
    $image = $_REQUEST['image'];
    $checkuser = "SELECT * from clients where email='$email'";
    $result1 = mysql_query($checkuser);

    $rows = mysql_num_rows($result1);
    $checkg = "SELECT * from clients where google_id='$googleid'";
    $result2 = mysql_query($checkg);

    $rows1 = mysql_num_rows($result2);

    if ($rows != 0) {
        if ($rows1 == 0) {
            $sql = "Update clients set google_id='$googleid',gender='$gender' where email='$email'";
            $res == mysql_query($sql);
        }
        //$data = array('result' => 'fail', 'message' => 'User already registered with the given email');
        $sql = "Update clients set regId='$regId' where email='$email'";
        $res == mysql_query($sql);
        $data = GoogleLogin($email, $googleid, $gender, $name);
    } else {
        $sql = "INSERT INTO clients(facebook_id,google_id,linked_id,email,password,gender,stylist_id,name,image,bodyshape,bodytype,skintype,styletype,age,pricerange,clubprice,ethicprice,denimprice,footwearprice,height,regId,signup_ip_address,created_at) VALUES('$facebookid','$googleid','$linkedid','$email','$password','$gender','$stylishid','$name','$image','$bodyshape','$bodytype','$skintype','$styletype','$age','$pricerange','$clubprice','$ethicprice','$denimprice','$footwearprice','$height','$regId','$user_signup_ip_address', '$current_date_time')";
        $insert = mysql_query($sql);
        $lastid = mysql_insert_id();
        if ($lastid) {

            $sql = "SELECT id,name,image,stylist_id,bodytype,bodyshape,height,age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice FROM clients where id='$lastid'";
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
            $data = GoogleLogin($email, $googleid, $gender, $name);
            $signup_successful = true;
        } else {
            $data = array('result' => 'fail', 'message' => 'Error in adding user');
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_REQUEST['email']) && !empty($_REQUEST['email']) && isset($_REQUEST['linked_id']) && !empty($_REQUEST['linked_id']) && isset($_REQUEST['gender']) && !empty($_REQUEST['gender']) && isset($_REQUEST['name']) && !empty($_REQUEST['name'])) {
    $email = $_REQUEST['email'];
    $linkedid = $_REQUEST['linked_id'];
    $gender = $_REQUEST['gender'];
    $name = $_REQUEST['name'];
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
    if ($gender == 'male') {
        $sql = "SELECT id from stylists where gender_id='1' AND status_id=1 ORDER BY id DESC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $lastfemaleid = $data['id'];
        $sql = "SELECT id from stylists where gender_id='1' AND status_id=1 ORDER BY id  LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $firstfemaleid = $data['id'];
        $sql = "SELECT stylist_id from clients where gender='male' ORDER BY id DESC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $lastuserid = $data['stylist_id'];
        $sql = "select id from stylists where id = (select min(id) from stylists where gender_id='1' AND id > $lastuserid AND status_id=1)";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $randomid = $data['id'];
        if ($lastuserid == $lastfemaleid) {
            $stylishid = $firstfemaleid;
        } else {
            $stylishid = $randomid;
        }

    } else {
        $sql = "SELECT stylist_id FROM clients ORDER BY id DESC LIMIT 1 ";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $lastuserstylish = $data['stylist_id'];
        $sql = "SELECT id from stylists WHERE status_id=1 ORDER BY id DESC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $laststylishid = $data['id'];

        if ($lastuserstylish == $laststylishid) {
            $stylishid = $lastuserstylish - ($laststylishid - 1);
        } else {
            $stylishid = $lastuserstylish + 1;
        }
    }
    $facebookid = "";
    $googleid = "";
    $password = "";
    $image = "";
    $checkuser = "SELECT * from clients where email='$email'";
    $result1 = mysql_query($checkuser);

    $rows = mysql_num_rows($result1);

    if ($rows != 0) {
        //$data = array('result' => 'fail', 'message' => 'User already registered with the given email');
        $data = LinkedinLogin($email, $linkedid, $gender, $name);
    } else {
        $sql = "INSERT INTO clients(facebook_id,google_id,linked_id,email,password,gender,stylist_id,name,image,bodyshape,bodytype,skintype,styletype,age,pricerange,clubprice,ethicprice,denimprice,footwearprice,height,signup_ip_address,created_at) VALUES('$facebookid','$googleid','$linkedid','$email','$password','$gender','$stylishid','$name','$image','$bodyshape','$bodytype','$skintype','$styletype','$age','$pricerange','$clubprice','$ethicprice','$denimprice','$footwearprice','$height','$user_signup_ip_address', '$current_date_time')";
        $insert = mysql_query($sql);
        $lastid = mysql_insert_id();


        if ($lastid) {


            $sql = "SELECT id,name,image,stylist_id,bodytype,bodyshape,height,age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice FROM clients where id='$lastid'";
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
            $data = LinkedinLogin($email, $linkedid, $gender, $name);
            $signup_successful = true;
        } else {
            $data = array('result' => 'fail', 'message' => 'Error in adding user');
        }
    }
} else {
    $data = array('result' => 'fail', 'message' => 'Request method is wrong or some parameters missing!');

}

if($signup_successful){
    if($data['result'] == 'success' && isset($data['response body']) && $data['response body']['id']!=''){
        $mailer = new Emailer();
        $mailer->enqueue(1, $data['response body']['id']);
        $mailer->enqueue(2, $data['response body']['id']);
    }
}


function login($email, $password, $gender, $stylishid)
{

    $sql = "SELECT email,password,gender from clients where email='$email'";
    $res = mysql_query($sql);
    $login = array();
    $rows = mysql_num_rows($res);
    if ($rows == 1) {
        while ($data = mysql_fetch_array($res)) {
            $login[0] = $data['email'];
            $login[1] = $data['password'];
            $login[2] = $data['gender'];
        }
    } else {

        $login[0] = "";
        $login[1] = "";
        $login[2] = "";

    }
    if ($login[0] == $email && $login[1] == $password && $login[2] == $gender) {
        if (isset($_POST['stylishcode']) && !empty($_POST['stylishcode'])) { //&& $row == 1) {
            $sql = "SELECT id from clients where email='$email' AND password='$password'";
            $res = mysql_query($sql);
            $data = mysql_fetch_assoc($res);
            $userid = $data['id'];
            $query = "UPDATE clients SET stylist_id='$stylishid' where id='$userid'";
            $res = mysql_query($query);
        }


        $sql = "SELECT id,stylist_id from clients where email='$email' AND password='$password'";
        $result = array();
        $res = mysql_query($sql);
        while ($data = mysql_fetch_assoc($res)) {


            $userid = $data['id'];
            $result[1] = $data['stylist_id'];

        }


        if ($result[1] != 0) {
            $sql = "SELECT user_id,name,image,stylists.name as stylist_name,bodytype,bodyshape,height,clients.age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice,stylists.code as stylist_code, stylists.image as stylist_image FROM clients Join stylists on stylists.id=clients.stylist_id where clients.id='$userid'";

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
            }

            $data = array('result' => 'success', 'message' => 'Login Success ', 'response body' => array("id" => $result[0], "user_id" => $result[0], "name" => $result[1], "image" => "http://istyleyou.in/istyleyouapi/profileimage/" . $result[2], "stylish_name" => $result[3], "body_type" => $result[4], "body_shape" => $result[5], "height" => $result[6], "age" => $result[7], "skin_type" => $result[8], 'price range' => array("club" => $result[10], "ethic" => $result[11], "denim" => $result[12], "footwear" => $result[13]), 'styletype' => $result[9], 'stylishcode' => $result[14], 'stylishimage' => $result[15]));

        } else {

            $data = array('result' => 'fail', 'message' => 'User not assign to any stylish,provide stylish code for that user');

        }
    } else {
        if ($login[0] == $email && $login[1] != $password && $login[2] == $gender) {
            $data = array('result' => 'fail', 'message' => 'Incorrect Password');

        } elseif ($login[0] == $email && $login[1] == $password && $login[2] != $gender) {
            $data = array('result' => 'fail', 'message' => 'Incorrect gender for the registered email');
        } else {
            $data = array('result' => 'fail', 'message' => 'The given Email is not registered yet ');
        }
    }
    return $data;
}

function FacebookLogin($email, $facebookid, $gender, $name)
{
    $sql = "SELECT email,facebook_id,gender from clients where email='$email'";
    $res = mysql_query($sql);
    $login = array();
    $rows = mysql_num_rows($res);
    if ($rows == 1) {
        while ($data = mysql_fetch_array($res)) {
            $login[0] = $data['email'];
            $login[1] = $data['facebook_id'];
            $login[2] = $data['gender'];
        }
    } else {

        $login[0] = "";
        $login[1] = "";
        $login[2] = "";

    }
    if ($login[0] == $email && $login[1] == $facebookid && $login[2] == $gender) {
        $sql = "SELECT id,stylist_id from clients where email='$email' AND facebook_id='$facebookid'";
        $result = array();
        $res = mysql_query($sql);
        while ($data = mysql_fetch_assoc($res)) {


            $userid = $data['id'];
            $result[1] = $data['stylist_id'];

        }


        if ($result[1] != 0) {
            $sql = "SELECT clients.id as user_id,name,image,stylists.name as stylist_name,bodytype,bodyshape,height,clients.age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice, stylists.code as stylist_code, stylists.image as stylist_image, stylists.id as stylist_id FROM clients Join stylists on stylists.id=clients.stylist_id where clients.id='$userid'";

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
            }

            $data = array('result' => 'success', 'message' => 'Login Success ', 'response body' => array("id" => $result[0], "user_id" => $result[0], "name" => $result[1], "image" => $result[2], "stylish_name" => $result[3], "body_type" => $result[4], "body_shape" => $result[5], "height" => $result[6], "age" => $result[7], "skin_type" => $result[8], 'price range' => array("club" => $result[10], "ethic" => $result[11], "denim" => $result[12], "footwear" => $result[13]), 'styletype' => $result[9], 'stylecode' => $result[14], 'styleimage' => $result[15], 'stylist_id' => $result[16], 'stylish_id' => $result[16]));
        } else {
            $data = array('result' => 'fail', 'message' => 'User not assign to any stylish,provide stylish code for that user');
        }
    } else {
        if ($login[0] == $email && $login[1] != $facebookid && $login[2] == $gender) {
            $data = array('result' => 'fail', 'message' => 'Incorrect facebook id');
        } elseif ($login[0] == $email && $login[1] == $facebookid && $login[2] != $gender) {
            $data = array('result' => 'fail', 'message' => 'Incorrect gender for the registered email');
        } else {
            $data = array('result' => 'fail', 'message' => 'The given Email is not registered yet ');
        }
    }
    return $data;
}

function GoogleLogin($email, $googleid, $gender, $name)
{
    $sql = "SELECT email,google_id,gender from clients where email='$email'";
    $res = mysql_query($sql);
    $login = array();
    $rows = mysql_num_rows($res);
    if ($rows == 1) {
        while ($data = mysql_fetch_array($res)) {
            $login[0] = $data['email'];
            $login[1] = $data['google_id'];
            $login[2] = $data['gender'];
        }
    } else {

        $login[0] = "";
        $login[1] = "";
        $login[2] = "";

    }
    if ($login[0] == $email && $login[1] == $googleid && $login[2] == $gender) {
        $sql = "SELECT id,stylist_id from clients where email='$email' AND google_id='$googleid'";
        $result = array();
        $res = mysql_query($sql);
        while ($data = mysql_fetch_assoc($res)) {


            $userid = $data['id'];
            $result[1] = $data['stylist_id'];

        }


        if ($result[1] != 0) {
            $sql = "SELECT clients.id as user_id,name,image,stylists.name as stylist_name,bodytype,bodyshape,height,clients.age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice, stylists.code as stylist_code, stylists.image as stylist_image, stylists.id FROM clients Join stylists on stylists.id=clients.stylist_id where clients.id='$userid'";

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
            }
            $data = array('result' => 'success', 'message' => 'Login Success ', 'response body' => array("id" => $result[0], "user_id" => $result[0], "name" => $result[1], "image" => "http://istyleyou.in/istyleyouapi/profileimage/" . $result[2], "stylish_name" => $result[3], "body_type" => $result[4], "body_shape" => $result[5], "height" => $result[6], "age" => $result[7], "skin_type" => $result[8], 'price range' => array("club" => $result[10], "ethic" => $result[11], "denim" => $result[12], "footwear" => $result[13]), 'styletype' => $result[9], 'stylecode' => $result[14], 'styleimage' => $result[15], 'stylist_id' => $result[16], 'stylish_id' => $result[16]));
        } else {
            $data = array('result' => 'fail', 'message' => 'User not assign to any stylish,provide stylish code for that user');
        }
    } else {
        if ($login[0] == $email && $login[1] != $googleid && $login[2] == $gender) {
            $data = array('result' => 'fail', 'message' => 'Incorrect google id');
        } elseif ($login[0] == $email && $login[1] == $googleid && $login[2] != $gender) {
            $data = array('result' => 'fail', 'message' => 'Incorrect gender for the registered email');
        } else {
            $data = array('result' => 'fail', 'message' => 'The given Email is not registered yet ');
        }
    }
    return $data;
}

function LinkedinLogin($email, $linkedid, $gender, $name)
{
    $sql = "SELECT email,linked_id,gender from clients where email='$email'";
    $res = mysql_query($sql);
    $login = array();
    $rows = mysql_num_rows($res);
    if ($rows == 1) {
        while ($data = mysql_fetch_array($res)) {
            $login[0] = $data['email'];
            $login[1] = $data['linked_id'];
            $login[2] = $data['gender'];
        }
    } else {

        $login[0] = "";
        $login[1] = "";
        $login[2] = "";

    }
    if ($login[0] == $email && $login[1] == $linkedid && $login[2] == $gender) {
        $sql = "SELECT id,stylist_id from clients where email='$email' AND linked_id='$linkedid'";
        $result = array();
        $res = mysql_query($sql);
        while ($data = mysql_fetch_assoc($res)) {


            $userid = $data['id'];
            $result[1] = $data['stylist_id'];

        }


        if ($result[1] != 0) {
            $sql = "SELECT clients.id as user_id,name,image,stylists.name as stylist_name,bodytype,bodyshape,height,clients.age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice, stylists.code as stylist_code, stylists.image as stylist_image FROM clients Join stylists on stylists.id=clients.stylist_id where clients.id='$userid'";

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
            }
            $data = array('result' => 'success', 'message' => 'Login Success ', 'response body' => array("id" => $result[0], "user_id" => $result[0], "name" => $result[1], "image" => "http://istyleyou.in/istyleyouapi/profileimage/" . $result[2], "stylish_name" => $result[3], "body_type" => $result[4], "body_shape" => $result[5], "height" => $result[6], "age" => $result[7], "skin_type" => $result[8], 'price range' => array("club" => $result[10], "ethic" => $result[11], "denim" => $result[12], "footwear" => $result[13]), 'styletype' => $result[9], 'stylecode' => $result[14], 'styleimage' => $result[15]));
        } else {
            $data = array('result' => 'fail', 'message' => 'User not assign to any stylish,provide stylish code for that user');
        }
    } else {
        if ($login[0] == $email && $login[1] != $linkedid && $login[2] == $gender) {
            $data = array('result' => 'fail', 'message' => 'Incorrect Linked id');
        } elseif ($login[0] == $email && $login[1] == $linkedid && $login[2] != $gender) {
            $data = array('result' => 'fail', 'message' => 'Incorrect gender for the registered email');
        } else {
            $data = array('result' => 'fail', 'message' => 'The given Email is not registered yet ');
        }
    }
    return $data;
}

mysql_close($conn);
/* JSON Response */
header("Content-type: application/json");
echo json_encode($data);
//file_put_contents('clients-data', "\n" . var_export($data, true), FILE_APPEND);

?>
