<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
<!-- SETTING GRAY BOX -->
<script type="text/javascript"> var GB_ROOT_DIR = "<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/"; </script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_scripts.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />
<!-- apai remark
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/static_files/help.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/static_files/help.css" rel="stylesheet" type="text/css" media="all" />
-->
</head>

<body>
<div style="position:absolute;">
<br>
<br>
<?php if ($CCTV_ENABLE){?>
	<a href="http://<?php echo "$CCTV_LOCAL_PATH";?>" title="Local Network" style="font-size:20px ">VIEW FROM LOCAL NETWORK</a><br>
<br>
<br>
	<a href="http://<?php echo "$CCTV_INTERNET_PATH";?>" title="Internet" style="font-size:20px ">VIEW FROM INTERNET</a>
<?php } else {?>

<a href="#" title="Internet" style="font-size:20px ">ONLINE CCTV NOT INSTALL</a>
<?php } ?>
</div>


</body>
</html>
