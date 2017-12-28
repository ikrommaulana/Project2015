<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
$p=$_REQUEST['p'];
if($p=="")
	echo "<script language=\"javascript\">location.href='../adm/index.php'</script>";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<!-- DW6 -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>
<body> 
<?php
 	include('../inc/masthead.php');
	include("$p.php"); 
	include('../inc/site_footer.php');
?>
</body>
</html>
