<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("ADMIN|AKADEMIK|KEUANGAN|GURU|HR|SOKONGAN|HEP|HEP-OPERATOR");
$username = $_SESSION['username'];
$uid=$_REQUEST['uid'];
$sql="select * from stu where uid='$uid'";
$res=mysql_query($sql) or die("$sql - query failed:".mysql_error());
$row=mysql_fetch_assoc($res);
$name=stripslashes(strtoupper($row['name']));
		
$sid=$_REQUEST['sid'];
$p=$_REQUEST['p'];
if($p=="")
	$p="stu_profile";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include("$MYLIB/inc/myheader_setting.php");?>	

</head>
<body style=" background:none">
<div style="background:#CCCCFF; width:100%" class="printhidden">
<div id="mytabdiv" style="font-size:12px; margin:0px 0px 0px 0px;">
	<div id="tab1" style="background-color:#DDDDDD; margin:0px 0px 0px 0px;<?php if($p=="stu_profile") echo "background:#CCCCFF;";?>" class="mytab"><a href="stu_info.php?p=stu_profile&uid=<?php echo $uid?>&sid=<?php echo $sid?>"><?php echo $lg_profile;?></a></div>
	<div id="tab2" style="background-color:#DDDDDD; margin:0px 0px 0px 0px;<?php if($p=="../exam/slip_exam") echo "background:#CCCCFF;";?>" class="mytab"><a href="stu_info.php?p=../exam/slip_exam&uid=<?php echo $uid?>&sid=<?php echo $sid?>"><?php echo $lg_exam;?></a></div>
	<div id="tab3" style="background-color:#DDDDDD; margin:0px 0px 0px 0px;<?php if($p=="../examrep/rep_exam_stu_performance") echo "background:#CCCCFF;";?>" class="mytab"><a href="stu_info.php?p=../examrep/rep_exam_stu_performance&uid=<?php echo $uid?>&sid=<?php echo $sid?>"><?php echo $lg_analysis;?></a></div>
	<div id="tab4" style="background-color:#DDDDDD; margin:0px 0px 0px 0px;<?php if($p=="../ehc/hc_stu") echo "background:#CCCCFF;";?>" class="mytab"><a href="stu_info.php?p=../ehc/hc_stu&uid=<?php echo $uid?>&sid=<?php echo $sid?>">Headcount</a></div>
	<div id="tab5" style="background-color:#DDDDDD; margin:0px 0px 0px 0px;<?php if($p=="../ehaf/hafazan_stu_rep") echo "background:#CCCCFF;";?>" class="mytab"><a href="stu_info.php?p=../ehaf/hafazan_stu_rep&uid=<?php echo $uid?>&sid=<?php echo $sid?>">Hafalan</a></div>
	<div id="tab6" style="background-color:#DDDDDD; margin:0px 0px 0px 0px;<?php if($p=="../ekoq/koq_stu_info") echo "background:#CCCCFF;";?>" class="mytab"><a href="stu_info.php?p=../ekoq/koq_stu_info&uid=<?php echo $uid?>&sid=<?php echo $sid?>"><?php echo $lg_cocurriculum;?></a></div>
	<div id="tab7" style="background-color:#DDDDDD; margin:0px 0px 0px 0px;<?php if($p=="../eatt/att_stu_rep") echo "background:#CCCCFF;";?>" class="mytab"><a href="stu_info.php?p=../eatt/att_stu_rep&uid=<?php echo $uid?>&sid=<?php echo $sid?>"><?php echo $lg_attendance;?></a></div>
	<div id="tab8" style="background-color:#DDDDDD; margin:0px 0px 0px 0px;<?php if($p=="../edis/dis_stu_rep") echo "background:#CCCCFF;";?>" class="mytab"><a href="stu_info.php?p=../edis/dis_stu_rep&uid=<?php echo $uid?>&sid=<?php echo $sid?>"><?php echo $lg_discipline;?></a></div>
	<div id="tab9" style="background-color:#DDDDDD; margin:0px 0px 0px 0px;<?php if($p=="../efee/feestu") echo "background:#CCCCFF;";?>" class="mytab"><a href="stu_info.php?p=../efee/feestu&uid=<?php echo $uid?>&sid=<?php echo $sid?>"><?php echo $lg_fee;?></a></div>
	<!-- 
	<div id="mytab"><a <?php if($p=="../examrep/rep_exam_transcript"){?>style="font-size:120%;color:#FFFFFF;"<?php } ?> href="stu_info.php?p=../examrep/rep_exam_transcript&uid=<?php echo $uid?>&sid=<?php echo $sid?>">Transkrip</a></div>
	<div id="mytab"><a <?php if($p=="feepay"){?>style="font-size:120%;color:#FFFFFF;"<?php } ?> href="stu_info.php?p=feepay&uid=<?php echo $uid?>&sid=<?php echo $sid?>">Yuran</a></div>
	-->
</div>
</div>

<?php include("$p.php"); ?>
</body>
</html>
