<?php
	$p=$_REQUEST['p'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
	<head>
		<title>A very basic Superfish menu example</title>
		<meta http-equiv="content-type" content="text/html;charset=utf-8">
		<link rel="stylesheet" type="text/css" href="../obj/superfish/css/superfish.css" media="screen">
		<script type="text/javascript" src="../obj/superfish/js/jquery-1.2.6.min.js"></script>
		<script type="text/javascript" src="../obj/superfish/js/hoverIntent.js"></script>
		<script type="text/javascript" src="../obj/superfish/js/superfish.js"></script>
		<script type="text/javascript">

		// initialise plugins
		jQuery(function(){
			jQuery('ul.sf-menu').superfish();
		});

		</script>
	</head>
	<body>
		<ul class="sf-menu">
			
		</ul>
	</body>
</html>