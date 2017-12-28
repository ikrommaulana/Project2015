<?php
include('../etc/config.php');
include("$MYINI/etc/ini.php");

ini_set('session.name',$session_waris);
session_start();

function process_login()
{
	include('../etc/config.php');
	include("$MYINI/etc/ini.php");
	$link=mysql_pconnect($DB_HOST,$DB_USER,$DB_PASS)or die("could not connect:".mysql_error());
	$db=mysql_select_db($DB_NAME,$link) or die("setting:".mysql_error());
	$user=md5($_POST['username']);
	$sid=$_POST['sid'];
	$uid=$_POST['uid'];
	
	$sql="select * from stu where sch_id=$sid and password='$user' and uid='$uid' and status=6";
	$res=mysql_query($sql,$link);
	if(mysql_num_rows($res)>0){
		$row=mysql_fetch_assoc($res);
		//$_SESSION['username']=$row['uid'];
		//$_SESSION['password']=$row['pass'];
		$_SESSION['username']=$user;//ic parent
		$_SESSION['sid']=$row['sch_id'];
		$_SESSION['uid']=$row['uid'];
		$sql="update stu set plogin=now() where sch_id=$sid and password='$user' and uid='$uid' and status=6";
		mysql_query($sql,$link);
		sys_log($link,"EWARIS","Login","Success","",$_SESSION['sid']);
		echo "<script language=\"javascript\">location.href='main.php'</script>";
	}else{
		echo "<script language=\"javascript\">location.href='index.php?login=0'</script>";
	}
}
function logout()
{
	//include('../etc/config.php');
	//include("$MYINI/etc/ini.php");
	session_destroy();
	unset($_SESSION);
	echo "<script language=\"javascript\">location.href='index.php'</script>";
}

function verify()
{
	include('../etc/config.php');
	include("$MYINI/etc/ini.php");
	if(session_name()!=$session_waris){
		session_destroy();
		unset($_SESSION);
		echo "<script language=\"javascript\">location.href='index.php'</script>";
		return 0;
	}
	if (isset($_SESSION['username']))
		return 1;
		
	session_destroy();
	unset($_SESSION);
	echo "<script language=\"javascript\">location.href='index.php'</script>";
	return 0;
}
function is_verify()
{
	include('../etc/config.php');
	include("$MYINI/etc/ini.php");
	if(session_name()!=$session_waris)
		return 0;
	if (isset($_SESSION['username']))
		return 1;
	return 0;
}
?>
