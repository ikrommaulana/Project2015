<?php
	include_once("$MYLIB/inc/language_$LG.php");
	$p=$_REQUEST['p'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
	<head>
		<title></title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8">
		<link rel="stylesheet" type="text/css" href="<?php echo $MYOBJ;?>/superfish/css/superfish.css" media="screen">
        <!-- apai remark this 130128 coz cannot open fancy box
		<script type="text/javascript" src="<?php echo $MYOBJ;?>/superfish/js/jquery-1.2.6.min.js"></script>
		<script type="text/javascript" src="<?php echo $MYOBJ;?>/superfish/js/hoverIntent.js"></script>
		<script type="text/javascript" src="<?php echo $MYOBJ;?>/superfish/js/superfish.js"></script>
		<script type="text/javascript">

		// initialise plugins
		jQuery(function(){
			jQuery('ul.sf-menu').superfish();
		});
		</script>
        -->
	</head>
	<body>
		<ul class="sf-menu">
			<!--<li <?php if($p==""){?> style="background-image:url(<?php echo $MYLIB;?>/img/bg_title_lite.jpg)" <?php }?> >
					<a href="../ga/index.php" style="border-right:none;text-decoration:none;<?php if($p==""){?> color:#000000; <?php }?>">Genaral Admin</a>
			</li>-->              
			<li <?php if($p=="asset"){?> style="background-image:url(<?php echo $MYLIB;?>/img/bg_title_lite.jpg)" <?php }?> >
					<a href="p.php?p=asset" style="border-right:none;text-decoration:none;<?php if($p=="asset"){?> color:#000000; <?php }?>">Sarpras</a>
			</li>
			<!--<li <?php if($p=="asset_tag"){?> style="background-image:url(<?php echo $MYLIB;?>/img/bg_title_lite.jpg)" <?php }?> >
					<a href="p.php?p=asset_tag" style="text-decoration:none;<?php if($p=="asset_tag"){?> color:#000000; <?php }?>">Staff Report</a>
			</li>-->
		</ul>
	</body>
</html>