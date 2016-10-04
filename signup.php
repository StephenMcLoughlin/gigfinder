<?php 
	session_start();
	//If user is logged in, header them away
	if(isset($_SESSION['username'])) {
		header("location: message.php?msg=No to that Weenis");
		exit();
	}
?>
<?php
	//Ajax call this name check code to execute
	if(isset($_POST['usernamecheck'])) {
		include_once("php_includes/db_conx.php");
		$username = preg_replace('#[^a-z0-9]#i', '', $_POST['usernamecheck']);
		$sql = "SELECT id FROM users WHERE username='$username' LIMIT 1";
		$query = mysql_query($sql);
		$uname_check = mysql_num_rows($query);
		if(strlen($username) < 3 || strlen($username) > 16) {
			echo '<strong style="color:#f00;">3 - 16 characters please</strong>';
			exit();
		}
		if(is_numeric($username[0])) {
			echo '<strong style="color:#f00;">Usernames must begin with a letter</strong>';
			exit();
		}
		if($uname_check < 1) {
			echo '<strong style="color:#009900;">' .$username. ' is ok</strong>';
			exit();
		} else {
			echo '<strong style="color:#009900;">' . $username . ' is taken</strong>';
			exit();
		}
	}
?>

<?php
	 if(isset($_POST["u"])) {
	 
		//Connect to the database
		include_once("php_includes/db_conx.php");
		
		//Gather the posted data into local variables
		$u = preg_replace('#[^a-z0-9]#i', '', $_POST['u']);
		$e = mysql_real_escape_string($_POST['e']);
		$p = $_POST['p'];
		
		//Get user ip Address
		$ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
		//Duplicate data checks for username and email
		$sql = "SELECT id FROM users WHERE username='$u' LIMIT 1";
		$query = mysql_query($sql);
		$u_check = mysql_num_rows($query);
		
		//----------------------------------
		$sql = "SELECT id FROM users WHERE email='$e' LIMIT 1";
		$query = mysql_query($sql);
		$e_check = mysql_num_rows($query);
		
		//Form data handling
		if($u == "" || $e == "" || $p == "") {
			echo "The form submission is missing values";
			exit();
		} else if($u_check > 0) {
			echo "The username you entered is already taken";
			exit();
		} else if($e_check > 0) {
			echo "This email address is already in use in the system";
			exit();
		} else if(strlen($u) < 3 || strlen($u) > 16) {
			echo "Username must be between 3 and 16 characters";
			exit();
		} else if(is_numeric($u[0])) {
			echo "Username cannot begin with a number";
			exit();
		} else {
			//End form data handling
			
			//Hashing video 7 
			$p_hash = md5($p);
			
			//Add user info into the database
			$sql = "INSERT INTO users (username, email, password, ip, signup, lastlogin, notescheck) VALUES('$u','$e','$p_hash','$ip', now(), now(), now())";
			$query = mysql_query($sql);
			$uid = mysql_insert_id($db_conx);
			
			//Establish their row in the useroptions table
			$sql = "INSERT INTO useroptions(id, username, background) VALUES('$uid', '$u', 'original')";
			$query = mysql_query($sql);
			
			//Create directory to hold each users files(pics, MP3, etc)
			if(!file_exists("user/$u")) {
				mkdir("user/$u");
			}
			
			/*
			//Email the user activation link
			require "class.phpmailer.php";
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->SMTPDebug = 2;
			$mail->From = "gigfinder.autoresponder@gmail.com";
			$mail->Host = "smtp.gmail.com";
			$mail->SMTPSecure = "ssl";
			$mail->Port = 465;
			$mail->SMTPAuth = true;
			$mail->Username = "gigfinder.autoresponder@gmail.com";
			$mail->Password = "gigfinder";
			$mail->AddAddress("smakamura85@gmail.com", "balls");
			$mail->WordWrap = 50;
			
			$mail->IsHTML(true);
			$mail->Subject = "test";
			$mail->Body = "test";
			$mail->Send();
			*/
			echo 'success_';
			echo 'activation.php?id='.$uid.'&u='.$u.'&e='.$e.'&p='.$p_hash.'';
			exit();
		}
		exit();
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Signup</title>
		<link rel="stylesheet" type="text/css" href="css/styles.css" />
		<script src="js/form.js"></script>
		<script src="js/main.js"></script>
		<script src="js/ajax.js"></script>
	</head>
	<body>
		<?php include_once("header.php") ?>
		<div id="content">
			<h3>Sign up</h3>
			<form name="signupform" id="signupform" onsubmit="return false;">
				<div>Username:</div>
				<input id="username" type="text" onblur="checkusername()" onkeyup="restrict('username')" maxlength="16">
				<span id="unamestatus"></span>
				<div>Email Address:</div>
				<input id="email" type="text" onfocus="emptyElement('status')" onkeyup="restrict('email')" maxlength="88">
				<div>Create Password:</div>
				<input id="pass1" type="text" onfocus="emptyElement('status')" maxlength="16">
				<div>Confirm Password:</div>
				<input id="pass2" type="text" onfocus="emptyElement('status')" maxlength="16">
				</br></br>
				<button id="signupbtn" onclick="signup()">Create Account</button>
				</br>
				<div id=status></div>
			</form>
		</div>
		<div id="footer">
			
		</div>
	</body>
</html>