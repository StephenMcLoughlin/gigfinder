<?php
	include_once("php_includes/check_login_status.php");
	
	//Init variables
	$u = "";
	$e = "";
	$joindate = "";
	$lastsession = "";
	$avatar_form = "";
	$profile_pic = "";
	$profile_pic_btn = "";
	
	//Make sure GET username is set and sanitise it
	if(isset($_GET['u'])) {
		$u = preg_replace('#[^a-z0-9]#', '', $_GET['u']);
	} else {
		header("location: http://www.gigfinder.net23.net");
		exit();
	}
	
	//Select member from users table
	$sql = "SELECT * FROM users WHERE username='$u' AND activated='1' LIMIT 1";
	$query = mysql_query($sql);
	
	//Now make sure that user exists in the table
	$numrows = mysql_num_rows($query);
	if($numrows < 1) {
		echo "That user does not exist or is not activated yet, press back";
		exit();
	}
	
	//Check to see if the viewer is the account owner
	$isOwner = "no";
	if($u == $log_username && $user_ok == true) {
		$isOwner = "yes";
		$profile_pic_btn = '<a href="#" onclick="return false;" onmousedown="toggleElement(\'avatar_form\')">Toggle Avatar Form</a>';
		$avatar_form  = '<form id="avatar_form" enctype="multipart/form-data" method="post" action="php_parsers/photo_system.php">';
		$avatar_form .=   '<h4>Change your avatar</h4>';
		$avatar_form .=   '<input type="file" name="avatar" required>';
		$avatar_form .=   '<p><input type="submit" value="Upload"></p>';
		$avatar_form .= '</form>';
	}
	
	//Fetch the user row from the query above
	while($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
		$profile_id = $row['id'];
		$profile_username = $row['username'];
		$signup = $row["signup"];
		$avatar = $row['avatar'];
		$joindate = strftime('%b %d, %Y', strtotime($signup));
	}
	
	$profile_pic = '<img src="user/'.$u.'/'.$avatar.'" alt='.$u.'">';
	if($avatar == NULL) {
		$profile_pic = '<img src="images/avatardefault.png" alt="poo">';
	}
?>
<?php 
	$friendsHTML = '';
	$friends_view_all_link = '';
	$sql = "SELECT COUNT(id) FROM friends WHERE user1='$u' AND accepted='1' OR user2='$u' AND accepted='1'";
	$query = mysql_query($sql);
	$query_count = mysql_fetch_row($query);
	$friend_count = $query_count[0];
	if($friend_count < 1) {
		$friendsHTML = $u. " has no friends yet";
	} else {
		$max = 18;
		$all_friends =  array();
		$sql = "SELECT user1 FROM friends WHERE user2='$u' AND accepted='1' ORDER BY RAND() LIMIT $max";
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
			array_push($all_friends, $row['user1']);
		}
		$sql = "SELECT user2 FROM friends WHERE user1='$u' AND accepted='1' ORDER BY RAND() LIMIT $max";
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
			array_push($all_friends, $row['user2']);
		}
		$friendArrayCount = count($all_friends);
		if($friendArrayCount > $max) {
			array_splice($all_friends, $max);
		}
		if($friend_count > $max) {
			$friends_view_all_link = '<a href="view_friends.php?u='.$u.'">view all</a>';
		}
		$orLogic = '';
		foreach($all_friends as $key => $user) {
			$orLogic .= "username='$user' OR ";
		}
		$orLogic = chop($orLogic, "OR ");
		$sql = "SELECT username, avatar FROM users WHERE $orLogic";
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
			$friend_username = $row['username'];
			$friend_avatar = $row['avatar'];
			if($friend_avatar != "") {
				$friend_pic = 'user/' .$friend_username. '/' .$friend_avatar. '';
			} else {
				$friend_pic = "images/avatardefault.png";
			}
			$friendsHTML .= '<a href="user.php?u='.$friend_username.'"><img class="friendpics" src="'.$friend_pic.'" alt="'.$friend_username.'" title="'.$friend_username.'"></a>';
		}
	}
