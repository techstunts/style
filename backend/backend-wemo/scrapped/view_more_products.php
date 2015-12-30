<?php
include 'databaseconnect.php';
	/* $str = '';
	for($i=0; $i<5; $i++){ 
		$str = $str.'<li style="height:20px; border:1px solid; float:left; width:100%;">Test'. $i.'</li>	';	
	} 
	echo $str; */
	
	//$query = $_POST['serstr'];
  $start=$_POST['start'];
  
 $query=$_POST['qstring']." LIMIT ".$start.",12";

  $res=mysql_query($query);

  //echo json_encode($return);
  $str='';
  while($value=mysql_fetch_array($res))
  {
  $str=$str.
  '<div class="col-sm-6 col-md-3">
          <div class="panel panel-dark panel-stat">
            <div class="panel-heading">
              <div class="stat">
                <div class="row">
                  <div class="col-xs-12">
          
           
           <a href="product_view.php?id='.$value['id'].'"><img class="img-thumbnail" src="'.$value['upload_image'].'"  alt="" /></a>
                
                       
                  </div>
                  
                </div><!-- row -->

                <div class="mb15"></div>
                <div class="row">
                  <div class="col-xs-6">
                    <div class="checkbox block"><label><input type="checkbox"  name="select[]" value="'. $value['id']. '"> Select</label></div>
                  </div>

                 
                </div><!-- row -->

              </div><!-- stat -->

            </div><!-- panel-heading -->
          </div><!-- panel -->
        </div><!-- col-sm-6 -->';
      }
      

  $arr['htm'] =$str;
  $arr['status'] = 'success';
  echo  json_encode($arr);
?> 