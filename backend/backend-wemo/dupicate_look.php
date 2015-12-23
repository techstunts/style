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

  <title>IStyleYou Admin Panel</title>

  <link href="css/style.default.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <script src="js/respond.min.js"></script>
  <![endif]-->
</head>

<body>
<?php
	include 'databaseconnect.php';
	
	
if(!empty($_REQUEST["id"])){
	$id=intval($_REQUEST["id"]);			
	$data1 = mysql_query("SELECT * FROM products JOIN looks JOIN stylish_details ON looks.product_id1=products.id AND looks.stylish_id=stylish_details.stylish_id Where looks.product_id1 IN (SELECT product_id1 FROM looks WHERE looks.id=$id)");
	//Puts it into an array 
	$info1 = mysql_fetch_array( $data1 );
 
	$data2 = mysql_query("SELECT * FROM products JOIN looks ON looks.product_id2=products.id Where looks.product_id2 IN (SELECT product_id2 FROM looks WHERE looks.id=$id )");
	//Puts it into an array 
	$info2 = mysql_fetch_array( $data2 );
	$data3 = mysql_query("SELECT * FROM products JOIN looks ON looks.product_id3=products.id Where looks.product_id3 IN (SELECT product_id3 FROM looks WHERE looks.id=$id )");
	//Puts it into an array 
	$info3 = mysql_fetch_array( $data3);
	$data4 = mysql_query("SELECT * FROM products JOIN looks ON looks.product_id4=products.id Where looks.product_id4 IN (SELECT product_id4 FROM looks WHERE looks.id=$id )");
	//Puts it into an array 
	$info4 = mysql_fetch_array( $data4 );
	
	// Initialize variables to null.
	$bodytype_err ="";
	$budget_err ="";
	$age_err ="";
	$occasion_err ="";
	$gender_err="";
	$stylishname_err="";
	$lookname_err="";
	$lookdescription_err="";
	$productname1_err="";
	$producttype1_err="";
	$productprice1_err="";
	$productlink1_err="";
	$brand1_err="";
	$image1_err="";
	$productname2_err="";
	$producttype2_err="";
	$productprice2_err="";
	$productlink2_err="";
	$image2_err="";
	$brand2_err="";
	$productname3_err="";
	$producttype3_err="";
	$productprice3_err="";
	$productlink3_err="";
	$image3_err="";
	$brand3_err="";
	$productname4_err="";
	$producttype4_err="";
	$productprice4_err="";
	$productlink4_err="";
	$image4_err="";
	$brand4_err="";
	$valid="";
	$productsame_err="";
}
	
