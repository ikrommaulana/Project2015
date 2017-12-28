<?php
/*************************************************************************************
** Author: Apai																		**
** Email : razali212@yahoo.com														**
**************************************************************************************/
include_once('../etc/ini.php');
include_once('../etc/db.php');
include_once("$MYLIB/inc/language_$LG.php");
include_once('session.php');
verify();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<!-- DW6 -->
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('inc/sitetitle.php')?></title>

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


<!-- SETTING GRAPH CHART -->
<script language="javascript">AC_FL_RunContent = 0;</script>
<script language="javascript"> DetectFlashVer = 0; </script>
<script src="<?php echo $MYOBJ;?>/charts/AC_RunActiveContent.js" language="javascript"></script>
<script language="JavaScript" type="text/javascript">
<!--
var requiredMajorVersion = 9;
var requiredMinorVersion = 0;
var requiredRevision = 45;
-->
</script>

<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>

</head>
<body > 

<?php include('inc/masthead.php')?>

<!--end pageNav--> 

<div id="panelleft">
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" >
	<?php include('inc/lmenu.php');?>
</form>
</div> 
<div id="content3"> 

<div align="center" style="font-size:14px; font-weight:bold; padding:2px; font-family:Arial; color:#FFFFFF; background-image:url(../img/bg_title_lite.jpg);border: 1px solid #99BBFF;">
		<?php echo strtoupper($name);?>
</div>

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="50%" >
	<div id="graph" align="center">
<?php 
$uid=$_SESSION['uid'];
$sid=$_SESSION['sid'];
$year=$_POST['year'];
if($year=="")
		$year=date('Y');
		
$chart_note_top=$lg_fee;;
$chart_item_group="Paid,Remaining";
//$chart_item_value="300,3";

//$chart_item_value="";
$sql="select count(*) from feestu where uid='$uid' and ses='$year' and sid=$sid and sta=1";
$res=mysql_query($sql)or die("query failed:".mysql_error());
$row=mysql_fetch_row($res);
$s=$row[0];

$sql="select count(*) from feestu where uid='$uid' and ses='$year' and sid=$sid and sta=0";
$res=mysql_query($sql)or die("query failed:".mysql_error());
$row=mysql_fetch_row($res);
$v=$row[0];
$chart_item_value="$s,$v";

							
$xml="../xml/graph/chart_pie2.php?dat=$chart_item_group|$chart_item_value|$chart_note_left|$chart_note_top|$chart_note_bottom|$chart_note_center|$chart_note_right|$chart_decimal_value";
?>
			<script language="JavaScript" type="text/javascript">
			<!--
			if (AC_FL_RunContent == 0 || DetectFlashVer == 0) {
				alert("This page requires AC_RunActiveContent.js.");
			} else {
				var hasRightVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
				if(hasRightVersion) { 
					AC_FL_RunContent(
						'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,45,0',
						'width', '100%',
						'height', '230',
						'scale', 'exactFit',
						'salign', 'TL',
						'bgcolor', '#FFFFFF',
						'wmode', 'opaque',
						'movie', 'charts',
						'src', '<?php echo $MYOBJ;?>/charts/charts',
						'FlashVars', 'library_path=<?php echo $MYOBJ;?>/charts/charts_library&xml_source=<?php  echo "$xml";?>', 
						'id', 'my_chart',
						'name', 'my_chart',
						'menu', 'true',
						'allowFullScreen', 'true',
						'allowScriptAccess','sameDomain',
						'quality', 'high',
						'align', 'middle',
						'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
						'play', 'true',
						'devicefont', 'false'
						); 
				} else { 
					var alternateContent = 'This content requires the Adobe Flash Player. '
					+ '<u><a href=http://www.macromedia.com/go/getflash/>Get Flash</a></u>.';
					document.write(alternateContent); 
				}
			}
			// -->
			</script>
			<noscript>
				<P>This content requires JavaScript.</P>
			</noscript>

</div>

</td>
<td width="50%" >
	<div id="graph" align="center">
<?php 
$chart_note_top=$lg_attendance;
$chart_item_group=$lg_attend.",".$lg_absence;
//$chart_item_value="300,3";
//$chart_item_value="";
$sql="select count(*) from stuatt where stu_uid='$uid' and year='$year' and sch_id=$sid and sta=0";
$res=mysql_query($sql)or die("query failed:".mysql_error());
$row=mysql_fetch_row($res);
$s=$row[0];
$v=100-$s;
$chart_item_value="$v,$s";
							
