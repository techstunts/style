<?php
require "databaseconnect.php";
if(isset($_POST['signup']) && $_POST['signup']!=''){
	$name = @$_POST['name'];
	$email = @$_POST['email'];
	$password = @$_POST['password'];
	$profile = @$_POST['profile'];
	$expertise = @$_POST['expertise'];
	$age = @$_POST['age'];
	$code = @$_POST['code'];
	$gender = @$_POST['gender'];
	$description = @$_POST['description'];
	$imageName = @$_FILES["image"]["name"];
	$temp_name = @$_FILES["image"]["tmp_name"];
	$imgtype = @$_FILES["image"]["type"];
	$target_path = 'stylish/';
	if(!empty($imageName)){
		move_uploaded_file($temp_name,'stylish/'.$imageName);
	}

	$cost = 10;
	$hash = password_hash($password, 1, ['cost' => $cost]);

	$sql = "INSERT INTO stylists (stylish_name, email, password,stylish_image, profile,stylish_code,expertise,stylish_age,stylish_gender,stylish_description) VALUES ('$name','$email','$hash','stylish/$imageName','$profile','$code','$expertise','$age','$gender','$description')";
	$result = mysql_query($sql);
	if($result)	{
	   header('Location:create_stylist.php');
	} 
   
}
?>