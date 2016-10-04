
<?php
	session_start();
	//If user is already logged in
	if(isset($_SESSION['username'])) {
		header("location: user.php?u=".$SESSION['username']);
		exit();
	}
?>
<?php
	//AJAX calls this login code to execute
	if(isset($_POST['e'])) {

		//Connect to database
		include_once("php_includes/db_conx.php");
		
		//Gather posted data into local variables and sanitise
		$e = mysql_real_escape_string($_POST['e']);
		$p = md5($_POST['p']);
		
		//Get user ip Address
		$ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
		
		//Form data error handling
		if($e == "" || $p == "") {
			echo "login_failed";
			exit();
		} else {
			$sql = "SELECT id, username, password FROM users WHERE email='$e' AND activated='1' LIMIT 1";
			$query = mysql_query($sql);
			$row = mysql_fetch_row($query);
			$db_id = $row[0];
			$db_username = $row[1];
			$db_pass_str = $row[2];
			
			if($p != $db_pass_str) {
				echo "login_failed";
				exit();
			} else {
			
				//Create their sessions and cookies
				$_SESSION['userid'] = $db_id;
				$_SESSION['username'] = $db_username;
				$_SESSION['password'] = $db_pass_str;
				
				setcookie("id", $db_id, strtotime( '+30 days' ), "/","","", TRUE);
				setcookie("user", $db_username, strtotime( '+30 days'), "/","","", TRUE);
				setcookie("pass", $db_pass_str, strtotime( '+30 days'), "/","","", TRUE);
				
				//Update their IP and Last login fields
				$sql = "UPDATE users SET ip='$ip', lastlogin=now() WHERE username='$db_username' LIMIT 1";
				$query = mysql_query($sql);
				echo $db_username;
				exit();
			}
		}
		exit();
		
	}
?>
<html>
	<head>	
		<title>Login</title>
		<link rel="stylesheet" type="text/css" href="css/styles.css" />
		<script src="js/main.js"></script>
		<script src="js/ajax.js"></script>
		<script src="js/form.js"></script>
		<script src="js/user.js"></script>
		
	</head>
	<body>
		<?php include_once("header.php")?>
		<div id="content">
			<form name="loginform" id="loginform" onsubmit="return false;">
				<input id="email" type="text" placeholder="Email Address" onfocus="emptyElement('status')" maxlength="100">
				<input id="password" type="password" placeholder="Password" onfocus="emptyElement('status')" maxlength="100">
				<button id="loginbtn" onclick="login()">Log In</button>
				<p id="status"></p>
			</form>
		</div>
	</body>
</html>