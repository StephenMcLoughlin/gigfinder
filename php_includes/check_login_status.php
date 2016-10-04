<?php
	session_start();
	include_once("db_conx.php");
	
	$user_ok = false;
	$log_id = "";
	$log_username = "";
	$log_password = "";
	
	//User Verify function
	function evalLoggedUser($id, $u, $p) {
		$sql = "SELECT ip FROM users WHERE id='$id' AND username='$u' AND password='$p' AND activated='1' LIMIT 1";
		$query = mysql_query($sql);
		$numrows = mysql_num_rows($query);
		if($numrows > 0) {
			return true;
		}
	}
	
	if(isset($_SESSION["userid"]) && isset($_SESSION["username"]) && isset($_SESSION["password"])) {
	
		$log_id = preg_replace('#[^0-9]#', '', $_SESSION['userid']);
		$log_username = preg_replace('#[^a-z0-9]#i', '', $_SESSION['username']);
		$log_password = preg_replace('#[^a-z0-9]#i', '', $_SESSION['password']);
		
		//Verify the user
		$user_ok = evalLoggedUser($log_id,$log_username,$log_password);
	} else if(isset($_COOKIE["userid"]) && isset($_COOKIE["username"]) && isset($_COOKIE["password"])) {
		$_SESSION['userid'] = preg_replace('#[^0-9]#', '', $_COOKIE['id']);
		$_SESSION['username'] = preg_replace('#[^a-z0-9]#', '', $_COOKIE['user']);
		$_SESSION['password'] = preg_replace('#[^a-z0-9]#', '', $_COOKIE['pass']);
		$log_id = $_SESSION['userid'];
		$log_username = $_SESSION['username'];
		$log_password = $_SESSION['password'];
		
		//Verify the user
		$user_ok = evalLoggedUser($log_id,$log_username,$log_password);
		if($user_ok == true) {
			//Update last login field
			$sql = "UPDATE users SET lastlogin=now() WHERE id='$log_id' LIMIT 1";
			$query = mysql_query($sql);
		}
	}
?>