<?php
include_once 'push.php';
$push = new pushmessage();

$params = array("pushtype"=>"android", "registration_id"=>"e9owNRnYnAQ:APA91bGdnqLtkcpsNmOuglLUuApInrHdTR1H9y_H2Kz3PTotBBX14ZPTaFNgb7rfUxsXCavGu_hJSRSPa3OIqQ9lMbyCwWzpWhsyeg1lJNFMxn6Cj2bJzgCmgqoKBgV0UIvn8OO3yCXZ", "message"=>"Hello, an ios user",'look_url'=>"gfh");

$rtn = $push->sendMessage($params);

?>