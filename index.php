<?php
	include_once("php_includes/check_login_status.php");
	$sql = "SELECT username, avatar FROM users WHERE avatar IS NOT NULL AND activated='1' ORDER BY RAND() LIMIT 32";
	$query = mysql_query($sql);
	$userlist = "";
	while($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
		$u = $row['username'];
		$avatar = $row['avatar'];
		$profile_pic = 'users/'.$u.'/'.$avatar;
		$userlist .= '<a href="user.php?u='.$u.'" title="'.$u.'"><img src="'.$profile_pic.'" alt="'.$u.'" style="width:100px; height:100px; margin:10px;></a>';
	}
	$sql = "SELECT COUNT(id) FROM users WHERE activated='1'";
	$query = mysql_query($sql);
	$row = mysql_fetch_row($query);
	$usercount = $row[0];
?>
<!DOCTYPE html>
<html>
	<head>
		<title>gigfinder</title>
		<link rel="stylesheet" type="text/css" href="css/styles.css" />
		<script src="js/main.js"></script>
	</head>
	<body>
		<?php include_once("header.php") ?>
		<div id="content">
			<b>Find gigs in your area</b>
		</div>
		<div id="footer">
		<?php echo $userlist ?>
		<h3 style="color:white;">Total Users: <?php echo $usercount; ?></h3>
		</div>
	</body>
</html>