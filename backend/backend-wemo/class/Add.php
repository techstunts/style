<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
class Add
{
	function registrationAdd() 	{
		
		if($_POST['email']) {
			$fullname	=	$_POST['fullname'];
			$email		=	$_POST['email'];
			$password	=	$_POST['password'];
			$city		=	$_POST['city'];
			$pincode	=	$_POST['pincode'];
			$phone		=	$_POST['phone'];
			$state		=	$_POST['state'];
			$address	=	$_POST['address'];
			$country	=	$_POST['country'];
			$user_type	=	$_POST['user_type'];
			//$activation_code	=	 generateRandomString();
			//echo $activation_code.'as';
			$length = 20;			
			$randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
			$reg_id		=	substr(str_shuffle("0123456789"), 0, 5)	;
			
			$insertQuery  	=	"INSERT INTO `login` SET  
			`reg_id`	=	'$reg_id', 
			`email`		=	'$email', 
			`user_type` =   '$user_type',
			`pass`		=	'$password', 
			`name`		=	'$fullname', 
			`contactno` =	'$phone', 
			`address`	=	'$address', 
			`city`		=	'$city', 
			`pin`		=	'$pincode', 
			`state`		=	'$state', 
			`country`	=	'$country', 
			`activation`=	'$randomString', 
			`reg_date`	=	now(), 
			`status`	=	'0', 
			`level`		=	'1', 
			`view`		=	'0'
			";
			$executeQuery	=	mysql_query($insertQuery);
			if($executeQuery){
				
				$email_to=$email;
				$email_subject="Your Account Ready With IRO 2015";
				$email_message="http://indianrobotolympiad.org/activation_account.php?activation_code=".$randomString;
				$headers = "From: IRO 2015 Registration Activation <info@indiastemfoundation.org> \r\n".
				"Reply-To: info@indiastemfoundation.org \r\n'" .
				"X-Mailer: PHP/" . phpversion();
				 mail($email_to, $email_subject, $email_message, $headers);  
				//echo "mail sent!";	

				$msg2 = " This user registered For IRO <br/>
			Name : $fullname <br/>	
			Email : $email  	<br/>		
			City : $city		<br/>
			Pincode : $pincode	<br/>
			Mobile : $phone		<br/>
			State : $state		<br/>
			Address : $address	<br/>
			Copuntry : $country	<br/>
			USer Type : $user_type	<br/>
				";


				$email_admin = "pooja@indiastemfoundation.org";
				$subject = "User Information";
				mail($email_admin, $subject, $msg2, $headers);  
				echo "true";
				}
			else{
				echo "false";
				}	
			
		}
	}
	
	

	
	
}


?>