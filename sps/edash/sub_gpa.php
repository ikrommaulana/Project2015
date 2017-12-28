<?php
//101215 - patch subjek kosong
//110108 - tambah compare by exam
$vmod="v6.0.0";
$vdate="110916";
//include_once('../etc/db.php');
//include_once('../etc/session.php');
include_once('../etc/config.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("");

	$sid=$_REQUEST['sid'];
	if($sid=="") $sid=$_SESSION['sid'];
	if($sid==0)
		$sql="select * from sch";
	else
		$sql="select * from sch where id=$sid";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
	$sid=$row['id'];
	$sname=stripcslashes($row['sname']);
	$namatahap=$row['clevel'];
	$issemester=$row['issemester'];
	$startsemester=$row['startsemester'];
	
	$year=$_POST['year'];
	if($year==""){
		$year=date('Y');
		$xx=$year+1;
		$sesyear="$year/$xx";
	}else
		$sesyear="$year";
	
	
	$xclslevel=$_POST['clslevel'];
	if($xclslevel=="")
		$xclslevel=1;
		
	$clslevel=$xclslevel;
	$sqlclslevel="and level='$clslevel'";
	$sql="select * from type where sid='$sid' and prm='$clslevel' and grp='classlevel'";
   	$res=mysql_query($sql)or die("query failed:".mysql_error());
      $row=mysql_fetch_assoc($res);
	$grading=$row['code'];

	$clscode=$_POST['clscode'];
	if($clscode!=""){
			$sqlclscode="and cls_code='$clscode'";
			$sql="select * from cls where sch_id=$sid and code='$clscode'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=$row['name'];
			$clslevel=$row['level'];
			$sqlclslevel="and level='$clslevel'";
	}
		
	$sql="select * from ses_cls where year='$year' and cls_code='$clscode' and sch_id=$sid ";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$row2=mysql_fetch_assoc($res2);
	$gurukelas=$row2['usr_name'];
		
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

<script language="JavaScript">
function processform(operation){
		if((document.myform.sid.value=="")||(document.myform.sid.value==0)){
			alert("Select school..");
			document.myform.sid.focus();
			return;
		}
		if(document.myform.clslevel.value==""){
			alert("Select level..");
			document.myform.clslevel.focus();
			return;
		}
		if(document.myform.exam.value==""){
			alert("Select exam..");
			document.myform.exam.focus();
			return;
		}
		document.myform.submit();
		
}

</script>

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
	</div> <!-- end mymenu -->
	<div id="viewpanel" align="right">
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br>
<select name="sid" id="sid" onchange="document.myform.clslevel[0].value='';document.myform.year[0].value='';document.myform.exam[0].value='';document.myform.cons[0].value='';document.myform.submit();">
        <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select $lg_school -</option>";
			else
                echo "<option value=$sid>$sname</option>";
				
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' and id>0 order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=stripcslashes($row['sname']);
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
				mysql_free_result($res);
			}							  
			
?>
	</select>
	<select name="year" onChange="document.myform.submit();">
        <?php
            echo "<option value=$sesyear>$sesyear</option>";
			$sql="select * from type where grp='session' and prm!='$sesyear' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
						echo "<option value=\"$s\">$s</option>";
            }				  
?>
    </select>
	<select name="clslevel" id="clslevel" onchange="document.myform.submit();">
               <?php    
		if($xclslevel=="")
            	echo "<option value=\"\">- $lg_select $lg_level -</option>";
		else
			echo "<option value=$clslevel>$namatahap $clslevel</option>";
		$sql="select * from type where grp='classlevel' and sid='$sid' and prm!='$clslevel' order by prm";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        while($row=mysql_fetch_assoc($res)){
        	$s=$row['prm'];
            echo "<option value=$s>$namatahap $s</option>";
        }
		//if($clslevel!="")
        	//echo "<option value=\"\">- Semua Tahap -</option>";