// On submitting form below function will execute.
if($_SERVER["REQUEST_METHOD"] == "POST"){

	$id = $_POST['id'];
	$valid=true;
	if (empty($_POST['bodytype'])) {
		$bodytype_err = "Please Select a Bodytype ";
		$valid=false;
	} else {
		$body_type = $_POST['bodytype'];
	}
	if (empty($_POST['budget'])) {
	$budget_err = "Please Select a Budget ";
	$valid=false;
	} else {
	$budget = $_POST['budget'];
	}
	if (empty($_POST['age'])) {
	$age_err = "Please Select an Age ";
	$valid=false;
	} else {
	$age = $_POST['age'];
	}
	if (empty($_POST['occasion'])) {
	$occasion_err = "Please Select an Occasion ";
	$valid=false;
	} else {
	$occasion = $_POST['occasion'];
	}
	if (empty($_POST['gender'])) {
	$gender_err = "Please Select a Gender ";
	$valid=false;
	} else {
	$gender = $_POST['gender'];
	}
	if (empty($_POST['stylish_id'])) {
	$stylishname_err = "Please Select a Stylish Name ";
	$valid=false;
	} else {
	$stylishid = $_POST['stylish_id'];
	}
	if (empty($_POST["look_name"])) {
	$lookname_err = "Look Name is required";
	$valid=false;
	} else {
	$lookname = test_input($_POST["look_name"]);

	}
	if (empty($_POST["look_description"])) {
	$lookdescription_err= "Look Description is required";
	$valid=false;
	} else {
	$lookdescription = test_input($_POST["look_description"]);
	}
	//Product 1
	if (empty($_POST["productname1"])) {
	$productname1_err = "Product name is required";
	$valid=false;
	} else {
	$productname1 = test_input($_POST["productname1"]);

	}
	if (empty($_POST['producttype1'])) {
	$producttype1_err = "Please Select a Product Type ";
	$valid=false;
	} else {
	$producttype1 = $_POST['producttype1'];
	}
	if (empty($_POST["productprice1"])) {
	$productprice1_err = "Product price is required";
	$valid=false;
	} else {
	$productprice1 = test_input($_POST["productprice1"]);
	// check name only contains letters and whitespace
	if (!preg_match("/^[0-9]/",$productprice1)) {
	$productprice1_err = "Only numbers are allowed";
	$valid=false;
	}
	}
	if (empty($_POST["productlink1"])) {
	$productlink1_err = "Product link is required";
	$valid=false;
	} else {
	$productlink1 = test_input($_POST["productlink1"]);
	// check name only contains letters and whitespace
	if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$productlink1)) {
	$productlink1_err = "Invalid URL";
	$valid=false;
	}
	}
	  
	if(empty($_FILES['image1']['name'])){ 

	 $oldimage1=$info1['upload_image'];
	 //print_r($info1);die;

	 $path = $info1['image_name'];
	 $newimage1="duplicate1".uniqid();
	$ext = pathinfo($path, PATHINFO_EXTENSION);
	$filename4= "uploadfile/".$newimage1.".".$ext; 
	copy($oldimage1,$filename4);
	$filename4=$newimage1.".".$ext;
	}
	else{
	$path = $_FILES['image1']['name'];
	$ext = pathinfo($path, PATHINFO_EXTENSION);
	$filename4=$_FILES["image1"]["name"];
	$temp_name=$_FILES["image1"]["tmp_name"];
			
			$imgtype=$_FILES["image1"]["type"];
			$target_path = 'uploadfile/';
			  if (($ext!= "jpeg")&&($ext!= "gif") &&($ext!= "jpg") &&($ext!= "png") &&($ext!= "JPG") &&($ext!= "GIF") &&($ext!= "JPEG") &&($ext!= "PNG")){
		echo $image1_err="Please upload a valid image";
		
		$valid=false;
		}
		else{
		if(move_uploaded_file($temp_name, $target_path.$filename4)){
			//echo "Move Sucessfully image4";
			}
			else  {
			echo "error";
			}
		}

		}
	//Product 2

	if (empty($_POST["productname2"])) {
	$productname2_err = "Product name is required";
	$valid=false;
	} else {
	$productname2 = test_input($_POST["productname2"]);

	}
	if ($_POST['producttype2']=='blank') {
	$producttype2_err = "Please Select a Product Type ";
	$valid=false;
	} else {
	$producttype2 = $_POST['producttype2'];
	}
	if (empty($_POST["productprice2"])) {
	$productprice2_err = "Product price is required";
	$valid=false;
	} else {
	$productprice2 = test_input($_POST["productprice2"]);
	// check name only contains letters and whitespace
	if (!preg_match("/^[0-9 ]/",$productprice2)) {
	$productprice2_err = "Only numbers are allowed";
	$valid=false;
	}
	}
	if (empty($_POST["productlink2"])) {
	$productlink2_err = "Product link is required";
	$valid=false;
	} else {
	$productlink2 = test_input($_POST["productlink2"]);
	// check name only contains letters and whitespace
	if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$productlink2)) {
	$productlink2_err = "Invalid URL";
	$valid=false;
	}
	}
	if(empty($_FILES['image2']['name'])){ 

	 $oldimage2=$info2['upload_image'];
	 //print_r($info1);die;

	 $path = $info2['image_name'];
	 $newimage2="duplicate2".uniqid();
	$ext = pathinfo($path, PATHINFO_EXTENSION);
	$filename1= "uploadfile/".$newimage2.".".$ext;
	
	copy($oldimage2,$filename1);
	$filename1=$newimage2.".".$ext;

	}
	else{
	$path = $_FILES['image2']['name'];
	$ext = pathinfo($path, PATHINFO_EXTENSION);
	$filename1=$_FILES["image2"]["name"];
	$temp_name=$_FILES["image2"]["tmp_name"];
			
			$imgtype=$_FILES["image2"]["type"];
			$target_path = 'uploadfile/';
			  if (($ext!= "jpeg")&&($ext!= "gif") &&($ext!= "jpg") &&($ext!= "png")&&($ext!= "JPG") &&($ext!= "GIF") &&($ext!= "JPEG") &&($ext!= "PNG")){
		echo $image2_err="Please upload a valid image";
		
		$valid=false;
		}
		else{
		if(move_uploaded_file($temp_name, $target_path.$filename1)){
			//echo "Move Sucessfully image4";
			}
			else  {
			echo "error";
			}
		}

		}
	//Product 3
	if (empty($_POST["productname3"])) {
	$productname3_err = "Product name is required";
	$valid=false;

	} else {
	$productname3 = test_input($_POST["productname3"]);

	}
	if (empty($_POST['producttype3'])) {
	$producttype3_err = "Please Select a Product Type ";
	$valid=false;
	} else {
	$producttype3 = $_POST['producttype3'];
	}
	if (empty($_POST["productprice3"])) {
	$productprice3_err = "Product price is required";
	$valid=false;
	} else {
	$productprice3 = test_input($_POST["productprice3"]);
	// check name only contains letters and whitespace
	if (!preg_match("/^[0-9 ]/",$productprice3)) {
	$productprice3_err = "Only numbers are allowed";
	$valid=false;
	}
	}
	if (empty($_POST["productlink3"])) {
	$productlink3_err = "Product link is required";
	$valid=false;
	} else {
	$productlink3 = test_input($_POST["productlink3"]);
	// check name only contains letters and whitespace
	if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$productlink3)) {
	$productlink3_err = "Invalid URL";
	$valid=false;
	}
	}

	if(empty($_FILES['image3']['name'])){ 
	$oldimage3=$info3['upload_image'];
	$path = $info3['image_name'];
	$newimage3="duplicate3".uniqid();
	$ext = pathinfo($path, PATHINFO_EXTENSION);
	$filename2= "uploadfile/".$newimage3.".".$ext; 
	copy($oldimage3,$filename2);
	$filename2=$newimage3.".".$ext;
	}
	else{
	$path = $_FILES['image3']['name'];
	$ext = pathinfo($path, PATHINFO_EXTENSION);
	$filename2=$_FILES["image3"]["name"];
	$temp_name=$_FILES["image3"]["tmp_name"];
			
			$imgtype=$_FILES["image3"]["type"];
			$target_path = 'uploadfile/';
			if (($ext!= "jpeg")&&($ext!= "gif") &&($ext!= "jpg") &&($ext!= "png") &&($ext!= "JPG") &&($ext!= "GIF") &&($ext!= "JPEG") &&($ext!= "PNG")){
		echo $image3_err="Please upload a valid image";
		
		$valid=false;
		}
		else{
		if(move_uploaded_file($temp_name, $target_path.$filename2)){
			//echo "Move Sucessfully image4";
			}
			else  {
			echo "error";
			}
		}
		}
			
		
		
			
	//Product 4
	if (empty($_POST["productname4"])) {
	$productname4_err = "Product name is required";
	$valid=false;
	} else {
	$productname4 = test_input($_POST["productname4"]);

	}

	if (empty($_POST['producttype4'])) {
	$producttype4_err = "Please Select a Product Type ";
	$valid=false;
	} else {
	$producttype4 = $_POST['producttype4'];
	}
	if (empty($_POST["productprice4"])) {
	$productprice4_err = "Product price is required";
	$valid=false;
	} else {
	$productprice4 = test_input($_POST["productprice4"]);
	// check name only contains letters and whitespace
	if (!preg_match("/^[0-9 ]/",$productprice4)) {
	$productprice4_err = "Only numbers are allowed";
	$valid=false;
	}
	}
	if (empty($_POST["productlink4"])) {
	$productlink4_err = "Product link is required";
	$valid=false;
	} else {
	$productlink4 = test_input($_POST["productlink4"]);
	// check name only contains letters and whitespace
	if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$productlink4)) {
	$productlink4_err = "Invalid URL";
	$valid=false;
	}
	}
	if(empty($_FILES['image4']['name'])){ 
				 $oldimage4=$info4['upload_image'];
				 //print_r($info1);die;

				 $path = $info4['image_name'];
				 $newimage4="duplicate4".uniqid();
				$ext = pathinfo($path, PATHINFO_EXTENSION);
				$filename3= "uploadfile/".$newimage4.".".$ext; 
				copy($oldimage4,$filename3);
				$filename3=$newimage4.".".$ext;
				
	}
		else{
				$path = $_FILES['image4']['name']; 
				$ext = pathinfo($path, PATHINFO_EXTENSION);
				$filename3=$_FILES["image4"]["name"]; 
				$temp_name=$_FILES["image4"]["tmp_name"];
						
					$imgtype=$_FILES["image4"]["type"];
					$target_path = 'uploadfile/';
					  if (($ext!= "jpeg")&&($ext!= "gif") &&($ext!= "jpg") &&($ext!= "png") &&($ext!= "JPG") &&($ext!= "GIF") &&($ext!= "JPEG") &&($ext!= "PNG")){
							echo $image4_err="Please upload a valid image";
							
							$valid=false;
						}
						else{
						if(move_uploaded_file($temp_name, $target_path.$filename3)){
							//echo "Move Sucessfully image4";
							}
							else  {
							echo "error";
							}
						}
				
		  
			
			}
