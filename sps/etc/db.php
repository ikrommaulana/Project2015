<?php
include('config.php');
include("$MYINI/etc/ini.php");
$link=mysql_pconnect($DB_HOST,$DB_USER,$DB_PASS)or die("could not connect:".mysql_error());
$db=mysql_select_db($DB_NAME,$link) or die("setting:".mysql_error());

function sys_log($link,$app,$act,$des,$det,$sid)
{
		$ip=$_SERVER['REMOTE_ADDR'];
		$uid=$_SESSION['username'];
		$lvl=$_SESSION['syslevel'];
		if($sid=="")
				$sid=0;
		$des=addslashes($des); 
		$det=addslashes($det); 
	
		$sql="insert into sys_log (dt,tm,uid,sid,lvl,ip,app,act,des,det) values 
		(now(),now(),'$uid',$sid,'$lvl','$ip','$app','$act','$des','$det')";
		mysql_query($sql,$link)or die("query failed:".mysql_error());
		return 0;
}

?>