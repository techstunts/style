<?php
session_start();
include('class/MyClass.php');
include('class/Display.php');
include('class/Add.php');
//include('class/Features.php');	

$obj 			= 	new MyClass();
$display 		= 	new Display();
$add			=	new Add();
@$action			=	@$_GET['action'];
@$email_login	=	@$_POST['username'];
@$email_pwd		=	@$_POST['password'];


switch($action)
{
	// For Display //
	case 'register':
	$add->registrationAdd();
	break;
	
	case 'login':
	$display->checkLogin($email_login,$email_pwd);	
	break;
	
	case 'logout':
	$display->checkLogout();	
	break;
	
	
	default :
	header ('Location:error.php');	
	break;
}