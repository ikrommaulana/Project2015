<?php 
	include_once("$MYLIB/inc/language_$LG.php");
?>
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
?>
		<ul class="sf-menu">
			<li <?php if($p=="main"){?> style="background-image:url(../img/bg_title_lite.jpg)" <?php }?> >
				<a href="p.php?p=main" style="text-decoration:none;<?php if($p=="main"){?> color:#000000; <?php }?>"><?php echo "Main Dashboard";?></a>
			</li>
			<li <?php if($p=="sub_gpa"){?> style="background-image:url(../img/bg_title_lite.jpg)" <?php }?> >
				<a href="p.php?p=sub_gpa" style="border-left:none;text-decoration:none;<?php if($p=="sub_gpa"){?> color:#000000; <?php }?>"><?php echo $lg_subject_analisys;?></a>
			</li>
			<li <?php if($p=="sub_avg"){?> style="background-image:url(../img/bg_title_lite.jpg)" <?php }?> >
				<a href="p.php?p=sub_avg" style="border-left:none;text-decoration:none;<?php if($p=="sub_avg"){?> color:#000000; <?php }?>"><?php echo $lg_average_mark;?></a>
			</li>
            <li <?php if($p=="sub_avg_cls"){?> style="background-image:url(../img/bg_title_lite.jpg)" <?php }?> >
				<a href="p.php?p=sub_avg_cls" style="border-left:none;text-decoration:none;<?php if($p=="sub_avg_cls"){?> color:#000000; <?php }?>"><?php echo  $lg_class_performance;?></a>
			</li>            
			<li <?php if($p=="sub_pas"){?> style="background-image:url(../img/bg_title_lite.jpg)" <?php }?> >
				<a href="p.php?p=sub_pas" style="border-left:none;text-decoration:none;<?php if($p=="sub_pas"){?> color:#000000; <?php }?>"><?php echo  $lg_pass_percentage;?></a>
			</li>
			<li <?php if($p=="stu_gpa"){?> style="background-image:url(../img/bg_title_lite.jpg)" <?php }?> >
				<a href="p.php?p=stu_gpa" style="border-left:none;text-decoration:none;<?php if($p=="stu_gpa"){?> color:#000000; <?php }?>"><?php echo  $lg_a_percentage;?></a>
			</li>
            <li <?php if($p=="stu_best"){?> style="background-image:url(../img/bg_title_lite.jpg)" <?php }?> >
				<a href="p.php?p=stu_best" style="border-left:none;text-decoration:none;<?php if($p=="stu_best"){?> color:#000000; <?php }?>"><?php echo  $lg_best_student;?></a>
			</li>
		</ul>
	</body>
</html>