?>
<?php
	$isFriend = false;
	$ownerBlockViewer = false;
	$viewerBlockOwner =  false;
	
	if($u != $log_username && $user_ok == true) {
		$friend_check = "SELECT id FROM friends WHERE user1='$log_username' AND user2='$u' AND accepted='1' OR user1='$u' AND user2='$log_username' AND accepted='1' LIMIT 1";
		$query = mysql_query($friend_check);
		if(mysql_num_rows(mysql_query($friend_check)) > 0) {
			$isFriend = true;
		}
	}
	
	//Check if owner has blocked viewer
	$block_check1 = "SELECT id FROM blockedusers WHERE blocker='$u' AND blockee='$log_username' LIMIT 1";
	$query = mysql_query($block_check1);
	if(mysql_num_rows($query) > 0) {
		$ownerBlockViewer = true;
	}
	
	//Check if viewer has blocked owner
	$block_check2 = "SELECT id FROM blockedusers WHERE blocker='$log_username' AND blockee='$u' LIMIT 1";
	$query = mysql_query($block_check2);
	if(mysql_num_rows($query) > 0) {
		$viewerBlockOwner = true;
	}
?>
<?php
	$friend_button = '<button disabled>Request As Friend</button>';
	$block_button = '<button disabled>Block User</button>';

	//Logic for friend button
	if($isFriend == true) {
		$friend_button = '<button onclick="friendToggle(\'unfriend\',\''.$u.'\',\'friendBtn\')">Unfriend</button';
	} else if($user_ok == true && $u != $log_username && $ownerBlockViewer == false) {
		$friend_button = '<button onclick="friendToggle(\'friend\',\''.$u.'\',\'friendBtn\')">Request As Friend</button>';
	}
	
	//Logic for block button
	if($viewerBlockOwner == true) {
		$block_button = '<button onclick="blockToggle(\'unblock\',\''.$u.'\',\'blockBtn\')">Unblock User</button';
	} else if($user_ok == true && $u != $log_username && $ownerBlockViewer == false) {
		$block_button = '<button onclick="blockToggle(\'block\',\''.$u.'\',\'blockBtn\')">Block User</button>';
	}
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/styles.css" />
		<script src="js/user.js"></script>
		<script src="js/main.js"></script>
		<script src="js/ajax.js"></script>
		
		
<style type="text/css">
#profile_pic_box{float:right; border:#999 2px solid; width:200px; height:200px; margin:20px 30px 0px 0px; overflow-y:hidden;}
#profile_pic_box > img{z-index:2000; width:200px;}
#profile_pic_box > a {
	display: none;
	position:absolute; 
	margin:0px 0px 0px 120px;
	z-index:4000;
	background:#D8F08E;
	border:#81A332 1px solid;
	border-radius:3px;
	padding:5px;
	font-size:12px;
	text-decoration:none;
	color:#60750B;
}
#profile_pic_box > form{
	display:none;
	position:absolute; 
	z-index:3000;
	padding:10px;
	opacity:.8;
	background:#F0FEC2;
	width:180px;
	height:180px;
}
#profile_pic_box:hover a{
	display: block;
   -webkit-filter: invert(100%);
    -moz-filter: invert(100%);
    -ms-filter: invert(100%);
    -o-filter: invert(100%);
    filter: invert(100%); 
}
img.friendpics{border:#000 1px solid; width:40px; height:40px; margin:2px;}
</style>
	</head>
	<body>
		<?php include_once("header.php") ?>
		<div id="profile_pic_box">
			<?php echo $profile_pic_btn; ?>
			<?php echo $avatar_form; ?>
			<?php echo $profile_pic; ?>
		</div>
		<h3><?php echo $u; ?></h3>
		<p>Is the owner <b><?php echo $isOwner; ?></p>
		<p> Friend Button: <span id="friendBtn"><?php echo $friend_button; ?></span><?php echo $u. " has " .$friend_count. " friends "?></p>
		<p> Block Button: <span id="blockBtn"><?php echo $block_button; ?></span></p>
		<p><?php echo $friendsHTML; ?></p>
		<a href="logout.php">Log out</a>
	</body>
</html>