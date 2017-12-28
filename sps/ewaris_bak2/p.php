<?php
include_once('../etc/db.php');
include_once('session.php');
$p=$_REQUEST['p'];
if($p=="")
	echo "<script language=\"javascript\">location.href='index.php'</script>";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<!-- DW6 -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>
</head>
<body > 
<?php include('inc/masthead.php')?>
<?php include("$p.php"); ?>  
<?php include('inc/siteinfo.php');?>
</body>
</html>