$xml="../xml/graph/chart_pie2.php?dat=$chart_item_group|$chart_item_value|$chart_note_left|$chart_note_top|$chart_note_bottom|$chart_note_center|$chart_note_right|$chart_decimal_value";
?>
			<script language="JavaScript" type="text/javascript">
			<!--
			if (AC_FL_RunContent == 0 || DetectFlashVer == 0) {
				alert("This page requires AC_RunActiveContent.js.");
			} else {
				var hasRightVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
				if(hasRightVersion) { 
					AC_FL_RunContent(
						'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,45,0',
						'width', '100%',
						'height', '230',
						'scale', 'exactFit',
						'salign', 'TL',
						'bgcolor', '#FFFFFF',
						'wmode', 'opaque',
						'movie', 'charts',
						'src', '<?php echo $MYOBJ;?>/charts/charts',
						'FlashVars', 'library_path=<?php echo $MYOBJ;?>/charts/charts_library&xml_source=<?php  echo "$xml";?>', 
						'id', 'my_chart',
						'name', 'my_chart',
						'menu', 'true',
						'allowFullScreen', 'true',
						'allowScriptAccess','sameDomain',
						'quality', 'high',
						'align', 'middle',
						'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
						'play', 'true',
						'devicefont', 'false'
						); 
				} else { 
					var alternateContent = 'This content requires the Adobe Flash Player. '
					+ '<u><a href=http://www.macromedia.com/go/getflash/>Get Flash</a></u>.';
					document.write(alternateContent); 
				}
			}
			// -->
			</script>
			<noscript>
				<P>This content requires JavaScript.</P>
			</noscript>

</div>

</td>
</tr><tr>
<td width="50%" >
	<div id="graph" align="center">
<?php 
$clevel=0;
$chart_note_top="";
$chart_note_center="$year";
$chart_note_left=$lg_average_mark;
$chart_item_value="$lg_exam,0";
//$chart_item_group="2007,2008,2009,2010,2011";
$chart_item_group="Evaluation";

$sql="select * from ses_stu where stu_uid='$uid' and year=$year";
$res2=mysql_query($sql)or die("query failed:".mysql_error());
$cname="";
$clevel=0;
if($row2=mysql_fetch_assoc($res2)){
		$cname=$row2['cls_name'];
		$clevel=$row2['cls_level'];
}
$j=0;
$sql="select * from type where grp='exam' and (lvl=0 or lvl=$clevel) and (sid=0 or sid=$sid) order by idx";
$res=mysql_query($sql)or die("$sql failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){
            $p=$row['prm'];
			$c=$row['code'];
			if($j==0){
				$chart_item_group="";
				$chart_item_value="$lg_exam";
			}
			$j++;
			if($chart_item_group!="")
				$chart_item_group=$chart_item_group.",";
			$chart_item_group=$chart_item_group.$p;
			

			$jumpoint=0;$jumsub=0;
			$sql4="select * from exam where stu_uid='$uid' and year='$year' and credit>0 and examtype='$c'";
			$res4=mysql_query($sql4)or die("query failed:".mysql_error());
			while($row4=mysql_fetch_assoc($res4)){
					$point=$row4['point'];
					if(is_numeric($point)){
						$jumpoint=$jumpoint+$point;
						$jumsub++;
					}
			}
			
			if($jumsub>0)
				$xx=round($jumpoint/$jumsub,2);
			else
				$xx=0;
				
			$chart_item_value=$chart_item_value.",";
			$chart_item_value=$chart_item_value.$xx;
}
			
$xml="chart_column1.php?dat=$chart_item_group|$chart_item_value|$chart_note_left|$chart_note_top|$chart_note_bottom|$chart_note_center|$chart_note_right|$chart_decimal_value";
?>

<script language="JavaScript" type="text/javascript">
			<!--
			if (AC_FL_RunContent == 0 || DetectFlashVer == 0) {
				alert("This page requires AC_RunActiveContent.js.");
			} else {
				var hasRightVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
				if(hasRightVersion) { 
					AC_FL_RunContent(
						'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,45,0',
						'width', '100%',
						'height', '230',
						'scale', 'exactFit',
						'salign', 'TL',
						'bgcolor', '#FFFFFF',
						'wmode', 'opaque',
						'movie', 'charts',
						'src', '<?php echo $MYOBJ;?>/charts/charts',
						'FlashVars', 'library_path=<?php echo $MYOBJ;?>/charts/charts_library&xml_source=../xml/graph/<?php  echo "$xml";?>', 
						'id', 'my_chart',
						'name', 'my_chart',
						'menu', 'true',
						'allowFullScreen', 'true',
						'allowScriptAccess','sameDomain',
						'quality', 'high',
						'align', 'middle',
						'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
						'play', 'true',
						'devicefont', 'false'
						); 
				} else { 
					var alternateContent = 'This content requires the Adobe Flash Player. '
					+ '<u><a href=http://www.macromedia.com/go/getflash/>Get Flash</a></u>.';
					document.write(alternateContent); 
				}
			}
			// -->
			</script>
			<noscript>
				<P>This content requires JavaScript.</P>
			</noscript>

