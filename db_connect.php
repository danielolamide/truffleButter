<?php
	$username="root";
	$password="";
	$db = "decentelect";

	$con = new mysqli("localhost",$username,$password,$db);
	$con->select_db($db) or die("Unable to select database");
?>