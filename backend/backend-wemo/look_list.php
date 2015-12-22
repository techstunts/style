<?php  session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<style>

.error {color: #FF0000;}
.stick {
  position: fixed;
  top: 65px;
  margin-left: 900px;
  width: 100%;
  z-index: 999;
}
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

  <script>
$(document).ready(function () {
     $('#button1').click(function (){
         var checkedNum = $('input[name="select[]"]:checked').length;

          if(!checkedNum){
            alert("Please select at least one look");
            return false;
          }
      });
});
</script>
  <script type="text/javascript">
 $(document).ready(function() {
    var s = $("#send1");
            var pos = s.position();                      
            $(window).scroll(function() {
                var windowpos = $(window).scrollTop()+60;   
                if (windowpos >= pos.top) {
                    s.addClass("stick");
                } else {
                    s.removeClass("stick");   
                }
            });
});
  </script>
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
          url: 'lazy-loading.php',
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
$lookerror="";
  $result_err="";
  $str="";
  $valid="";
   $valid1="";
$valid=true;
  $query_str1="";
  if(!empty($_REQUEST['bodytype'])){  
         
           $query_str1.="body_type='";
        $query_str1.=$_POST['bodytype'];
      $query_str1.=" ' ";
    
   
    $str.="body_type=".$_POST['bodytype'];
    
    }
    
  if(!empty($_POST['budget'])){
    if( !empty($_REQUEST['bodytype']) || (!empty($_REQUEST['gender']) AND !empty($_REQUEST['age']) AND !empty($_REQUEST['occasion']) AND !empty($_REQUEST['producttype1']) AND !empty($_REQUEST['bodytype']) ) )
    {
      $query_str1 .=" AND ";
      $str.="&";
    }
               $query_str1.="budget='";
        $query_str1.=$_POST['budget'];
      $query_str1.=" ' ";
      
   
    $str.="budget=".$_POST['budget'];
  
  }
  
  if(!empty($_POST['age'])){
    if(!empty($_REQUEST['bodytype']) || !empty($_REQUEST['budget'])  || (!empty($_REQUEST['gender']) AND !empty($_REQUEST['occasion']) AND !empty($_REQUEST['producttype1']) AND !empty($_REQUEST['bodytype']) AND !empty($_REQUEST['budget']) ) )
    {
    $query_str1 .=" AND ";
    $str.="&";
    }
      
           $query_str1.="age='";
        $query_str1.=$_POST['age'];
      $query_str1.=" ' ";
    $str.="age=".$_POST['age'];
    
  }
  if(!empty($_POST['occasion'])){
    if(!empty($_REQUEST['bodytype']) || !empty($_REQUEST['budget'])  || !empty($_REQUEST['age']) || (!empty($_REQUEST['gender']) AND !empty($_REQUEST['age']) AND !empty($_REQUEST['occasion']) AND !empty($_REQUEST['producttype1']) AND !empty($_REQUEST['bodytype']) AND !empty($_REQUEST['budget']) ) )
    {
    $query_str1 .=" AND ";
      $str.="&";
    }
   
           $query_str1.="occasion LIKE '";
        $query_str1.='%'.$_POST['occasion'];
      $query_str1.="%' ";
    
   
      $str.="occasion=".$_POST['occasion'];
  
  }
  
  if(!empty($_POST['gender'])){
    if(!empty($_REQUEST['bodytype']) || !empty($_REQUEST['budget'])  || !empty($_REQUEST['age']) || !empty($_REQUEST['occasion']) || (!empty($_REQUEST['age']) AND !empty($_REQUEST['occasion']) AND !empty($_REQUEST['producttype1']) AND !empty($_REQUEST['bodytype']) AND !empty($_REQUEST['budget']) AND !empty($_REQUEST['age']) AND !empty($_REQUEST['occasion'])) )
    {
    $query_str1 .=" AND ";
    $str.="&";
    }
     
           $query_str1.="gender='";
        $query_str1.=$_POST['gender'];
      $query_str1.=" ' ";
    
    $str.="gender=".$_POST['gender'];
  
  }
  if(!empty($_REQUEST['producttype1'])){
    if(!empty($_REQUEST['bodytype']) || !empty($_REQUEST['budget'])  || !empty($_REQUEST['age']) || !empty($_REQUEST['gender']) || !empty($_REQUEST['occasion']) || (!empty($_REQUEST['gender']) AND !empty($_REQUEST['age']) AND !empty($_REQUEST['occasion']) AND !empty($_REQUEST['bodytype']) AND !empty($_REQUEST['budget']) AND !empty($_REQUEST['age']) ))
    {
    $query_str1 .=" AND ";
    $str.="&";
    }
    
           $query_str1.="product_type='";
        $query_str1.=$_POST['producttype1'];
      $query_str1.=" ' ";
    
 
    $str.="producttype=".$_POST['producttype1'];
    }
  
  if(!empty($_REQUEST['filter'])) {
    $valid=true;
    if(!empty($_REQUEST['gender']) || !empty($_REQUEST['bodytype']) || !empty($_REQUEST['budget']) || !empty($_REQUEST['age']) || !empty($_REQUEST['occasion']) || !empty($_REQUEST['producttype1']) || (!empty($_REQUEST['gender']) AND !empty($_REQUEST['bodytype']) AND !empty($_REQUEST['budget']) AND !empty($_REQUEST['age']) AND !empty($_REQUEST['occasion']) AND !empty($_REQUEST['producttype1'])) )
    {
    $query_str1.=" AND ( ";
    
    }
    else{
    $query_str1.="(";
  }
  $filtering=$_REQUEST['filter'];
   
    foreach ($filtering as $value){
      if(!empty($value)){
                 $query_str1.="stylish_id='";
        $query_str1.=$value;
      $query_str1.=" ' ";
    
        $query_str1 .=" OR ";
      }           
    }
    $query_str1=substr($query_str1,0,-4);
          $query_str1.=")";
          
    }

        //$filter_err="Please Select atleast 1 filter";
        //$valid=false;
  //if(!empty($_POST['gender']) || !empty($_POST['bodytype']) || !empty($_POST['budget']) || !empty($_POST['age']) || !empty($_POST['occasion']) || !empty($_POST['producttype1']))     
  //{
    if(!empty($_POST['bodytype']) || !empty($_POST['budget']) || !empty($_POST['gender']) || !empty($_POST['age']) || !empty($_POST['occasion']) || !empty($_POST['producttype1']) || !empty($_POST['filter'])){
  
  $valid=true;
  $filter_err="";
  
  
  
$sql1="SELECT * from lookdescrip join createdlook ON createdlook.product_id1=lookdescrip.id where ".$query_str1." ORDER BY look_id DESC LIMIT 0,12";
  
    
  }else{
    
       
        
        $valid1=true;
        $valid=false;
        $valid=true;
        $filter_err="";
        $str = '';
        $sql1="SELECT * from createdlook  ORDER BY `look_id` DESC LIMIT 0,12";
    } 
    
    

  
  
  /*
 else{
    if(empty($_REQUEST['gender']) AND empty($_REQUEST['bodytype']) AND empty($_REQUEST['budget']) AND empty($_REQUEST['age']) AND empty($_REQUEST['occasion']) AND empty($_REQUEST['producttype1']) ){
        $filter_err="Please Select atleast 1 filter";
        $valid=false;
    }
  }*/

  
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
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default">          
          <div class="panel-heading">
              <h4 class="panel-title">Stylish Look Filter</h4>  <br>
  <span class="error"> <?php echo $filter_err;?></span>
   
            </div>
      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <div class="form-group">
              <label class="col-sm-0 control-label" for="checkbox"></label>
              <div class="col-sm-5">
          <?php
        $sql = "SELECT * FROM stylish_details";
$result = mysql_query($sql);

while ($row = mysql_fetch_array($result)) {
    echo "<div class="."checkbox block"."><label><input type="."'checkbox'"."name="."filter[]"." value='" . $row['stylish_id'] . "'>" . $row['stylish_name'] . "</label></div>";
}

?>
              </div>
          </div>
        </div>    
        </div>
       <div class="col-md-8">
          <div class="panel panel-default">
            <div class="panel-heading">
              <div class="panel-btns">
                <a href="" class="minimize">&minus;</a>
              </div>
              <h4 class="panel-title">More Filters</h4>        
            </div>          
            <div class="panel-body">
              <div class="row row-pad-5">
                <div class="col-lg-4">
                 <select class="form-control mb15" name="bodytype">
          <option value="">Select Body Type</option>
          <option <?php if (isset($_REQUEST['bodytype']) && $_REQUEST['bodytype']=="Apple") echo "selected";?> value="Apple">Apple</option>
          <option <?php if (isset($_REQUEST['bodytype']) && $_REQUEST['bodytype']=="Banana") echo "selected";?> value="Banana">Banana</option>
          <option <?php if (isset($_REQUEST['bodytype']) && $_REQUEST['bodytype']=="Pear") echo "selected";?> value="Pear">Pear</option>
            <option <?php if (isset($_REQUEST['bodytype']) && $_REQUEST['bodytype']=="Hourglass") echo "selected";?> value="Hourglass">Hourglass</option>
          <option <?php if (isset($_REQUEST['bodytype']) && $_REQUEST['bodytype']=="Muscular") echo "selected";?> value="Muscular">Muscular</option>
          <option <?php if (isset($_REQUEST['bodytype']) && $_REQUEST['bodytype']=="Regular") echo "selected";?> value="Regular">Regular</option>
          <option <?php if (isset($_REQUEST['bodytype']) && $_REQUEST['bodytype']=="Round") echo "selected";?> value="Round">Round</option>
                </select>
                </div>
                <div class="col-lg-4">
                  <select class="form-control mb15" name="budget">        
          <option value="">Select Budget</option>
                  <option <?php if (isset($_REQUEST['budget']) && $_REQUEST['budget']=="<2000") echo "selected";?> value="2000"><2000</option>
                  <option <?php if (isset($_REQUEST['budget']) && $_REQUEST['budget']=="2000-5000") echo "selected";?> value="2000-5000">2000-5000</option>
                  <option <?php if (isset($_REQUEST['budget']) && $_REQUEST['budget']=="5000-10000") echo "selected";?> value="5000-10000">5000-10000</option>
          <option <?php if (isset($_REQUEST['budget']) && $_REQUEST['budget']==">10000") echo "selected";?> value=">10000">>10000</option>
                </select>
                </div>
                <div class="col-lg-4">
                 <select class="form-control mb15" name="age">         
          <option value="">Select Age</option>
                  <option <?php if (isset($_REQUEST['age']) && $_REQUEST['age']=="Teenager") echo "selected";?> value="Teenager">Teenager</option>
                  <option <?php if (isset($_REQUEST['age']) && $_REQUEST['age']=="Young(18-22)") echo "selected";?> value="Young(18-22)">Young(18-22)</option>
                  <option  <?php if (isset($_REQUEST['age']) && $_REQUEST['age']=="Young(18-22)") echo "selected";?> value="Young Medium (22-30)">Young Medium (22-30)</option>
          <option  <?php if (isset($_REQUEST['age']) && $_REQUEST['age']=="Medium (30-40)") echo "selected";?> value="Medium (30-40)</">Medium (30-40)</option>
          <option <?php if (isset($_REQUEST['age']) && $_REQUEST['age']=="Old > 40") echo "selected";?> value="Old > 40">Old > 40</option>
                </select>
                </div>
              </div><!-- row -->
              <div class="row row-pad-5">
                <div class="col-lg-4">
                 <select class="form-control mb15" name="occasion">       
          <option value="">Select Occasion</option>
          <option <?php if (isset($_REQUEST['occasion']) && $_REQUEST['occasion']=="Work") echo "selected";?> value="Work">Work Wear</option>
                  <option <?php if (isset($_REQUEST['occasion']) && $_REQUEST['occasion']=="Wine") echo "selected";?> value="Wine">Wine & Dine</option>
                  <option <?php if (isset($_REQUEST['occasion']) && $_REQUEST['occasion']=="Ethnic") echo "selected";?> value="Ethnic">Ethnic/Festive</option>
                  <option <?php if (isset($_REQUEST['occasion']) && $_REQUEST['occasion']=="Club") echo "selected";?> value="Club">Club</option>
          <option <?php if (isset($_REQUEST['occasion']) && $_REQUEST['occasion']=="Casuals") echo "selected";?> value="Casuals">Casuals</option>
                </select>
                </div>
                <div class="col-lg-4">
                  <select class="form-control mb15" name="gender">         
           <option value="">Select Gender</option>
            <option <?php if (isset($_REQUEST['gender']) && $_REQUEST['gender']=="Male") echo "selected";?> value="Male">Male</option>
                  <option <?php if (isset($_REQUEST['gender']) && $_REQUEST['gender']=="Female") echo "selected";?> value="Female">Female</option>              
                </select>
                </div>
                <div class="col-lg-4">
                    <select class="form-control mb15"name="producttype1">         
            <option value="">Select Product Type </option>
            <option <?php if (isset($_REQUEST['producttype1']) && $_REQUEST['producttype1']=="Shirts") echo "selected";?> value="Shirts">Shirts</option>
            <option <?php if (isset($_REQUEST['producttype1']) && $_REQUEST['producttype1']=="Saree") echo "selected";?> value="Saree">Saree</option>
            <option <?php if (isset($_REQUEST['producttype1']) && $_REQUEST['producttype1']=="Ethnic top") echo "selected";?> value="Ethnic top">Ethnic top</option>
            <option <?php if (isset($_REQUEST['producttype1']) && $_REQUEST['producttype1']=="Ethnic Bottom") echo "selected";?> value="Ethnic Bottom">Ethnic Bottom</option>
            <option <?php if (isset($_REQUEST['producttype1']) && $_REQUEST['producttype1']=="Top wear") echo "selected";?> value="Top wear">Top wear</option>
            <option <?php if (isset($_REQUEST['producttype1']) && $_REQUEST['producttype1']=="Winter Wear") echo "selected";?> value="Winter Wear">Winter Wear</option>
            <option <?php if (isset($_REQUEST['producttype1']) && $_REQUEST['producttype1']=="Skirts") echo "selected";?> value="Skirts">Skirts</option>
            <option <?php if (isset($_REQUEST['producttype1']) && $_REQUEST['producttype1']=="Jeans") echo "selected";?> value="Jeans">Jeans</option>
            <option <?php if (isset($_REQUEST['producttype1']) && $_REQUEST['producttype1']=="Pants") echo "selected";?> value="Pants">Pants</option>
            <option <?php if (isset($_REQUEST['producttype1']) && $_REQUEST['producttype1']=="Bags") echo "selected";?> value="Bags">Bags</option>
            <option <?php if (isset($_REQUEST['producttype1']) && $_REQUEST['producttype1']=="Footwear") echo "selected";?> value="Footwear">Footwear</option>
            <option <?php if (isset($_REQUEST['producttype1']) && $_REQUEST['producttype1']=="Jewelry") echo "selected";?> value="Jewelry">Jewelry</option>
            <option <?php if (isset($_REQUEST['producttype1']) && $_REQUEST['producttype1']=="Accessory ") echo "selected";?> value="Accessory">Accessory </option>
            <option <?php if (isset($_REQUEST['producttype1']) && $_REQUEST['producttype1']=="Cosmetics") echo "selected";?> value="Cosmetics">Cosmetics</option>
            <option <?php if (isset($_REQUEST['producttype1']) && $_REQUEST['producttype1']=="Lowers") echo "selected";?> value="Lowers">Lowers</option>
          </select>
                </div>
              </div><!-- row -->
             <div class="row row-pad-5">
                  <div class="col-lg-3">
             <button name="submit" class="btn btn-success" style="width:130px;" >Filter</button>
             </div> 
             <div class="col-lg-4">
             <button name="submit1" class="btn btn-success" style="width:130px;" >Clear Filter</button>
             </div> 
     </div>
            </div><!-- panel-body -->

<?php 
if($valid1){
echo "Please Select atleast one filter     ";
?>
<br>
<?php
echo "All Looks are:-";
}
?>        
        </div><!-- panel -->
        </div> 
      </div>
    </form>
    <form method="POST" id="select1" action="look_list.php" >
      <div class="content_div">
      <div>
      <div class="row">
<!--image code 1-->

<?php


  
  if($valid){

        $result1=mysql_query($sql1);
?>

<?php
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
          
           
           <a href="list_style_item.php?id=<?php echo $value['look_id'] ?>"><img class="img-thumbnail" src="<?php echo $value['look_image'] ?>"  alt="" /></a>
                
                       
                  </div>
                  
                </div><!-- row -->

                <div class="mb15"></div>
                <div class="row">
                  <div class="col-xs-6">
                    <div class="checkbox block "><label><input type="checkbox" class="cbox"id="chkSelect" name="select[]" value="<?php echo $value['look_id'] ?>"> Select</label></div>
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
<div id="send1" class="stick">
  <button name="send" id="button1" class="btn btn-success">send</button>

</div>
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
      $ids=$_REQUEST['ids'];
      foreach($ids as $askid){
    $check="update asklook set send='1' where asklookid='$askid'";
          $res1=mysql_query($check);
  }

      $regid=array();
      foreach($userid as $uid){
          if(!empty($uid)){
              $fetch="select regId from userdetails where user_id='$uid'";

              $res=mysql_query($fetch);
              
              while($data=mysql_fetch_array($res)){
                $rid=$data[0];
                array_push($regid, $rid);
                $_SESSION['reg']=$regid;
              }
          }
      }
  }  


if(isset($_POST['send']) && !empty($_REQUEST['select'])){

 $id=$_SESSION["users"];
 foreach($id as $uid1){
$stylishname="select stylish_info.stylish_name from stylish_info join userdetails on stylish_info.stylish_id=userdetails.stylish_id where user_id='$uid1'";
$res13=mysql_query($stylishname);
while($a=mysql_fetch_array($res13)){
$stylish=$a[0];
}
      $fetch="select regId from userdetails where user_id='$uid1'";
       $res=mysql_query($fetch);
              $regid=array();
              while($data=mysql_fetch_array($res)){
                $regid=$data[0];  
              }

  $gendercheck="select gender from userdetails where user_id='$uid1'";
  $res11=mysql_query($gendercheck);
  while($data11=mysql_fetch_array($res11)){
    $ug=$data11[0];
    $ug=strtolower($ug);
  }

$looks=$_REQUEST['select'];
$r=0;
foreach($looks as $value){
  
  $lookgender="select gender from createdlook where look_id='$value'";
  $res12=mysql_query($lookgender);
  while($data12=mysql_fetch_array($res12)){
    $lg=$data12[0];
    $lg=strtolower($lg);
  }
  if($ug==$lg){
 if($r==0){
    $sql="select look_image from createdlook where look_id='$value'";
    $res=mysql_query($sql);
    $data=mysql_fetch_array($res);
    $firstlook=$data[0];
    $r++;
  }
      $insert="Insert into sendlook(user_id,look_id) Values('$uid1','$value')";
      $res=mysql_query($insert);
  }else{
  
   $lookerror= "look ID No. ".$value." having gender ".$lg." whereas user have gender ".$ug;
  $lookerror=preg_replace("/\r?\n/", "\\n", addslashes($lookerror));
     echo "<script type='text/javascript'>alert(\"$lookerror\")</script>";
  }

  
}
include_once 'push.php';
$push = new pushmessage();
$params = array("pushtype"=>"android", "registration_id"=>$regid, "message"=>$stylish." has sent you looks","message_summery"=>$stylish." has sent you looks","look_url"=>$firstlook);

$rtn = $push->sendMessage($params);
}

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