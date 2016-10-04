<?php
	if(isset($_GET['id']) && isset($_GET['u']) && isset($_GET['e']) && isset($_GET['p'])) {
	
		//Connect to the database and sanitise the $_GET variables
		include_once("php_includes/db_conx.php");
		$id = preg_replace('#[^0-9]#i', '', $_GET['id']);
		$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
		$e = mysql_real_escape_string($_GET['e']);
		$p = mysql_real_escape_string($_GET['p']);
		
		
		//Evaluate the lengths of incoming $GET variables
		if(strlen($u) < 3 || strlen($e) < 5 || $p == "") {
			header("location: message.php?msg=activation_string_length_issues");
			exit();
		}
		
		//Check their credentials against the database
		$sql = "SELECT * FROM users WHERE id='$id' AND username='$u' AND email='$e' AND password='$p' LIMIT 1";
		$query = mysql_query($sql);
		$numrows = mysql_num_rows($query);
		
		//Evaluate for a match in the system (0 = no match, 1 = match)
		if($numrows == 0) {
			header("location: message.php?msg=Your_credentials_are_not_matching_anything_on_our_system");
			exit();
		}
		
		//Match was found, can activate
		$sql = "UPDATE users SET activated='1' WHERE id='$id' LIMIT 1";
		$query = mysql_query($sql);
		
		//Double check to see if activated in fact is now = 1
		$sql = "SELECT * FROM users WHERE id='$id' AND activated='1' LIMIT 1";
		$query = mysql_query($sql);
		$numrows = mysql_num_rows($query);
		
		//Evaluate double check
		if($numrows == 0) {
			header("location: message.php?msg=activation_failure");
			exit();
		} else if($numrows == 1) {
			header("location: message.php?msg=activation_success");
			exit();
		}
	
	} else {
		header("location message.php?msg=Missing $_GET_variables");
		exit();
	}
?>