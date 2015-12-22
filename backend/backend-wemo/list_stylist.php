<?php
include 'databaseconnect.php';
?>                
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="images/favicon.png" type="image/png">

  <title>I Style You</title>
 <style>
table.dataTable.select tbody tr,
table.dataTable thead th:first-child {
  cursor: pointer;
}
</style>
 <link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.9/css/dataTables.bootstrap.min.css">
  
  
  
  <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.9/js/dataTables.bootstrap.min.js"></script>
  <link href="css/style.default.css" rel="stylesheet">
    <script type="text/javascript" class="init">
$(document).ready(function() {
    $('#table').DataTable();
} );
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
                <li><a href="query.php?action=logout"><i class="glyphicon glyphicon-log-out"></i>Log Out</a></li>
              </ul>
            </div>
          </li>
          
        </ul>
      </div><!-- header-right -->

    </div><!-- headerbar -->

    <div class="pageheader">
      <h2><i class="fa fa-home"></i> List Stylist <span>Subtitle goes here...</span></h2>
      <div class="breadcrumb-wrapper">
        <span class="label">You are here:</span>
        <ol class="breadcrumb">
          <li><a href="dashboard.php">istyleyou</a></li>
          <li class="active">Dashboard</li>
        </ol>
      </div>
    </div>

    <div class="contentpanel">

      <div class="row">

                  <div>
                    <form name="frm-example" id="frm-example">
                      <table id="table" class="display select" cellspacing="0" width="100%" >
                        <thead>
                          <tr>
                               
                               
                              <th>ID</th>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Expertise</th>
                              <th>Profile</th>
                              <th>Stylish Code</th>
                              <th>Image</th>
                           </tr>
                        </thead>
                        <tfoot>
                      <tr>
                             
                              <th>ID</th>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Expertise</th>
                              <th>Profile</th>
                              <th>Stylish Code</th>
                              <th>Image</th>
                          
                      </tr>
                    </tfoot>
                        <tbody>
              <?php
                 $query="Select stylish_name,stylish_email,expertise,profile,stylish_code,stylish_image from stylish_details";
                    $res = mysql_query($query);      
                  $numRows = mysql_num_rows($res);
                  if($numRows!=0){
                    $id=1;
                  while($resultSet = mysql_fetch_array($res))

                    {
                    ?>
                  <tr>
                 
                  <td><?php echo $id ?></td>
                  <td><?php echo $resultSet[0]?></td>
                  <td><?php echo $resultSet[1]?></td>
                  <td><?php echo $resultSet[2]?></td>
                  <td><?php echo $resultSet[3]?></td>
                  <td><?php echo $resultSet[4]?></td>
                  <td><img height="70" width="80" src="<?php echo $resultSet[5]?>"</img></td>
                          </tr>
                            <?php $id++;}}?>  
                        </tbody>
                     </table>
                    </div><!-- table-responsive -->
                    <div class="clearfix mb30"></div>
                </div><!-- row -->

    

          

      </div>

    </div><!-- contentpanel -->

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


</body>
</html>
