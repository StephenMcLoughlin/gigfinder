<?php
/*$db_conx = mysql_connect("mysql4.000webhost.com","a7090258_smac", "Fuckyou2");*/
	$db_conx = mysql_connect("localhost","root", "");

//Evaluate the connection
if (mysql_errno()) {
	//echo  "balls";
	exit();
}
/*mysql_select_db("a7090258_social");*/
mysql_select_db("users");
?>
