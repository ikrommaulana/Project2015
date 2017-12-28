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
	
		$examcode=$_REQUEST['exam'];
		if($examcode!=""){
			$sql="select * from type where grp='exam' and code='$examcode'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $examname=$row['prm'];
			$sqlcls="";
		}
		
		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
		$sqlyear="and year='$year'";
		
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
		
		$clslevel=$_REQUEST['clslevel'];

		$sql="select * from cls where sch_id='$sid' and code='$clscode'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$clsname=$row['name'];

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
<script language="JavaScript">
function processform(operation){
		if(document.myform.exam.value==""){
			alert("Please select exam");
			document.myform.exam.focus();
			return;
		}
		if(document.myform.sid.value=="0"){
			alert("Please select school");
			document.myform.sid.focus();
			return;
		}
		if(document.myform.clslevel.value=="0"){
			alert("Please select level");
			document.myform.clslevel.focus();
			return;
		}
		document.myform.clscode.value="";
		document.myform.update_result.value="1";
		document.myform.submit();
}
function myform_clear_print(){
	document.myform.action="";
	document.myform.target="";
    document.myform.isprint.value="";
}
function showheader(ele){
	 if(ele.checked==true)
     	show('showheader');
     else
     	hide('showheader');
}
</script>

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

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>

<body>

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="p" value="../examrep/rep_skora_exam">

<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>	
	<a href="#" onClick="showhide('tipsdiv')" id="mymenuitem"><img src="../img/help22.png"><br>HowTo</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
</div>
	<div align="right"  ><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br></div>
	</div> <!-- end mypanel -->
	<div id="mytabletitle" class="printhidden" align="right" >
		<a href="#" title="<?php echo $vdate;?>"></a><br><br>

		<select name="year" onchange="document.myform.submit();">
<?php
            echo "<option value=$year>$lg_session $year</option>";
			$sql="select * from type where grp='session' and prm!='$year' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        echo "<option value=\"$s\">$lg_session $s</option>";
            }				  
?>
          </select>
		   <select name="sid" id="sid"  onchange="document.myform.clslevel[0].value='';document.myform.submit();">
                <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_school -</option>";
			else
                echo "<option value=$sid>$ssname</option>";
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
		<select name="clslevel" id="clslevel" onchange="document.myform.submit();">
<?php    
		if($clslevel=="")
            	echo "<option value=\"\">- $lg_level -</option>";
		else
			echo "<option value=$clslevel>$namatahap $clslevel</option>";
			$sql="select * from type where grp='classlevel' and sid='$sid' and prm!='$clslevel' order by prm";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=$s>$namatahap $s</option>";
            }

?>		
  		</select>
		<input type="button" onClick="document.myform.submit();" value="View">
		<br>
		<input type="checkbox" name="showgraph" value="1"  onClick="document.myform.submit();" <?php if($showgraph) echo "checked";?>>Show Graph 
		<input type="checkbox" name="showheader" value="1"  onClick="showhide('showheader','')" <?php if($showheader) echo "checked";?>>Show Header
	
</div><!-- end panel -->

<div id="story">

<div id="showheader" <?php if(!$showheader) echo "style=\"display:none\"";?> >
	<?php  include('../inc/school_header.php')?>
</div>

<div id="mytitlebg">
<?php if($LG=="BM"){?> LAPORAN SKOR A PELAJAR : <?php echo strtoupper($sname); }else{ ?> SCORE A REPORT : <?php echo strtoupper($sname); } ?>
</div>

<div id="tipsdiv" style="display:none ">
<?php if($LG=="BM"){?>
	Tips:<br>
	1. Pilih tahun /  sesi peperiksaan yang hendak dipaparkan<br>
	2. Tick pada graph untuk memaparkan graph. Graph boleh disetkan fullscreen mode<br>
	3. Klik pada nama peperikaan untuk zoom analisa peperiksaan tersebut<br>
	4. Klik pada jumlah/peratus pelajar untuk melihat senarai pelajar tersebut<br>
<?php }else {?>
	Tips:<br>
	1. Select session/year of exam<br>
	2. Tick at graph to show graph analysis<br>
	3. Click at exam name to view detail exam analysis<br>
	4. Click at number of total/percent to view the student<br>
<?php } ?>
</div>

