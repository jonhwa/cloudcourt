<?php
require("connect.php");
//require("passwordhash.php");

$username = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT club_id, password FROM clubs WHERE username='$username'";
$result = pg_query($pg_conn, $query);
if ($row = pg_fetch_assoc($result)) {
	echo 'Got it. ';
	echo $row['password'];
} else {
	echo 'Failed.';
}


/*$errors = array();
$values = array();

$go = $_GET['go'];
$email = mysql_real_escape_string($_POST['email']);
$password = $_POST['password'];
$remember = $_POST['remember'];

$values['email'] = $email;
$values['password'] = $password;
$values['remember'] = $remember;

$query = "SELECT password, id, roomid FROM users WHERE email='".$email."'";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);

if (empty($row['id'])) {
	$errors['email'] = "This account doesn't exist.";
}

$hasher = new PasswordHash(8, false);
if (!$hasher->CheckPassword($password, $row['password'])) {
	$errors['password'] = "This password doesn't match the account.";
}

//Proceed if there were no errors
if (count($errors) == 0) {
	//If user wants to stay logged in, store a cookie
	if ($remember == 'Yes') {
		//Store cookie in database
		$salt = "fLz95myiPA";
		$id = $row['id'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$string = md5($salt.$id.$ip.$salt);
		$query = "UPDATE users SET cookie='$string' WHERE id='$id'";
		mysql_query($query) or die(mysql_error());
	
		//Store cookie on user's computer
		setCookie('user',$id,time() + (20 * 365 * 24 * 60 * 60),'/','roomiou.com');
	}
	
	$_SESSION['user_id'] = $row['id'];
	$_SESSION['room_id'] = $row['roomid'];
	
	if ($go == 'pass') {
		header("Location: ../settings.php?go=pass");
	} else {
		header("Location: ../summary.php");
	}
} else {
	$_SESSION['errors'] = $errors;
	$_SESSION['values'] = $values;
	header("Location: ../index.php?go=".$go);
}*/
?>