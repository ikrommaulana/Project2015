<?php
include('config.php');
include("../$MYINI/etc/ini.php");
ini_set('session.name',$session_name);
session_start();

function process_login()
{
	include('config.php');
	include("../$MYINI/etc/ini.php");
	$link=mysql_pconnect($DB_HOST,$DB_USER,$DB_PASS)or die("could not connect:".mysql_error());
	$db=mysql_select_db($DB_NAME,$link) or die("setting:".mysql_error());

	$user=$_POST['xuser'];
	$pass=$_POST['xpass'];
	$xkey=$_POST['xkey'];

	$sql="select * from usr where uid='$user' and pass=md5('$pass') and ic='$xkey' and status=0";
	$res=mysql_query($sql,$link);
	if(mysql_num_rows($res)==1){
		$row=mysql_fetch_assoc($res);
		$_SESSION['username']=$row['uid'];
		$_SESSION['password']=$row['pass'];
		$_SESSION['name']=stripslashes($row['name']);
		$_SESSION['sid']=$row['sch_id'];
		$_SESSION['syslevel']=$row['syslevel'];
		$_SESSION['sysaccess']=$row['sysaccess'];
		$_SESSION['sesname']=$session_name;
		$_SESSION['sesid']=time();
		$sesid=$_SESSION['sesid'];
		sys_log($link,"Login","SPS","Success","",$_SESSION['sid']);
		$sql="update usr set ll=now(),sesid='$sesid',sestm=$sesid,seson=1 where uid='$user'";
		$res=mysql_query($sql,$link);
		echo "<script language=\"javascript\">location.href='index.php'</script>";
	}
	else{
			if(($user!="") && ($pass!="")){
				if(($user==$SUPERUSER) && ($pass==$SUPERPASS)&& ($xkey==$SUPERKEY)){
						$_SESSION['username']='Root';
						$_SESSION['password']=$pass;
						$_SESSION['name']="AWFATECH ADMIN";
						$_SESSION['sid']=0;
						$_SESSION['syslevel']="ROOT";
						$_SESSION['sysaccess']="ROOT";
						$_SESSION['sesname']=$session_name;
						sys_log($link,"Login","SPS","Success","",$_SESSION['sid']);
						echo "<script language=\"javascript\">location.href='index.php'</script>";
						return;
				}
			}
			sys_log($link,"Login","SPS","Failed $user","",0);
			echo "<script language=\"javascript\">location.href='index.php?login=-1'</script>";
	}
	
}
function process_logout()
{
	include_once('db.php');
	$link=mysql_pconnect($DB_HOST,$DB_USER,$DB_PASS)or die("could not connect:".mysql_error());
	$db=mysql_select_db($DB_NAME,$link) or die("setting:".mysql_error());
	sys_log($link,"Logout","SPS","Success","",$_SESSION['sid']);
	
	$uid=$_SESSION['username'];
	$ses=$_SESSION['sesid'];
	$tm=time();
	$sql="update usr set seson=0 where uid='$uid' and sesid='$ses'";
	$res=mysql_query($sql);
	
	session_destroy();
	unset($_SESSION);
	echo "<script language=\"javascript\">location.href='../adm/index.php?login=0'</script>";
}

function verify($user)
{
	include('config.php');
	include("../$MYINI/etc/ini.php");
	include_once('db.php');
	if($_SESSION['username']==""){
		session_destroy();
		unset($_SESSION);
		echo "<script language=\"javascript\">location.href='../adm/index.php?login=-2'</script>";
		return 0;
	}
	
	if(session_name()!=$session_name){
		session_destroy();
		unset($_SESSION);
		echo "<script language=\"javascript\">location.href='../adm/index.php?login=-3'</script>";
		return 0;
	}
	
	if ($_SESSION['syslevel']=="ROOT")
		return 1;
		
	$uid=$_SESSION['username'];
	$ses=$_SESSION['sesid'];
	
	$sql="select count(*) from usr where uid='$uid' and seson=1 and sesid='$ses'";
	$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
	$row=mysql_fetch_row($res);
	$alive=$row[0];
	if(!$alive){
		session_destroy();
		unset($_SESSION);
		echo "<script language=\"javascript\">location.href='../adm/index.php?login=-4'</script>";
		return 0;
	}
	
	$tm=time();
	$sql="update usr set sestm=$tm where uid='$uid' and sesid='$ses'";
	$res=mysql_query($sql);
	
	if($user=="")
		return 1;

	$x=strtok($user,"|");
	while($x!=NULL){
		if ($_SESSION['syslevel']==$x)
			return 1;
		$x=strtok("|");
	}

		
	echo "<script language=\"javascript\">location.href='../adm/sys_lock.php'</script>";
	return 0;
}

function is_verify($user)
{
	if($_SESSION['username']=="")
		return 0;
		
	if($user=="")
		return 1;
		
	$x=strtok($user,"|");
	while($x!=NULL){
		if ($_SESSION['syslevel']==$x)
			return 1;
		$x=strtok("|");
	}
	if ($_SESSION['syslevel']=="ROOT")
		return 1;
		
	return 0;
}


function ISACCESS($module, $autoreject)
{
	$usraccess=$_SESSION['sysaccess'];
	$x=strtok($usraccess,"|");
	while($x!=NULL){
		if ($module==$x)
			return 1;
		$x=strtok("|");
	}
	if ($_SESSION['syslevel']=="ROOT")
		return 1;
		
	if($autoreject){
		echo "<script language=\"javascript\">location.href='../adm/sys_lock.php'</script>";
	}
	return 0;
}

?>
