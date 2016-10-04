<?php
	$message = "No message";
	$msg = preg_replace('#[^a-z0-9.:_()]#i', '', $_GET['msg']);
	if($msg == "activation_failure") {
		$message = '<h2>Activation Failure</h2>';
	} else if($msg == "activation_success") {
		$message = '<h2>Activation Success</h2></br><a href="login.php">Click here to log in</a>';
	} else { 
		$message = $msg;
	}	
?>
<div><?php echo $message; ?></div>