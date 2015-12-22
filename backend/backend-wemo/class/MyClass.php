<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
class MyClass
{
	public $hostname;
	public $username;
	public $password;
	public $db;
	
	
	public function MyCLass()
	{
		$this->getLocalConnection();
	}
	
	private function getLocalConnection()
	{
		$this->hostname	=	'localhost';
		$this->username	=	'root';
		$this->password	=	'mysqlpass';
		$this->db		=	'istylrwd_istyleyou';
		
		mysql_connect($this->hostname,$this->username,$this->password) or die(mysql_error());
		mysql_select_db($this->db) or die(mysql_error());
	}
	
	private function getLiveConnection()
	{
		$this->hostname	=	'localhost';
		$this->username	=	'iro2015';
		$this->password	=	'All@123';
		$this->db		=	'indianro_iro2015';
		
		mysql_connect($this->hostname,$this->username,$this->password) or die(mysql_error());
		mysql_select_db($this->db) or die(mysql_error());
	}
	
}
?>