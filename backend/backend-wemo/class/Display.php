<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
class Display
{
	
############################ 		Login Detail	#######################################	

	public function checkLogin($username , $password)
	{				
			//echo $username.'sds'.$password;
			
			if (get_magic_quotes_gpc()) 
					{
					  $username = stripslashes($username);
					  $password = stripslashes($password);
					}
					$username = mysql_real_escape_string($username);
					$password = mysql_real_escape_string($password);
					//$password = md5($password);
					
					$query 		= 	"SELECT * FROM `stylists`
									WHERE stylish_email = '$username' AND stylish_password = '$password' ";

					$db_query	=	mysql_query($query);
					$num 		= 	mysql_num_rows($db_query);			
					//echo $num.$db_query.$query;
					
					if(@$num>0)					{				

						$data = mysql_fetch_array($db_query);
						$_SESSION['isu_user_id']		=	@$data['stylish_id'];
						$_SESSION['isu_user_name']		=	@$data['stylish_email'];
						
						//header("Location:http://www.google.com");
						$status  = true;
						
					}

					else
					{
						$_SESSION['login_check']				=	"Password doesn't match";
						$status  =  'incorrect';
						//header("Location:login.php");
					}	
		
		echo json_encode($status);
		exit();

	}

		

	

	function checkLogout()

	{
		unset($_SESSION['isu_user_id']);
		unset($_SESSION['isu_user_name']);
		 session_destroy();
		header("Location:index.php");

	}

	function getProfileInformationById($id)

	{
		$query      =   "SELECT * FROM `login` WHERE  login.reg_id = '$id' ";
        $sql        =   mysql_query($query)or die(mysql_error()); 
        $data  		=   @mysql_fetch_array($sql);
        return @$data; 

	}

	
		
	public function MyMail()
	{
		$data	= array();
		
		$data['PasswordResetLink'] 		= 	'Password Details';
		$data['SubjectApplicationForm']	= 	'Application Form received';
		$data['SubjectPassword']	= 	'Password Form received';
		
		$data['sender']					=	"contact@playyoursport.com";
		
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers.= 'From: The Play Your Sport Team <'.$data['sender'].'> '."\n";
		
		$data['headers']				=	$headers;
		return @$data;
	}
   
   
   
   
   public function AddContactUs()
   {
		$name	=	@$_POST['name'];
		$email	=	@$_POST['email'];
		$phone	=	@$_POST['phone'];
		$msg	=	@$_POST['message'];
		
		if(@$_POST['name'])
		{												
			$sql = "INSERT INTO `contactus` SET		 contactus_name					=	'$name',
													 contactus_emailid				=	'$email',
													 contactus_subject				=	'$phone',
													 contactus_message				=	'$msg',
													 contactus_creation_date		=	now()
													";									
			if(mysql_query($sql))
			{
				// Send Mail
				$mail					=	$this->MyMail();
				$SubjectContactUs		=	$mail['SubjectContactUs'];
				$SubjectApplicationForm	=	$mail['SubjectApplicationForm'];
				$headers				=	$mail['headers'];
				
				$info					=	$this->ShowDefaultInformation();
				$adminemail				=	$info['email_contact'];
				$adminemail1			=	$info['email_cc_contact'];
				
				$msg 	 			= 	$this->TemplateForContactUsForm($name,$email,$phone,$msg);
				@mail($adminemail,$SubjectContactUs,$msg,$headers);
				@mail($adminemail1,$SubjectContactUs,$msg,$headers);
				 
				$_SESSION['msg1']	=	"<font color='#006600'><b>Sucessfully Sent, We Will Revert You soon</b></font>";
			}
			else 
			{ 
				$_SESSION['msg1']	=	"<font color='#FF0000'>Error Contact Form Not Submited to us</font> ";
			}
		}
		else 
		{
			$_SESSION['$msg1']	=	"Please Fill All Details";  
		}
		
		header ('Location:contact.php');
	}
	
	

	public function TemplateForPasswordForm($msg)
	{
		$msg = "<table width='611' border='0' cellspacing='0' cellpadding='0'>
				  <tr><td colspan='2'><strong>Dear Administrator,</strong></td></tr>
				  <tr><td width='221'>&nbsp;</td><td width='390'>&nbsp;</td></tr>
				  <tr><td colspan='2'>User has sent you a Contact Us Form</td></tr>
				  <tr><td width='221'>&nbsp;</td><td width='390'>&nbsp;</td></tr>
				  <tr><td width='221'>&nbsp;</td><td width='390'>&nbsp;</td></tr>
				  <tr><td>Message </td><td>$msg</td></tr>
				  <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				  <tr><td colspan='2'>Play Your Sport Team</td></tr>
				</table>";

			return $msg;
	}

	public function TemplateForContactUsForm($name,$email,$phone,$msg)
	{
		$msg = "<table width='611' border='0' cellspacing='0' cellpadding='0'>
				  <tr><td colspan='2'><strong>Dear Administrator,</strong></td></tr>
				  <tr><td width='221'>&nbsp;</td><td width='390'>&nbsp;</td></tr>
				  <tr><td colspan='2'>User has sent you a Contact Us Form</td></tr>
				  <tr><td width='221'>&nbsp;</td><td width='390'>&nbsp;</td></tr>
				  <tr><td width='221'>&nbsp;</td><td width='390'>&nbsp;</td></tr>
				  <tr><td>Name </td><td>$name</td></tr>
				  <tr><td>Email Id </td><td>$email</td></tr>
				  <tr><td>Phone Number </td><td>$phone</td></tr>
				  <tr><td>Message </td><td>$msg</td></tr>
				  <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
				  <tr><td colspan='2'>Play Your Sport Team</td></tr>
				</table>";

			return $msg;
	}


}

?>