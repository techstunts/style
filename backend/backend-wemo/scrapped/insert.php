<?php
	include 'databaseconnect.php';

$body_type = $_POST['bodytype'];
$budget = $_POST['budget'];
$age = $_POST['age'];

$occasion = $_POST['occasion'];
$gender = $_POST['gender'];
$lookname = $_POST['look_name'];
$lookdescription = $_POST['look_description'];
$stylishid = $_POST['stylish_id'];

$productname1 = $_POST['productname1'];
$producttype1 = $_POST['producttype1'];
$productprice1 = $_POST['productprice1'];
$productlink1 = $_POST['productlink1'];
 $image1=$_FILES["image1"]["name"];
    $filename4=uniqid().time().$image1;
	    $temp_name=$_FILES["image1"]["tmp_name"];
		
	    $imgtype=$_FILES["image1"]["type"];
	    $target_path = 'uploadfile/';
		if(move_uploaded_file($temp_name, $target_path.$filename4)){
		//echo "Move Sucessfully image1";
		}
	    else  {
		echo "error";
		}



$productname2 = $_POST['productname2'];
$producttype2 = $_POST['producttype2'];
$productprice2 = $_POST['productprice2'];
$productlink2 = $_POST['productlink2'];
 $image2=$_FILES["image2"]["name"];
    $filename1=uniqid().time().'234'.$image2;
	    $temp_name=$_FILES["image2"]["tmp_name"];
		
	    $imgtype=$_FILES["image2"]["type"];
	    $target_path = 'uploadfile/';
		if(move_uploaded_file($temp_name, $target_path.$filename1)){
		//echo "Move Sucessfully image2";
		}
	    else  {
		echo "error";
		}
$productname3 = $_POST['productname3'];
$producttype3 = $_POST['producttype3'];
$productprice3 = $_POST['productprice3'];
$productlink3 = $_POST['productlink3'];
 $image3=$_FILES["image3"]["name"];
     $filename2=uniqid().time().'23fef4'.$image3;
	    $temp_name=$_FILES["image3"]["tmp_name"];
		
	    $imgtype=$_FILES["image3"]["type"];
	    $target_path = 'uploadfile/';
		if(move_uploaded_file($temp_name, $target_path.$filename2)){
		//echo "Move Sucessfully image3";
		}
	    else  {
		echo "error";
		}

$productname4 = $_POST['productname4'];
$producttype4 = $_POST['producttype4'];
$productprice4 = $_POST['productprice4'];
$productlink4 = $_POST['productlink4'];
 $image4=$_FILES["image4"]["name"];
    $filename3=uniqid().time().'2fgdf4'.$image4;
	    $temp_name=$_FILES["image4"]["tmp_name"];
		
	    $imgtype=$_FILES["image4"]["type"];
	    $target_path = 'uploadfile/';
		if(move_uploaded_file($temp_name, $target_path.$filename3)){
		//echo "Move Sucessfully image4";
		}
	    else  {
		echo "error";
		}


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

//echo "<img src=getimage.php?id=$lastid>";
/*
for($i=1;$i<5;$i++)
{
$productid.$i=$productid1+$i;

 //$sql="SELECT * FROM products where id=$lastid+$i";
//$result=mysql_query($sql);
 // $result = mysql_fetch_array($result);
  //header("Content-type: image/jpg");
 //echo  "<img src='".$result['upload_image']."'/>"; 
}*/

require 'imageex.php';
header('location:look_list.php');
?>