if($productname1==$productname2 || $productname1==$productname3 || $productname1==$productname4 || $productname2==$productname3 || $productname2==$productname4 || $productname3==$productname4)
	{
	$productsame_err="Two Product Name can't be same in a Look";
	$valid=false;
	}

if($valid)

{


$sql = "INSERT INTO products (product_name, product_type, product_price,product_link,upload_image,image_name) VALUES ('$productname1', '$producttype1', '$productprice1','$productlink1','uploadfile/$filename4','$filename4')";
mysql_query($sql);
$productlastid=mysql_insert_id();

$sql = "INSERT INTO products (product_name, product_type, product_price,product_link,upload_image,image_name) VALUES ('$productname2', '$producttype2', '$productprice2','$productlink2','uploadfile/$filename1','$filename1')";
mysql_query($sql);
$productlastid1=mysql_insert_id();
$sql = "INSERT INTO products (product_name, product_type, product_price,product_link,upload_image,image_name) VALUES ('$productname3', '$producttype3', '$productprice3','$productlink3','uploadfile/$filename2','$filename2')";
mysql_query($sql);
$productlastid2=mysql_insert_id();
$sql = "INSERT INTO products (product_name, product_type, product_price,product_link,upload_image,image_name) VALUES ('$productname4', '$producttype4', '$productprice4','$productlink4','uploadfile/$filename3','$filename3')";
mysql_query($sql);
$productlastid3=mysql_insert_id();
$productid1=$productlastid;
$productid2=$productlastid1;
$productid3=$productlastid2;
$productid4=$productlastid3;
$lookprice=$productprice1+$productprice2+$productprice3+$productprice4;



require 'imageex.php';
header('location:dashboard.php');
}
}
function test_input($data) {
$data = trim($data);
$data = stripslashes($data);
$data = htmlspecialchars($data);
return $data;
}


