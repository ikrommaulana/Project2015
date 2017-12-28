<?php
$vmod="v5.0.0";
$vdate="160910";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR');
	
		$showheader=$_REQUEST['showheader'];
		$showgraph=$_REQUEST['showgraph'];

		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
	
		$exam=$_REQUEST['exam'];
		if($exam!=""){
			$sql="select * from type where grp='exam' and code='$exam'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $examname=$row['prm'];
			$sqlcls="";
		}
		
		$year=$_REQUEST['year'];
		if($year=="")
			$year=date('Y');
		
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$ssname=$row['sname'];
			$simg=$row['img'];
			$namatahap=$row['clevel'];			  
		}
		else{
			$namatahap="Tahap";
		}
		
		$clslevel=$_REQUEST['lvl'];
		$sql="select * from type where sid='$sid' and prm='$clslevel' and grp='classlevel'";
    	$res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		
		$grading=$row['code'];
		$sql="select * from type where sid='$sid' and prm='$clslevel' and grp='classlevel'";
    	$res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$grading=$row['code'];
		
		$sql="select * from grading where name='$grading' order by val desc limit 1";
    	$res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$topgrade=$row['grade'];
		
		$sql="select count(*) from sub where sch_id='$sid' and level='$clslevel' and grptype=0";
    	$res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_row($res);
		$jumlah_sub=$row[0];//not use
		
		$sql="select g1 from examrank where sch_id='$sid' and cls_level='$clslevel' and year='$year' order by g1 desc limit 1";
    	$res2=mysql_query($sql)or die("query failed:".mysql_error());
        $row2=mysql_fetch_row($res2);
		$jumlah_sub=$row2[0];
		if($jumlah_sub=="")
			$jumlah_sub=0;
			
		
		if((strtoupper($namatahap)=="TINGKATAN")&&($clslevel>3))
			$show_a1a2=1;
		else
			$show_a1a2=0;
		
		if($VARIANT=="MDQ")
			$show_a1a2=1;
			
		if($show_a1a2){
			$sql="select g1+g2 as jumsub from examrank where sch_id='$sid' and cls_level='$clslevel' and year='$year' order by jumsub desc limit 1";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			$row2=mysql_fetch_row($res2);
			$jumlah_sub=$row2[0];
			if($jumlah_sub=="")
				$jumlah_sub=0;
			$topgrade="A";
		}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
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

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>

<body>

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="p" value="../examrep/rep_skora_ana">
<input type="hidden" name="sid" value="<?php echo $sid;?>">
<input type="hidden" name="lvl" value="<?php echo $clslevel;?>">
<input type="hidden" name="exam" value="<?php echo $exam;?>">
<input type="hidden" name="year" value="<?php echo $year;?>">

<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
	<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
</div>

<div id="right" align="right">
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		<input type="checkbox" name="showheader" value="1"  onClick="showhide('showheader','')" <?php if($showheader) echo "checked";?>>Show Header
     </div><!-- end panel right -->
	
</div><!-- end panel -->


<div id="story">

<div id="showheader" <?php if(!$showheader) echo "style=\"display:none\"";?> >
	<?php  include('../inc/school_header.php')?>
</div>



<div id="mytitlebg" align="center">
	
	<?php
	if($LG=="BM") 
		echo strtoupper("LAPORAN SKOR $topgrade : PELAJAR $sname");
	else
		echo strtoupper("SCORE $topgrade REPORT : STUDENT $sname");
	?>
</div>

<table width="100%"  cellpadding="2"  cellspacing="0" style="font-size:12px ">
  <tr>
    <td id="mytabletitle" align="center" width="3%"><?php echo strtoupper($lg_no);?></td>
	<td id="mytabletitle" align="center" width="7%"><?php echo strtoupper($lg_code);?></td>
    <td id="mytabletitle" width="20%"><?php echo strtoupper($lg_exam);?></td>
