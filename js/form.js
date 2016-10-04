function restrict(elem) {
	var tf = getElement(elem);
	var rx = new RegExp;
	if(elem == "email") {
		rx = /[' "]/gi;
	} else if (elem == "username"){
		rx = /[^a-z0-9]/gi;
	}
	tf.value = tf.value.replace(rx, "");
}

function emptyElement(x) {
	getElement(x).innerHTML = "";
}

function checkusername() {
	var u = getElement("username").value;
	if(u != "") {
		getElement("unamestatus").innerHTML = "checking..."
		var ajax = ajaxObj("POST", "signup.php");
		ajax.onreadystatechange = function() {
			if(ajaxReturn(ajax) == true) {
				getElement("unamestatus").innerHTML = ajax.responseText;
			}
		}
		ajax.send("usernamecheck=" + u);
	}
}

function signup() {
	var username = getElement("username").value;
	var email = getElement("email").value;
	var pass = getElement("pass1").value;
	var pass2 = getElement("pass2").value;
	var status = getElement("status");
	if(username == "" || email== "" || pass == "" || pass2 == "") {
		status.innerHTML = "Fill out the form data";
	} else if (pass != pass2) {
		status.innerHTML = "Your password does not match";
	} else {
		getElement("signupbtn").style.display = "none";
		status.innerHTML = "please wait...";
		var ajax = ajaxObj("POST", "signup.php");
		ajax.onreadystatechange = function() {
			if(ajaxReturn(ajax) ==  true) {
				var response = ajax.responseText.trim();
				
				//Since SMTP isn't on server, split success response and  Activation link
				var splitResponse = response.split("_");
				var link = splitResponse[1];
				
				if(splitResponse[0] === "success") {
					window.scrollTo(0,0);
					getElement('signupform').innerHTML = "<a href=" + link + ">Activate Account</a>";
					//getElement("signupform").innerHTML = "Thank you " + username + " for signing up to gigfinder </br>Your email=" + email + "</br>Pass=" + pass + "</br>An activation link has been sent to your email</br><a href=\"activation.php?id='.$uid.'&u='.$username.'&e='.$email.'&p=''.pass.'\">Activate Account</a>";
					
				} else {
					status.innerHTML = "Fail";
					getElement("signupbtn").style.display = "block";
				}
			} 
		}
		ajax.send("u="+username+"&e="+email+"&p="+pass);
	}
}
