<?php
	session_start();
	require_once("db_connect.php");
	$name="";
	$pass="";
	$email="";
	$msg="";
	$error="";
	$error1="";
	if(isset($_POST['create_acc_btn'])){
		$name = $con->real_escape_string($_POST['studentName']);
		$email = $con->real_escape_string($_POST['studentEmail']);
		$pass = $con->real_escape_string($_POST['sPass1']);
		$hash = password_hash($pass,PASSWORD_BCRYPT);
		if($_POST['sPass1']!=$_POST['sPass2']){
			echo '<script>alert("Passwords do bot");</script>';
		}
		else{
			$emailExists = $con->query("SELECT * FROM s_details WHERE s_email='$email'");
			if($emailExists->num_rows>0)
			{
				$error = "<p><span>*</span>User with this email already exists</p>";
			}
			else{
				$addUserQuery = $con->query("INSERT INTO s_details(s_name,s_email,s_pass) VALUES('$name','$email','$hash')");
				if($addUserQuery){
					$msg="<p>Successful Registration<p>";
				}
			}
		}
	}
	if(isset($_POST['loginBtn'])){
		$email= $con->real_escape_string($_POST['studentEmail']);
		$pass= $con->real_escape_string($_POST['sPass1']);
		$loginQuery = $con->query("SELECT * FROM s_details WHERE s_email ='$email'");
		if($loginQuery->num_rows>0){
			$data = $loginQuery->fetch_array();
			if((password_verify($pass,$data['s_pass']))){
				$_SESSION['userName'] = $data['s_name'];
				$_SESSION['email'] = $data['s_email'];
				$msg="<p>Successful Log In<p>";
			}
			else{
				$error1 = "<p><span>*</span>Password is incorrect</p>";
			}
		}
		else{
			$error1 = "<p><span>*</span>Account doesn't exist</p>";
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Voter Registration</title>
	<link rel="stylesheet" type="text/css" href="./css/style.css">
	<link rel="icon" type="image/png" href="./images/favicon.png">
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">
</head>
<body>
	<div class="wrapper">
		<div class="panel1">
			<div class="img-text">
			<span>Let your voice be heard</span>
			<span>#StrathmoreElections2018#</span>
		</div>
		</div>
		<div class="panel2">
			<div class="tab">
				<button  id="defaultOpen" class="tabLinks" onclick="openTab(event,'register')">Sign Up</button>
				<span>or</span>
				<button class="tabLinks" onclick="openTab(event,'login')">Sign In</button>
			</div>
			<div class="tabcontent" id="register">
			<form method="post">
				<div class="field-name">
					<span>Full Name</span>
				</div>
				<div class="field-input">
					<input type="text" name="studentName" placeholder="Enter your full name" required autocomplete="off">
				</div>
				<div class="field-name">
					Email Address
				</div>
				<div class="field-input">
					<input type="email" name="studentEmail" placeholder="Your e-mail goes here" autocomplete="off" required>
				</div>
				<div class="field-name">
					Password
				</div>
				<div class="field-input">
					<input type="password" name="sPass1" placeholder="Password" required>
				</div>
				<div class="field-name">
					Re-type Password
				</div>
				<div class="field-input">
					<input type="password" name="sPass2" placeholder="Re-type Password" required>
				</div>
				<div class="error">
					<?php 
						if($error){
							echo $error;
						}
					?>
				</div>
				<div class="submitBtn">
					<button class="sign-up" name="create_acc_btn">Sign Up</button>
				</div>
				<div class="account_present">
					<button onclick="openTab(event,'login')">I already have an account</button>
				</div>
			</form>
			</div>
			<div class="tabcontent" id="login">
			<form method="post">
				<div class="field-name">
					Email Address
				</div>
				<div class="field-input">
					<input type="email" name="studentEmail" placeholder="Your e-mail goes here" required="" autocomplete="off">
				</div>
				<div class="field-name">
					Password
				</div>
				<div class="field-input">
					<input type="password" name="sPass1" placeholder="Password" required autocomplete="off">
				</div>
				<div class="error">
					<?php 
						if($error1){
							echo $error1;
						}
						else{
							echo $msg;
						}
					 ?>
				</div>
				<div class="submitBtn">
					<button class="sign-up" name="loginBtn">Sign In</button>
				</div>
				<div class="account_present">
					<button onclick="openTab(event,'register')">I need an account</button>
				</div>
			</form>
			</div>
		</div>
	</div>
	<footer style="text-align: center; font-family: 'Source Sans Pro',sans-serif;">
		<?php include 'footer.php';  ?>
	</footer>
	<script src="./scripts/index.js"></script>
</body>
</html>