if(empty($id)){
 //echo 'test';die;
	header('location:look_list.php');
	//exit(0);
}

//php code ends here
?>
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
                    <h4>John Doe</h4>
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
        <li><a href="look_list.php"><i class="fa fa-envelope-o"></i> <span>List All</span></a></li>
        <li><a href="product_list.php"><i class="fa fa-envelope-o"></i> <span>View Products</span></a></li>
        <li><a href="create_stylist.php"><i class="fa fa-envelope-o"></i> <span>Create Stylist</span></a></li>
        <li><a href="add_cat.php"><i class="fa fa-envelope-o"></i> <span>Add Category</span></a></li>
        <li><a href="list_stylist.php"><i class="fa fa-envelope-o"></i> <span>See Stylist</span></a></li>
        <li><a href="list_users.php"><i class="fa fa-envelope-o"></i> <span>See All Users</span></a></li>
        
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
                John Doe
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

 
    <div class="pageheader">
      <h2><i class="fa fa-home"></i> Duplicate Look <span></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">You are here:</span>
        <ol class="breadcrumb">
          <li><a href="dashboard.php">IStyleYou</a></li>
          <li class="active">Create A Look</li>
        </ol>
      </div>
    </div>

    <div class="contentpanel">

      <div class="row">
        
        <div class="col-md-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              <div class="panel-btns">
                <a href="" class="minimize">&minus;</a>
              </div>
              <h4 class="panel-title">No Label Form</h4>
              <p>This is an example of a form using a placeholder instead of label.</p>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
            <div class="panel-body">
              <div class="row row-pad-5">
                <div class="col-lg-4">
                 <select class="form-control mb15" name="bodytype">
				   <option value="">Select Body Type</option>
				   <?php $bodytype=$info1['body_type']?>
					<option <?php if(isset($bodytype ) && $bodytype == "Apple"){print "selected=\"selected\"";} ?> value="Apple">Apple</option>
					<option <?php if(isset($bodytype) && $bodytype == "Banana"){print "selected=\"selected\"";} ?>  value="Banana">Banana</option>
					<option <?php if(isset($bodytype) && $bodytype == "Pear"){print "selected=\"selected\"";} ?>  value="Pear">Pear</option>
				    <option <?php if(isset($bodytype) && $bodytype == "Hourglass"){print "selected=\"selected\"";} ?>  value="Hourglass">Hourglass</option>
					<option <?php if (isset($bodytype) && $bodytype=="Muscular") echo "selected";?> value="Muscular">Muscular</option>
					<option <?php if (isset($bodytype) && $bodytype=="Regular") echo "selected";?> value="Regular">Regular</option>
					<option <?php if (isset($bodytype) && $bodytype=="Round") echo "selected";?> value="Round">Round</option>
                </select>
					  <span class="error">* <?php echo $bodytype_err;?></span>
                </div>
                <div class="col-lg-4">
                 <select class="form-control mb15" name="budget">
				
				  <option value="">Select Budget</option>
				  <?php $bud_get=$info1['budget']?>
                  
                  <option <?php if(isset($bud_get) && $bud_get == ">2000"){print "selected=\"selected\"";} ?>  value="2000"><2000</option>
                  <option <?php if(isset($bud_get) && $bud_get == "2000-5000"){print "selected=\"selected\"";} ?> value="2000-5000">2000-5000</option>
                  <option <?php if(isset($bud_get) && $bud_get == "5000-10000"){print "selected=\"selected\"";} ?> value="5000-10000">5000-10000</option>
				   <option <?php if(isset($bud_get) && $bud_get == ">10000"){print "selected=\"selected\"";} ?> value=">10000">>10000</option>
				  
                </select>
				 <span class="error">* <?php echo $budget_err;?></span>
                </div>
                <div class="col-lg-4">
                  <select class="form-control mb15" name="age">
				 
				   <option value="">Select Age</option>
				   <?php $ag_e=$info1['age']?>
                  <option  <?php if(isset($ag_e) && $ag_e == "Teenager"){print "selected=\"selected\"";} ?> value="Teenager">Teenager</option>
                  <option  <?php if(isset($ag_e) && $ag_e == "Young(18-22)"){print "selected=\"selected\"";} ?> value="Young(18-22)">Young(18-22)</option>
                  <option  <?php if(isset($ag_e) && $ag_e == "Young Medium (22-30)"){print "selected=\"selected\"";} ?> value="Young Medium (22-30)">Young Medium (22-30)</option>
				  <option  <?php if(isset($ag_e) && $ag_e == "Medium (30-40)"){print "selected=\"selected\"";} ?> value="Medium (30-40)">Medium (30-40)</option>
				  <option  <?php if(isset($ag_e) && $ag_e == "Old > 40"){print "selected=\"selected\"";} ?> value="Old > 40">Old > 40</option>
                </select>
				 <span class="error">* <?php echo $age_err;?></span>
                </div>
              </div><!-- row -->

              <div class="row row-pad-5">
                <div class="col-lg-4">
                   <select class="form-control mb15" name="occasion">
				
				  <option value="">Select Occasion</option>
				  <?php $occa_sion=$info1['occasion']?>
				   <option <?php if(isset($occa_sion) && $occa_sion == "Work Wear"){print "selected=\"selected\"";} ?> value="Work Wear">Work Wear</option>
                   <option <?php if(isset($occa_sion) && $occa_sion == "Wine & Dine"){print "selected=\"selected\"";} ?> value="Wine & Dine">Wine & Dine</option>
				    <option <?php if(isset($occa_sion) && $occa_sion == "Ethnic/Festive"){print "selected=\"selected\"";} ?> value="Ethnic/Festive">Ethnic/Festive</option>
					 <option <?php if(isset($occa_sion) && $occa_sion == "Club"){print "selected=\"selected\"";} ?> value="Club">Club</option>
					 	 <option <?php if(isset($occa_sion) && $occa_sion == "Casuals"){print "selected=\"selected\"";} ?> value="Casuals">Casuals</option>
					 	  <option <?php if (isset($_POST['occasion']) && $_POST['occasion']=="Formals") echo "selected";?> value="Formals">Formals</option>
                </select>
				 <span class="error">* <?php echo $occasion_err;?></span>
                </div>
                
                <div class="col-lg-4">
                  <select class="form-control mb15" name="gender">
				 
				   <option value="">Select Gender</option>
				    <?php $gen_der=$info1['gender']?>
				    <option <?php if(isset($gen_der) && $gen_der == "Male"){print "selected=\"selected\"";} ?> value="Male">Male</option>
                  <option <?php if(isset($gen_der) && $gen_der == "Female"){print "selected=\"selected\"";} ?> value="Female">Female</option>
               
                </select>
				 <span class="error">* <?php echo $gender_err;?></span>
                </div>
				 <div class="col-lg-4">
                  <?php
				
