function login() {
	var e = getElement('email').value;
	var p = getElement('password').value;
	if(e == "" || p == "") {
		getElement('status').innerHTML = "Please fill out all form data";
	} else {
		getElement('loginbtn').style.display = "none";
		getElement('status').innerHTML = "Please wait ..."
		var ajax = ajaxObj("POST", "login.php");
		ajax.onreadystatechange = function() {
			if(ajaxReturn(ajax) == true) {
				if(ajax.responseText.trim() == "login_failed") {
					getElement('status').innerHTML = "Login unsuccessful, please try again";
					getElement('loginbtn').style.display = "block";
				} else {
					window.location = "user.php?u="+ajax.responseText.trim();
				}
			}
		}
		ajax.send("e="+e+"&p="+p);
	}
}

function friendToggle(type, user, elem) {
	var conf = confirm("Press OK to confirm the '"+type+"' action for user <?php echo $u; ?>.");
	if(conf != true) {
		return false;
	}
	getElement(elem).innerHTML = 'please wait ... ';
	var ajax = ajaxObj("POST", "php_parsers/friend_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText == "friend_request_sent") {
				getElement(elem).innerHTML = "Friend request sent";
			} else if(ajax.responseText.trim() == "unfriend_ok") {
				getElement(elem).innerHTML = '<button onclick="friendToggle(\'friend\',\'<?php echo $u; ?>\'friendBtn\')">Request As Friend</button>';
			} else {
				alert(ajax.responseText.trim());
				getElement(elem).innerHTML = "Try again";
			}
		}
	}
	ajax.send("type="+type+"&user="+user);
}

function blockToggle(type, blockee, elem) {
	var conf = confirm("Press OK to confirm the '"+type+"' action on user <?php echo $u; ?>.");
	if(conf != true) {
		return false;
	}
	getElement(elem).innerHTML = 'please wait ... ';
	var ajax = ajaxObj("POST", "php_parsers/block_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			if(ajax.responseText.trim() == "blocked_ok") {
				getElement(elem).innerHTML = '<button onclick="blockToggle(\'unblock\',\'<?php echo $u; ?>\',\'blockBtn\')">Unblock User</button>';
			} else if(ajax.responseText.trim() == "unblocked_ok") {
				getElement(elem).innerHTML = '<button onclick="blockToggle(\'block\',\'<?php echo $u; ?>\',\'blockBtn\')">Block User</button>';
			} else {
				alert(ajax.responseText);
				getElement(elem).innerHTML = "Try again";
			}
		}
	}
	ajax.send("type="+type+"&blockee="+blockee);
}