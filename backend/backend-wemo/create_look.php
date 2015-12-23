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
// Initialize variables to null.
include 'databaseconnect.php';
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
// On submitting form below function will execute.
if($_SERVER["REQUEST_METHOD"] == "POST"){
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
// check name only contains letters and whitespace

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
// check name only contains letters and whitespace

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
$image1_err="Please Select an image to upload"; 
$valid=false; 
}
else{
$path = $_FILES['image1']['name'];
$ext = pathinfo($path, PATHINFO_EXTENSION);

    if (($ext!= "jpeg")&&($ext!= "gif") &&($ext!= "jpg") &&($ext!= "png") &&($ext!= "JPG") &&($ext!= "GIF") &&($ext!= "JPEG") &&($ext!= "PNG")){
	echo $image1_err="Please upload a valid image";
	
	$valid=false;
	}
	
	}
//Product 2

if (empty($_POST["productname2"])) {
$productname2_err = "Product name is required";
$valid=false;
} else {
$productname2 = test_input($_POST["productname2"]);
// check name only contains letters and whitespace

}
if (empty($_POST['producttype2'])) {
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
$image2_err="Please Select an image to upload"; 
$valid=false; 
}
else{
$path = $_FILES['image2']['name'];
$ext = pathinfo($path, PATHINFO_EXTENSION);

    if (($ext!= "jpeg")&&($ext!= "gif") &&($ext!= "jpg") &&($ext!= "png") &&($ext!= "JPG") &&($ext!= "GIF") &&($ext!= "JPEG") &&($ext!= "PNG")){
	echo $image2_err="Please upload a valid image";
	
	$valid=false;
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
$image3_err="Please Select an image to upload"; 
$valid=false; 
}
else{
$path = $_FILES['image3']['name'];
$ext = pathinfo($path, PATHINFO_EXTENSION);

    if (($ext!= "jpeg")&&($ext!= "gif") &&($ext!= "jpg") &&($ext!= "png") &&($ext!= "JPG") &&($ext!= "GIF") &&($ext!= "JPEG") &&($ext!= "PNG")){
	echo $image3_err="Please upload a valid image";
	
	$valid=false;
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
$image4_err="Please Select an image to upload"; 
$valid=false; 
}
else{
$path = $_FILES['image4']['name'];
$ext = pathinfo($path, PATHINFO_EXTENSION);

    if (($ext!= "jpeg")&&($ext!= "gif") &&($ext!= "jpg") &&($ext!= "png") &&($ext!= "JPG") &&($ext!= "GIF") &&($ext!= "JPEG") &&($ext!= "PNG")){
	echo $image4_err="Please upload a valid image";
	
	$valid=false;
	}
	
	}
if($productname1==$productname2 || $productname1==$productname3 || $productname1==$productname4 || $productname2==$productname3 || $productname2==$productname4 || $productname3==$productname4)
	{
	$productsame_err="Two Product Name can't be same in a Look";
	$valid=false;
	}
}
if($valid)
{
require 'insert.php';
}
function test_input($data) {
$data = trim($data);
$data = stripslashes($data);
$data = htmlspecialchars($data);
return $data;
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
      <h2><i class="fa fa-home"></i> Create A Look <span></span></h2>
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
            <form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >
            <div class="panel-body">
              <div class="row row-pad-5">
                <div class="col-lg-4">
                  <select class="form-control mb15" name="bodytype">
				   <option value="">Select Body Type</option>
                  <option <?php if (isset($_POST['bodytype']) && $_POST['bodytype']=="Apple") echo "selected";?> value="Apple">Apple</option>
                  <option <?php if (isset($_POST['bodytype']) && $_POST['bodytype']=="Banana") echo "selected";?> value="Banana">Banana</option>
                  <option <?php if (isset($_POST['bodytype']) && $_POST['bodytype']=="Pear") echo "selected";?> value="Pear">Pear</option>
				    <option <?php if (isset($_POST['bodytype']) && $_POST['bodytype']=="Hourglass") echo "selected";?> value="Hourglass">Hourglass</option>
					 <option <?php if (isset($_POST['bodytype']) && $_POST['bodytype']=="Muscular") echo "selected";?> value="Muscular">Muscular</option>
					  <option <?php if (isset($_POST['bodytype']) && $_POST['bodytype']=="Regular") echo "selected";?> value="Regular">Regular</option>
					   <option <?php if (isset($_POST['bodytype']) && $_POST['bodytype']=="Round") echo "selected";?> value="Round">Round</option>
                </select>
				  <span class="error">* <?php echo $bodytype_err;?></span>
                </div>
                <div class="col-lg-4">
                 <select class="form-control mb15" name="budget">
				
				  <option value="">Select Budget</option>
                  <option <?php if (isset($_POST['budget']) && $_POST['budget']=="<2000") echo "selected";?> value="2000"><2000</option>
                  <option <?php if (isset($_POST['budget']) && $_POST['budget']=="2000-5000") echo "selected";?> value="2000-5000">2000-5000</option>
                  <option <?php if (isset($_POST['budget']) && $_POST['budget']=="5000-10000") echo "selected";?> value="5000-10000">5000-10000</option>
				   <option <?php if (isset($_POST['budget']) && $_POST['budget']==">10000") echo "selected";?> value=">10000">>10000</option>
                </select>
				 <span class="error">* <?php echo $budget_err;?></span>
                </div>
                <div class="col-lg-4">
                  <select class="form-control mb15" name="age">
				 
				   <option value="">Select Age</option>
                  <option <?php if (isset($_POST['age']) && $_POST['age']=="Teenager") echo "selected";?> value="Teenager">Teenager</option>
                  <option <?php if (isset($_POST['age']) && $_POST['age']=="Young(18-22)") echo "selected";?> value="Young(18-22)">Young(18-22)</option>
                  <option  <?php if (isset($_POST['age']) && $_POST['age']=="Young(18-22)") echo "selected";?> value="Young Medium (22-30)">Young Medium (22-30)</option>
				  <option  <?php if (isset($_POST['age']) && $_POST['age']=="Medium (30-40)") echo "selected";?> value="Medium (30-40)">Medium (30-40)</option>
				  <option <?php if (isset($_POST['age']) && $_POST['age']=="Old > 40") echo "selected";?> value="Old > 40">Old > 40</option>
                </select>
				 <span class="error">* <?php echo $age_err;?></span>
                </div>
              </div><!-- row -->

              <div class="row row-pad-5">
                
                <div class="col-lg-4">
                 <select class="form-control mb15" name="occasion">
				
				  <option value="">Select Occasion</option>
				   <option <?php if (isset($_POST['occasion']) && $_POST['occasion']=="Work Wear") echo "selected";?> value="Work Wear">Work Wear</option>
                  <option <?php if (isset($_POST['occasion']) && $_POST['occasion']=="Wine & Dine") echo "selected";?> value="Wine & Dine">Wine & Dine</option>
                  <option <?php if (isset($_POST['occasion']) && $_POST['occasion']=="Ethnic/Festive") echo "selected";?> value="Ethnic/Festive">Ethnic/Festive</option>
                  <option <?php if (isset($_POST['occasion']) && $_POST['occasion']=="Club") echo "selected";?> value="Club">Club</option>
				  <option <?php if (isset($_POST['occasion']) && $_POST['occasion']=="Casuals") echo "selected";?> value="Casuals">Casuals</option>
				    <option <?php if (isset($_POST['occasion']) && $_POST['occasion']=="Formals") echo "selected";?> value="Formals">Formals</option>
                </select>
				 <span class="error">* <?php echo $occasion_err;?></span>
                </div>
                <div class="col-lg-4">
                  <select class="form-control mb15" name="gender">
				 
				   <option value="">Select Gender</option>
				    <option <?php if (isset($_POST['gender']) && $_POST['gender']=="Male") echo "selected";?> value="Male">Male</option>
                  <option <?php if (isset($_POST['gender']) && $_POST['gender']=="Female") echo "selected";?> value="Female">Female</option>
               
                </select>
				 <span class="error">* <?php echo $gender_err;?></span>
                </div>
				 <div class="col-lg-4">
				<?php
				$sql = "SELECT * FROM stylish_details";
$result = mysql_query($sql);
echo "<select class="."form-control mb15"." name='stylish_id'>";
while ($row = mysql_fetch_array($result)) {
    echo "<option value='" . $row['stylish_id'] . "'>" . $row['stylish_name'] . "</option>";
}
echo "</select>";
?>
                 
				  <span class="error">* <?php echo $stylishname_err;?></span>
              </div><!-- row -->

          
              <div class="col-lg-4 mb15">
                  <input type="text" name="look_name" placeholder="Look Name" value="<?PHP if(!empty($_POST['look_name'])) echo htmlspecialchars($_POST['look_name']); ?>"class="form-control">
				  <span class="error">* <?php echo $lookname_err;?></span>
                </div></div>
  <div class="row">
                 <div class="col-lg-12 mb15">
                  <textarea name="look_description" placeholder="Look Description" style="height:80px;  rows="8" cols="10"  class="form-control" ><?PHP if(!empty($_POST['look_description'])) echo htmlspecialchars($_POST['look_description']); ?></textarea>
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
                    <input type="text" name="productname1" value="<?PHP if(!empty($_POST['productname1'])) echo htmlspecialchars($_POST['productname1']); ?>"class="form-control" />
					<span class="error">* <?php echo $productname1_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Product Type</label>
                    <select class="form-control input-lg"name="producttype1">
					
					 <option value="">Select Product Type </option>
					  <option <?php if (isset($_POST['producttype1']) && $_POST['producttype1']=="Shirts") echo "selected";?> value="Shirts">Shirts</option>
					  <option <?php if (isset($_POST['producttype1']) && $_POST['producttype1']=="Saree") echo "selected";?> value="Saree">Saree</option>
					   <option <?php if (isset($_POST['producttype1']) && $_POST['producttype1']=="Ethnic top") echo "selected";?> value="Ethnic top">Ethnic top</option>
					    <option <?php if (isset($_POST['producttype1']) && $_POST['producttype1']=="Ethnic Bottom") echo "selected";?> value="Ethnic Bottom">Ethnic Bottom</option>
						 <option <?php if (isset($_POST['producttype1']) && $_POST['producttype1']=="Top wear") echo "selected";?> value="Top wear">Top wear</option>
						   <option <?php if (isset($_POST['producttype1']) && $_POST['producttype1']=="Winter Wear") echo "selected";?> value="Winter Wear">Winter Wear</option>
					   <option <?php if (isset($_POST['producttype1']) && $_POST['producttype1']=="Skirts") echo "selected";?> value="Skirts">Skirts</option>
					    <option <?php if (isset($_POST['producttype1']) && $_POST['producttype1']=="Jeans") echo "selected";?> value="Jeans">Jeans</option>
						 <option <?php if (isset($_POST['producttype1']) && $_POST['producttype1']=="Pants") echo "selected";?> value="Pants">Pants</option>
						   <option <?php if (isset($_POST['producttype1']) && $_POST['producttype1']=="Bags") echo "selected";?> value="Bags">Bags</option>
					   <option <?php if (isset($_POST['producttype1']) && $_POST['producttype1']=="Footwear") echo "selected";?> value="Footwear">Footwear</option>
					    <option <?php if (isset($_POST['producttype1']) && $_POST['producttype1']=="Jewelry") echo "selected";?> value="Jewelry">Jewelry</option>
						 <option <?php if (isset($_POST['producttype1']) && $_POST['producttype1']=="Accessory ") echo "selected";?> value="Accessory">Accessory </option>
						   <option <?php if (isset($_POST['producttype1']) && $_POST['producttype1']=="Cosmetics") echo "selected";?> value="Cosmetics">Cosmetics</option>
					   <option <?php if (isset($_POST['producttype1']) && $_POST['producttype1']=="Lowers") echo "selected";?> value="Lowers">Lowers</option>
					    
                     </select>
					 <span class="error">* <?php echo $producttype1_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
              </div><!-- row -->
              
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Price</label>
                    <input type="text" name="productprice1" value="<?PHP if(!empty($_POST['productprice1'])) echo htmlspecialchars($_POST['productprice1']); ?>"class="form-control" />
					<span class="error">* <?php echo $productprice1_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Product Link</label>
                    <input type="text" name="productlink1"value="<?PHP if(!empty($_POST['productprice1'])) echo htmlspecialchars($_POST['productprice1']); ?>" class="form-control" />
					<span class="error">* <?php echo $productlink1_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
              </div><!-- row -->
              <div class="row">
                <div class="col-sm-6">
                <input id="uploadFile1" placeholder="Choose File" disabled="disabled" />
                  <div class="fileUpload btn btn-primary">
                      <span>Upload</span>
                      <input id="uploadBtn1" type="file" class="upload"name="image1" />
					 
                  </div>
				   <span class="error">* <?php echo $image1_err;?></span>
                </div>
              </div>
              
            </div><!-- panel-body -->

            <div class="panel-body">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Product Name</label>
                    <input type="text" name="productname2" value="<?PHP if(!empty($_POST['productname2'])) echo htmlspecialchars($_POST['productname2']); ?>"class="form-control" />
					<span class="error">* <?php echo $productname2_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Product Type</label>
                    <select class="form-control input-lg"name="producttype2">
				
					 <option value="">Select Product Type </option>
					  <option <?php if (isset($_POST['producttype2']) && $_POST['producttype2']=="Shirts") echo "selected";?> value="Shirts">Shirts</option>
					  <option <?php if (isset($_POST['producttype2']) && $_POST['producttype2']=="Saree") echo "selected";?> value="Saree">Saree</option>
					   <option <?php if (isset($_POST['producttype2']) && $_POST['producttype2']=="Ethnic top") echo "selected";?> value="Ethnic top">Ethnic top</option>
					    <option <?php if (isset($_POST['producttype2']) && $_POST['producttype2']=="Ethnic Bottom") echo "selected";?> value="Ethnic Bottom">Ethnic Bottom</option>
						 <option <?php if (isset($_POST['producttype2']) && $_POST['producttype2']=="Top wear") echo "selected";?> value="Top wear">Top wear</option>
						   <option <?php if (isset($_POST['producttype2']) && $_POST['producttype2']=="Winter Wear") echo "selected";?> value="Winter Wear">Winter Wear</option>
					   <option <?php if (isset($_POST['producttype2']) && $_POST['producttype2']=="Skirts") echo "selected";?> value="Skirts">Skirts</option>
					    <option <?php if (isset($_POST['producttype2']) && $_POST['producttype2']=="Jeans") echo "selected";?> value="Jeans">Jeans</option>
						 <option <?php if (isset($_POST['producttype2']) && $_POST['producttype2']=="Pants") echo "selected";?> value="Pants">Pants</option>
						   <option <?php if (isset($_POST['producttype2']) && $_POST['producttype2']=="Bags") echo "selected";?> value="Bags">Bags</option>
					   <option <?php if (isset($_POST['producttype2']) && $_POST['producttype2']=="Footwear") echo "selected";?> value="Footwear">Footwear</option>
					    <option <?php if (isset($_POST['producttype2']) && $_POST['producttype2']=="Jewelry") echo "selected";?> value="Jewelry">Jewelry</option>
						 <option <?php if (isset($_POST['producttype2']) && $_POST['producttype2']=="Accessory ") echo "selected";?> value="Accessory">Accessory </option>
						   <option <?php if (isset($_POST['producttype2']) && $_POST['producttype2']=="Cosmetics") echo "selected";?> value="Cosmetics">Cosmetics</option>
					   <option <?php if (isset($_POST['producttype2']) && $_POST['producttype2']=="Lowers") echo "selected";?> value="Lowers">Lowers</option>
                     </select>
					 	<span class="error">* <?php echo $producttype2_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
              </div><!-- row -->
              
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Price</label>
                    <input type="text" name="productprice2" value="<?PHP if(!empty($_POST['productprice2'])) echo htmlspecialchars($_POST['productprice2']); ?>" class="form-control" />
					<span class="error">* <?php echo $productprice2_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Product Link</label>
                    <input type="text" name="productlink2" value="<?PHP if(!empty($_POST['productlink2'])) echo htmlspecialchars($_POST['productlink2']); ?>" class="form-control" />
					<span class="error">* <?php echo $productlink2_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
              </div><!-- row -->
              <div class="row">
                <div class="col-sm-6">
                <input id="uploadFile2" placeholder="Choose File" disabled="disabled" />
                  <div class="fileUpload btn btn-primary">
                      <span>Upload</span>
                      <input id="uploadBtn2" type="file" class="upload" name="image2"/>
                  </div>
				   <span class="error">* <?php echo $image2_err;?></span>
                </div>
              </div>
              
            </div><!-- panel-body -->

            <div class="panel-body">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Product Name</label>
                    <input type="text" name="productname3" value="<?PHP if(!empty($_POST['productname3'])) echo htmlspecialchars($_POST['productname3']); ?>" class="form-control" />
					<span class="error">* <?php echo $productname3_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Product Type</label>
                    <select class="form-control input-lg"name="producttype3">
					
					 <option value="">Select Product Type </option>
					  <option <?php if (isset($_POST['producttype3']) && $_POST['producttype3']=="Shirts") echo "selected";?> value="Shirts">Shirts</option>
					  <option <?php if (isset($_POST['producttype3']) && $_POST['producttype3']=="Saree") echo "selected";?> value="Saree">Saree</option>
					   <option <?php if (isset($_POST['producttype3']) && $_POST['producttype3']=="Ethnic top") echo "selected";?> value="Ethnic top">Ethnic top</option>
					    <option <?php if (isset($_POST['producttype3']) && $_POST['producttype3']=="Ethnic Bottom") echo "selected";?> value="Ethnic Bottom">Ethnic Bottom</option>
						 <option <?php if (isset($_POST['producttype3']) && $_POST['producttype3']=="Top wear") echo "selected";?> value="Top wear">Top wear</option>
						   <option <?php if (isset($_POST['producttype3']) && $_POST['producttype3']=="Winter Wear") echo "selected";?> value="Winter Wear">Winter Wear</option>
					   <option <?php if (isset($_POST['producttype3']) && $_POST['producttype3']=="Skirts") echo "selected";?> value="Skirts">Skirts</option>
					    <option <?php if (isset($_POST['producttype3']) && $_POST['producttype3']=="Jeans") echo "selected";?> value="Jeans">Jeans</option>
						 <option <?php if (isset($_POST['producttype3']) && $_POST['producttype3']=="Pants") echo "selected";?> value="Pants">Pants</option>
						   <option <?php if (isset($_POST['producttype3']) && $_POST['producttype3']=="Bags") echo "selected";?> value="Bags">Bags</option>
					   <option <?php if (isset($_POST['producttype3']) && $_POST['producttype3']=="Footwear") echo "selected";?> value="Footwear">Footwear</option>
					    <option <?php if (isset($_POST['producttype3']) && $_POST['producttype3']=="Jewelry") echo "selected";?> value="Jewelry">Jewelry</option>
						 <option <?php if (isset($_POST['producttype3']) && $_POST['producttype3']=="Accessory ") echo "selected";?> value="Accessory">Accessory </option>
						   <option <?php if (isset($_POST['producttype3']) && $_POST['producttype3']=="Cosmetics") echo "selected";?> value="Cosmetics">Cosmetics</option>
					   <option <?php if (isset($_POST['producttype3']) && $_POST['producttype3']=="Lowers") echo "selected";?> value="Lowers">Lowers</option>
                     </select>
					 <span class="error">* <?php echo $producttype3_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
              </div><!-- row -->
              
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Price</label>
                    <input type="text" name="productprice3" value="<?PHP if(!empty($_POST['productprice3'])) echo htmlspecialchars($_POST['productprice3']); ?>" class="form-control" />
					<span class="error">* <?php echo $productprice3_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Product Link</label>
                    <input type="text" name="productlink3" value="<?PHP if(!empty($_POST['productlink3'])) echo htmlspecialchars($_POST['productlink3']); ?>" class="form-control" />
					<span class="error">* <?php echo $productlink3_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
              </div><!-- row -->
              <div class="row">
                <div class="col-sm-6">
                <input id="uploadFile3" placeholder="Choose File" disabled="disabled" />
                  <div class="fileUpload btn btn-primary">
                      <span>Upload</span>
                      <input id="uploadBtn3" type="file" class="upload"name="image3" />
                  </div>
				   <span class="error">* <?php echo $image3_err;?></span>
                </div>
              </div>
              
            </div><!-- panel-body -->


            <div class="panel-body">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Product Name</label>
                    <input type="text" name="productname4" value="<?PHP if(!empty($_POST['productname4'])) echo htmlspecialchars($_POST['productname4']); ?>" class="form-control" />
					<span class="error">* <?php echo $productname4_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Product Type</label>
                    <select class="form-control input-lg" name="producttype4"> 
					
					<option value="">Select Product Type </option>
					<option <?php if (isset($_POST['producttype4']) && $_POST['producttype4']=="Shirts") echo "selected";?> value="Shirts">Shirts</option>
					  <option <?php if (isset($_POST['producttype4']) && $_POST['producttype4']=="Saree") echo "selected";?> value="Saree">Saree</option>
					   <option <?php if (isset($_POST['producttype4']) && $_POST['producttype4']=="Ethnic top") echo "selected";?> value="Ethnic top">Ethnic top</option>
					    <option <?php if (isset($_POST['producttype4']) && $_POST['producttype4']=="Ethnic Bottom") echo "selected";?> value="Ethnic Bottom">Ethnic Bottom</option>
						 <option <?php if (isset($_POST['producttype4']) && $_POST['producttype4']=="Top wear") echo "selected";?> value="Top wear">Top wear</option>
						   <option <?php if (isset($_POST['producttype4']) && $_POST['producttype4']=="Winter Wear") echo "selected";?> value="Winter Wear">Winter Wear</option>
					   <option <?php if (isset($_POST['producttype4']) && $_POST['producttype4']=="Skirts") echo "selected";?> value="Skirts">Skirts</option>
					    <option <?php if (isset($_POST['producttype4']) && $_POST['producttype4']=="Jeans") echo "selected";?> value="Jeans">Jeans</option>
						 <option <?php if (isset($_POST['producttype4']) && $_POST['producttype4']=="Pants") echo "selected";?> value="Pants">Pants</option>
						   <option <?php if (isset($_POST['producttype4']) && $_POST['producttype4']=="Bags") echo "selected";?> value="Bags">Bags</option>
					   <option <?php if (isset($_POST['producttype4']) && $_POST['producttype4']=="Footwear") echo "selected";?> value="Footwear">Footwear</option>
					    <option <?php if (isset($_POST['producttype4']) && $_POST['producttype4']=="Jewelry") echo "selected";?> value="Jewelry">Jewelry</option>
						 <option <?php if (isset($_POST['producttype4']) && $_POST['producttype4']=="Accessory ") echo "selected";?> value="Accessory">Accessory </option>
						   <option <?php if (isset($_POST['producttype4']) && $_POST['producttype4']=="Cosmetics") echo "selected";?> value="Cosmetics">Cosmetics</option>
					   <option <?php if (isset($_POST['producttype4']) && $_POST['producttype4']=="Lowers") echo "selected";?> value="Lowers">Lowers</option>
                     </select>
					 <span class="error">* <?php echo $producttype4_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
              </div><!-- row -->
              
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Price</label>
                    <input type="text" name="productprice4" value="<?PHP if(!empty($_POST['productprice4'])) echo htmlspecialchars($_POST['productprice4']); ?>" class="form-control" />
					<span class="error">* <?php echo $productprice4_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Product Link</label>
                    <input type="text" name="productlink4" value="<?PHP if(!empty($_POST['productlink4'])) echo htmlspecialchars($_POST['productlink4']); ?>" class="form-control" />
					<span class="error">* <?php echo $productlink4_err;?></span>
                  </div>
                </div><!-- col-sm-6 -->
              </div><!-- row -->
              <div class="row">
                <div class="col-sm-6">
                <input id="uploadFile4" placeholder="Choose File" disabled="disabled" />
                  <div class="fileUpload btn btn-primary">
                      <span>Upload</span>
                      <input id="uploadBtn4" type="file" class="upload" name="image4"/>
                  </div>
				   <span class="error">* <?php echo $image4_err;?></span>
                </div>
              </div>
              
            </div><!-- panel-body -->
            <div class="panel-footer">
              <button name="submit" class="btn btn-success">Submit</button>
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
