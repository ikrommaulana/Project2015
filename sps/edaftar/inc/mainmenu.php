<?php
include_once("$MYLIB/inc/language_$LG.php");
$p=$_REQUEST['p'];
?>
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
			<li <?php if($p=="stu_register"){?> style="background-image:url(../img/bg_title_lite.jpg)" <?php }?> >
				<a href="p.php?p=stu_register" style="border-right:none;text-decoration:none;
				<?php if($p=="stu_register"){?> color:#000000; <?php }?>">
                &nbsp;<?php echo $lg_registration_process;?></a>
			</li>
			<li <?php if($p=="stu_interview"){?> style="background-image:url(../img/bg_title_lite.jpg)" <?php }?> >
				<a href="p.php?p=stu_interview" style="border-right:none;text-decoration:none;
				<?php if($p=="stu_interview"){?> color:#000000; <?php }?>">
                &nbsp;<?php echo $lg_interview_process;?></a>
			</li>
			<li <?php if($p=="stu_report_day"){?> style="background-image:url(../img/bg_title_lite.jpg)" <?php }?> >
				<a href="p.php?p=stu_report_day" style="border-right:none;text-decoration:none;
				<?php if($p=="stu_report_day"){?> color:#000000; <?php }?>">
                &nbsp;Registrasi Harian</a>
			</li>
 			<li <?php if($p=="stu_report"){?> style="background-image:url(../img/bg_title_lite.jpg)" <?php }?> >
				<a href="p.php?p=stu_report" style="border-right:none;text-decoration:none;<?php if($p=="stu_report"){?> color:#000000; <?php }?>">
                &nbsp;<?php echo $lg_registration_report;?></a>
			</li>

            <li <?php if($p=="dashboard"){?> style="background-image:url(../img/bg_title_lite.jpg)" <?php }?> >
				<a href="p.php?p=dashboard" style="border-right:none;text-decoration:none;<?php if($p=="dashboard"){?> color:#000000; <?php }?>">
                &nbsp;Dashboard</a>
			</li>

		</ul>
	</body>
</html>