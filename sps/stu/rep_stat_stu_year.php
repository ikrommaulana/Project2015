<?php
$vmod="v5.0.0";
$vdate="100909";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");

verify('ADMIN|AKADEMIK|KEUANGAN');
$username = $_SESSION['username'];
		
	$year=$_POST['year'];
	if($year=="")
		$year=date('Y');
		
	$lvl=$_REQUEST['lvl'];
	if($lvl=="") 
		$lvl=1;
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
	if($sid!=0){
		$sql="select * from sch where id='$sid'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
        $sname=$row['name'];
		$stype=$row['level'];
		$level=$row['clevel'];
		$addr=$row['addr'];
		$state=$row['state'];
		$tel=$row['tel'];
		$fax=$row['fax'];
		$web=$row['url'];
		$school_img=$row['img'];
        mysql_free_result($res);					  
	}
	else
		$level="$lg_year / $lg_level";
	
/** sorting control **/
	$order=$_POST['order'];
	if($order=="")
		$order="desc";
		
	if($order=="desc")
		$nextdirection="asc";
	else
		$nextdirection="desc";
		
	$sort=$_POST['sort'];
	if($sort=="")
		$sqlsort="order by id $order";
	else
		$sqlsort="order by $sort $order, id desc";
		
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="javascript">
	var myWind = ""
	function openchild(w,h,url) {
		if (myWind == "" || myWind.closed || myWind.name == undefined) {
    			myWind = window.open(url,"subWindow","HEIGHT=680,WIDTH=850,scrollbars=yes,status=yes,resizable=yes,left=0,top=0,toolbar")
				//myWind.resizeTo(screen.availWidth,screen.availHeight);
	  	} else{
    			myWind.focus();
  		}

	}
	function processform(operation){
		if(document.myform.sid.value==""){
			alert("Please select school");
			document.myform.sid.focus();
			return;
		}
		document.myform.submit();
		
} 
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include("$MYLIB/inc/myheader_setting.php");?>	

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
	<input type="hidden" name="p" value="rep_stat_stu_year">

<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
</div> <!-- end mymenu -->
<div align="right">
<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
</div>

</div> <!-- end mypanel -->
<div id="story">

<div id="story_title"><?php echo strtoupper($lg_total_student_report);?></div>

<!-- sekolah -->
<table width="100%">
	<tr><td width="100%" valign="top">
		<table width="100%" style="font-size:120% ">
		  <tr>
			<td id="mytabletitle" align="center" width="20%"><?php echo strtoupper($lg_school);?></td>
<?php 	
		$sql="select * from type where grp='session' order by prm";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$session=$row['prm'];
			if($chart_item_group!="")
				$chart_item_group=$chart_item_group.",";
			$chart_item_group=$chart_item_group."$session";
?>
			<td id="mytabletitle" align="center" width="20%"><?php echo $session;?></td>
<?php }?>
		  </tr>
		<?php 
		$sql="select * from sch order by id";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$s=$row['sname'];
			
			$schid=$row['id'];
			$chart_item_avalue[$schid]="$s";
			if(($q++%2)==0)
				$bg="bgcolor=#FAFAFA";
			else
				$bg="";
?>
		<tr <?php echo $bg;?>>
			<td id="myborder" align="center"><?php echo $s;?></td>
<?php
			$sql="select * from type where grp='session' order by prm";
			$res3=mysql_query($sql)or die("query failed:".mysql_error());
			while($row3=mysql_fetch_assoc($res3)){
				$session=$row3['prm'];
			
				$sql="select count(*) from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$schid and ((stu.status=6) || (stu.status=3)) and year='$session'";
				$res2=mysql_query($sql)or die("query failed:".mysql_error());
				$row=mysql_fetch_row($res2);
				$total=$row[0];
				if($total=="")
					$total="0";
				
				$chart_item_avalue[$schid]=$chart_item_avalue[$schid].",$total";//$chart_item_value=$chart_item_value.";$session,$totalstu";
				
				$showgraph=$showgraph+$total;
				//echo "SS:".$chart_item_avalue[$schid];
?>
		 
			<td id="myborder" align="center"><?php echo "$total";?></td>
<?php } ?>
		  </tr>
<?php } ?>

		  <tr>
			<td id="mytabletitle" align="center"><?php echo strtoupper($lg_total);?></td>
<?php

		$chart_item_group="";
		$sql="select * from type where grp='session' order by prm";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$session=$row['prm'];
			if($chart_item_group!="")
				$chart_item_group=$chart_item_group.",$session";
			else
				$chart_item_group="$session";
			$sql="select count(*) from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where year='$session' and ((stu.status=6) || (stu.status=3))";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			$row2=mysql_fetch_row($res2);
			$totalstu=$row2[0];
?>
			<td id="mytabletitle" align="center"><?php echo "$totalstu";?></td>
<?php } ?>
		  </tr>
		</table>

<?php
		$chart_item_value="";
		$sql="select * from sch order by id";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$schid=$row['id'];
			if($chart_item_value!="")
				$chart_item_value=$chart_item_value.";".$chart_item_avalue[$schid];
			else
				$chart_item_value=$chart_item_avalue[$schid];
		}
		
	$xx=number_format($totalstu,'.','',',');
	$chart_note_left="$lg_total_student";
	$chart_note_center="";
	$chart_note_top="";
	$chart_decimal_value=0;
	//$chart_item_group="BAHASA ARAB,BAHASA INGGERIS,BAHASA MELAYU,GEOGRAFI,KEMAHIRAN HIDUP-KEMAHIRAN TEKNIKAL,MATEMATIK,PENDIDIKAN ISLAM,SAINS,SEJARAH";
	//$chart_item_value="2009,1.39,1.61,1.11,1.11,1.54,1.25,1.04,1.50,1.32;2008;2007;2006;2005";
	//$chart_item_group="Tahap 1,Tahap 2,Tahap 3,Tahap 4,Tahap 5";
	//$chart_item_value="2009,30,20;2008;2007;2006;2005";
	
	$xml="chart_mline1.php?dat=$chart_item_group|$chart_item_value|$chart_note_left|$chart_note_top|$chart_note_bottom|$chart_note_center|$chart_note_right|$chart_decimal_value";
	echo $xml;
?>

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
			'width', '80%',
			'height', '300',
			'scale', 'exactFit',
			'salign', 'TL',
			'bgcolor', '#FFFFFF',
			'wmode', 'opaque',
			'movie', 'charts',
			'src', '<?php echo $MYOBJ;?>/charts/charts',
			'FlashVars', 'library_path=<?php echo $MYOBJ;?>/charts/charts_library&xml_source=xml/<?php  echo "$xml";?>', 
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

</div></div>
</form>

</body>
</html>
