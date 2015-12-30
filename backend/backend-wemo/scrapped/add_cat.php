
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="images/favicon.png" type="image/png">

  <title>I Style You : Add Cat</title>

  <link href="css/style.default.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script type="text/javascript">
      $(document).ready(function() {
          $('#b_submit').click( function () {
           alert("B SUbmit");
         });

           $('#style_submit').click( function () {
           alert("style SUbmit");
         });

            $('#price_submit').click( function () {
           alert("B price_submit");
         });

             $('#o_submit').click( function () {
           alert("Ocassion SUbmit");
         });


       });
  </script>
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
      <h2><i class="fa fa-home"></i> Create Category <span></span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">You are here:</span>
        <ol class="breadcrumb">
          <li><a href="dashboard.php">istyleyou</a></li>
          <li class="active">Create Look</li>
        </ol>
      </div>
    </div>

    <div class="contentpanel">
      
      <div class="row">
        <div class="col-md-6">
          
          <form id="form1_body" class="form-horizontal" action="">
            <div class="panel panel-default">
              <div class="panel-heading">
                <div class="panel-btns">
                  <a href="#" class="panel-close">&times;</a>
                  <a href="#" class="minimize">&minus;</a>
                </div>
                <h4 class="panel-title">Add Body Type</h4>
                <p>Add New Body type From here.</p>
              </div>
              <div class="panel-body">
              
                <div class="form-group">
                  <label class="col-sm-4 control-label">Type Name:</label>
                  <div class="col-sm-8">
                    <input type="text" name="body_name" class="form-control" />
                  </div>
                </div>
                
              </div><!-- panel-body -->
              <div class="panel-footer">
                <button class="btn btn-primary" type="button" id="b_submit">Submit</button>
                <button type="reset" class="btn btn-default">Reset</button>
              </div><!-- panel-footer -->
            </div><!-- panel-default -->
          </form>
            
        </div><!-- col-md-6 -->
        
        <div class="col-md-6">
          
          <form id="form_oca" class="form-horizontal form-bordered" action="">
            <div class="panel panel-default">
              <div class="panel-heading">
                <div class="panel-btns">
                  <a href="#" class="panel-close">&times;</a>
                  <a href="#" class="minimize">&minus;</a>
                </div>
                <h4 class="panel-title">Occasion</h4>
                <p>Add New Occasion From here.</p>
              </div>
              <div class="panel-body panel-body-nopadding">
              
                <div class="form-group">
                  <label class="col-sm-4 control-label">Occasion Name:</label>
                  <div class="col-sm-8">
                    <input type="text" name="O_name" class="form-control" />
                  </div>
                </div>
                
              </div><!-- panel-body -->
              <div class="panel-footer">
                <button class="btn btn-primary" type="button" id="o_submit">Submit</button>
                <button type="reset" class="btn btn-default">Reset</button>
              </div><!-- panel-footer -->
            </div><!-- panel-default -->
          </form>
            
        </div><!-- col-md-6 -->
        
      </div><!-- row -->
      
      <div class="row">
        
        <div class="col-md-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              <div class="panel-btns">
                <a href="#" class="panel-close">&times;</a>
                <a href="#" class="minimize">&minus;</a>
              </div>
              <h4 class="panel-title">Style Type</h4>
                <p>Add New Style Type From here.</p>
            </div>
            <form id="style_type" name="style_type"  action="">
            <div class="panel-body">
              <div class="row row-pad-5">
                <div class="col-lg-8">
                  <input type="text" name="style_ty_name" placeholder="Style Type Name" class="form-control" />
                </div>
               
              </div><!-- row -->
              
            </div><!-- panel-body -->
            <div class="panel-footer">
              <button class="btn btn-primary" type="button" id="style_submit">Add Comment</button>
            </div>
            </form>
          </div><!-- panel -->
        </div>
        
        <div class="col-md-6">
          <div class="panel panel-default">
            <div class="panel-heading">
              <div class="panel-btns">
                <a href="#" class="panel-close">&times;</a>
                <a href="#" class="minimize">&minus;</a>
              </div>
              <h4 class="panel-title">Price Range</h4>
              <p>add new price range from here</p>
            </div>
            <form id="price_range" name="price_range"  action="">
            <div class="panel-body">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">Start Price Value</label>
                    <input type="text" name="start_price" class="form-control" />
                  </div>
                </div><!-- col-sm-6 -->
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="control-label">End Price Value</label>
                    <input type="text" name="end_price" class="form-control" />
                  </div>
                </div><!-- col-sm-6 -->
              </div><!-- row -->
              
            </div><!-- panel-body -->
            <div class="panel-footer">
              <button class="btn btn-primary" type="button" id="price_submit">Submit Card</button>
            </div>
          </form>
          </div>
        </div>

        
      </div><!-- row -->
      
    </div> <!-- Content Panel   --><!-- contentpanel -->

  </div><!-- mainpanel -->

  <div class="rightpanel">
    
  </div><!-- rightpanel -->

</section>

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
<script src="js/cat_data.js"></script>



</body>
</html>
