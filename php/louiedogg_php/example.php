<?php
/*
 * 
 * Plugin Name: Restaurateur Inc. Menu Profit
 * Plugin URI: http://restaurateurinc.com
 * Description: Restaurant Costing Program
 * Version: 1.0
 * Author: admin@louiedogg.com
 * Author URI: http://louiedogg.com
 * 
 * 
 * Copyright 2015 Louiedogg <admin@louiedogg.com>
 * 
 * @author Louiedogg <admin@louiedogg.com>
 * @copyright Copyright 2015 
 * @license GPLv2
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */
 
 $louiedogg = $_SERVER['DOCUMENT_ROOT']."/test/lib/louiedogg.php";
 $hello = "hello.pl";
 
 include($louiedogg);
 
 	$louie 			= new louie;		//	Holds all properties and basic functions needed
	$dogg_forms 	= new dogg_forms;	//	Creates and supports HTML forms
	$dogg_handle 	= new dogg_handle;	//	Handles forms Infromation and application data
	$dogg_write 	= new dogg_write;	// 	Writes data sql or csv
	$dogg_email 	= new dogg_email;	//	Integrated PHPMailer - Supports Mail, Group Mailing list etc...
	$dogg_display 	= new dogg_display;	//	Searchs and Displays Data Based Search Criteria Types
	$dogg_cron 		= new dogg_cron;	//	Sets and manages immediate, delayed, or reoccuring actions 
	$dogg_system 	= new dogg_system;	//	System commands executed in Perl
 
	$test = array("test_name", "test_email");
	
 if(!$_SERVER['REQUEST_METHOD'] == "POST")
 {
 
	$dogg_system->dogg_cgi($hello);
 
	$dogg_forms->start_HTML("POST", "");
	$dogg_forms->HTML_form_sql_text("Name", "test_name", "test", "1");
	$dogg_forms->HTML_form_sql_text("Email", "test_email", "test", "1");
	
	$dogg_forms->HTML_form_sql_select("DB Name", "db_name", "test", "2" , $test);
	
	$dogg_forms->HTML_submit("end", "GO JOE");
	$dogg_forms->HTML_end;
	
}
else
{
	$dogg_handle->HTML_form_handle_sql_edit("test", $test, "1");
}
	

	
 

?>

