<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>

        .error {
            color: #FF0000;
        }

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
            $('#button1').click(function () {
                var checkedNum = $('input[name="select[]"]:checked').length;

                if (!checkedNum) {
                    alert("Please select at least one look");
                    return false;
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            var s = $("#send1");
            var pos = s.position();
            $(window).scroll(function () {
                var windowpos = $(window).scrollTop() + 60;
                if (windowpos >= pos.top) {
                    s.addClass("stick");
                } else {
                    s.removeClass("stick");
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            var window_height = $('.content_div').height();
            var start_pos = 10;

            var start = parseInt($('#start').val());
            var query_str = $('#serstr').val();

            var scroll = 80; //set this to 'Scroll position(see below)' you see after scrolling down the container
            $('#vm').click(function () {
                var scroll_pos = $('.content_div').scrollTop() + start_pos;
                $('.content_div').append('<div class="loader"><img src="http://ajaxload.info/images/exemples/4.gif" /></div>');
                var str = 'start=' + start + "&qstring=" + query_str;
                $.ajax({
                    dataType: 'json',
                    type: "POST",
                    url: 'view_more_looks.php',
                    data: str,
                    async: false,
                    success: function (msg) {
                        console.log(msg.status);

                        $('.content_div').append(msg.htm);
                        $('.loader').remove();
                        start = start + 12;

                        $('#start').val(start);
                    }
                });
                start_pos += 10; //replace 10 with len (no. of rows to be fetched on every ajax call), same as 'len'
                scroll += window_height + 80; //replace 80 with 'scroll' value above, don't just write 'scroll' here

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
                <input type="text" class="form-control" name="keyword" placeholder="Search here..."/>
            </form>

            <div class="header-right">
                <ul class="headermenu">
                    <li>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <img src="images/photos/loggeduser.png" alt=""/>
                                Test User
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                                <li><a href="query.php?action=logout"><i class="glyphicon glyphicon-log-out"></i> Log
                                        Out</a></li>
                            </ul>
                        </div>
                    </li>

                </ul>
            </div><!-- header-right -->

        </div><!-- headerbar -->
        <?php
        include 'databaseconnect.php';

        // session_start();
        $lookerror = "";
        $result_err = "";
        $str = "";
        $valid = "";
        $valid1 = "";
        $valid = true;
        $query_str1 = "";
        if (!empty($_REQUEST['body_type_id'])) {

            $query_str1 .= "l.body_type_id='";
            $query_str1 .= $_POST['body_type_id'];
            $query_str1 .= " ' ";


            $str .= "body_type=" . $_POST['body_type_id'];

        }

        if (!empty($_POST['budget_id'])) {
            if (!empty($_REQUEST['body_type_id']) || (!empty($_REQUEST['gender_id']) AND !empty($_REQUEST['age_group_id']) AND !empty($_REQUEST['occasion_id']) AND !empty($_REQUEST['status_id']) AND !empty($_REQUEST['body_type_id']))) {
                $query_str1 .= " AND ";
                $str .= "&";
            }
            $query_str1 .= "l.budget_id='";
            $query_str1 .= $_POST['budget_id'];
            $query_str1 .= " ' ";


            $str .= "budget_id=" . $_POST['budget_id'];

        }

        if (!empty($_POST['age_group_id'])) {
            if (!empty($_REQUEST['body_type_id']) || !empty($_REQUEST['budget_id']) || (!empty($_REQUEST['gender_id']) AND !empty($_REQUEST['occasion_id']) AND !empty($_REQUEST['status_id']) AND !empty($_REQUEST['body_type_id']) AND !empty($_REQUEST['budget_id']))) {
                $query_str1 .= " AND ";
                $str .= "&";
            }

            $query_str1 .= "age_group_id='";
            $query_str1 .= $_POST['age_group_id'];
            $query_str1 .= " ' ";
            $str .= "age_group_id=" . $_POST['age_group_id'];

        }
        if (!empty($_POST['occasion_id'])) {
            if (!empty($_REQUEST['body_type_id']) || !empty($_REQUEST['budget_id']) || !empty($_REQUEST['age_group_id']) || (!empty($_REQUEST['gender_id']) AND !empty($_REQUEST['age_group_id']) AND !empty($_REQUEST['occasion_id']) AND !empty($_REQUEST['status_id']) AND !empty($_REQUEST['body_type_id']) AND !empty($_REQUEST['budget_id']))) {
                $query_str1 .= " AND ";
                $str .= "&";
            }

            $query_str1 .= "l.occasion_id='" . $_POST['occasion_id'] . "'";

            $str .= "occasion_id=" . $_POST['occasion_id'];

        }

        if (!empty($_POST['gender_id'])) {
            if (!empty($_REQUEST['body_type_id']) || !empty($_REQUEST['budget_id']) || !empty($_REQUEST['age_group_id']) || !empty($_REQUEST['occasion_id']) || (!empty($_REQUEST['age_group_id']) AND !empty($_REQUEST['occasion_id']) AND !empty($_REQUEST['status_id']) AND !empty($_REQUEST['body_type_id']) AND !empty($_REQUEST['budget_id']) AND !empty($_REQUEST['age_group_id']) AND !empty($_REQUEST['occasion_id']))) {
                $query_str1 .= " AND ";
                $str .= "&";
            }

            $query_str1 .= "l.gender_id='";
            $query_str1 .= $_POST['gender_id'];
            $query_str1 .= " ' ";

            $str .= "gender_id=" . $_POST['gender_id'];

        }
        if (!empty($_REQUEST['status_id'])) {
            if (!empty($_REQUEST['body_type_id']) || !empty($_REQUEST['budget_id']) || !empty($_REQUEST['age_group_id']) || !empty($_REQUEST['gender_id']) || !empty($_REQUEST['occasion_id']) || (!empty($_REQUEST['gender_id']) AND !empty($_REQUEST['age_group_id']) AND !empty($_REQUEST['occasion_id']) AND !empty($_REQUEST['body_type_id']) AND !empty($_REQUEST['budget_id']) AND !empty($_REQUEST['age_group_id']))) {
                $query_str1 .= " AND ";
                $str .= "&";
            }

            $query_str1 .= "l.status_id='";
            $query_str1 .= $_POST['status_id'];
            $query_str1 .= " ' ";


            $str .= "status_id=" . $_POST['status_id'];
        }

        if (!empty($_REQUEST['filter'])) {
            $valid = true;
            if (!empty($_REQUEST['gender_id']) || !empty($_REQUEST['body_type_id']) || !empty($_REQUEST['budget_id']) || !empty($_REQUEST['age_group_id']) || !empty($_REQUEST['occasion_id']) || !empty($_REQUEST['status_id']) || (!empty($_REQUEST['gender_id']) AND !empty($_REQUEST['body_type_id']) AND !empty($_REQUEST['budget_id']) AND !empty($_REQUEST['age_group_id']) AND !empty($_REQUEST['occasion_id']) AND !empty($_REQUEST['status_id']))) {
                $query_str1 .= " AND ( ";

            } else {
                $query_str1 .= "(";
            }
            $filtering = $_REQUEST['filter'];

            foreach ($filtering as $value) {
                if (!empty($value)) {
                    $query_str1 .= "l.stylish_id='";
                    $query_str1 .= $value;
                    $query_str1 .= " ' ";

                    $query_str1 .= " OR ";
                }
            }
            $query_str1 = substr($query_str1, 0, -4);
            $query_str1 .= ")";

        }

        //$filter_err="Please Select atleast 1 filter";
        //$valid=false;
        //if(!empty($_POST['gender_id']) || !empty($_POST['body_type_id']) || !empty($_POST['budget_id']) || !empty($_POST['age_group_id']) || !empty($_POST['occasion_id']) || !empty($_POST['status_id']))
        //{
        if (!empty($_POST['body_type_id']) || !empty($_POST['budget_id']) || !empty($_POST['gender_id']) || !empty($_POST['age_group_id']) || !empty($_POST['occasion_id']) || !empty($_POST['status_id']) || !empty($_POST['filter'])) {

            $valid = true;
            $filter_err = "";


            $sql1 = "SELECT distinct l.*, l.id as look_id, l.image as look_image
        from looks l
        join looks_products lp ON l.id = lp.look_id
        join products p ON lp.product_id = p.id
        where " . $query_str1 . "
        ORDER BY l.id DESC LIMIT 0,12";


        } else {


            $valid1 = true;
            $valid = false;
            $valid = true;
            $filter_err = "";
            $str = '';
            $sql1 = "SELECT *, looks.id as look_id, looks.image as look_image  from looks  ORDER BY looks.id DESC LIMIT 0,12";
        }

echo $sql1;

        /*
       else{
          if(empty($_REQUEST['gender_id']) AND empty($_REQUEST['body_type_id']) AND empty($_REQUEST['budget_id']) AND empty($_REQUEST['age_group_id']) AND empty($_REQUEST['occasion_id']) AND empty($_REQUEST['status_id']) ){
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
                            <span class="error"> <?php echo $filter_err; ?></span>

                        </div>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group">
                                <label class="col-sm-0 control-label" for="checkbox"></label>
                                <div class="col-sm-5">
                                    <?php
                                    $sql = "SELECT * FROM stylists";
                                    $result = mysql_query($sql);

                                    while ($row = mysql_fetch_array($result)) {
                                        echo "<div class=" . "checkbox block" . "><label><input type=" . "'checkbox'" . "name=" . "filter[]" . " value='" . $row['stylish_id'] . "'>" . $row['name'] . "</label></div>";
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
                                    <select class="form-control mb15" name="body_type_id">
                                        <option value="">Select Body Type</option>
                                        <option <?php if (isset($_REQUEST['body_type_id']) && $_REQUEST['body_type_id'] == "1") echo "selected"; ?>
                                            value="1">Apple
                                        </option>
                                        <option <?php if (isset($_REQUEST['body_type_id']) && $_REQUEST['body_type_id'] == "2") echo "selected"; ?>
                                            value="2">Banana
                                        </option>
                                        <option <?php if (isset($_REQUEST['body_type_id']) && $_REQUEST['body_type_id'] == "5") echo "selected"; ?>
                                            value="5">Pear
                                        </option>
                                        <option <?php if (isset($_REQUEST['body_type_id']) && $_REQUEST['body_type_id'] == "3") echo "selected"; ?>
                                            value="3">Hourglass
                                        </option>
                                        <option <?php if (isset($_REQUEST['body_type_id']) && $_REQUEST['body_type_id'] == "4") echo "selected"; ?>
                                            value="4">Muscular
                                        </option>
                                        <option <?php if (isset($_REQUEST['body_type_id']) && $_REQUEST['body_type_id'] == "6") echo "selected"; ?>
                                            value="6">Regular
                                        </option>
                                        <option <?php if (isset($_REQUEST['body_type_id']) && $_REQUEST['body_type_id'] == "7") echo "selected"; ?>
                                            value="7">Round
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <select class="form-control mb15" name="budget_id">
                                        <option value="">Select Budget</option>
                                        <option <?php if (isset($_REQUEST['budget_id']) && $_REQUEST['budget_id'] == "1") echo "selected"; ?>
                                            value="1"><2000
                                        </option>
                                        <option <?php if (isset($_REQUEST['budget_id']) && $_REQUEST['budget_id'] == "2") echo "selected"; ?>
                                            value="2">2000-5000
                                        </option>
                                        <option <?php if (isset($_REQUEST['budget_id']) && $_REQUEST['budget_id'] == "3") echo "selected"; ?>
                                            value="3">5000-10000
                                        </option>
                                        <option <?php if (isset($_REQUEST['budget_id']) && $_REQUEST['budget_id'] == "4") echo "selected"; ?>
                                            value="4">>10000
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <select class="form-control mb15" name="age_group_id">
                                        <option value="">Select Age</option>
                                        <option <?php if (isset($_REQUEST['age_group_id']) && $_REQUEST['age_group_id'] == "2") echo "selected"; ?>
                                            value="2">Teenager
                                        </option>
                                        <option <?php if (isset($_REQUEST['age_group_id']) && $_REQUEST['age_group_id'] == "4") echo "selected"; ?>
                                            value="4">Young(18-22)
                                        </option>
                                        <option <?php if (isset($_REQUEST['age_group_id']) && $_REQUEST['age_group_id'] == "3") echo "selected"; ?>
                                            value="3">Young Medium (22-30)
                                        </option>
                                        <option <?php if (isset($_REQUEST['age_group_id']) && $_REQUEST['age_group_id'] == "1") echo "selected"; ?>
                                            value="1">Medium (30-40)
                                        </option>
                                        <option <?php if (isset($_REQUEST['age_group_id']) && $_REQUEST['age_group_id'] == "5") echo "selected"; ?>
                                            value="5">Old > 40
                                        </option>
                                    </select>
                                </div>
                            </div><!-- row -->
                            <div class="row row-pad-5">
                                <div class="col-lg-4">
                                    <select class="form-control mb15" name="occasion_id">
                                        <option value="">Select Occasion</option>
                                        <option <?php if (isset($_REQUEST['occasion_id']) && $_REQUEST['occasion_id'] == "6") echo "selected"; ?>
                                            value="6">Work Wear
                                        </option>
                                        <option <?php if (isset($_REQUEST['occasion_id']) && $_REQUEST['occasion_id'] == "5") echo "selected"; ?>
                                            value="5">Wine & Dine
                                        </option>
                                        <option <?php if (isset($_REQUEST['occasion_id']) && $_REQUEST['occasion_id'] == "3") echo "selected"; ?>
                                            value="3">Ethnic/Festive
                                        </option>
                                        <option <?php if (isset($_REQUEST['occasion_id']) && $_REQUEST['occasion_id'] == "2") echo "selected"; ?>
                                            value="2">Club
                                        </option>
                                        <option <?php if (isset($_REQUEST['occasion_id']) && $_REQUEST['occasion_id'] == "1") echo "selected"; ?>
                                            value="1">Casuals
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <select class="form-control mb15" name="gender_id">
                                        <option value="">Select Gender</option>
                                        <option <?php if (isset($_REQUEST['gender_id']) && $_REQUEST['gender_id'] == "2") echo "selected"; ?>
                                            value="2">Male
                                        </option>
                                        <option <?php if (isset($_REQUEST['gender_id']) && $_REQUEST['gender_id'] == "1") echo "selected"; ?>
                                            value="1">Female
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <select class="form-control mb15" name="status_id">
                                        <option value="">Select Status</option>
                                        <option <?php if (isset($_REQUEST['status_id']) && $_REQUEST['status_id'] == "1") echo "selected"; ?>
                                            value="1">Active
                                        </option>
                                        <option <?php if (isset($_REQUEST['status_id']) && $_REQUEST['status_id'] == "2") echo "selected"; ?>
                                            value="2">Inactive
                                        </option>
                                        <option <?php if (isset($_REQUEST['status_id']) && $_REQUEST['status_id'] == "5") echo "selected"; ?>
                                            value="5">Submitted
                                        </option>
                                    </select>
                                </div>
                            </div><!-- row -->
                            <div class="row row-pad-5">
                                <div class="col-lg-3">
                                    <button name="submit" class="btn btn-success" style="width:130px;">Filter</button>
                                </div>
                                <div class="col-lg-4">
                                    <button name="submit1" class="btn btn-success" style="width:130px;">Clear Filter
                                    </button>
                                </div>
                            </div>
                            <div class="row row-pad-5">
                                <div class="col-lg-12">
                                    Targeted app view:<br/>
                                    <?php
                                    $app_sections = array(
                                        "1" => "Style suggest",
                                        "2" => "Trending",
                                        "3" => "My Requests",
                                        "4" => "My Products",
                                        "5" => "Ask Advice",
                                        "6" => "Ask Look",
                                        "7" => "Ask Product",
                                        "8" => "Stylist",
                                    );
                                    foreach($app_sections as $id => $name){
                                        $checked = "";
                                        if(isset($_REQUEST['app_section']) && $_REQUEST['app_section'] == $id){
                                            $checked = "checked=checked";
                                        }
                                        echo "<label><input type='radio' name='app_section' value='{$id}' {$checked}> {$name}</label>";
                                    }
                                    ?>

                                </div>
                            </div>
                            <div class="row row-pad-5">
                                <div class="col-lg-8">
                                    <?php
                                    if(isset($_REQUEST['skip_gender_check']) && $_REQUEST['skip_gender_check'] == "Skip"){
                                    $checked = "checked=checked";
                                    }
                                    echo "<label><input type='checkbox' name='skip_gender_check' value='Skip' $checked> Send looks of any gender</label>";
                                    ?>
                                </div>
                            </div>
                        </div><!-- panel-body -->

                        <?php
                        if ($valid1) {
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
            <form method="POST" id="select1" action="look_list.php">
                <div class="content_div">
                    <div>
                        <div class="row">
                            <!--image code 1-->

                            <?php


                            if ($valid) {

                                $result1 = mysql_query($sql1);
                                ?>

                                <?php
                                $images = array();
                                $index = 0;

                                while ($row = mysql_fetch_assoc($result1)) // loop to give you the data in an associative array so you can use it however.
                                {
                                    $images[$index] = $row;
                                    $index++;
                                }
                                $rows = mysql_num_rows($result1);

                                if ($rows == 0) {

                                    echo $result_err = "No Results Found";

                                }
                                foreach ($images as &$value) {
                                    ?>

                                    <div class="col-sm-6 col-md-3">
                                        <div class="panel panel-dark panel-stat">
                                            <div class="panel-heading">
                                                <div class="stat">
                                                    <div class="row">
                                                        <div class="col-xs-12">


                                                            <a href="look_view.php?id=<?php echo $value['look_id'] ?>"><img
                                                                    class="img-thumbnail"
                                                                    src="<?php echo $value['look_image'] ?>"
                                                                    alt=""/></a>


                                                        </div>

                                                    </div><!-- row -->

                                                    <div class="mb15"></div>
                                                    <div class="row">
                                                        <div class="col-xs-6">
                                                            <div class="checkbox block "><label><input type="checkbox"
                                                                                                       class="cbox"
                                                                                                       id="chkSelect"
                                                                                                       name="select[]"
                                                                                                       value="<?php echo $value['look_id'] ?>">
                                                                    Select</label></div>
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
                            if ($valid)
                            {
                            ?>


                        </div><!-- row -->
                        <?php
                        $sql1 = substr($sql1, 0, -11);


                        ?>

                    </div>
                </div>
                <input type="hidden" name="serstr" id="serstr" value="<?php echo $sql1; ?>"/>
                <input type="hidden" name="start" id="start" value="12"/>

                <?php

                if ($rows != 0) { ?>
                    <center>
                        <div id="vm" class="btn btn-success">view more</div>
                    </center>
                    <?php
                }
                }

                $app_section = 3;
                if(isset($_POST['app_section']) && $_POST['app_section']!=""){
                    $app_section = $_POST['app_section'];
                }

                $skip_gender_check = false;
                if(isset($_POST['skip_gender_check']) && $_POST['skip_gender_check']!=""){
                    $skip_gender_check = $_POST['skip_gender_check'];
                }
                ?>
                <div id="send1" class="stick">
                    <button name="send" id="button1" class="btn btn-success">send</button>
                    <input type="hidden" name="app_section" value="<?php echo $app_section;?>"/>
                    <input type="hidden" name="skip_gender_check" value="<?php echo $skip_gender_check;?>"/>
                </div>
            </form>

        </div>

    </div><!-- contentpanel -->

    </div><!-- mainpanel -->

    <div class="rightpanel">

    </div><!-- rightpanel -->
    <?php
    if (!empty($_REQUEST['userid'])) {
        $_SESSION["users"] = $_REQUEST['userid'];
        $userid = $_REQUEST['userid'];
        $ids = $_REQUEST['ids'];
        $_SESSION['style_request_ids'] = $ids;

        $regid = array();
        foreach ($userid as $uid) {
            if (!empty($uid)) {
                $fetch = "select regId from userdetails where user_id='$uid'";

                $res = mysql_query($fetch);

                while ($data = mysql_fetch_array($res)) {
                    $rid = $data[0];
                    array_push($regid, $rid);
                    $_SESSION['reg'] = $regid;
                }
            }
        }
    }


    if (isset($_POST['send']) && !empty($_REQUEST['select'])) {

        $app_section = 3;
        if(isset($_POST['app_section']) && $_POST['app_section']!=""){
            $app_section = $_POST['app_section'];
        }

        $skip_gender_check = false;
        if(isset($_POST['skip_gender_check']) && $_POST['skip_gender_check']=="Skip"){
            $skip_gender_check = true;
        }


        $ids = $_SESSION['style_request_ids'];
        unset($_SESSION['style_request_ids']);

        $id = $_SESSION["users"];
        foreach ($id as $uid1) {
            $stylishname = "select stylists.name from stylists join userdetails on stylists.stylish_id=userdetails.stylish_id where user_id='$uid1'";
            $res13 = mysql_query($stylishname);
            while ($a = mysql_fetch_array($res13)) {
                $stylish = $a[0];
            }
            $fetch = "select regId from userdetails where user_id='$uid1'";
            $res = mysql_query($fetch);
            $regid = array();
            while ($data = mysql_fetch_array($res)) {
                $regid = $data[0];
            }

            $gendercheck = "select gender from userdetails where user_id='$uid1'";
            $res11 = mysql_query($gendercheck);
            while ($data11 = mysql_fetch_array($res11)) {
                $ug = $data11[0];
                $ug = strtolower($ug);
            }

            $looks = $_REQUEST['select'];
            $r = 0;
            foreach ($looks as $value) {

                $lookgender = "select g.name from looks l inner join lu_gender g on l.gender_id = g.id where l.id='$value'";
                $res12 = mysql_query($lookgender);
                while ($data12 = mysql_fetch_array($res12)) {
                    $lg = $data12[0];
                    $lg = strtolower($lg);
                }
                if ($ug == $lg || $skip_gender_check) {
                    if ($r == 0) {
                        $sql = "select image from looks where id='$value'";
                        $res = mysql_query($sql);
                        $data = mysql_fetch_array($res);
                        $firstlook = $data[0];
                        $r++;
                    }
                    $insert = "INSERT INTO recommendations(user_id, entity_id, entity_type_id, recommendation_type_id, created_at, created_by)
                           VALUES ('$uid1','$value', '2', '1', date('Y-m-d H:i:s'), {$_SESSION['isu_user_id']})";
                    $res = mysql_query($insert);
                    $recommendation_id = mysql_insert_id();

                    if ($ids[0]) {
                        $insert = "INSERT INTO style_requests_recommendations(style_request_id, recommendation_id)
                               VALUES ('{$ids[0]}','$recommendation_id')";
                        $res = mysql_query($insert);
                    }

                } else {

                    $lookerror = "look ID No. " . $value . " having gender " . $lg . " whereas user have gender " . $ug;
                    $lookerror = preg_replace("/\r?\n/", "\\n", addslashes($lookerror));
                    echo "<script type='text/javascript'>alert(\"$lookerror\")</script>";
                }

            }
            include_once 'push.php';
            $push = new pushmessage();
            $params = array(
                "pushtype" => "android",
                "registration_id" => $regid,
                "message" => $stylish . " has sent you looks",
                "message_summery" => $stylish . " has sent you looks",
                "look_url" => $firstlook,
                'app_section' => $app_section
            );

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
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-55586762-1', 'auto');
    ga('send', 'pageview');

</script>

</body>
</html>