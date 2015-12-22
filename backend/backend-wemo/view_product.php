<?php  session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<style>

.error {color: #FF0000;}

</style>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="images/favicon.png" type="image/png">

  <title>I Style You</title>

  <link href="css/style.default.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <script src="js/respond.min.js"></script>
  <![endif]-->


  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>


<script type="text/javascript">
$(document).ready(function(){   
  var window_height= $('.content_div').height();
    var start_pos=10;
    
    var start=parseInt($('#start').val());
    var query_str=$('#serstr').val();
    
    var scroll=80; //set this to 'Scroll position(see below)' you see after scrolling down the container
    $('#vm').click(function() {
      var scroll_pos=$('.content_div').scrollTop()+ start_pos;  
      $('.content_div').append('<div class="loader"><img src="http://ajaxload.info/images/exemples/4.gif" /></div>');
        var str='start='+start+"&qstring="+query_str;       
        $.ajax({
          dataType : 'json',
          type: "POST", 
          url: 'lazy-loading1.php',
          data: str,  
          async:false,
          success: function(msg){           
            console.log(msg.status);          
            $('.content_div').append(msg.htm);            
            $('.loader').remove();
            start = start+12;
          $('#start').val(start);
          }   
        });
      start_pos+=10; //replace 10 with len (no. of rows to be fetched on every ajax call), same as 'len'
      scroll+=window_height+80; //replace 80 with 'scroll' value above, don't just write 'scroll' here
      
  });
});
</script>
</head>

<body>
<!-- Preloader -->
<div id="preloader">
    <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
</div>

<section>

  <div class="leftpanel">

    <div class="logopanel">
        <h1><span>[</span> I Style You <span>]</span></h1>
    </div><!-- logopanel -->

    <div class="leftpanelinner">

        <!-- This is only visible to small devices -->
        <div class="visible-xs hidden-sm hidden-md hidden-lg">
            <div class="media userlogged">
                <img alt="" src="images/photos/loggeduser.png" class="media-object">
                <div class="media-body">
                    <h4>Test User</h4>
                    <span>"Life is so..."</span>
                </div>
            </div>

            <h5 class="sidebartitle actitle">Account</h5>
            <ul class="nav nav-pills nav-stacked nav-bracket mb30">
              <li><a href="query.php?action=logout"><i class="fa fa-sign-out"></i> <span>Sign Out</span></a></li>
            </ul>
        </div>

      <h5 class="sidebartitle">Navigation</h5>
      <ul class="nav nav-pills nav-stacked nav-bracket">
        <li class="active"><a href="dashboard.php"><i class="fa fa-home"></i> <span>Dashboard</span></a></li>
        <li><a href="create_look.php"><i class="fa fa-envelope-o"></i> <span>Create Look</span></a></li>
        <li><a href="look_list.php"><i class="fa fa-envelope-o" ></i> <span>List All</span></a></li>
        <li><a href="view_product.php"><i class="fa fa-envelope-o"></i> <span>View Products</span></a></li>
        <li><a href="create_stylist.php"><i class="fa fa-envelope-o"></i> <span>Create Stylist</span></a></li>
        
        <li><a href="list_stylist.php"><i class="fa fa-envelope-o"></i> <span>See Stylist</span></a></li>
        <li><a href="list_usres.php"><i class="fa fa-envelope-o"></i> <span>See All Users</span></a></li>
      </ul>

    

    </div><!-- leftpanelinner -->
  </div><!-- leftpanel -->

  <div class="mainpanel">

    <div class="headerbar">

      <a class="menutoggle"><i class="fa fa-bars"></i></a>

      <form class="searchform" action="" method="post">
        <input type="text" class="form-control" name="keyword" placeholder="Search here..." />
      </form>

      <div class="header-right">
        <ul class="headermenu">
          <li>
            <div class="btn-group">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <img src="images/photos/loggeduser.png" alt="" />
                 Test User
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                <li><a href="query.php?action=logout"><i class="glyphicon glyphicon-log-out"></i> Log Out</a></li>
              </ul>
            </div>
          </li>
          
        </ul>
      </div><!-- header-right -->

    </div><!-- headerbar -->
<?php 
  include 'databaseconnect.php';

  // session_start();

 
  
  
  
$sql1="SELECT * from lookdescrip ORDER BY id DESC LIMIT 0,12";
    $valid=true;
  
  
?>

    <div class="pageheader">
      <h2><i class="fa fa-home"></i> Look Style <span>Subtitle goes here...</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">You are here:</span>
        <ol class="breadcrumb">
          <li><a href="index.html">Istyleyou</a></li>
          <li class="active">Dashboard</li>
        </ol>
      </div>
    </div>

    <div class="contentpanel">
     
    <form method="POST" id="select1" >
      <div class="content_div">
      <div>
      <div class="row">
<!--image code 1-->

<?php


  
  if($valid){

        $result1=mysql_query($sql1);

$images = array();
$index = 0;

while($row = mysql_fetch_assoc($result1)) // loop to give you the data in an associative array so you can use it however.
{
     $images[$index] = $row;
     $index++;
}
$rows=mysql_num_rows($result1);

if($rows==0)
{

echo $result_err="No Results Found";

}
foreach($images as &$value)
{
?>

        <div class="col-sm-6 col-md-3">
          <div class="panel panel-dark panel-stat">
            <div class="panel-heading">
              <div class="stat">
                <div class="row">
                  <div class="col-xs-12">
          
           
           <a href="list_style_item.php?id=<?php echo $value['id'] ?>"><img class="img-thumbnail" src="<?php echo $value['upload_image'] ?>"  alt="" /></a>
                
                       
                  </div>
                  
                </div><!-- row -->

                <div class="mb15"></div>
                <div class="row">
                  <div class="col-xs-6">
                    <div class="checkbox block "><label><input type="checkbox" class="cbox"id="chkSelect" name="select[]" value="<?php echo $value['id'] ?>"> Select</label></div>
                  </div>

                 
                </div><!-- row -->

              </div><!-- stat -->

            </div><!-- panel-heading -->
          </div><!-- panel -->
        </div><!-- col-sm-6 -->
   <?php
          }
        ?>
        
        <?php
          }
          if($valid)
          {
?>  


      </div><!-- row -->
      <?php 
$sql1=substr($sql1,0,-11);



      ?>
        
  </div>
</div>
<input type="hidden" name="serstr" id="serstr" value="<?php echo $sql1; ?>" />
       <input type="hidden" name="start" id="start" value="12" />
       
<?php 

if($rows!=0){ ?>
<center><div id="vm" class="btn btn-success">view more</div></center>
<?php
} 
}
?>

        </form>  

      </div>

    </div><!-- contentpanel -->

  </div><!-- mainpanel -->

  <div class="rightpanel">
    
  </div><!-- rightpanel -->
<?php
//print_r($_REQUEST);
  if(!empty($_REQUEST['userid'])){
       $_SESSION["users"]=$_REQUEST['userid']; 
      $userid=$_REQUEST['userid'];


      $regid=array();
      foreach($userid as $uid){
          if(!empty($uid)){
              $fetch="select regId from userdetails where user_id='$uid'";

              $res=mysql_query($fetch);
              $check="update asklook set send='1' where user_id='$uid'";
              $res1=mysql_query($check);
              while($data=mysql_fetch_array($res)){
                $rid=$data[0];
                array_push($regid, $rid);
                $_SESSION['reg']=$regid;
              }
          }
      }
  }  


 
if(isset($_POST['send']) && !empty($_REQUEST['select'])){
 $id=$_SESSION['users'];
 foreach($id as $uid1){
$looks=$_REQUEST['select'];
$r=0;
foreach($looks as $value){
  if($r==0){
    $sql="select look_image from createdlook where look_id='$value'";
    $res=mysql_query($sql);
    $data=mysql_fetch_array($res);
    $firstlook=$data[0];
    $r++;
  }
  $insert="Insert into sendlook(user_id,look_id) Values('$uid1','$value')";
  $res=mysql_query($insert);
  
}
}

include_once 'push.php';
$push = new pushmessage();

$regid=$_SESSION['reg'];

$params = array("pushtype"=>"android", "registration_id"=>$regid, "message"=>"Hello, an android user","look_url"=>"uploadfile1/55b5ff00def251437990656.jpeg");

$rtn = $push->sendMessage($params);

}
 

?>


</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

<script src="js/jquery-migrate-1.2.1.min.js"></script>
<script src="js/jquery-ui-1.10.3.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/modernizr.min.js"></script>
<script src="js/jquery.sparkline.min.js"></script>
<script src="js/toggles.min.js"></script>
<script src="js/retina.min.js"></script>
<script src="js/jquery.cookies.js"></script>

<script src="js/flot/jquery.flot.min.js"></script>
<script src="js/flot/jquery.flot.resize.min.js"></script>
<script src="js/flot/jquery.flot.spline.min.js"></script>
<script src="js/morris.min.js"></script>
<script src="js/raphael-2.1.0.min.js"></script>

<script src="js/custom.js"></script>
<script src="js/dashboard.js"></script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-55586762-1', 'auto');
  ga('send', 'pageview');

</script>

</body>
</html>