?>
	</select>

	   <select name="exam" id="exam" onchange="document.myform.submit();">
        <?php	
      				if($exam=="")
						echo "<option value=\"\">- $lg_select $lg_exam -</option>";
					else
						echo "<option value=\"$exam\">$examname</option>";
					$sql="select * from type where grp='exam' and code!='$exam' and (lvl=0 or lvl=$clslevel) and (sid=0 or sid=$sid) order by idx";
            		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $a=$row['prm'];
						$b=$row['code'];
                        echo "<option value=\"$b\">$a</option>";
            		}
            		mysql_free_result($res);	
			?>
      </select>
	   <select name="cons" id="cons" onchange="document.myform.submit();">
         <?php	
      				if($cons=="")
						echo "<option value=\"\">- $lg_select $lg_group $lg_subject -</option>";
					else
						echo "<option value=\"$cons\">$cons</option>";
					$sql="select * from type where grp='subtype' and prm!='$cons' and (sid=0 or sid=$sid) and (lvl=0 or lvl='$clslevel') order by idx";
            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=$row['prm'];
                        echo "<option value=\"$b\">$b</option>";
            		}
			?>
       </select>

      <input type="button" name="Submit" value="<?php echo $lg_view;?>" onClick="processform()" >
	</div>
</div><!-- end mypanel -->


<div id="story">
<table width="100%" id="mytitlebg">
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
			<td><?php echo strtoupper("$namatahap $clslevel : $sesyear");?></td>
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
			<td><?php echo strtoupper("$lg_subject_analisys");?>
			</td>
		  </tr>
		</table>
	
	</td>
  </tr>
</table>



<table width="100%" cellspacing="0" cellpadding="0">
<tr>
<?php 
	$total_pass=0;$total_fail=0;
	$sql="select distinct(sub_code) from ses_sub where sch_id=$sid and year='$year' and cls_level=$clslevel $sqlclscode $sqlcons $sqlsort";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
			$chart_item_value="";
			$chart_item_group="";
			$chart_note_bottom="";
			$sc=$row['sub_code'];
			$sql="select * from sub where code='$sc' and level=$clslevel";
			$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			$row2=mysql_fetch_assoc($res2);
			$grading=$row2['grading'];
			$sn=$row2['name'];
			//echo "$sc:$sn:<br>";
			$chart_note_top=$sn;
			$gp=0;$totalgp=0;$totalstu=0;
			$sql="select * from grading where name='$grading' and val>=0 order by val desc";
			$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			while($row2=mysql_fetch_assoc($res2)){
					$p=$row2['grade'];
					$gp=$row2['gp'];
					$isfail=$row2['sta'];
					$sql="select count(*) from exam where sch_id=$sid and year='$year' and cls_level=$clslevel $sqlclscode and sub_code='$sc' and examtype='$exam' and grade='$p'";
					$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					$row3=mysql_fetch_row($res3);
					$x=$row3[0];
					$totalgp=$totalgp+($x*$gp);
					$totalstu=$totalstu+$x;
					if($isfail)
						$total_fail=$total_fail+$x;
					else
						$total_pass=$total_pass+$x;
					//echo "$p:$x<br>";
					
					if($chart_item_group!="")
						$chart_item_group=$chart_item_group.",";
					//$chart_item_group=$chart_item_group. urlencode("$p");
					$chart_item_group=$chart_item_group. str_replace("+","*","$p");
					if($chart_item_value!="")
						$chart_item_value=$chart_item_value.",";
					$chart_item_value=$chart_item_value.$x;
					

		 	} 
			if($totalstu>0)
				$chart_note_bottom=sprintf("GPA %0.2f",$totalgp/$totalstu);
			$xml="chart_pie2.php?dat=$chart_item_group|$chart_item_value|$chart_note_left|$chart_note_top|$chart_note_bottom|$chart_note_center|$chart_note_right|$chart_decimal_value";
//echo "$xml<br>";
?>
<td width="15%">
<?php //echo "$sc:$sn:<br>"; ?>
<div id="graph" align="center">
		
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
						'height', '220',
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
<?php $newrow++; if($newrow==4){ $newrow=0; echo "</tr><tr>";}} ?>


</tr>
</table>
</div></div>


</form>

</body>
</html>

<!--
v2.7
27/11/08: Gui
v2.6
15/11/08: fixed percent culculation
13/11/08: update interface
Author: razali212@yahoo.com
-->