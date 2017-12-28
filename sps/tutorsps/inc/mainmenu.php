<?php include_once("$MYLIB/inc/language_$LG.php");?>
<!-- estaff -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
	<head>
		<title>A very basic Superfish menu example</title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8">
		<link rel="stylesheet" type="text/css" href="<?php echo $MYOBJ;?>/superfish/css/superfish.css" media="screen">
		<script type="text/javascript" src="<?php echo $MYOBJ;?>/superfish/js/jquery-1.2.6.min.js"></script>
		<script type="text/javascript" src="<?php echo $MYOBJ;?>/superfish/js/hoverIntent.js"></script>
		<script type="text/javascript" src="<?php echo $MYOBJ;?>/superfish/js/superfish.js"></script>
		<script type="text/javascript">

		// initialise plugins
		jQuery(function(){
			jQuery('ul.sf-menu').superfish();
		});

		</script>
	</head>
	<body>
<?php
	$p=$_REQUEST['p'];
	$cat=$_REQUEST['cat'];
	if($cat=="")
		$cat="passport";
?>
		<ul class="sf-menu">
			<li <?php if($p=="tutorial"){?> style="background-image:url(../img/bg_title_lite.jpg)" <?php }?> >
				<a href="p.php?p=tutorial" style="text-decoration:none;<?php if($p=="tutorial"){?> color:#000000; <?php }?>">Video Tutorial</a>
			</li>
		</ul>
	</body>
</html>