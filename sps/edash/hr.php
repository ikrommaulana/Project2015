<?php
$vmod="v6.0.0";
$vdate="110916";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("");

		$p=$_REQUEST['p'];
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];

		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
		
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
<input type="hidden" name="op" >
<input type="hidden" name="id">
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
	<div id="viewpanel" align="right">
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
	</div>
</div>
<!-- end mypanel-->
<div id="story">
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="33%">
	<div id="graph" align="center">
<?php 
$chart_note_top="";
$chart_note_left="$lg_total $lg_staff";
$chart_note_center="";
//$chart_item_group="Rendah,Menengah,Tahfiz,Tadika";
//$chart_item_value="800,900,1000,1100";
$chart_item_value="$lg_staff";
$chart_item_group="";
$sql="select * from sch order by id";
$res=mysql_query($sql)or die("query failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){
		$s=$row['sname'];
		$id=$row['id'];
		if($chart_item_group!="")
			$chart_item_group=$chart_item_group.",";
		$chart_item_group=$chart_item_group.$s;
		
		$sql="select count(*) from usr where sch_id=$id and status=0";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$t=$row2[0];
		if($chart_item_value!="")
			$chart_item_value=$chart_item_value.",";
		$chart_item_value=$chart_item_value.$t;
}
		$chart_item_group=$chart_item_group.",HQ";
		
		$sql="select count(*) from usr where sch_id=0 and status=0";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$t=$row2[0];
		if($chart_item_value!="")
			$chart_item_value=$chart_item_value.",";
		$chart_item_value=$chart_item_value.$t;

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

<td width="33%" >
	<div id="graph" align="center">
<?php 
$chart_note_top="";
$chart_note_left="$lg_staff $year";
$chart_note_center="";
//$chart_item_group="Rendah,Menengah,Tahfiz,Tadika";
//$chart_item_value="800,900,1000,1100";

$chart_item_value="";
$chart_item_group="";

$totalstu=$lg_male;
$totalonline=$lg_female;
$sql="select id,sname from sch order by id";
$res=mysql_query($sql)or die("query failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){
		$s=$row['sname'];
		$x=$row['id'];
		if($chart_item_group!="")
			$chart_item_group=$chart_item_group.",";
		$chart_item_group=$chart_item_group.$s;
		
		$sql="select count(*) from usr where sch_id='$x' and status=0 and sex=1";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$t=$row2[0];
		if($totalstu!="")
			$totalstu=$totalstu.",";
		$totalstu=$totalstu.$t;
		
		$sql="select count(*) from usr where sch_id='$x' and status=0 and sex=0";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$t=$row2[0];
		
		if($totalonline!="")
			$totalonline=$totalonline.",";
		$totalonline=$totalonline.$t;
}

$chart_item_value="$totalstu;$totalonline";
							
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


<td width="33%">

	<div id="graph" align="center">
<?php 
$chart_note_top="";
$chart_note_left="$lg_division";
$chart_note_center="";
//$chart_item_group="Rendah,Menengah,Tahfiz,Tadika";
//$chart_item_value="800,900,1000,1100";
$chart_item_value="$lg_staff";
$chart_item_group="";
$sql="select * from type where grp='jobdiv' order by prm";
$res=mysql_query($sql)or die("query failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){
		$s=$row['prm'];
		if($chart_item_group!="")
			$chart_item_group=$chart_item_group.",";
		
		$s=addslashes($s);
		$x=str_replace('&','',$s);
		
		$chart_item_group=$chart_item_group.$x;
		
		$sql="select count(*) from usr where jobdiv='$s' and status=0";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$t=$row2[0];
		if($chart_item_value!="")
			$chart_item_value=$chart_item_value.",";
		$chart_item_value=$chart_item_value.$t;
}
	

$xml="chart_column1.php?dat=$chart_item_group|$chart_item_value|$chart_note_left|$chart_note_top|$chart_note_bottom|$chart_note_center|$chart_note_right|$chart_decimal_value";
//echo $xml;
//exit;
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

</tr>
<tr>
<td width="33%">
	<div id="graph" align="center">
<?php 
$chart_note_top="";
$chart_note_left="$lg_total $lg_country";
$chart_note_center="";
//$chart_item_group="Rendah,Menengah,Tahfiz,Tadika";
//$chart_item_value="800,900,1000,1100";
$chart_item_value="";
$chart_item_group="";
$sql="select distinct(citizen) from usr order by citizen";
$res=mysql_query($sql)or die("query failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){
		$s=$row['citizen'];
		if($chart_item_group!="")
			$chart_item_group=$chart_item_group.",";
		$chart_item_group=$chart_item_group.$s;
		
		$sql="select count(*) from usr where citizen='$s' and status=0";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$t=$row2[0];
		if($chart_item_value!="")
			$chart_item_value=$chart_item_value.",";
		$chart_item_value=$chart_item_value.$t;
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

<td width="33%" >
	<div id="graph" align="center">
<?php 
$chart_note_top="";
$chart_note_left="$lg_job $lg_status";
$chart_note_center="";
//$chart_item_group="Rendah,Menengah,Tahfiz,Tadika";
//$chart_item_value="800,900,1000,1100";
$chart_item_value="$lg_staff";
$chart_item_group="";
$sql="select prm from type where grp='jobsta' order by prm";
$res=mysql_query($sql)or die("query failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){
		$s=$row['prm'];
		if($chart_item_group!="")
			$chart_item_group=$chart_item_group.",";
		$chart_item_group=$chart_item_group.$s;
		
		$sql="select count(*) from usr where jobsta='$s' and status=0";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$t=$row2[0];
		if($chart_item_value!="")
			$chart_item_value=$chart_item_value.",";
		$chart_item_value=$chart_item_value.$t;
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


<td width="33%" >
	<div id="graph" align="center">
<?php 
$chart_note_top="";
$chart_note_left=$lg_parent_online;
$chart_note_center="";
$chart_item_value="";
$chart_item_group="";

$totalstu="$lg_total $lg_parent";
$totalonline=$lg_parent_online;
$sql="select id,sname from sch order by id";
$res=mysql_query($sql)or die("query failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){
		$s=$row['sname'];
		$x=$row['id'];
		if($chart_item_group!="")
			$chart_item_group=$chart_item_group.",";
		$chart_item_group=$chart_item_group.$s;
		
		$sql="select count(distinct(p1ic)) from stu where sch_id='$x' and status=6";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$t=$row2[0];
		if($totalstu!="")
			$totalstu=$totalstu.",";
		$totalstu=$totalstu.$t;
		
		$sql="select count(*) from stu where sch_id='$x' and status=6 and plogin is not null";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$t=$row2[0];
		
		if($totalonline!="")
			$totalonline=$totalonline.",";
		$totalonline=$totalonline.$t;
}
$chart_note_center="";
$chart_note_left=$lg_parent_online;
$chart_item_value="$totalstu;$totalonline";
							
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

</tr>
</table>

	
</div></div>
</form>
</body>
</html>