</div>

</td>
<td width="25%" >
	<div id="graph" align="center">
<?php 
$chart_note_top="Al-Quran";
$chart_item_group="Progress,Remaining";
$chart_note_center="Coming Soon";

$sql="select count(*) from hafazan_rec where sid=$sid and uid='$uid'";
$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
$row2=mysql_fetch_row($res2);
$jum=$row2[0];
if($jum>=603)
	$jum=603;
$bal=603-$jum;

$s=$row[0];
$v=100-$s;
$chart_item_value="$jum,$bal";
							
$xml="../xml/graph/chart_pie2.php?dat=$chart_item_group|$chart_item_value|$chart_note_left|$chart_note_top|$chart_note_bottom|$chart_note_center|$chart_note_right|$chart_decimal_value";
?>
			<script language="JavaScript" type="text/javascript">
			<!--
			if (AC_FL_RunContent == 0 || DetectFlashVer == 0) {
				alert("This page requires AC_RunActiveContent.js.");
			} else {
				var hasRightVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
				if(hasRightVersion) { 
					AC_FL_RunContent(
						'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,45,0',
						'width', '100%',
						'height', '230',
						'scale', 'exactFit',
						'salign', 'TL',
						'bgcolor', '#FFFFFF',
						'wmode', 'opaque',
						'movie', 'charts',
						'src', '<?php echo $MYOBJ;?>/charts/charts',
						'FlashVars', 'library_path=<?php echo $MYOBJ;?>/charts/charts_library&xml_source=<?php  echo "$xml";?>', 
						'id', 'my_chart',
						'name', 'my_chart',
						'menu', 'true',
						'allowFullScreen', 'true',
						'allowScriptAccess','sameDomain',
						'quality', 'high',
						'align', 'middle',
						'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
						'play', 'true',
						'devicefont', 'false'
						); 
				} else { 
					var alternateContent = 'This content requires the Adobe Flash Player. '
					+ '<u><a href=http://www.macromedia.com/go/getflash/>Get Flash</a></u>.';
					document.write(alternateContent); 
				}
			}
			// -->
			</script>
			<noscript>
				<P>This content requires JavaScript.</P>
			</noscript>

</div>

</td>

</tr>
</table>
</div><!-- end content3 -->
<div id="panelright">
<?php include("../calendar/cal_ewaris.php");?>

<div align="center" style="font-size:12px; padding:2px; font-family:Arial; color:#FFFFFF; background-color:#666666;border: 1px solid #99BBFF;">
		<strong>News &amp; Announcement</strong>
</div>
<div id="myborder" style="height:150px; min-height:150px; overflow-y: auto; background-image:url(../img/bg_panel.jpg)">
<marquee direction="up" width="100%" height="150px" scrollamount="2" onMouseOut="javascript:fast2(this);" onMouseOver="javascript:slow(this);">
<?php
		$sql="select content.* from content where app='NEWS' and  sta=1 and (sid=0 or sid='$xsid') and (access like '%IBUBAPA%' or access like '%PARENT%') order by id desc";	
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        while($row=mysql_fetch_assoc($res)){
				$usr=$row['usr'];
				$msg=htmlspecialchars(stripslashes($row['msg']));
				//$msg=stripslashes($row['msg']);
				$tit=$row['tit'];
				$dt=$row['dt'];
				$f1=$row['file1'];
				$f2=$row['file2'];
				$f3=$row['file3'];
				$xid=$row['id'];
				if(strlen($msg)>100)
					$msg=substr($msg,0,100).".. more...";
					
				$q++;
				$now=date('Y-m-d');
				$date_parts1=explode("-", $dt);
			    $date_parts2=explode("-", $now);
			
			   $start_date=gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
			   $end_date=gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
			   $jumhari= $end_date - $start_date;
			   if($jumhari<4)
			   		$new="<img src=../img/newicon.png>";
				else
					$new="";
				if(($f1!="")||($f2!="")||($f3!=""))
			   		$attach="<img src=../img/attach16.png>";
				else
					$attach="";
?>

		<a href="../adm/news_info.php?id=<?php echo $xid;?>" title="Buletin" rel="gb_page_center[800, 480]" target="_blank">
			<table width="100%" id="myborder" style="font-size:11px;">
			  <tr>
			  	<td><strong><?php echo "$tit $attach $new";?></strong>..read more</td>
			 </tr>
			 <tr>
				<td><?php echo "$dt<br>";?></td>
			  </tr>
			</table>
		</a>

<?php } ?>
	</marquee>
</div>


		</div>
	</div>
	
</div> <!--end content--> 
   <?php include('inc/siteinfo.php');?>
</body>
</html>
