<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="images/favicon.png" type="image/png">

  <title>IStyle You 
  
  </title>

  <link href="css/style.default.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <script src="js/respond.min.js"></script>
  <![endif]-->
  <style>.error {color: #FF0000;}</style>
</head>

<body>
<!-- Preloader -->
<div id="preloader">
    <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
</div>
<?php

include 'databaseconnect.php';
$nameErr=$emailErr=$passwordErr=$profileErr=$expertiseErr=$ageErr=$codeErr= $genderErr=$descriptionErr=$imageErr="";
$name = $email = $password = $profile = $expertise = $age = $code = $gender = $description = $image= "";
$valid="";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$valid=true;
   if (empty($_POST["name"])) {
     $nameErr = "Name is required";
	 $valid=false;
   } else {
     $name = test_input($_POST["name"]);
   }
   
   if (empty($_POST["email"])) {
     $emailErr = "Email is required";
	 $valid=false;
   } else {
     $email = test_input($_POST["email"]);
     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
         $valid=false;
}
   }
     
   if (empty($_POST["password"])) {
	   $valid=false;
     $passwordErr = "password is required";
   } else {
     $password = test_input($_POST["password"]);
   }

   if (empty($_POST["profile"])) {
    $profileErr = "profile is required";
	$valid=false;
   } else {
     $profile = test_input($_POST["profile"]);
   }

   if (empty($_POST["expertise"])) {
     $expertiseErr = "expertise is required";
	 $valid=false;
   } else {
     $expertise = test_input($_POST["expertise"]);
   }
    if (empty($_POST["age"])) {
     $ageErr = "age is required";
	 $valid=false;
   } else {
     $age = test_input($_POST["age"]);
     if (!preg_match("/^[0-9]/",$age)) {
          $ageErr = "Only numbers are allowed";
          $valid=false;
      }
   }
    if (empty($_POST["code"])) {
     $codeErr = "code is required";
	 $valid=false;
   } else {
     $code = test_input($_POST["code"]);
   }
    if (empty($_POST["gender"])) {
     $genderErr = " please select Gender";
	 $valid=false;
   } else {
     $gender = test_input($_POST["gender"]);
   }
    if (empty($_POST["description"])) {
     $descriptionErr = "description is required";
	 $valid=false;
   } else {
     $description = test_input($_POST["description"]);
   }
  if(empty($_FILES['image']['name'])){ 
$imageErr="Please Select an image to upload"; 
$valid=false; 
}
else{
$path = $_FILES['image']['name'];
$ext = pathinfo($path, PATHINFO_EXTENSION);

    if (($ext!= "jpeg")&&($ext!= "gif") &&($ext!= "jpg") &&($ext!= "png") &&($ext!= "JPG") &&($ext!= "GIF") &&($ext!= "JPEG") &&($ext!= "PNG")){
	$imageErr="Please upload a valid image";
	$valid=false; 
	
	}
	
	}

}
if($valid){
require 'signup.php';
}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>
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
      <h2><i class="fa fa-home"></i> Create Stylist <span>Subtitle goes here...</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">You are here:</span>
        <ol class="breadcrumb">
          <li><a href="index.php">istyleyou</a></li>
          <li class="active">Dashboard</li>
        </ol>
      </div>
    </div>

    <div class="contentpanel">

      <div class="row">

        
 <div class="col-md-6">
                
             <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype ="multipart/form-data" > 
                    
                    <h3 class="nomargin">Create Stylist</h3>
                
                    <label class="control-label">Name</label>
                    <div class="row mb10">
                        <div class="col-sm-12">
                            <input type="text" name="name" id="name" value = "<?PHP if(!empty($_POST['name'])) echo htmlspecialchars($_POST['name']); ?>"  class="form-control" placeholder="name" />
							<span class="error">* <?php echo $nameErr;?></span>
                        </div>
                                           
	                  </div>
                     <label class="control-label">Email</label>
                    <div class="row mb10">
                        <div class="col-sm-12">
                            <input type="text" name="email" id="email" value="<?PHP if(!empty($_POST['email'])) echo htmlspecialchars($_POST['email']); ?>" class="form-control" placeholder="email" />
							<span class="error">*<?php echo $emailErr;?></span>
                        </div>
                                           
										   </div>
                    
                     <label class="control-label">Password</label>
                    <div class="row mb10">
                      <div class="col-sm-12">
                    <input type="password" name="password" id="password" id="name" value="<?PHP if(!empty($_POST['password'])) echo htmlspecialchars($_POST['password']); ?>" class="form-control" placeholder="password" />
							<span class="error">*<?php echo $passwordErr;?></span>
                        </div>
                        </div>
                    
                    <label class="control-label">Profile</label>
                    <div class="row mb10">
                        <div class="col-sm-12">
                            <input type="text" name="profile"  id="profile" value="<?PHP if(!empty($_POST['profile'])) echo htmlspecialchars($_POST['profile']);?>" class="form-control" placeholder="profile" />
							<span class="error">*<?php echo $profileErr;?></span>
                        </div>
                        </div>
                    
                    <label class="control-label">Expertise</label>
                    <div class="row mb10">
                        <div class="col-sm-12">
                            <input type="text" name="expertise"  id="expertise" value="<?PHP if(!empty($_POST['expertise'])) echo htmlspecialchars($_POST['expertise']); ?>" class="form-control" placeholder="expertise" />
							<span class="error">*<?php echo $expertiseErr;?></span>
                        </div>
                        </div>
                   
                    
                   <div class="row">
    <div class="col-sm-4">
      <label class="control-label">Age</label>
     <input type="text"  name="age" id="age" value="<?PHP if(!empty($_POST['age'])) echo htmlspecialchars($_POST['age']);?>" class="form-control" placeholder="age" />
	 <span class="error">*<?php echo $ageErr;?></span>
    </div>
    <div class="col-sm-4">   
      <label class="control-label">Code</label>
     <input type="text"  name="code" id="code" value="<?PHP if(!empty($_POST['code'])) echo htmlspecialchars($_POST['code']);?>" class="form-control" placeholder="code" />
	 <span class="error">*<?php echo $codeErr;?></span>
    </div>
	       
    <label class="control-label">Gender</label>
	
    <div class="col-sm-4">
        <label class="radio-inline"> 
		<input type="radio" name="gender" id="male" value="male" checked> male </label>
       
        <label class="radio-inline"> <input type="radio" name="gender" id="female" value="female" > Female </label><br>
		<span class="error">*<?php echo $genderErr;?></span>
    </div>
  </div></br>
                         
   <label class="control-label"> Description</label>
       <div class="row mb10">
        <div class="col-sm-12">
      <textarea rows="5" id="description" name="description" class="form-control"  ><?PHP if(!empty($_POST['description'])) echo htmlspecialchars($_POST['description']); ?></textarea>
	  <span class="error">*<?php echo $descriptionErr;?></span>
    </div>
     </div>
                <div class="row">
                <div class="col-sm-12">
                <input id="uploadFile1" placeholder="Choose File" disabled="disabled" />
                  <div class="fileUpload btn btn-primary">
                      <span>Upload</span>
                      <input id="uploadBtn1" type="file" class="upload" name="image" />
					 
                  </div>
				  <span class="error">*<?php echo $imageErr;?></span>
                </div>
              </div><br>
			 <div class="row mb10">
              <div class="col-sm-5">
			  </div>
			  <div class="col-sm-3">

      <button class="btn btn-success btn-block" name="signup" id="signup" value="singup">Sign Up</button>   
    </div>
                    
			
					</div>
					
                    <br />
                     
                    
                </form>
            </div><!-- col-sm-6 -->
      

      </div><!-- row -->


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
