<?php
$vmod="v6.0.0";
$vdate="110916";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
//verify("");

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
	
	$sesyear=$_POST['year'];
	if($sesyear==""){
		$sql="select * from type where grp='session' order by val desc";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
       	$row=mysql_fetch_assoc($res);
        $sesyear=$row['prm'];

	}


?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>


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
<!--
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
 -->
	<select name="year" onChange="document.myform.submit();">
        <?php
            echo "<option value=$year>$lg_session $sesyear</option>";
			$sql="select * from type where grp='session' and prm!='$year' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
			echo "<option value=\"$s\">$s</option>";
            }				  
?>
    </select>
	<input type="button" name="Submit" value="<?php echo $lg_view;?>" onClick="document.myform.submit()" >

		
</div>
<div id="story">

<table width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="33%" >
	<div id="graph" align="center">
<?php 
$chart_note_center="$sesyear";
$chart_note_top="";
$strfail=$lg_fail;
$strpass=$lg_pass;

$chart_item_group="";//"Besut,K.Terengganu,K.Berang,Dungun,Kemaman";
$sql="select * from sch order by id";
$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){
		$sc=$row['sname'];
		if($chart_item_group!="")
			$chart_item_group=$chart_item_group.",";
		$chart_item_group=$chart_item_group. rawurlencode($sc);
}
//$chart_note_left="1230 Permohonan";
$sql="select count(*) from stureg where sesyear='$sesyear' and isdel=0";
$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
$row=mysql_fetch_row($res);
$total=$row[0];
$chart_note_left="Jumlah Pendaftar - $total";

//$chart_item_value="Lelaki,10,11,12,10,5;Perempuan,5,4,9,10,20";
$totalmale=$lg_male;
$totalgirl=$lg_female;
$sql="select id,sname from sch order by id";
$res=mysql_query($sql)or die("query failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){
		$s=$row['sname'];
		$x=$row['id'];
		
		$sql="select count(*) from stureg where sch_id='$x' and sesyear='$sesyear' and sex=1 and isdel=0";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$t=$row2[0];
		if($totalmale!="")
			$totalmale=$totalmale.",";
		$totalmale=$totalmale.$t;
		
		$sql="select count(*) from stureg where sch_id='$x' and sesyear='$sesyear' and sex=0 and isdel=0";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$t=$row2[0];
		
		if($totalgirl!="")
			$totalgirl=$totalgirl.",";
		$totalgirl=$totalgirl.$t;
}

$chart_item_value="$totalmale;$totalgirl";

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
						'height', '330',
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
$chart_note_left="Jumlah Pendaftar";
$chart_item_group="";//"Rendah,Menengah,Tahfiz,Tadika";
$sql="select * from state order by name";
$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){
		$sc=$row['name'];
		if($chart_item_group!="")
			$chart_item_group=$chart_item_group.",";
		$chart_item_group=$chart_item_group. rawurlencode($sc);
}
//$chart_item_value="Provinsi,53,27,38,27,28,39,78,93,92,10,38,39,30,28,49,19,17";
$chart_item_value="Asal Pendaftar";
$sql="select * from state order by name";
$res=mysql_query($sql)or die("query failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){
		$s=$row['name'];
		$sql="select count(*) from stureg where sesyear='$sesyear' and state='$s' and isdel=0";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$t=$row2[0];
		if($chart_item_value!="")
			$chart_item_value=$chart_item_value.",";
		$chart_item_value=$chart_item_value. rawurlencode($t);
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
						'height', '330',
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
<td>


<div id="graph" align="center">
<?php 
$chart_note_top="";
$chart_note_left="Jumlah Siswa";
$chart_item_group="";//"Rendah,Menengah,Tahfiz,Tadika";
$sql="select * from type where grp='salary' order by val";
$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){
		$sc=$row['prm'];
		if($chart_item_group!="")
			$chart_item_group=$chart_item_group.",";
		$chart_item_group=$chart_item_group. rawurlencode($sc);
}
//$chart_item_value="Negeri,53,27,38,27,28,39,78,93,92,10,38,39,30,28,49,19,17";
$chart_item_value="Pendapatan Keluarga";
$sql="select * from type where grp='salary' order by val";
$res=mysql_query($sql)or die("query failed:".mysql_error());
$num=mysql_num_rows($res);
$min=-1;
$i=1;
while($row=mysql_fetch_assoc($res)){
		$max=$row['val'];
		if(($i++)<$num)
			$sql="select count(*) from stureg where sesyear='$sesyear' and (totalsal<=$max and totalsal>$min) and isdel=0";
		else
			$sql="select count(*) from stureg where sesyear='$sesyear' and (totalsal>=$max) and isdel=0";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$t=$row2[0];
		if($chart_item_value!="")
			$chart_item_value=$chart_item_value.",";
		$chart_item_value=$chart_item_value. rawurlencode($t);
		$min=$max;
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
						'height', '330',
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