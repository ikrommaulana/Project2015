<?php
$vmod="v5.2.0";
$vdate="110401";
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
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<a href="#" onClick="document.myform.id.value='';document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
	</div>
 	<div align="right">
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
	</div>   
</div>
<!-- end mypanel-->
<div id="story" style="border:none ">

<div id="mytabletitle" align="right" class="printhidden">

	  <select name="sid" id="sid" onchange="document.myform.exam[0].value='';document.myform.cons[0].value='';document.myform.submit();">
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
	  

   
      <input type="button" name="Submit" value="View" onClick="document.myform.submit()" >

		
</div>
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
			<td><?php echo strtoupper("$lg_pass $lg_percentage");?>
			</td>
		  </tr>
		</table>
	
	</td>
  </tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="100%" >
	<div id="graph" align="center">
<?php
$xml="polar1.php?dat=$chart_item_group|$chart_item_value|$chart_note_left|$chart_note_top|$chart_note_bottom|$chart_note_center|$chart_note_right|$chart_decimal_value";
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
						'width', '850',
						'height', '450',
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
</tr>
</table>

	
</div></div>
</form>
</body>
</html>