echo "<select class="."form-control mb15"." name='stylish_id'>";

    echo "<option value='" . $info1['stylish_id'] . "'>" . $info1['stylish_name'] . "</option>";

echo "</select>";
?>
                 
				  <span class="error">* <?php echo $stylishname_err;?></span>
            

              </div><!-- row -->

             <div class="col-lg-4 mb15">
                  <input type="text" name="look_name" placeholder="Look Name" Value="<?php echo $info1['look_name'] ?>"class="form-control">
				  <span class="error">* <?php echo $lookname_err;?></span>
                </div></div>
  <div class="row">
                 <div class="col-lg-12 mb15">
                  <textarea name="look_description" placeholder="Look Description"  rows="4" cols="10"class="form-control"><?php echo $info1['look_description'] ?></textarea>
				  <span class="error">* <?php echo $lookdescription_err;?></span>
                </div>

             </div>
            </div><!-- panel-body -->
            
            
          </div><!-- panel -->
        </div>

      <div class="row">

           <div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              <div class="panel-btns">
                
                <a href="" class="minimize">&minus;</a>
              </div>
              <h4 class="panel-title">Upload The Images and Content</h4>
              <span class="error">* <?php echo $productsame_err;?></span> 
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Product Name</label>
                    <input type="text" name="productname1" Value="<?php echo $info1['product_name'] ?>" class="form-control" />
					<span class="error">* <?php echo $productname1_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Product Type</label>
                    <select class="form-control input-lg"name="producttype1">
					 <option value="">Select Product Type </option>
					 <?php $productype1=$info1['product_type']?>
					 <option <?php if(isset($productype1) && $productype1 == "Shirts"){print "selected=\"selected\"";} ?> value="Shirts">Shirts</option>
					  <option <?php if(isset($productype1) && $productype1 == "Saree"){print "selected=\"selected\"";} ?> value="Saree">Saree</option>
           <option <?php if(isset($productype1) && $productype1 == "Ethnic top"){print "selected=\"selected\"";} ?> value="Ethnic top">Ethnic top</option>
		    <option <?php if(isset($productype1) && $productype1 == "Ethnic Bottom"){print "selected=\"selected\"";} ?> value="Ethnic Bottom">Ethnic Bottom</option>
			 <option <?php if(isset($productype1) && $productype1 == "Top wear"){print "selected=\"selected\"";} ?> value="Top wear">Top wear</option>
			  <option <?php if(isset($productype1) && $productype1 == "Winter Wear"){print "selected=\"selected\"";} ?> value="Winter Wear">Winter Wear</option>
			   <option <?php if(isset($productype1) && $productype1 == "Skirts"){print "selected=\"selected\"";} ?> value="Skirts">Skirts</option>
			    <option <?php if(isset($productype1) && $productype1 == "Jeans"){print "selected=\"selected\"";} ?> value="Jeans">Jeans</option>
				 <option <?php if(isset($productype1) && $productype1 == "Pants"){print "selected=\"selected\"";} ?> value="Pants">Pants</option>
				  <option <?php if(isset($productype1) && $productype1 == "Bags"){print "selected=\"selected\"";} ?> value="Bags">Bags</option>
				   <option <?php if(isset($productype1) && $productype1 == "Footwear"){print "selected=\"selected\"";} ?> value="Footwear">Footwear</option>
		  <option <?php if(isset($productype1) && $productype1 == "Jewelry"){print "selected=\"selected\"";} ?> value="Jewelry">Jewelry</option>
				   <option <?php if(isset($productype1) && $productype1 == "Accessory"){print "selected=\"selected\"";} ?> value="Accessory">Accessory</option>                   
				   		  <option <?php if(isset($productype1) && $productype1 == "Cosmetics"){print "selected=\"selected\"";} ?> value="Cosmetics">Cosmetics</option>
				   <option <?php if(isset($productype1) && $productype1 == "Lowers"){print "selected=\"selected\"";} ?> value="Lowers">Lowers</option>  
				   </select>
					  <span class="error">* <?php echo $producttype1_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
              </div><!-- row -->
              
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Price</label>
                    <input type="text" name="productprice1" Value="<?php echo $info1['product_price'] ?>" class="form-control" />
					<span class="error">* <?php echo $productprice1_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Product Link</label>
                    <input type="text" name="productlink1" Value="<?php echo $info1['product_link'] ?>" class="form-control" />
					<span class="error">* <?php echo $productlink1_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
              </div><!-- row -->
              <div class="row">
                <div class="col-sm-6">
                <input id="uploadFile1" placeholder="Choose File"  disabled="disabled"Value="<?php echo $info1['upload_image'] ?>"  />
                  <div class="fileUpload btn btn-primary">
                      <span>Upload</span>
                      <input id="uploadBtn1" type="file" class="upload"name="image1" />
					 
                  </div>
				   Current Image:<img src="<?php echo $info1['upload_image'] ?>" width="100" height="100" />
				   <span class="error">* <?php echo $image1_err;?></span>
                </div>
              </div>
              
            </div><!-- panel-body -->

            <div class="panel-body">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Product Name</label>
                    <input type="text" name="productname2" Value="<?php echo $info2['product_name'] ?>"class="form-control" />
					<span class="error">* <?php echo $productname2_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Product Type</label>
                    <select class="form-control input-lg"name="producttype2">
					 <option value="">Select Product Type </option>
					  <?php $productype2=$info2['product_type']?>
					  <option <?php if(isset($productype2) && $productype2 == "Shirts"){print "selected=\"selected\"";} ?> value="Shirts">Shirts</option>
					  <option <?php if(isset($productype2) && $productype2 == "Saree"){print "selected=\"selected\"";} ?> value="Saree">Saree</option>
           <option <?php if(isset($productype2) && $productype2 == "Ethnic top"){print "selected=\"selected\"";} ?> value="Ethnic top">Ethnic top</option>
		    <option <?php if(isset($productype2) && $productype2 == "Ethnic Bottom"){print "selected=\"selected\"";} ?> value="Ethnic Bottom">Ethnic Bottom</option>
			 <option <?php if(isset($productype2) && $productype2 == "Top wear"){print "selected=\"selected\"";} ?> value="Top wear">Top wear</option>
			  <option <?php if(isset($productype2) && $productype2 == "Winter Wear"){print "selected=\"selected\"";} ?> value="Winter Wear">Winter Wear</option>
			   <option <?php if(isset($productype2) && $productype2 == "Skirts"){print "selected=\"selected\"";} ?> value="Skirts">Skirts</option>
			    <option <?php if(isset($productype2) && $productype2 == "Jeans"){print "selected=\"selected\"";} ?> value="Jeans">Jeans</option>
				 <option <?php if(isset($productype2) && $productype2 == "Pants"){print "selected=\"selected\"";} ?> value="Pants">Pants</option>
				  <option <?php if(isset($productype2) && $productype2 == "Bags"){print "selected=\"selected\"";} ?> value="Bags">Bags</option>
				   <option <?php if(isset($productype2) && $productype2 == "Footwear"){print "selected=\"selected\"";} ?> value="Footwear">Footwear</option>
		  <option <?php if(isset($productype2) && $productype2 == "Jewelry"){print "selected=\"selected\"";} ?> value="Jewelry">Jewelry</option>
				   <option <?php if(isset($productype2) && $productype2 == "Accessory"){print "selected=\"selected\"";} ?> value="Accessory">Accessory</option>                   
				   		  <option <?php if(isset($productype2) && $productype2 == "Cosmetics"){print "selected=\"selected\"";} ?> value="Cosmetics">Cosmetics</option>
				   <option <?php if(isset($productype2) && $productype2 == "Lowers"){print "selected=\"selected\"";} ?> value="Lowers">Lowers</option>
                     </select>
					  	<span class="error">* <?php echo $producttype2_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
              </div><!-- row -->
              
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Price</label>
                    <input type="text" name="productprice2" Value="<?php echo $info2['product_price'] ?>" class="form-control" />
						<span class="error">* <?php echo $productprice2_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Product Link</label>
                    <input type="text" name="productlink2" Value="<?php echo $info2['product_link'] ?>"class="form-control" />
						<span class="error">* <?php echo $productlink2_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
              </div><!-- row -->
              <div class="row">
                <div class="col-sm-6">
                <input id="uploadFile2" placeholder="Choose File"  disabled="disabled" Value="<?php echo $info2['upload_image'] ?>" />
                  <div class="fileUpload btn btn-primary">
                      <span>Upload</span>
                      <input id="uploadBtn2" type="file" class="upload" name="image2"/>
                  </div>
				   Current Image:<img src="<?php echo $info2['upload_image'] ?>" width="100" height="100" />
				    <span class="error">* <?php echo $image2_err;?></span>
                </div>
              </div>
              
            </div><!-- panel-body -->

            <div class="panel-body">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Product Name</label>
                    <input type="text" name="productname3" Value="<?php echo $info3['product_name'] ?>" class="form-control" />
						<span class="error">* <?php echo $productname3_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Product Type</label>
                    <select class="form-control input-lg"name="producttype3">
					 <option value="">Select Product Type </option>
					 <?php $productype3=$info3['product_type']?>
					 <option <?php if(isset($productype3) && $productype3 == "Shirts"){print "selected=\"selected\"";} ?> value="Shirts">Shirts</option>
					  <option <?php if(isset($productype3) && $productype3 == "Saree"){print "selected=\"selected\"";} ?> value="Saree">Saree</option>
           <option <?php if(isset($productype3) && $productype3 == "Ethnic top"){print "selected=\"selected\"";} ?> value="Ethnic top">Ethnic top</option>
		    <option <?php if(isset($productype3) && $productype3 == "Ethnic Bottom"){print "selected=\"selected\"";} ?> value="Ethnic Bottom">Ethnic Bottom</option>
			 <option <?php if(isset($productype3) && $productype3 == "Top wear"){print "selected=\"selected\"";} ?> value="Top wear">Top wear</option>
			  <option <?php if(isset($productype3) && $productype3 == "Winter Wear"){print "selected=\"selected\"";} ?> value="Winter Wear">Winter Wear</option>
			   <option <?php if(isset($productype3) && $productype3 == "Skirts"){print "selected=\"selected\"";} ?> value="Skirts">Skirts</option>
			    <option <?php if(isset($productype3) && $productype3 == "Jeans"){print "selected=\"selected\"";} ?> value="Jeans">Jeans</option>
				 <option <?php if(isset($productype3) && $productype3 == "Pants"){print "selected=\"selected\"";} ?> value="Pants">Pants</option>
				  <option <?php if(isset($productype3) && $productype3 == "Bags"){print "selected=\"selected\"";} ?> value="Bags">Bags</option>
				   <option <?php if(isset($productype3) && $productype3 == "Footwear"){print "selected=\"selected\"";} ?> value="Footwear">Footwear</option>
		  <option <?php if(isset($productype3) && $productype3 == "Jewelry"){print "selected=\"selected\"";} ?> value="Jewelry">Jewelry</option>
				   <option <?php if(isset($productype3) && $productype3 == "Accessory"){print "selected=\"selected\"";} ?> value="Accessory">Accessory</option>                   
				   		  <option <?php if(isset($productype3) && $productype3 == "Cosmetics"){print "selected=\"selected\"";} ?> value="Cosmetics">Cosmetics</option>
				   <option <?php if(isset($productype3) && $productype3 == "Lowers"){print "selected=\"selected\"";} ?> value="Lowers">Lowers</option>
                     </select>
					  <span class="error">* <?php echo $producttype3_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
              </div><!-- row -->
              
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Price</label>
                    <input type="text" name="productprice3" Value="<?php echo $info3['product_price'] ?>"class="form-control" />
					<span class="error">* <?php echo $productprice3_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Product Link</label>
                    <input type="text" name="productlink3" Value="<?php echo $info3['product_link'] ?>" class="form-control" />
					<span class="error">* <?php echo $productlink3_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
              </div><!-- row -->
              <div class="row">
                <div class="col-sm-6">
                <input id="uploadFile3" placeholder="Choose File"  disabled="disabled" Value="<?php echo $info3['upload_image'] ?>" />
                  <div class="fileUpload btn btn-primary">
                      <span>Upload</span>
                      <input id="uploadBtn3" type="file" class="upload" name="image3" />
                  </div>
				   Current Image:<img src="<?php echo $info3['upload_image'] ?>" width="100" height="100" />
				     <span class="error">* <?php echo $image3_err;?></span>
                </div>
              </div>
              
            </div><!-- panel-body -->


            <div class="panel-body">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Product Name</label>
                    <input type="text" name="productname4" Value="<?php echo $info4['product_name'] ?>" class="form-control" />
					<span class="error">* <?php echo $productname4_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Product Type</label>
                    <select class="form-control input-lg" name="producttype4"> 
					<option value="">Select Product Type </option>
					<?php $productype4=$info4['product_type']?>
					<option <?php if(isset($productype4) && $productype4 == "Shirts"){print "selected=\"selected\"";} ?> value="Shirts">Shirts</option>
					  <option <?php if(isset($productype4) && $productype4 == "Saree"){print "selected=\"selected\"";} ?> value="Saree">Saree</option>
           <option <?php if(isset($productype4) && $productype4 == "Ethnic top"){print "selected=\"selected\"";} ?> value="Ethnic top">Ethnic top</option>
		    <option <?php if(isset($productype4) && $productype4 == "Ethnic Bottom"){print "selected=\"selected\"";} ?> value="Ethnic Bottom">Ethnic Bottom</option>
			 <option <?php if(isset($productype4) && $productype4 == "Top wear"){print "selected=\"selected\"";} ?> value="Top wear">Top wear</option>
			  <option <?php if(isset($productype4) && $productype4 == "Winter Wear"){print "selected=\"selected\"";} ?> value="Winter Wear">Winter Wear</option>
			   <option <?php if(isset($productype4) && $productype4 == "Skirts"){print "selected=\"selected\"";} ?> value="Skirts">Skirts</option>
			    <option <?php if(isset($productype4) && $productype4 == "Jeans"){print "selected=\"selected\"";} ?> value="Jeans">Jeans</option>
				 <option <?php if(isset($productype4) && $productype4 == "Pants"){print "selected=\"selected\"";} ?> value="Pants">Pants</option>
				  <option <?php if(isset($productype4) && $productype4 == "Bags"){print "selected=\"selected\"";} ?> value="Bags">Bags</option>
				   <option <?php if(isset($productype4) && $productype4 == "Footwear"){print "selected=\"selected\"";} ?> value="Footwear">Footwear</option>
		  <option <?php if(isset($productype4) && $productype4 == "Jewelry"){print "selected=\"selected\"";} ?> value="Jewelry">Jewelry</option>
				   <option <?php if(isset($productype4) && $productype4 == "Accessory"){print "selected=\"selected\"";} ?> value="Accessory">Accessory</option>                   
				   		  <option <?php if(isset($productype4) && $productype4 == "Cosmetics"){print "selected=\"selected\"";} ?> value="Cosmetics">Cosmetics</option>
				   <option <?php if(isset($productype4) && $productype4 == "Lowers"){print "selected=\"selected\"";} ?> value="Lowers">Lowers</option>
                     </select>
					  <span class="error">* <?php echo $producttype4_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
              </div><!-- row -->
              
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Price</label>
                    <input type="text" name="productprice4" Value="<?php echo $info4['product_price'] ?>" class="form-control" />
						<span class="error">* <?php echo $productprice4_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Product Link</label>
                    <input type="text" name="productlink4" Value="<?php echo $info4['product_link'] ?>" class="form-control" />
					<span class="error">* <?php echo $productlink4_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
              </div><!-- row -->
              <div class="row">
                <div class="col-sm-6">
                <input id="uploadFile4" placeholder="Choose File"  disabled="disabled" Value="<?php echo $info4['upload_image'] ?>" />
                  <div class="fileUpload btn btn-primary">
                      <span>Upload</span>
                      <input id="uploadBtn4" type="file" class="upload"  name="image4"/>
                  </div>
				   Current Image:<img src="<?php echo $info4['upload_image'] ?>" width="100" height="100" />
				  				   <span class="error">* <?php echo $image4_err;?></span>
                </div>
              </div>
              <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />
            </div><!-- panel-body  --> 
            <div class="panel-footer">
              <button class="btn btn-success">Submit</button>
            </div>
          </div>
        </div>

        </form>


      </div><!-- row -->

    

          

      </div>

    </div><!-- contentpanel -->

  </div><!-- mainpanel -->

  <div class="rightpanel">
    
  </div><!-- rightpanel -->


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