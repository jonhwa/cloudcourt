<?
include_once("connect.php");
if(isset($_SESSION['user_id'])) {
	$id = $_SESSION['user_id'];
	session_unset();
	session_destroy();
	//setcookie('user','4',time()-1000,'/','roomiou.com');
	//mysql_query("UPDATE users SET cookie='' WHERE id='$id'");
	header("Location: ../index.php");
}
?>