<?php for($i=$jumlah_sub;$i>=0;$i--){?>
	<td id="mytabletitle" align="center" width="10%"><?php echo "$i$topgrade";?></td>
<?php } ?>
   <td id="mytabletitle" align="center" width="5%"><?php echo strtoupper("$lg_total $lg_student");?></td>
  </tr>
 <?php
 		$sql="select count(*) from examrank where sch_id='$sid' and cls_level='$clslevel' and year='$year' and exam='$exam'";
    	$res2=mysql_query($sql)or die("query failed:".mysql_error());
        $row2=mysql_fetch_row($res2);
		$jumlah_stu=$row2[0];
		
		$yy=0;
		$q++;
 ?>
  <tr>
    <td id="myborder" align="center"><?php echo $q;?></td>
	<td id="myborder" align="center"><?php echo $exam;?></td>
    <td id="myborder">
	<?php echo strtoupper("$examname");?>
	</td>
<?php for($i=$jumlah_sub;$i>=0;$i--){
		if($show_a1a2)
			$sql="select count(*) from examrank where sch_id='$sid' and cls_level='$clslevel' and year='$year' and exam='$exam' and (g1+g2)=$i";
		else
			$sql="select count(*) from examrank where sch_id='$sid' and cls_level='$clslevel' and year='$year' and exam='$exam' and g1=$i";
    	$res2=mysql_query($sql)or die("query failed:".mysql_error());
        $row2=mysql_fetch_row($res2);
		$xx=$row2[0];
		$yy=$yy+$xx
?>
	<td id="myborder" align="center">
		
		<?php echo "$xx";?> - <?php if($xx==0) echo "0"; else echo round($xx/$jumlah_stu*100,0);?>%

		</td>
<?php } ?>
    <td id="myborder" align="center"><?php echo $yy;?></td>
  </tr>
</table>

<div id="graph">
<table width="100%">
<tr><td width="50%">
<script language="JavaScript" type="text/javascript">
<!--
if (AC_FL_RunContent == 0 || DetectFlashVer == 0) {
	alert("This page requires AC_RunActiveContent.js.");
} else {
	var hasRightVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
	if(hasRightVersion) { 
		AC_FL_RunContent(
			'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,45,0',
			'width', '95%',
			'height', '300',
			'scale', 'exactFit',
			'salign', 'TL',
			'bgcolor', '#FFFFFF',
			'wmode', 'opaque',
			'movie', 'charts',
			'src', '<?php echo $MYOBJ;?>/charts/charts',
			'FlashVars', 'library_path=<?php echo $MYOBJ;?>/charts/charts_library&xml_source=xml/../examrep/rep_skora_ana_bar.php?<?php echo "dat=$sid|$clslevel|$year|$jumlah_sub|$exam|$topgrade";?>', 
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
</td>
<td  width="50%">
<script language="JavaScript" type="text/javascript">
<!--
if (AC_FL_RunContent == 0 || DetectFlashVer == 0) {
	alert("This page requires AC_RunActiveContent.js.");
} else {
	var hasRightVersion = DetectFlashVer(requiredMajorVersion, requiredMinorVersion, requiredRevision);
	if(hasRightVersion) { 
		AC_FL_RunContent(
			'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,45,0',
			'width', '95%',
			'height', '300',
			'scale', 'exactFit',
			'salign', 'TL',
			'bgcolor', '#FFFFFF',
			'wmode', 'opaque',
			'movie', 'charts',
			'src', '<?php echo $MYOBJ;?>/charts/charts',
			'FlashVars', 'library_path=<?php echo $MYOBJ;?>/charts/charts_library&xml_source=xml/../examrep/rep_skora_ana_pie.php?<?php echo "dat=$sid|$clslevel|$year|$jumlah_sub|$exam|$topgrade";?>', 
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
</td></tr></table>
</div>



</div></div>
</form>
</body>
</html>
