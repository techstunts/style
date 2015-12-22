<?php session_start();
if(@$_SESSION['isu_user_id'] != '') {
  header('Location:dashboard.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from themepixels.com/demo/webpage/bracket/signin.html by HTTrack Website Copier/3.x [XR&CO'2013], Thu, 17 Apr 2014 07:05:37 GMT -->
<head>
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script type="text/javascript">
  $(document).ready(function(){
  $("#add_err").css('display', 'none', 'important');
   $("#login").click(function(){  
    
      username=$("#name").val();
      password=$("#word").val();
      $.ajax({
       type: "POST",
       url: "query.php?action=login",
      data: "username="+username+"&password="+password,
       success: function(html){    
        
      if(html=='true')    {
       //$("#add_err").html("right username or password");
       window.location="look_list.php";
      }
      else    {
      $("#add_err").css('display', 'inline', 'important');
       $("#add_err").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong> wrong username or password</strong> Change a few things up and try submitting again. </div>');
      }
       },
       beforeSend:function()
       {
      $("#add_err").css('display', 'inline', 'important');
      $("#add_err").html("<img src='images/loaders/loader6.gif' alt=''> Loading...")
       }
      });
    return false;
  });
});



  </script>
</head>

<body class="signin">

<!-- Preloader -->
<div id="preloader">
    <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
</div>

<section>
  
    <div class="signinpanel">
        
        <div class="row">
          <div class="col-md-2">
          </div>
             <?php  /*  ?> 
            <div class="col-md-7">
              
                <div class="signin-info">
                    <div class="logopanel">
                        <h1><span>[</span> bracket <span>]</span></h1>
                    </div><!-- logopanel -->
                
                    <div class="mb20"></div>
                
                    <h5><strong>Welcome to Bracket Bootstrap 3 Template!</strong></h5>
                    <ul>
                        <li><i class="fa fa-arrow-circle-o-right mr5"></i> Fully Responsive Layout</li>
                        <li><i class="fa fa-arrow-circle-o-right mr5"></i> HTML5/CSS3 Valid</li>
                        <li><i class="fa fa-arrow-circle-o-right mr5"></i> Retina Ready</li>
                        <li><i class="fa fa-arrow-circle-o-right mr5"></i> WYSIWYG CKEditor</li>
                        <li><i class="fa fa-arrow-circle-o-right mr5"></i> and much more...</li>
                    </ul>
                    <div class="mb20"></div>
                    <strong>Not a member? <a href="signup.html">Sign Up</a></strong>
                </div><!-- signin0-info -->
              
            </div><!-- col-sm-7 -->
            <?php */ ?>
            <div class="col-md-6">
                
                <form method="post" action="">
                    <h4 class="nomargin">Sign In</h4>
                    <p class="mt5 mb20">Login to access your account.</p>
                    <span id="add_err"></span>
                    <input type="text" class="form-control uname" name="name" id="name" placeholder="Username" />
                    <input type="password" class="form-control pword" name="word" id="word" placeholder="Password" />
                    <!-- <a href="#"><small>Forgot Your Password?</small></a> -->
                    <button class="btn btn-success btn-block" id="login">Sign In</button>
                    
                </form>
            </div><!-- col-sm-5 -->
            <div class="col-md-4">
          </div>
            
        </div><!-- row -->
        
        <div class="signup-footer">
            <div class="pull-left">
                &copy; 2015. All Rights Reserved. I Style You
            </div>
            <!-- <div class="pull-right">
                Created By: <a href="http://themepixels.com/" target="_blank">ThemePixels</a>
            </div>
          -->
        </div>
        
    </div><!-- signin -->
  
</section>


<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/jquery-migrate-1.2.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/modernizr.min.js"></script>
<script src="js/retina.min.js"></script>

<script src="js/custom.js"></script>

</body>

<!-- Mirrored from themepixels.com/demo/webpage/bracket/signin.html by HTTrack Website Copier/3.x [XR&CO'2013], Thu, 17 Apr 2014 07:05:37 GMT -->
</html>
