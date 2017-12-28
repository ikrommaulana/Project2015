<?php
//10/05/2010 - repair header
//13/04/2010 - new loaded
$vmod="v5.3.3";
$vdate="111128";
include_once('../etc/db.php');
$uid=$_REQUEST['id'];
$ic=$_REQUEST['ic'];
$sid=$_REQUEST['sid'];
$op=$_REQUEST['op'];
$lid=$_REQUEST['lid'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">	
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>
<body style="background:none;">
<div id="content">
	<div id="mypanel">
		<div id="mymenu" align="center">
			<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
			<a href="#" onClick="window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
		</div>
		<div align="right">
		 	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
		</div>
	</div>

<?php
			$sql="select * from stureg where id='$uid' and ic='$ic'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$sid=$row['sch_id'];
			
			$sql="select * from sch where id='$sid'";
			$res2=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row2=mysql_fetch_assoc($res2);
			$school_name=$row2['name'];
			
			$system_date=date('j F Y');
			
			$student_name=stripslashes($row['name']);
			$student_ic=$row['ic'];
			$contact_hp=$row['hp'];
			$contact_mel=$row['mel'];
			
			$father_name=stripslashes($row['p1name']);
			$father_ic=stripslashes($row['p1ic']);
			$father_hp=stripslashes($row['p1hp']);
			$father_mel=stripslashes($row['p1mel']);
			$mother_name=stripslashes($row['p2name']);
			$mother_ic=stripslashes($row['p2ic']);
			$mother_hp=stripslashes($row['p2hp']);
			$mother_mel=stripslashes($row['p2mel']);
			$address=str_replace(",",",<br>",$row['addr']);
			$city=$row['bandar'];
			$poscode=$row['poskod'];
			$state=$row['state'];
			$clssession=$row['clssession'];
			$interview_center=$row['pt'];
			$interview_date=$row['tarikhtemuduga'];
			
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$stype=$row['level'];
			$simg=$row['img'];
			
			$sql="select * from letter where name='$lid'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$content=$row['content'];
			$isheader=$row['isheader'];
			$content=stripslashes($content);
?>
<div id="story" style="font-size:120%; border:none">
<div id="letter" style="display:block; border:none; padding:10px 10px 10px 10px">
<?php 
	if($isheader){ 
			//include("../inc/school_header.php");
			include("../inc/header_global.php");
			echo "<div id=myborder></div>";
	}

	$content=str_replace("{system_date}",$system_date,$content);
	$content=str_replace("{school_name}",$school_name,$content);
	$content=str_replace("{organization_name}",$organization_name,$content);
	
	$content=str_replace("{father_name}",$father_name,$content);
	$content=str_replace("{father_ic}",$father_ic,$content);
	$content=str_replace("{father_hp}",$father_hp,$content);
	$content=str_replace("{father_mel}",$father_mel,$content);
	
	$content=str_replace("{mother_name}",$mother_name,$content);
	$content=str_replace("{mother_ic}",$mother_ic,$content);
	$content=str_replace("{mother_hp}",$mother_hp,$content);
	$content=str_replace("{mother_mel}",$mother_mel,$content);
	
	$content=str_replace("{address}",$address,$content);
	$content=str_replace("{city}",$city,$content);
	$content=str_replace("{poscode}",$poscode,$content);
	$content=str_replace("{state}",$state,$content);
	
	$content=str_replace("{student_name}",$student_name,$content);
	$content=str_replace("{student_ic}",$student_ic,$content);
	$content=str_replace("{student_class}",$student_class,$content);
	$content=str_replace("{content_report}",$content_report,$content);
	
	$content=str_replace("{clssession}",$clssession,$content);
	$content=str_replace("{interview_date}",$interview_date,$content);
	$content=str_replace("{interview_center}",$interview_center,$content);
	
	
	echo $content;

?> 

</div><!-- letter -->
</div><!-- story -->
</div>
</body>
</html>