<table width="100%"  cellpadding="2"  cellspacing="0">
  <tr>
    <td id="mytabletitle" align="center" width="3%"><?php echo strtoupper($lg_no);?></td>
    <td id="mytabletitle" width="20%"><?php echo strtoupper($lg_exam);?></td>
<?php for($i=$jumlah_sub;$i>=0;$i--){?>
	<td id="mytabletitle" align="center" width="3%">
	<?php 
		echo "$i$topgrade";
		if($chart_item_group!="")
			$chart_item_group=$chart_item_group.",";
		$chart_item_group=$chart_item_group."$i $topgrade";
	?>
	</td>
<?php } ?>
   <td id="mytabletitle" align="center" width="4%"><?php echo strtoupper("$lg_total $lg_student");?></td>
  </tr>
 <?php
 	$sql="select * from type where grp='exam' and val=1 and (lvl=0 or lvl='$clslevel') and (sid=0 or sid=$sid) order by idx";
    $res=mysql_query($sql)or die("$sql query failed:".mysql_error());
    while($row=mysql_fetch_assoc($res)){
    	$examname=$row['prm'];
		$exam=$row['code'];
		
		if($chart_item_value!="")
			$chart_item_value=$chart_item_value.";";
		$chart_item_value=$chart_item_value.$examname;
	
		$sql="select count(*) from examrank where sch_id='$sid' and cls_level='$clslevel' and year='$year' and exam='$exam'";
    	$res2=mysql_query($sql)or die("query failed:".mysql_error());
        $row2=mysql_fetch_row($res2);
		$jumlah_stu=$row2[0];
		
		$yy=0;
		if(($q++%2)==0)
			$bg="";
		else
			$bg="";
		
 ?>
  <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
    <td id="myborder" align="center"><?php echo $q;?></td>
    <td id="myborder">
		<a href="../examrep/rep_skora_ana.php?<?php echo "sid=$sid&year=$year&lvl=$clslevel&exam=$exam";?>" title="Detail Exam" target="_blank" rel="gb_page_center[1000, 480]">
	<?php echo strtoupper("$examname");?>
	</a>
	</td>
<?php for($i=$jumlah_sub;$i>=0;$i--){
	if($show_a1a2)
		$sql="select count(*) from examrank where sch_id='$sid' and cls_level='$clslevel' and year='$year' and exam='$exam' and (g1+g2)=$i";
	else
		$sql="select count(*) from examrank where sch_id='$sid' and cls_level='$clslevel' and year='$year' and exam='$exam' and g1=$i";
    	$res2=mysql_query($sql)or die("query failed:".mysql_error());
        $row2=mysql_fetch_row($res2);
		$xx=$row2[0];
		$yy=$yy+$xx;
		
		if($chart_item_value!="")
			$chart_item_value=$chart_item_value.",";
		$chart_item_value=$chart_item_value.$xx;
?>
	<td id="myborder" align="center">
		<a href="../examrep/rep_skora_stu.php?<?php echo "sid=$sid&year=$year&lvl=$clslevel&exam=$exam&skora=$i";?>" title="Student A" target="_blank" rel="gb_page_center[800, 480]">
		<?php echo "$xx";?>-<?php if($xx==0) echo "0"; else echo round($xx/$jumlah_stu*100,0);?>%
		</a>
		</td>
<?php } ?>
    <td id="myborder" align="center"><?php echo $yy;?></td>
  </tr>
<?php } ?>
</table>

<br>
<?php 
	if($LG=="BM")
		$chart_note_left="Bil. Pelajar A";
	else
		$chart_note_left="Total Student A";
	$chart_note_center=$year;
	$chart_decimal_value=0;
	
	/**
		$chart_item_group=BAHASA ARAB,BAHASA INGGERIS,BAHASA MELAYU,GEOGRAFI,KEMAHIRAN HIDUP-KEMAHIRAN TEKNIKAL,MATEMATIK,PENDIDIKAN ISLAM,SAINS,SEJARAH
		$chart_item_value=2009,1.39,1.61,1.11,1.11,1.54,1.25,1.04,1.50,1.32;2008;2007;2006;2005
	**/
	$xml="chart_column1.php?dat=$chart_item_group|$chart_item_value|$chart_note_left|$chart_note_top|$chart_note_bottom|$chart_note_center|$chart_note_right|$chart_decimal_value";
	//echo $xml;
	if($showgraph){

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
			'height', '400',
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
<?php } ?>


</div></div>
</form>
</body>
</html>
