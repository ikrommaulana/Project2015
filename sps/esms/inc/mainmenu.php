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
		<ul class="sf-menu">
		<!-- 
			<li class="current">
				<a href="index.php" style="text-decoration:none;" title="Send SMS to A group of member"><img src="../img/home16.png" id="mycontrolicon">&nbsp;Home</a>
			</li>
		 -->
			<li class="current">
				<a href="p.php?p=sms_bcast" style="text-decoration:none; color:#000000" title="Send SMS to A group of member"><img src="../img/flash16.png" id="mycontrolicon">&nbsp;Broadcast&nbsp;SMS</a>
			</li>
			<li>
				<a href="p.php?p=composer" style="text-decoration:none; color:#000000" title="Send SMS to single or multiple user"><img src="../img/email16.png" id="mycontrolicon">&nbsp;Compose&nbsp;SMS</a>
			</li>
			<li>
				<a href="p.php?p=sms_msg" style="text-decoration:none; color:#000000" title="Interactive question and answer"><img src="../img/dialog16.png" id="mycontrolicon">&nbsp;Interactive&nbsp;SMS</a>
			</li>
			<li>
				<a href="p.php?p=sms_key" style="text-decoration:none; color:#000000" title="Auto response messaged"><img src="../img/key16.png" id="mycontrolicon">&nbsp;SMS&nbsp;Auto&nbsp;Response</a>
			</li>
			<li>
				<a href="#" style="text-decoration:none; color:#000000"><img src="../img/graphbar16.png" id="mycontrolicon">&nbsp;SMS&nbsp;Reports</a>
				<ul>
							<li><a href="p.php?p=sms_trans" style="text-decoration:none; color:#000000">Transactions</a></li>
							<li><a href="p.php?p=sms_report" style="text-decoration:none; color:#000000">Summary Report</a></li>
				</ul>
			</li>
		</ul>
	</body>
</html>