<?php
include_once('../etc/db.php');
include_once('session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify();

$uid=$_SESSION['uid'];
$sid=$_SESSION['sid'];
	
$sql="select * from type where grp='openexam' and prm='EPEPERIKSAAN' and sid='$sid'";
$res=mysql_query($sql)or die("query failed:".mysql_error());
$row=mysql_fetch_assoc($res);
$sta=$row['val'];
if($sta!="1")
	echo "<script language=\"javascript\">location.href='p.php?p=close'</script>";
	

$year=$_REQUEST['year'];
if($year=="")
		$year=date("Y");


		
		$clslevel=0;
		$sql="select * from ses_stu where stu_uid='$uid' and year='$year' and sch_id=$sid";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		if($row=mysql_fetch_assoc($res)){
			$clsname=$row['cls_name'];
			$clscode=$row['cls_code'];
			$clslevel=$row['cls_level'];
		}
		$sql="select * from type where sid='$sid' and prm='$clslevel' and grp='classlevel'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$grading=$row['code'];
		
		$exam=$_REQUEST['exam'];
		$sql="select * from type where grp='exam' and code='$exam'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $examname=$row['prm'];
			
		//check guru kelas for this subject
		$sql="select count(*) from ses_cls where sch_id=$sid and cls_code='$clscode' and usr_uid='$username' and year='$year'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$gurukelas=$row2[0];
		
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>	

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

</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<input type="hidden" name="p" value="ana">

<div id="panelleft">
	<?php include('inc/lmenu.php');?>
</div> 
<div id="content2">
 <div align="center" style="font-size:14px; font-weight:bold; padding:2px; font-family:Arial; color:#FFFFFF; background-image:url(../img/bg_title_lite.jpg);border: 1px solid #99BBFF;">
		<?php echo strtoupper($name);?>
</div>
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
		<a href="#" onClick="location.href='p.php?p=exam'" id="mymenuitem"><img src="../img/exam.png"><br>Result</a>
	</div>
	
	<div align="right">
<select name="year" onchange="document.myform.submit();">
<?php
            echo "<option value=$year>$lg_session $year</option>";
			$sql="select * from ses_stu where stu_uid='$uid' and sch_id='$sid' and year!='$year' order by year desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $y=$row['year'];
                        echo "<option value=\"$y\">$lg_session $y</option>";
            }
            mysql_free_result($res);					  
?>
 </select>
<input name="button" type="button" value="View" onClick="document.myform.submit();">
</div>
</div><!-- end mypanel -->

<div id="story" >


<?php
		$gidx=0;$data_val[0]=0;
		$sql="select * from type where grp='subtype' and val=0 and code='1' order by idx";
		$res=mysql_query($sql)or die("query failed:".mysql_error());

		while($row=mysql_fetch_assoc($res)){
			$subgrp=strtoupper($row['prm']);
			$chart_note_center="";
			$chart_note_left="$year";
			$chart_item_group="";//"2007,2008,2009,2010,2011";
			$chart_item_value="";//"Lelaki,300,350,400,440,500;Perempuan,380,390,480,490,580;Jumlah,700,800,900,1000,1100";
			$EMPTY=0;
			echo "<div id=mytitle  style=\"background-color:#CCCCFF; border:1px solid #666666;\">$subgrp </div>";
			$plotidx=0;
			$sql="select * from type where grp='exam' and (lvl=0 or lvl=$clslevel) and (sid=0 or sid=$sid) and etc=1 order by idx";
            $res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			while($row2=mysql_fetch_assoc($res2)){
					$examcode=$row2['code'];
					$examname=$row2['prm'];
					$i=0;
					$s=str_replace("&"," ",$examname);
					if($chart_item_value!="")
						$chart_item_value=$chart_item_value.";";
					$chart_item_value=$chart_item_value. $s;
					$chart_item_group="";
					$dataxname="";
					$sql="select sub_code,sub_name from ses_sub where year='$year' and sch_id=$sid and cls_code='$clscode' and sub_grp='$subgrp' and credit>0";
					$res3=mysql_query($sql)or die("query failed:".mysql_error());
					$num=mysql_num_rows($res3);
					if($num==0)
						$EMPTY=1;
					while($row3=mysql_fetch_assoc($res3)){
							$sn=$row3['sub_name'];
							$sn=str_replace("&","and",$sn);
							$sc=$row3['sub_code'];
							if($chart_item_group!="")
								$chart_item_group=$chart_item_group.",";
							$chart_item_group=$chart_item_group	. rawurlencode($sn);
					
							$point=0;
							$sql="select point,examtype from exam where stu_uid='$uid' and year='$year' and sch_id=$sid and cls_code='$clscode' and sub_code='$sc' and examtype='$examcode'";
							$res4=mysql_query($sql)or die("query failed:".mysql_error());
							if($row4=mysql_fetch_assoc($res4))
								$point=$row4['point'];
							else
								$point=0;
							if(!is_numeric($point))
								$point=0;
							
							if($chart_item_value!="")
								$chart_item_value=$chart_item_value.",";
							$chart_item_value=$chart_item_value.$point;
					
							$dataxname=$dataxname."$sn($sc)&nbsp;&nbsp;&nbsp;";
							$i++;
					}//while subject
			}
			
			$xml="chart_column2.php?dat=$chart_item_group|$chart_item_value|$chart_note_left|$chart_note_top|$chart_note_bottom|$chart_note_center|$chart_note_right|$chart_decimal_value|0";
?>

<table width="100%" bgcolor="#666666">
<tr><td width="100%" bgcolor="#FFFFFF">

<?php if(!$EMPTY) { ?>

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
						'height', '300',
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
			
<?php } ?>
			
			</td></tr>

</table>
<br>
<?php 
}// while group
?>



</div> <!-- story -->
</div> <!-- content -->
</form>

</body>
</html>
