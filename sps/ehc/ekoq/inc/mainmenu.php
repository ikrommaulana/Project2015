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
			<li <?php if($p=="koq_stu_list"){?> style="background-image:url(../img/bg_title_lite.jpg)" <?php }?> >
				<a href="p.php?p=koq_stu_list" style="text-decoration:none;<?php if($p=="koq_stu_list"){?> color:#000000; <?php }?>" title="Student Ko-Q">&nbsp;KO-Q <?php echo $lg_student;?></a>
			</li>
			<?php if(is_verify("ADMIN")){ ?>
			<li <?php if($p=="koq_tea_list"){?> style="background-image:url(../img/bg_title_lite.jpg)" <?php }?> >
				<a href="p.php?p=koq_tea_list" style="text-decoration:none;<?php if($p=="koq_tea_list"){?> color:#000000; <?php }?>" title="Teacher Ko-Q">&nbsp;KO-Q <?php echo $lg_teacher;?></a>
			</li>
			<?php } ?>
			<li <?php if($p=="koq_stu_rep"){?> style="background-image:url(../img/bg_title_lite.jpg)" <?php }?> >
				<a href="p.php?p=koq_stu_rep" style="text-decoration:none;<?php if($p=="koq_stu_rep"){?> color:#000000; <?php }?>" title="Report"><img src="../img/graph16.png" style="float:left; ">&nbsp;KO-Q <?php echo $lg_report;?></a>
			</li>
            <li <?php if($p=="koq_report"){?> style="background-image:url(../img/bg_title_lite.jpg)" <?php }?> >
			<a href="p.php?p=koq_report" style="text-decoration:none;<?php if($p=="koq_report"){?> color:#000000; <?php }?>" title="Report"><img src="../img/graph16.png" style="float:left; ">&nbsp;KO-Q <?php echo $lg_slip;?></a>
            </li>
		</ul>
	</body>
</html>