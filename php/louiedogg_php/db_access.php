<?php

class dogg_db extends louie
{
	protected 	$host;
	protected	$user;
	protected	$userPassword;
	protected	$database;
	
	public function db_open($db_type) 
	{
		if($db_type == "mysql")
		{		
		//	$dbc_file = (WP_CONTENT_DIR . '/plugins/success/lib/dbConnect.php');
		//	$dbc_getData = file_get_contents($dbc_file);
		//	$dbcInfo = unserialize($dbc_getData);
		//	extract($dbcInfo);
			
			$this->host = 'host';						// = $dbcInfo['host'];
			$this->user = 'username';					// = $dbcInfo['user'];
			$this->userPassword = 'password';			// = $dbcInfo['userPassword'];
			$this->database = 'database';			// = $dbcInfo['database'];
							
		}
				
	}
	
	public function db_close($db_type) 
	{
		if($db_type == "mysql")
		{
			if($this->con)
			{
			mysqli_close($dbc);
			}
		}
	}
		
	
}
	       

?>
