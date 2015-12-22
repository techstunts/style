<!DOCTYPE html>
<html lang="en">
<head>
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
      <h2><i class="fa fa-home"></i> Dashboard <span>...</span></h2>
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


      </div><!-- row -->
		<?php
			include 'databaseconnect.php';
			$id=intval($_GET["id"]);
			
			
			 $data = mysql_query("SELECT * FROM createdlook JOIN stylish_details ON createdlook.stylish_id=stylish_details.stylish_id WHERE look_id = $id");
 //Puts it into an array 
 $info = mysql_fetch_array( $data );
 $sql=mysql_query("select * from lookdescrip join createdlook on createdlook.product_id1=lookdescrip.id OR createdlook.product_id2=lookdescrip.id OR createdlook.product_id3=lookdescrip.id OR createdlook.product_id4=lookdescrip.id where look_id=$id" );
while($data1=mysql_fetch_array($sql)){
  $totaldata[]=$data1;
}


 ?> 
    <div class="panel panel-default panel-blog">
          <div class="panel-body">
            <h3 class="blogsingle-title">Look Name : <?php echo $info['look_name']; ?></h3>
            
            <ul class="blog-meta">
              <li>By: <?php echo $info['stylish_name']; ?></li>
              <li><?php echo $info['date']; ?></li>
            </ul>
            
            <br />
          <center><img src="<?php echo $info['look_image'] ?>" class="img-responsive" alt="" /></center>
			
	


			     
            <div class="mb20"></div>            
            <h3>Look Description</h3><p><?php echo $info['look_description'] ?></p>
            
          </div><!-- panel-body -->
        </div><!-- panel -->
<div class="col-sm-6 col-md-3">
          <div class="panel panel-dark panel-stat">
            <div class="panel-heading">
              <div class="stat">
                <div class="row">
                  <div class="col-xs-12">
          
           
           <a href="list_style_item1.php?id=<?php echo $totaldata[0]['id'] ?>"><img class="img-thumbnail" src="<?php echo $totaldata[0]['upload_image'] ?>"  alt="" /></a>
                
                       
                  </div>
                  
                </div><!-- row -->

                <div class="mb15"></div>
                <div class="row">
                 
                </div><!-- row -->

              </div><!-- stat -->

            </div><!-- panel-heading -->
          </div><!-- panel -->
        </div><!-- col-sm-6 -->

        <div class="col-sm-6 col-md-3">
          <div class="panel panel-dark panel-stat">
            <div class="panel-heading">
              <div class="stat">
                <div class="row">
                  <div class="col-xs-12">
          
           
           <a href="list_style_item1.php?id=<?php echo $totaldata[1]['id'] ?>"><img class="img-thumbnail" src="<?php echo $totaldata[1]['upload_image'] ?>"  alt="" /></a>
                
                       
                  </div>
                  
                </div><!-- row -->

                <div class="mb15"></div>
                <div class="row">
                 
                </div><!-- row -->

              </div><!-- stat -->

            </div><!-- panel-heading -->
          </div><!-- panel -->
        </div><!-- col-sm-6 -->

        <div class="col-sm-6 col-md-3">
          <div class="panel panel-dark panel-stat">
            <div class="panel-heading">
              <div class="stat">
                <div class="row">
                  <div class="col-xs-12">
          
           
           <a href="list_style_item1.php?id=<?php echo $totaldata[2]['id'] ?>"><img class="img-thumbnail" src="<?php echo $totaldata[2]['upload_image'] ?>"  alt="" /></a>
                
                       
                  </div>
                  
                </div><!-- row -->

                <div class="mb15"></div>
                <div class="row">
                  
                </div><!-- row -->

              </div><!-- stat -->

            </div><!-- panel-heading -->
          </div><!-- panel -->
        </div><!-- col-sm-6 -->

        <div class="col-sm-6 col-md-3">
          <div class="panel panel-dark panel-stat">
            <div class="panel-heading">
              <div class="stat">
                <div class="row">
                  <div class="col-xs-12">
          
           
           <a href="list_style_item1.php?id=<?php echo $totaldata[3]['id'] ?>"><img class="img-thumbnail" src="<?php echo $totaldata[3]['upload_image'] ?>"  alt="" /></a>
                
                       
                  </div>
                  
                </div><!-- row -->

                <div class="mb15"></div>
                <div class="row">
                  
                </div><!-- row -->

              </div><!-- stat -->

            </div><!-- panel-heading -->
          </div><!-- panel -->
        </div><!-- col-sm-6 -->
        
          <div class="row">
            <div class="panel-footer">
             <center><a href="dupicate_look.php?id=<?php echo $info['look_id'] ?>"><button class="btn btn-lightblue">Duplicate Look</button></a></center>
            
            </div>
          </div>

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