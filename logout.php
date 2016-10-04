<?php
	session_start();
	
	//Set session data to an empty array
	$_SESSION = array();
	
	//Expire their cookie filesize
	if(isset($_COOKIE['id']) && isset($_COOKIE['user']) && isset($_COOKIE['pass'])) {
		setcookie("id", '', strtotime('-5 days'), '/');
		setcookie("user", '', strtotime('-5 days'), '/');
		setcookie("pass", '', strtotime('-5 days'), '/');
	}
	
	//Destroy session variables
	session_destroy();
	
	//Double check
	if(isset($_SESSION['username'])) {
		header("location: message.php?msg=Error:_Logout_Failed");
	} else {
		/*header("location: http://www.gigfinder.net23.net");*/
		header("location: index.php");
		exit();
	}
?>