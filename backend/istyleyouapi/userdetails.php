<?php
include("db_config.php");
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
        $query = "Select stylish_id from stylists where code='$stylishcode'";
        $res = mysql_query($query);
        $row = mysql_num_rows($res);
        if ($row == 1) {
            $res = mysql_fetch_array($res);
            $stylishid = $res['stylish_id'];
        } else {
            $valid = false;
        }
    } else {
        if ($gender == 'male') {
            $sql = "SELECT stylish_id from stylists where stylish_gender='female' and status_id=1 ORDER BY stylish_id  LIMIT 1";
            $res = mysql_query($sql);
            $data = mysql_fetch_array($res);
            $lastfemaleid = $data['stylish_id'];
            $sql = "SELECT stylish_id from stylists where stylish_gender='female' and status_id=1 ORDER BY stylish_id  LIMIT 1";
            $res = mysql_query($sql);
            $data = mysql_fetch_array($res);
            $firstfemaleid = $data['stylish_id'];
            $sql = "SELECT stylish_id from userdetails where gender='male' ORDER BY user_id DESC LIMIT 1";
            $res = mysql_query($sql);
            $data = mysql_fetch_array($res);
            $lastuserid = $data['stylish_id'];
            $sql = "select stylish_id from stylists where stylish_id = (select min(stylish_id) from stylists where stylish_gender='female' AND stylish_id > $lastuserid and status_id=1)";
            $res = mysql_query($sql);
            $data = mysql_fetch_array($res);
            $randomid = $data['stylish_id'];
            if ($lastuserid == $lastfemaleid) {
                $stylishid = $firstfemaleid;
            } else {
                $stylishid = $randomid;
            }

        } else {
            $sql = "SELECT stylish_id FROM userdetails ORDER BY user_id DESC LIMIT 1 ";
            $res = mysql_query($sql);
            $data = mysql_fetch_array($res);
            $lastuserstylish = $data['stylish_id'];
            $sql = "SELECT stylish_id from stylists WHERE status_id=1 ORDER BY stylish_id DESC LIMIT 1";
            $res = mysql_query($sql);
            $data = mysql_fetch_array($res);
            $laststylishid = $data['stylish_id'];

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
    $username = "";
    $userimage = "";
    $checkuser = "SELECT * from userdetails where user_email='$email'";
    $result1 = mysql_query($checkuser);

    $rows = mysql_num_rows($result1);
    if ($valid) {
        if ($rows != 0) {
            $data = array('result' => 0, 'message' => 'User already registered with the given email');
            $data = login($email, $password, $gender);
        } else {
            $sql = "INSERT INTO userdetails(facebook_id,google_id,linked_id,user_email,user_pass,gender,stylish_id,username,userimage,bodyshape,bodytype,skintype,styletype,age,pricerange,clubprice,ethicprice,denimprice,footwearprice,height) VALUES('$facebookid','$googleid','$linkedid','$email','$password','$gender','$stylishid','$username','$userimage','$bodyshape','$bodytype','$skintype','$styletype','$age','$pricerange','$clubprice','$ethicprice','$denimprice','$footwearprice','$height')";
            $insert = mysql_query($sql);
            $lastid = mysql_insert_id();


            if ($lastid) {
                if ($stylishid != 0) {
                    $sql = "SELECT user_id,username,userimage,stylists.name as stylist_name,bodytype,bodyshape,height,age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice FROM userdetails Join stylists on stylists.stylish_id=userdetails.stylish_id  where userdetails.user_id='$lastid'";
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

                    $data = login($email, $password, $gender);
                } else {
                    $sql = "SELECT user_id,username,userimage,stylish_id,bodytype,bodyshape,height,age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice FROM userdetails Where user_id='$lastid'";

                    $select = mysql_query($sql);
                    $result = array();
                    while ($data = mysql_fetch_assoc($select)) {

                        $result[0] = $data['user_id'];
                        $result[1] = $data['username'];
                        $result[2] = $data['userimage'];
                        $result[3] = $data['stylish_id'];
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
                }
            } else {
                $data = array('result' => 'fail', 'message' => 'Error in adding user');
            }

        }
    } else {
        $data = array('result' => 'fail', 'message' => 'failed...You Entered Wrong Stylish Code');
    }
} elseif ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_REQUEST['email']) && !empty($_REQUEST['email']) && isset($_REQUEST['facebook_id']) && !empty($_REQUEST['facebook_id']) && isset($_REQUEST['gender']) && !empty($_REQUEST['gender']) && isset($_REQUEST['username']) && !empty($_REQUEST['username'])) {
    $email = $_REQUEST['email'];
    $facebookid = $_REQUEST['facebook_id'];
    $gender = $_REQUEST['gender'];
    $username = $_REQUEST['username'];
    $regId = $_REQUEST['regid'];
    $userimage = 'http://graph.facebook.com/' . $facebookid . '/picture?type=square';
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
        $sql = "SELECT stylish_id from stylists where stylish_gender='female' AND status_id=1 ORDER BY stylish_id DESC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $lastfemaleid = $data['stylish_id'];
        $sql = "SELECT stylish_id from stylists where stylish_gender='female' AND status_id=1 ORDER BY stylish_id ASC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $firstfemaleid = $data['stylish_id'];
        $sql = "SELECT stylish_id from userdetails where gender='male' ORDER BY user_id DESC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $lastuserid = $data['stylish_id'];
        $sql = "select stylish_id from stylists where stylish_id = (select min(stylish_id) from stylists where stylish_gender='female' AND stylish_id > $lastuserid AND status_id=1)";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $randomid = $data['stylish_id'];
        if ($lastuserid == $lastfemaleid) {
            $stylishid = $firstfemaleid;
        } else {
            $stylishid = $randomid;
        }

    } else {

        $sql = "SELECT stylish_id from stylists where stylish_gender='female' AND status_id=1 ORDER BY stylish_id DESC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $lastfemaleid = $data['stylish_id'];
        $sql = "SELECT stylish_id from stylists where stylish_gender='female' AND status_id=1 ORDER BY stylish_id ASC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $firstfemaleid = $data['stylish_id'];
        $sql = "SELECT stylish_id from userdetails where gender='female' ORDER BY user_id DESC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $lastuserid = $data['stylish_id'];
        $sql = "select stylish_id from stylists where stylish_id = (select min(stylish_id) from stylists where stylish_gender='female' AND stylish_id > $lastuserid AND status_id=1)";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $randomid = $data['stylish_id'];
        if ($lastuserid == $lastfemaleid) {
            $stylishid = $firstfemaleid;
        } else {
            $stylishid = $randomid;
        }
    }
    $password = "";
    $googleid = "";
    $linkedid = "";

    $checkuser = "SELECT * from userdetails where user_email='$email'";
    $result1 = mysql_query($checkuser);
    $rows = mysql_num_rows($result1);
    $checkfb = "SELECT * from userdetails where facebook_id='$facebookid'";
    $result2 = mysql_query($checkfb);

    $rows1 = mysql_num_rows($result2);
    if ($rows != 0) {
        if ($rows1 == 0) {
            $sql = "Update userdetails set facebook_id='$facebookid',gender='$gender'  where user_email='$email'";
            $res == mysql_query($sql);
        }
        //$data = array('result' => 0, 'message' => 'User already registered with the given email');
        $sql = "Update userdetails set regId='$regId' where user_email='$email'";
        $res == mysql_query($sql);
        $data = FacebookLogin($email, $facebookid, $gender, $username);

    } else {
        $sql = "INSERT INTO userdetails(facebook_id,google_id,linked_id,user_email,user_pass,gender,stylish_id,username,userimage,bodyshape,bodytype,skintype,styletype,age,pricerange,clubprice,ethicprice,denimprice,footwearprice,height,regId) VALUES('$facebookid','$googleid','$linkedid','$email','$password','$gender','$stylishid','$username','$userimage','$bodyshape','$bodytype','$skintype','$styletype','$age','$pricerange','$clubprice','$ethicprice','$denimprice','$footwearprice','$height','$regId')";
        $insert = mysql_query($sql);
        $lastid = mysql_insert_id();


        if ($lastid) {

            $sql = "SELECT user_id,username,userimage,stylish_id,bodytype,bodyshape,height,age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice FROM userdetails where user_id='$lastid'";
            $select = mysql_query($sql);
            $result = array();
            while ($data = mysql_fetch_assoc($select)) {

                $result[0] = $data['user_id'];
                $result[1] = $data['username'];
                $result[2] = $data['userimage'];
                $result[3] = $data['stylish_id'];
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
            $data = FacebookLogin($email, $facebookid, $gender, $username);
        } else {
            $data = array('result' => 'fail', 'message' => 'Error in adding user');
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_REQUEST['email']) && !empty($_REQUEST['email']) && isset($_REQUEST['google_id']) && !empty($_REQUEST['google_id']) && isset($_REQUEST['gender']) && !empty($_REQUEST['gender']) && isset($_REQUEST['username']) && !empty($_REQUEST['username'])) {

    $email = $_REQUEST['email'];
    $googleid = $_REQUEST['google_id'];
    $gender = $_REQUEST['gender'];
    $username = $_REQUEST['username'];
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
        $sql = "SELECT stylish_id from stylists where stylish_gender='female' AND status_id=1 ORDER BY stylish_id DESC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $lastfemaleid = $data['stylish_id'];
        $sql = "SELECT stylish_id from stylists where stylish_gender='female' AND status_id=1 ORDER BY stylish_id ASC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $firstfemaleid = $data['stylish_id'];
        $sql = "SELECT stylish_id from userdetails where gender='male' ORDER BY user_id DESC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $lastuserid = $data['stylish_id'];
        $sql = "select stylish_id from stylists where stylish_id = (select min(stylish_id) from stylists where stylish_gender='female' AND stylish_id > $lastuserid AND status_id=1)";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $randomid = $data['stylish_id'];
        if ($lastuserid == $lastfemaleid) {
            $stylishid = $firstfemaleid;
        } else {
            $stylishid = $randomid;
        }

    } else {

        $sql = "SELECT stylish_id from stylists where stylish_gender='female' AND status_id=1 ORDER BY stylish_id DESC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $lastfemaleid = $data['stylish_id'];
        $sql = "SELECT stylish_id from stylists where stylish_gender='female' AND status_id=1 ORDER BY stylish_id ASC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $firstfemaleid = $data['stylish_id'];
        $sql = "SELECT stylish_id from userdetails where gender='female' ORDER BY user_id DESC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $lastuserid = $data['stylish_id'];
        $sql = "select stylish_id from stylists where stylish_id = (select min(stylish_id) from stylists where stylish_gender='female' AND stylish_id > $lastuserid AND status_id=1)";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $randomid = $data['stylish_id'];
        if ($lastuserid == $lastfemaleid) {
            $stylishid = $firstfemaleid;
        } else {
            $stylishid = $randomid;
        }
    }
    $facebookid = "";
    $password = "";
    $linkedid = "";
    $userimage = $_REQUEST['userimage'];
    $checkuser = "SELECT * from userdetails where user_email='$email'";
    $result1 = mysql_query($checkuser);

    $rows = mysql_num_rows($result1);
    $checkg = "SELECT * from userdetails where google_id='$googleid'";
    $result2 = mysql_query($checkg);

    $rows1 = mysql_num_rows($result2);

    if ($rows != 0) {
        if ($rows1 == 0) {
            $sql = "Update userdetails set google_id='$googleid',gender='$gender' where user_email='$email'";
            $res == mysql_query($sql);
        }
        //$data = array('result' => 'fail', 'message' => 'User already registered with the given email');
        $sql = "Update userdetails set regId='$regId' where user_email='$email'";
        $res == mysql_query($sql);
        $data = GoogleLogin($email, $googleid, $gender, $username);
    } else {
        $sql = "INSERT INTO userdetails(facebook_id,google_id,linked_id,user_email,user_pass,gender,stylish_id,username,userimage,bodyshape,bodytype,skintype,styletype,age,pricerange,clubprice,ethicprice,denimprice,footwearprice,height,regId) VALUES('$facebookid','$googleid','$linkedid','$email','$password','$gender','$stylishid','$username','$userimage','$bodyshape','$bodytype','$skintype','$styletype','$age','$pricerange','$clubprice','$ethicprice','$denimprice','$footwearprice','$height','$regId')";
        $insert = mysql_query($sql);
        $lastid = mysql_insert_id();
        if ($lastid) {

            $sql = "SELECT user_id,username,userimage,stylish_id,bodytype,bodyshape,height,age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice FROM userdetails where user_id='$lastid'";
            $select = mysql_query($sql);
            $result = array();
            while ($data = mysql_fetch_assoc($select)) {

                $result[0] = $data['user_id'];
                $result[1] = $data['username'];
                $result[2] = $data['userimage'];
                $result[3] = $data['stylish_id'];
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
            $data = GoogleLogin($email, $googleid, $gender, $username);
        } else {
            $data = array('result' => 'fail', 'message' => 'Error in adding user');
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_REQUEST['email']) && !empty($_REQUEST['email']) && isset($_REQUEST['linked_id']) && !empty($_REQUEST['linked_id']) && isset($_REQUEST['gender']) && !empty($_REQUEST['gender']) && isset($_REQUEST['username']) && !empty($_REQUEST['username'])) {
    $email = $_REQUEST['email'];
    $linkedid = $_REQUEST['linked_id'];
    $gender = $_REQUEST['gender'];
    $username = $_REQUEST['username'];
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
        $sql = "SELECT stylish_id from stylists where stylish_gender='female' AND status_id=1 ORDER BY stylish_id DESC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $lastfemaleid = $data['stylish_id'];
        $sql = "SELECT stylish_id from stylists where stylish_gender='female' AND status_id=1 ORDER BY stylish_id  LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $firstfemaleid = $data['stylish_id'];
        $sql = "SELECT stylish_id from userdetails where gender='male' ORDER BY user_id DESC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $lastuserid = $data['stylish_id'];
        $sql = "select stylish_id from stylists where stylish_id = (select min(stylish_id) from stylists where stylish_gender='female' AND stylish_id > $lastuserid AND status_id=1)";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $randomid = $data['stylish_id'];
        if ($lastuserid == $lastfemaleid) {
            $stylishid = $firstfemaleid;
        } else {
            $stylishid = $randomid;
        }

    } else {
        $sql = "SELECT stylish_id FROM userdetails ORDER BY user_id DESC LIMIT 1 ";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $lastuserstylish = $data['stylish_id'];
        $sql = "SELECT stylish_id from stylists WHERE status_id=1 ORDER BY stylish_id DESC LIMIT 1";
        $res = mysql_query($sql);
        $data = mysql_fetch_array($res);
        $laststylishid = $data['stylish_id'];

        if ($lastuserstylish == $laststylishid) {
            $stylishid = $lastuserstylish - ($laststylishid - 1);
        } else {
            $stylishid = $lastuserstylish + 1;
        }
    }
    $facebookid = "";
    $googleid = "";
    $password = "";
    $userimage = "";
    $checkuser = "SELECT * from userdetails where user_email='$email'";
    $result1 = mysql_query($checkuser);

    $rows = mysql_num_rows($result1);

    if ($rows != 0) {
        //$data = array('result' => 'fail', 'message' => 'User already registered with the given email');
        $data = LinkedinLogin($email, $linkedid, $gender, $username);
    } else {
        $sql = "INSERT INTO userdetails(facebook_id,google_id,linked_id,user_email,user_pass,gender,stylish_id,username,userimage,bodyshape,bodytype,skintype,styletype,age,pricerange,clubprice,ethicprice,denimprice,footwearprice,height) VALUES('$facebookid','$googleid','$linkedid','$email','$password','$gender','$stylishid','$username','$userimage','$bodyshape','$bodytype','$skintype','$styletype','$age','$pricerange','$clubprice','$ethicprice','$denimprice','$footwearprice','$height')";
        $insert = mysql_query($sql);
        $lastid = mysql_insert_id();


        if ($lastid) {


            $sql = "SELECT user_id,username,userimage,stylish_id,bodytype,bodyshape,height,age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice FROM userdetails where user_id='$lastid'";
            $select = mysql_query($sql);
            $result = array();
            while ($data = mysql_fetch_assoc($select)) {

                $result[0] = $data['user_id'];
                $result[1] = $data['username'];
                $result[2] = $data['userimage'];
                $result[3] = $data['stylish_id'];
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
            $data = LinkedinLogin($email, $linkedid, $gender, $username);
        } else {
            $data = array('result' => 'fail', 'message' => 'Error in adding user');
        }
    }
} else {
    $data = array('result' => 'fail', 'message' => 'Request method is wrong or some parameters missing!');

}

function login($email, $password, $gender)
{

    $sql = "SELECT user_email,user_pass,gender from userdetails where user_email='$email'";
    $res = mysql_query($sql);
    $login = array();
    $rows = mysql_num_rows($res);
    if ($rows == 1) {
        while ($data = mysql_fetch_array($res)) {
            $login[0] = $data['user_email'];
            $login[1] = $data['user_pass'];
            $login[2] = $data['gender'];
        }
    } else {

        $login[0] = "";
        $login[1] = "";
        $login[2] = "";

    }
    if ($login[0] == $email && $login[1] == $password && $login[2] == $gender) {
        if (isset($_POST['stylishcode']) && !empty($_POST['stylishcode']) && $row == 1) {
            $sql = "SELECT user_id from userdetails where user_email='$email' AND user_pass='$password'";
            $res = mysql_query($sql);
            $data = mysql_fetch_assoc($res);
            $userid = $data['user_id'];
            $query = "UPDATE userdetails SET stylish_id='$stylishid' where user_id='$userid'";
            $res = mysql_query($query);
        }


        $sql = "SELECT user_id,stylish_id from userdetails where user_email='$email' AND user_pass='$password'";
        $result = array();
        $res = mysql_query($sql);
        while ($data = mysql_fetch_assoc($res)) {


            $userid = $data['user_id'];
            $result[1] = $data['stylish_id'];

        }


        if ($result[1] != 0) {
            $sql = "SELECT user_id,username,userimage,stylists.name as stylist_name,bodytype,bodyshape,height,age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice,stylists.code as stylist_code, stylists.image as stylist_image FROM userdetails Join stylists on stylists.stylish_id=userdetails.stylish_id where userdetails.user_id='$userid'";

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
            }

            $data = array('result' => 'success', 'message' => 'Login Success ', 'response body' => array("user_id" => $result[0], "username" => $result[1], "userimage" => "http://istyleyou.in/istyleyouapi/profileimage/" . $result[2], "stylish_name" => $result[3], "body_type" => $result[4], "body_shape" => $result[5], "height" => $result[6], "age" => $result[7], "skin_type" => $result[8], 'price range' => array("club" => $result[10], "ethic" => $result[11], "denim" => $result[12], "footwear" => $result[13]), 'styletype' => $result[9], 'stylishcode' => $result[14], 'stylishimage' => $result[15]));

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

function FacebookLogin($email, $facebookid, $gender, $username)
{
    $sql = "SELECT user_email,facebook_id,gender from userdetails where user_email='$email'";
    $res = mysql_query($sql);
    $login = array();
    $rows = mysql_num_rows($res);
    if ($rows == 1) {
        while ($data = mysql_fetch_array($res)) {
            $login[0] = $data['user_email'];
            $login[1] = $data['facebook_id'];
            $login[2] = $data['gender'];
        }
    } else {

        $login[0] = "";
        $login[1] = "";
        $login[2] = "";

    }
    if ($login[0] == $email && $login[1] == $facebookid && $login[2] == $gender) {
        $sql = "SELECT user_id,stylish_id from userdetails where user_email='$email' AND facebook_id='$facebookid'";
        $result = array();
        $res = mysql_query($sql);
        while ($data = mysql_fetch_assoc($res)) {


            $userid = $data['user_id'];
            $result[1] = $data['stylish_id'];

        }


        if ($result[1] != 0) {
            $sql = "SELECT user_id,username,userimage,stylists.name as stylist_name,bodytype,bodyshape,height,age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice, stylists.code as stylist_code, stylists.image as stylist_image, stylists.stylish_id FROM userdetails Join stylists on stylists.stylish_id=userdetails.stylish_id where userdetails.user_id='$userid'";

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
                $result[16] = $data['stylish_id'];
            }
            $data = array('result' => 'success', 'message' => 'Login Success ', 'response body' => array("user_id" => $result[0], "username" => $result[1], "userimage" => $result[2], "stylish_name" => $result[3], "body_type" => $result[4], "body_shape" => $result[5], "height" => $result[6], "age" => $result[7], "skin_type" => $result[8], 'price range' => array("club" => $result[10], "ethic" => $result[11], "denim" => $result[12], "footwear" => $result[13]), 'styletype' => $result[9], 'stylecode' => $result[14], 'styleimage' => $result[15], 'stylish_id' => $result[16]));
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

function GoogleLogin($email, $googleid, $gender, $username)
{
    $sql = "SELECT user_email,google_id,gender from userdetails where user_email='$email'";
    $res = mysql_query($sql);
    $login = array();
    $rows = mysql_num_rows($res);
    if ($rows == 1) {
        while ($data = mysql_fetch_array($res)) {
            $login[0] = $data['user_email'];
            $login[1] = $data['google_id'];
            $login[2] = $data['gender'];
        }
    } else {

        $login[0] = "";
        $login[1] = "";
        $login[2] = "";

    }
    if ($login[0] == $email && $login[1] == $googleid && $login[2] == $gender) {
        $sql = "SELECT user_id,stylish_id from userdetails where user_email='$email' AND google_id='$googleid'";
        $result = array();
        $res = mysql_query($sql);
        while ($data = mysql_fetch_assoc($res)) {


            $userid = $data['user_id'];
            $result[1] = $data['stylish_id'];

        }


        if ($result[1] != 0) {
            $sql = "SELECT user_id,username,userimage,stylists.name as stylist_name,bodytype,bodyshape,height,age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice, stylists.code as stylist_code, stylists.image as stylist_image, stylists.stylish_id FROM userdetails Join stylists on stylists.stylish_id=userdetails.stylish_id where userdetails.user_id='$userid'";

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
                $result[16] = $data['stylish_id'];
            }
            $data = array('result' => 'success', 'message' => 'Login Success ', 'response body' => array("user_id" => $result[0], "username" => $result[1], "userimage" => "http://istyleyou.in/istyleyouapi/profileimage/" . $result[2], "stylish_name" => $result[3], "body_type" => $result[4], "body_shape" => $result[5], "height" => $result[6], "age" => $result[7], "skin_type" => $result[8], 'price range' => array("club" => $result[10], "ethic" => $result[11], "denim" => $result[12], "footwear" => $result[13]), 'styletype' => $result[9], 'stylecode' => $result[14], 'styleimage' => $result[15], 'stylish_id' => $result[16]));
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

function LinkedinLogin($email, $linkedid, $gender, $username)
{
    $sql = "SELECT user_email,linked_id,gender from userdetails where user_email='$email'";
    $res = mysql_query($sql);
    $login = array();
    $rows = mysql_num_rows($res);
    if ($rows == 1) {
        while ($data = mysql_fetch_array($res)) {
            $login[0] = $data['user_email'];
            $login[1] = $data['linked_id'];
            $login[2] = $data['gender'];
        }
    } else {

        $login[0] = "";
        $login[1] = "";
        $login[2] = "";

    }
    if ($login[0] == $email && $login[1] == $linkedid && $login[2] == $gender) {
        $sql = "SELECT user_id,stylish_id from userdetails where user_email='$email' AND linked_id='$linkedid'";
        $result = array();
        $res = mysql_query($sql);
        while ($data = mysql_fetch_assoc($res)) {


            $userid = $data['user_id'];
            $result[1] = $data['stylish_id'];

        }


        if ($result[1] != 0) {
            $sql = "SELECT user_id,username,userimage,stylists.name as stylist_name,bodytype,bodyshape,height,age,skintype,styletype,clubprice,ethicprice,denimprice,footwearprice, stylists.code as stylist_code, stylists.image as stylist_image FROM userdetails Join stylists on stylists.stylish_id=userdetails.stylish_id where userdetails.user_id='$userid'";

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
            }
            $data = array('result' => 'success', 'message' => 'Login Success ', 'response body' => array("user_id" => $result[0], "username" => $result[1], "userimage" => "http://istyleyou.in/istyleyouapi/profileimage/" . $result[2], "stylish_name" => $result[3], "body_type" => $result[4], "body_shape" => $result[5], "height" => $result[6], "age" => $result[7], "skin_type" => $result[8], 'price range' => array("club" => $result[10], "ethic" => $result[11], "denim" => $result[12], "footwear" => $result[13]), 'styletype' => $result[9], 'stylecode' => $result[14], 'styleimage' => $result[15]));
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

?>
