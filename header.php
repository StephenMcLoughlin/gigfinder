<?php
	$megaphone = '<li><a href="notifications.php"><img src="images/megaphone.png" width="30" height="30" alt="Notifications"></a></li>';
	$loginLink = '<li><a href="login.php"><img src="images/login2.png"></a></li>
				<li><a href="signup.php"><img src="images/signup.png"></a></li>';
	if($user_ok == true) {
		$sql = "SELECT notescheck FROM users WHERE username='$log_username' LIMIT 1";
		$query = mysql_query($sql);
		$row = mysql_fetch_row($query);
		$notescheck = $row[0];
		$sql = "SELECT id FROM notifications WHERE username='$log_username' AND date_time > '$notescheck' LIMIT 1";
		$query = mysql_query($sql);
		$numrows = mysql_num_rows($query);
		if($numrows == 0) {
			$megaphone = '<a href="notifications.php" title="Your notifications and friend requests"><img src="images/notifications.png" width="30" height="30" alt="Notes"></a>';
		} else {
			$megaphone = '<a href="notifications.php" title="Your notifications and friend requests"><img src="images/login.png" width="30" height="30" alt="Notes"></a>';
		}
		$loginLink = '<a href="user.php?u='.$log_username.'">'.$log_username.'</a> &nbsp; | &nbsp; <a href="logout.php"><img src="images/signup.png"></a>';
	}
	
	if($log_username == "") {
		$megaphone = "";
	} else {
		$megaphone = '<li><a href="notifications.php"><img src="images/megaphone.png" width="30" height="30" alt="Notifications"></a></li>';
	}
	
?>

<div id="header">
		<div id="navbar">
			<img src="images/gigfinder-logo.png">
			<ul>		
				<?php echo $megaphone; ?>
				<?php echo $loginLink; ?>
			</ul>
		</div>
</div>