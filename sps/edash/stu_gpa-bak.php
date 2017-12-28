<?php
$vmod="v6.0.0";
$vdate="110916";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("");

	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];

	if($sid==0)
		$sql="select * from sch";
	else
		$sql="select * from sch where id=$sid";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
	$sid=$row['id'];
	$sname=$row['sname'];
	$namatahap=$row['clevel'];
	$issemester=$row['issemester'];
	$startsemester=$row['startsemester'];
	
	$year=$_POST['year'];
	if($year==""){
		$year=date('Y');
		if(($issemester)&&(date('n')<$startsemester))
			$year=$year-1;
	}
	$xx=$year+1;
	if($issemester)
		$sesyear="$year/$xx";	  
	else
		$sesyear="$year";

	$exam=$_POST['exam'];
	if($exam=="")
		$sql="select * from type where grp='exam' and (sid=0 or sid=$sid) order by idx";
	else
		$sql="select * from type where grp='exam' and code='$exam' and (sid=0 or sid=$sid) order by idx";
	
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$examname=$row['prm'];
	$exam=$row['code'];
	
	$cons=$_POST['cons'];
	if($cons=="")
		$sql="select * from type where grp='subtype' and (sid=0 or sid=$sid) order by idx";
	else
		$sql="select * from type where grp='subtype' and prm='$cons' and (sid=0 or sid=$sid) order by idx";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$cons=$row['prm'];
	
	$sqlcons="and sub_grp='$cons'";


?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>


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

<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
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
	<input type="hidden" name="p" value="<?php echo $p;?>">
<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br><?php echo $lg_print;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
	</div>
 	<div align="right">
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
	</div>   
</div>
<!-- end mypanel-->


<div id="mytabletitle" class="printhidden" style="padding:5px 5px 5px 5px;margin:0px 1px 0px 1px;" align="right">

	  <select name="sid" id="sid" onchange="document.myform.exam[0].value='';document.myform.submit();">
        <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select $lg_school -</option>";
			else
                echo "<option value=$sid>$sname</option>";
				
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['sname'];
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
			}							  
			
?>
	</select>
	<select name="year" onChange="document.myform.submit();">
        <?php
            echo "<option value=$year>$lg_session $sesyear</option>";
			$sql="select * from type where grp='session' and prm!='$year' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        $xx=$s+1;
						if($issemester)
								$xsesyear="$s/$xx";
						else
								$xsesyear=$s;
						echo "<option value=\"$s\">$lg_session $xsesyear</option>";
            }				  
?>
    </select>
	<select name="exam" id="exam" onchange="document.myform.submit();">
        <?php	
      				if($exam=="")
						echo "<option value=\"\">- $lg_select $lg_exam -</option>";
					else
						echo "<option value=\"$exam\">$examname</option>";
					$sql="select distinct(code),prm from type where grp='exam' and code!='$exam' and (sid=0 or sid=$sid) order by idx";
            		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $a=$row['prm'];
						$b=$row['code'];
                        echo "<option value=\"$b\">$a</option>";
            		}
            		mysql_free_result($res);	
			?>
      </select>
      <input type="button" name="Submit" value="<?php echo $lg_view;?>" onClick="document.myform.submit()" >

		
</div>
<div id="story">
<table width="100%" id="mytabletitle">
  <tr>
    <td width="50%">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="20%"><?php echo strtoupper($lg_school);?></td>
			<td width="1%">:</td>
			<td><?php echo strtoupper($sname);?></td>
		  </tr>
		  <tr>
			<td width="20%"><?php echo strtoupper($lg_session);?></td>
			<td width="1%">:</td>
			<td><?php echo strtoupper("$sesyear");?></td>
		  </tr>
		</table>
	</td>
    <td width="50%" valign="top">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="20%"><?php echo strtoupper($lg_exam);?></td>
			<td width="1%">:</td>
			<td><?php echo strtoupper($examname);?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_report);?></td>
			<td>:</td>
			<td><?php echo strtoupper($lg_student_performance);?></td>
		  </tr>
		</table>
	
	</td>
  </tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
<?php
	$sql="select distinct(level) from cls where sch_id='$sid'";
	$res10=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row10=mysql_fetch_assoc($res10)){
			$clslevel=$row10['level'];
?>
<td width="15%" >
	<div id="graph" align="center">
<?php 
$chart_note_center="GPA";
$chart_item_value="";
$chart_item_group="";
$chart_note_top="$namatahap $clslevel";

		$sql="select * from type where sid='$sid' and prm='$clslevel' and grp='classlevel'";
        $res=mysql_query($sql)or die("$sql failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$grading=$row['code'];
		$sql="select gp from grading where name='$grading' and gp>=0 order by gp desc";
		$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
		$row3=mysql_fetch_assoc($res3);
		$maxgp=$row3['gp'];
		
		for($i=$maxgp;$i>0;$i--){
				$gp=$i;
				if($gp==1)
					$gpfrom=$gp-1+0.00;
				else
					$gpfrom=$gp-1+0.01;
				
				$tmp="$gpfrom-$gp";
				//echo "$gp-$gpfrom<br>";
				$sqlgpk="and (gpk<='$gp' and gpk>='$gpfrom')";
				//echo "$sqlgpk<br>";
				
				if($chart_item_group!="")
						$chart_item_group=$chart_item_group.",";
				$chart_item_group=$chart_item_group.$tmp;
				
				$sql="select count(*) from examrank	 where sch_id=$sid and year='$year' and cls_level=$clslevel and exam='$exam' $sqlgpk and avg>=0";
				$res4=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				$row4=mysql_fetch_row($res4);
				$x=$row4[0];
				if($chart_item_value!="")
						$chart_item_value=$chart_item_value.",";
				$chart_item_value=$chart_item_value.$x;
				//echo "$clslevel ($sqlgpk):$x<br>";
		}
//$chart_item_group="1,2,3,4,5";
//$chart_item_value="700,800,900,1000,0,0,0,0,0";
$xml="chart_pie1.php?dat=$chart_item_group|$chart_item_value|$chart_note_left|$chart_note_top|$chart_note_bottom|$chart_note_center|$chart_note_right|$chart_decimal_value";
//echo "$xml<br><br>";
//$exam="";
if($exam!=""){
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
						'height', '250',
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
<?php } $newrow++; if($newrow==3) echo "</tr><tr>";} ?>
</tr>
</table>

	
</div></div>
</form>
</body>
</html>