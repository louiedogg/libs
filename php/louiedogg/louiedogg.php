<?php
/*
 * louiedogg.php
 * 
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
 * 
 * Standard format for variables is tableName_columnName
 * 
 * camelHump_camelHump
 * 
 * Louiedogg stores all sql database files like this as well
 * 
 * This is a library to create and handle HTML forms very quickly
 * 
 * 
 */
 
 // Louiedogg HTML form builder and handler
 		
	class  louie
	{

		private $web_root;										// This is used to establish the root of the public folder
		private $sql_access_file = "/test/lib/db_access.php";	// Path to db file from web root
		public 	$dbc = "wet pussy";											// Sql Database Connection	
		public 	$sql_type = "mysql";

		
		public function __construct()
		{	
	
		}
	
		public function __destruct()
		{	
	
		}
		
		public function web_root($directory) 
		{
			$this->web_root = $_SERVER['DOCUMENT_ROOT'].$directory;	
			return $this->web_root;	
		}
						
		public function db_access($db_type, $openORclose)
		{
			require_once(louie::web_root($this->sql_access_file));
			$dogg_db = new dogg_db;
				
			if($openORclose == TRUE)
			{								

				$dogg_db->db_open($db_type);
				$con = mysqli_connect($dogg_db->host, $dogg_db->user, $dogg_db->userPassword, $dogg_db->database);
				
				if(mysqli_connect_errno())
				{
					echo "<p>mySql error:".mysqli_connect_error()."</p>";
					print "Fuck shit";

				}
				else
				{
					$this->dbc = $con;
				}					
			}
			if($onORoff == FALSE)
			{
				$dogg_db->db_close($db_type);
				
			}
		}
			
	} 
		
	class  dogg_forms extends louie
	{
						
		public $HTML_end = "</form>";
		
		public function HTML_start($method, $action)
		{
			echo "<form method='{$method}' action='{$action}' >";
		}
		
		public function HTML_start_with_upload($method, $action)
		{
			echo "<form enctype='multipart/form-data' method='{$method}' action='{$action}' >";
		}
		
		public function HTML_submit($id, $submit_value) 
		{
			echo "<br><input type='submit' name='{$id}_submit' id='{$id}_submit' class='{$id}' value='{$submit_value}'/>";
		}
		
		public function HTML_form($HTML_form_type, $label, $id, $value, $arr=null, $table=null) 
		{
				
			if ($HTML_form_type == "text")
			{
				echo "<div class='form_label_{$label}'>
						<p>{$label}</p>
					</div>
					<div class='form_input_{$id}'>
							<input type='text' name='{$id}' id='{$id}' class='{$id}' value='{$value}'/>
					</div>";
			}				
			
			if ($HTML_form_type == "select")
			{
				echo "<div class='form_label_{$id}'>
							<p>{$label}</p>
						</div>
						<div class='form_input_{$id}'>
								<select name='{$id}' id='{$id}' class='{$id}' >";
									
									foreach($arr as $val)
									{						
										echo '<option value="'.$val.'">'.$val.'</option>';
									}
							echo "</select>
					</div>";					
			}
			
			if ($HTML_form_type == "sql_select")
			{			
				louie::db_access($this->sql_type, TRUE);
				
				$arr_count = count($arr);
											
				echo "<div class='form_label_{$id}'>
							<p>{$label}</p>
						</div>";
																
				echo "<div class='form_input_{$id}'>
						<select name='{$id}'>
							<option></option>";		
														
				if ($arr_count < 1) // You have to declare an empty array or you will get an error!
				{						
						$query = mysqli_query($this->dbc, "SELECT ".$table."_seq FROM {$table}");
												
									if (!$query) 
									{
										die('<p>Query to show fields from table failed --- '.mysqli_error($dbc).' Query was: '.$query.'</p>');
									} 
												
									while ($result = mysqli_fetch_assoc($query)) 
									{

										foreach($result as $row=>$cell)
										{													
											echo '<option value="'.$cell.'">'.$cell.'</option>'; 
										}							
									}
				
				}
				
				if ($arr_count >= 1)
				{
					
					$string = rtrim(implode(',', $arr), ',');	
					
									
					$query  = "SELECT ".$table."_seq, {$string} FROM {$table}";
					$selectQ = mysqli_query($this->dbc, $query);
												
					if (!$selectQ) 
					{
						die('<p>Query to show fields from table failed --- '.mysqli_error($dbc).' Query was: '.$query.'</p>');
					} 
								
					while ($row = mysqli_fetch_row($selectQ)) 
					{					
						$seq = array_shift($row);				
						echo '<option value="'.$seq.'">';
						
						foreach($row as $cell)
						{										
							echo $cell.' ';
						}	
						
						echo '</option>';					
					}			
				} 
				
				echo '</select>
						</div>';
		
				louie::db_access($this->sql_type, FALSE);
					
			}	
		
		
		}
		
		public function HTML_form_sql_text($label, $id, $table, $row_seq) 
		{
			$query = "SELECT {$id} FROM {$table} WHERE {$table}_seq={$row_seq}";
						
			louie::db_access($this->sql_type, TRUE);
			
			$action = mysqli_query($this->dbc, $query);
			$row = mysqli_fetch_assoc($action);
			
			louie::db_access($this->sql_type, FALSE);
			
			echo "<div class='form_label_{$label}'>
					<p>{$label}</p>
				</div>
				<div class='form_input_{$id}'>
						<input type='text' name='{$id}' id='{$id}' class='{$id}' value='{$row[$id]}'/>
				</div>";
		}
		public function HTML_form_sql_select($label, $id, $table, $row_seq, $arr)
		{			
			$arr_count = count($arr);
			$string = rtrim(implode(',', $arr), ',');
											
			echo "<div class='form_label_{$id}'>
						<p>{$label}</p>
					</div>";
															
			echo "<div class='form_input_{$id}'>
					<select name='{$id}'>";	
					
			louie::db_access($this->sql_type, TRUE);	

				$query_yes = mysqli_query($this->dbc, "SELECT {$table}_seq, {$string} FROM {$table} WHERE {$table}_seq ={$row_seq}");
				
				$query_no = mysqli_query($this->dbc, "SELECT {$table}_seq, {$string} FROM {$table} WHERE {$table}_seq !={$row_seq}");

				while ($yes= mysqli_fetch_assoc($query_yes)) {
	
					$yes_seq = array_shift($yes);
					echo '<option value="'.$yes_seq.'">';
							
						foreach($yes as $yes_value)
						{
							echo $yes_value.' ';	
						}
					echo '</option>';

				}

				while ($no = mysqli_fetch_assoc($query_no)) {
		
					$no_seq = array_shift($no);
					echo  '<option value="'.$no_seq.'">';
					
						foreach($no as $no_value)
						{
							echo $no_value.' ';
						} 
					echo '</option>';

				 }
			louie::db_access($this->sql_type, FALSE);

			echo '</select>
					</div>';
					
		}
		
		public function HTML_file_upload() 
		{		
			echo "<div class='form_label_{$id}'>
						<p>{$label}</p>
					</div>";
															
			echo "<div class='form_input_{$id}'>
					<select name='{$id}'>";	
			
			echo '<input size="50" type="file" name="filename"><br>';
		}
		
			
					
	} // End Class		

	class dogg_handle extends louie
	{		
		public $handled = array(); 		// array to store the column names or array keys and input values
		public $validated_email;
		
		public function HTML_form_validate($validate_type, $validate_arr) 		
		{
				
				foreach ($validate_arr as $text_key => $text_value)
				{
					// Empty Validation
					if($validate_type == "empty")
					{

						if (empty($_POST["{$text_value}"]) ) 
						{
							echo "<h5 style='color:red;'>Please enter an ".$_POST["{$text_value}"]."</h5>";
						} 
					
					}
					// Email Validation
					if($validate_type == "email")
					{						
						if (!filter_var($_POST["{$text_value}"], FILTER_VALIDATE_EMAIL)) 
						{
							echo "<h5 style='color:red;'>".$_POST["{$text_value}"]." is not a valid email address.</h5>";
						}
						else
						{
							$this->validated_email = $_POST["{$text_value}"];
							return $this->validated_email;
						}
						
					
					}																												
				}
			//}
		} 
		public function HTML_form_handle($fileORtable, $handle_type, $handle_arr)
		{			
					
			foreach ($handle_arr as $handled_value)
			{
				$val = trim(strip_tags($_POST["{$handled_value}"]));				
				$handled["{$handled_value}"] = $val;
			}
					
			$handled_keys = array_keys($handled);
			$handled_values = array_values($handled);
			
			$write = new dogg_write;
			
			if($handle_type == "sql")
			{										
				$write->sql_insert($fileORtable, $handled_keys, $handled_values); 		// $table, $key_arry, $value_array
			}
					
			if($handle_type == "csv")
			{
				$write->csv_write($fileORtable, $handled_keys, $handled_values); 		// $file, $key_arr, $val_arr				
			}		
		
			extract($handled);
		
		}
		public function HTML_form_handle_sql_edit($fileORtable, $handle_arr, $row_seq) 
		{
			foreach ($handle_arr as $handled_value)
			{
				$val = trim(strip_tags($_POST["{$handled_value}"]));				
				$handled["{$handled_value}"] = $val;
			}
			
			$update_array = array();	
			$i = 0;
					
			foreach ($handled as $key => $value)
			{
				$update_array[$i] = "$key='$value'";
				$i++;
			}
			
			$string = rtrim(implode(', ', $update_array), ',');
			
			$write = new dogg_write;
			$write->sql_edit($fileORtable, $string, $row_seq);
						
		}
		public function confirm_sql_delete($table, $id_column, $row_seq) 
		{
			$query = "SELECT {$id_column} FROM {$table} WHERE {$table}_seq={$row_seq}";
			$action = mysqli_query($this->dbc, $confirm);
			
			if (!$action) 
			{
				die('Query to show fields from table failed --- '.mysql_error($dbc).' Query was: '.$subCatAcctQuery);
			} 

			$result = mysql_fetch_array($action);

			print "Are you SURE you want to delete {$action[0]}?<br>";
			
			$form = new dogg_forms;
			
			$form->HTML_start("POST", "");
			$form->HTML_submit("delete_cancel", "Cancel");
			$form->HTML_submit("delete_yes", "Delete {$action[0]}?");
			$form->HTML_end;
			
		}
		
		public function HTML_sql_csv_import($table, $arr) 		
		{
			$handle = fopen($_FILES['filename']['tmp_name'], "r");
		 
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
			{
				
				$import="INSERT into {$table} ({$table}_dateTime, {$string} VALUES('now()'";
					
				foreach($data as $value)		
				{
					$import .=", '$value'";
				}									
				
				mysqli_query($this->dbc, $import) or die(mysqli_error($this->dbc));
			}
		 
			fclose($handle);
		}
		
		
		// Required array format: array("test_seq" => "KEY", "test_dateTime" => "TIMESTAMP", "test_name" => "CHAR", "test_email" => "CHAR", "select_seq" =>"DOLLAR");
		
		public function create_table($table, $arr) 
		{											
			$i = 0;
		
			foreach ($arr as $key => $value)
			{
				if($value == "INT")
				{
					$handled[$i] = "$key INT";
				}
				if($value == "CHAR")
				{
					$handled[$i] = "$key CHAR (255)";
				}
				if($value == "KEY")
				{
					$handled[$i] = "$key INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY";
				}
				if($value == "TIMESTAMP")
				{
					$handled[$i] = "$key DATETIME NOT NULL";
				}
				if($value == "DOLLAR")
				{
					$handled[$i] = "$key FLOAT (15,2)";
				}
				$i++;
			}
		
			$string = rtrim(implode(', ', $handled), ',');			
			$sql_query = "CREATE TABLE {$table}({$string})";
			
			$write = new dogg_write;
			
			$write->sql_create_table($sql_query);
			
		}

	} // End of Class
	
	class dogg_write extends louie
	{
		public function __construct()
		{	

		}
	
		public function __destruct()
		{	
	
		}
		
		public function sql_insert($table, $key_arr, $val_arr)
		{					
			louie::db_access($this->sql_type,TRUE);	
			
			$key_string = rtrim(implode(',', $key_arr), ',');
			$value_string = rtrim(implode(',', $val_arr), ',');
			
			$insertQ = "INSERT INTO {$table} ({$table}_dateTime, {$key_string}) VALUES(now(),{$value_string})"; 
			if ((@mysqli_query($insertQ, $dbc)) == TRUE)
			{
			}
			else
			{
				print '<p style="color: red;">Could not add the entry because:<br />' . mysqli_error($dbc) . '.</p><p>The query being run was: ' . $insertQ . '</p>';
			}
			
			louie::db_access($this->sql_type,FALSE);	
		}
		public function sql_edit($table, $string, $row_seq)
		{						
			$update = "UPDATE {$table} SET {$string} WHERE {$table}_seq={$row_seq}"; 
			
			louie::db_access($this->sql_type,TRUE);
			
			if ((@mysqli_query($this->dbc, $update)) == TRUE)
			{

			}
			else
			{
				print '<p style="color: red;">Could not add the entry because:<br />' . mysqli_error($dbc) . '.</p><p>The query being run was: ' . $update . '</p>';
			}
			
			louie::db_access($this->sql_type,FALSE);	
			
		}
		
		public function sql_create_table($sql_query) 
		{
			
			louie::db_access($this->sql_type,TRUE);	
					
			$action = mysqli_query($this->dbc, $sql_query);
			
			if (!$action) 
			{
				die('<p>Query to show fields from table failed --- '.mysqli_error($this->dbc).' Query was: '.$sql_query.'</p>');
			} 
			else
			{
				print "<p>Table was added successfully</p>";
			}
			
			louie::db_access($this->sql_type,FALSE);	
		}
		public function sql_delete_row($table, $row_seq) 
		{
			$delete = "DELETE FROM {$table} WHERE {$table}_seq={$row_seq}";
			
			louie::db_access($this->sql_type,TRUE);	
			
			if(!@mysqli_query($this->dbc, $delete))
			{
				die('<p>Query to show fields from table failed --- '.mysqli_error($this->dbc).' Query was: '.$delete.'</p>');
			}
			else
			{
				print "<p>Row Deleted</p>";
			}
			louie::db_access($this->sql_type,FALSE);
		}
		
		
		public function csv_write($file, $key_arr, $val_arr) 
		{				
								
			$filename = strstr( $file, '.', true);
						
			$seq_name = $filename."_seq";
			$time_name = $filename."_dateTime";
						
			array_unshift($key_arr, "$seq_name", "$time_name");
			
			$key_string = rtrim(implode(',', $key_arr), ',');
			$time = time();
						
			if (file_exists($file))
			{
				$fp = fopen($file, "r");
				
				$rows = file($file);
				$last_row = array_pop($rows);
				$data = str_getcsv($last_row);
				$id = $data[0];
				$id++;

				fclose($fp);
									
				array_unshift($val_arr, "$id", "$time");	
				
				$value_string = rtrim(implode(',', $val_arr), ',');
							
				$value_string = "$value_string\n";
				
				$fp = fopen($file, 'a');
				$write = fwrite($fp, $value_string);
				
				if ($write)
				{
					print '<p>You have written the line to the file line number '.$id.'</p>';
				}
				else
				{
					print '<p>You have a problem with writing the file</p>';
				}
				fclose($fp);
						
			}		
			else
			{
				//mkdir("$_SERVER['DOCUMENT_ROOT']"."/test/db/", 0755, true);
				$key_string = "$key_string\n";
				
				$seq=1000;
				++$seq;
				array_unshift($val_arr, "$seq", "$time");	
				
				$value_string = rtrim(implode(',', $val_arr), ',');
				
				$value_string ="$value_string\n";
				
				$fp = fopen($file, 'a');
				fwrite($fp, $key_string);
				fwrite($fp, $value_string);
				fclose($fp);
				print '<p>You have created a new file and written two lines to it';
			}
			
						
		}
			
	}
		
	class  dogg_display extends louie
	{

		public function __construct()
		{	
			louie::db_access($this->sql_type,TRUE);	

		}
			
		public function __destruct()
		{	
			louie::db_access($this->sql_type,FALSE);
		}
		
		public function sql_search_display($display_name, $search_name, $select_statement, $search_types ) 
		{
			$dogg_forms = new dogg_forms;
			
			$dogg_forms->HTML_start("POST", "");
			$dogg_forms->HTML_form("select", $display_name, $display_name, "", $search_types);
			$dogg_forms->HTML_form("text", $search_name, $search_name, "");
			$dogg_forms->HTML_submit("search","Search");
			$dogg_forms->HTML_end;
			
			$selectQ = mysqli_query($this->dbc, $select_statement); 
														
			if (!$selectQ) {
				die('Query to show fields from table failed --- '.mysqli_error($dbc));
			} 
			
			echo "<table border='1'><tr>";
				
				echo "<tr>";
				
				foreach($search_types as $hcell)
				{
					echo "<th>{$hcell}</th>";
				}
				
				echo"<th>Edit</th>";
				echo"<th>Delete</th>";
				echo "</tr>";
							
				if (!($selectQ) == NULL) {
					
					while($row = mysqli_fetch_assoc($selectQ))
					{
						echo "<tr>";
							foreach($row as $dcell)
   							{
								echo "<td>{$dcell}</td>";
							}
								
							echo '<td>';
								$dogg_forms->HTML_start("POST", "");
								$dogg_forms->HTML_submit("edit", "Edit");
								$dogg_forms->HTML_end;
							echo '</td>';
							echo '<td>';
								$dogg_forms->HTML_start("POST", "");
								$dogg_forms->HTML_submit("delete", "Delete");						
								$dogg_forms->HTML_end;
								
							echo '</td>';
							
						echo "</tr>\n";
		
					}


					mysqli_free_result($selectQ);	
				}

				echo "</table>";
			
		}
		
		public function display_csv() 
		{
			
		}
		
		
	}// End of Class
	
	class dogg_email extends louie
	{

		private $phpMailer = "/test/lib/PHPMailer/class.phpmailer.php";	// Path to PHPmailer
		private $mail_return = "<p>Mail has been sent! Thank you.</p>"; 
		
		public function __construct()
		{	
	
		}
	
		public function __destruct()
		{	
	
		}
		public function send_email($to, $from, $fromName, $subject, $body, $attachments=FALSE) 
		{

			$to_count = count($to);
			$attachment_count = count($attachments);
						
			require_once(dogg_HTML_form::web_root($this->phpMailer));			
			$mail = new PHPMailer();
						
			if ($to_count == 0)
			{
				echo "<p>Please supply a To: address</p>";
			}
			else if ($to_count == 1)
			{
				$mail->AddAddress($to);
			}
			else 
			{
				foreach($to as $address)
				{
					$mail->AddAddress($address);
				}
			}
			
			$mail->SetFrom($from, $FromName);
			$mail->FromName = $FromName;
			$mail->Subject = $subject;  // Subject is a property
			$mail->Body	= $body;
				
			if(!$attachments)
			{
				//Do nothing
			}
			else
			{			
				if($attachment_count == 0)
				{
				// Do nothing
				}
				else if($attachment_count == 1)
				{
					$mail->AddAttachment($attachments);
				}
				else
				{
					foreach($attachments as $attachment)
					{
						$mail->AddAttachment($attachment);
					}

				}
			}
			
			if(!$mail->Send())
			{
				echo"<p>Mail Error: ".$mail->ErrorInfo."</p>";
			}
			else
			{
				return $this->mail_return;
			}
		}
	}

	class dogg_cron extends louie
	{
		public function __construct()
		{	
	
		}
	
		public function __destruct()
		{	
	
		}
	} 
	
	
	class dogg_system extends louie 
	{
		public function __construct()
		{	
	
		}
	
		public function __destruct()
		{	
	
		}
		public function dogg_cgi($script) 
		{
			$script = $_SERVER['DOCUMENT_ROOT']."/cgi-bin/".$script;
			
			system($script);
			
		}
		
		
		
	} 
	
	 
/*	
* 
* 
* 	
* 
* 
* 
*/
 
 

?>
