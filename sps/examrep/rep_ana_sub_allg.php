<?php
//110627 - sort by clsrank
//110913 - patch case & 
$vmod="v6.0.0";
$vdate="110913";

include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
	
	verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN|HEP|HEP-OPERATOR');
	$username=$_SESSION['username'];
	$showheader=$_REQUEST['showheader'];
	$sid=$_REQUEST['sid'];
	
	
	if($sid=="")
		$sid=$_SESSION['sid'];
	$namatahap="Tahap";
	if($sid!=0){
		$sql="select * from sch where id=$sid";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=$row['name'];
		$slevel=$row['level'];
		$simg=$row['img'];
		$namatahap=$row['clevel'];
     	mysql_free_result($res);					  
	}else
		$sname="Semua $lg_sekolah";

	$view=$_REQUEST['view'];
	if($view=="")
		
		$view=$lg_average_mark;
		
	$clslevel=$_REQUEST['clslevel'];
	if($clslevel!=""){
		$sqlclslevel="and level='$clslevel'";
		$sqlclslevel="and cls_level='$clslevel'";
		$sql="select * from type where sid='$sid' and prm='$clslevel' and grp='classlevel'";
    	$res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$grading=$row['code'];
	}else{
		$clslevel=0;
	}
	$clscode=$_REQUEST['clscode'];
	if($clscode!=""){
			$sqlclscode="and cls_code='$clscode'";
			$sql="select * from cls where sch_id=$sid and code='$clscode'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=$row['name'];
			$clslevel=$row['level'];
			$sqlclslevel="and level='$clslevel'";
	}
	$year=$_REQUEST['year'];
	if($year=="")
		$year=date('Y');
		
	$sql="select * from ses_cls where year='$year' and cls_code='$clscode' and sch_id=$sid ";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$row2=mysql_fetch_assoc($res2);
	$gurukelas=$row2['usr_name'];
		
	$exam=$_REQUEST['exam'];
	if($exam!=""){
			$sql="select * from type where grp='exam' and code='$exam'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $examname=$row['prm'];
	}
	$cons=$_REQUEST['cons'];
	//if($cons!="")
	$sqlcons="and sub_grp='$cons'";
	
	//if($cons!=""){
		$sql="select * from sub where level='$clslevel' and grp='$cons' and sch_id=$sid";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$grading=$row['grading'];
	//}
	$sql="select * from grading where name='$grading' and val>=0 order by val desc";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$row2=mysql_fetch_assoc($res2);
	$topgrade=$row2['grade'];
		
	$report_type=$_REQUEST['report_type'];
	if($report_type=="")
		$report_type=0; 
	if($report_type){
		$clscode="";
		$sqlclscode="";
		$clsname="";
		$gurukelas="";
	}
	if($report_type=="0")
		$laporan=$lg_analysis_subject;
	if($report_type=="1")
		$laporan=$lg_analysis_class;
	if($report_type=="2")
		$laporan=$lg_analysis_year;
	if($report_type=="3")
		$laporan=$lg_analysis_school;
	if($report_type=="4")
		$laporan=$lg_analysis_by_exam;
		

/** sorting control **/
	$order=$_POST['order'];
	if($order=="")
		$order="asc";
		
	if($order=="desc")
		$nextdirection="asc";
	else
		$nextdirection="desc";
		
	$sort=$_POST['sort'];
	if($sort=="")
		$sqlsort="order by name $order";
	else
		$sqlsort="order by $sort $order, name";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<SCRIPT LANGUAGE="Javascript" SRC="obj/fcf/FusionCharts.js"></SCRIPT>
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
	

	
<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
		<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
		<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
	</div> <!-- end mymenu -->
	<div align="right">
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>

				<select name="view" onChange="document.myform.submit();">
				<?php 
					echo "<option value=\"$view\">$view</option>";
					if($view!="$lg_average_mark")
						echo "<option value=\"$lg_average_mark\">$lg_average_mark</option>";
					if($view!="Gred Rata-Rata")
						echo "<option value=\"Gred Rata-Rata\">Gred Rata-Rata</option>";
					if($view!="$lg_pass_percentage")
						echo "<option value=\"$lg_pass_percentage\">$lg_pass_percentage</option>";
					if($view!="Jumlah A")
						echo "<option value=\"Jumlah A\">Jumlah A</option>";
					if($view!="Persen A")
						echo "<option value=\"Persen A\">Persen A</option>";
				?>
				</select>
		</div>
</div><!-- end mypanel -->

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
			<td><?php echo strtoupper("$namatahap $clslevel / $year");?></td>
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
			<td><?php echo strtoupper($lg_class);?></td>
			<td>:</td>
			<td>
				<?php 
					if($clscode=="") 
							echo strtoupper("$lg_all $lg_class"); 
					else 
							echo strtoupper("$clsname");
				?>
			</td>
		  </tr>
		</table>
	
	</td>
  </tr>
</table>


<?php
if($report_type=="0"){//purata markah
	$totalsub=0;
	if($clscode=="") $chart_item_value="$namatahap $clslevel"; else $chart_item_value="$clsname";
	$sql="select distinct(sub_code),sub_name from ses_sub where sch_id=$sid and year='$year' and cls_level=$clslevel $sqlclscode $sqlcons order by sub_grp,sub_name";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$sc=$row['sub_code'];
		$sn=$row['sub_name'];
		$totalsub++;
		$total_fail=0;
		$total_pass=0;
	
		$sql="select count(*) from exam where sch_id=$sid and year='$year' and cls_level=$clslevel $sqlclscode and sub_code='$sc' and examtype='$exam' and grade!='TT'";
		$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$total_stu=$row2[0];
		
		$sql="select count(*) from exam where sch_id=$sid and year='$year' and cls_level=$clslevel $sqlclscode and sub_code='$sc' and examtype='$exam' and grade='TH'";
		$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$total_th=$row2[0];
		$total_hadir=$total_stu-$total_th;
		
		$jum_markah=0;$grade_a=0;$total_pelajar_amik=0;
		$sql="select point,grade from exam where sch_id=$sid and year='$year' and cls_level=$clslevel $sqlclscode and sub_code='$sc' and examtype='$exam'";
		$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		while ($row2=mysql_fetch_row($res2)){
			$point=$row2[0];
			$grade=$row2[1];
			if($grade==$topgrade)
					$grade_a++;
			
			if(is_numeric($point)){
				if($si_exam_use_th){
					$jum_markah=$jum_markah+$point;
					$total_pelajar_amik++;
				}else{
					if($gred!="TH"){
						$jum_markah=$jum_markah+$point;
						$total_pelajar_amik++;
					}
				}
			}
		}

	$gp=0;$gpmp=0;
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
		if($isfail)
			$total_fail=$total_fail+$x;
		else
			$total_pass=$total_pass+$x;
		//$gp++;
		$gpmp=$x*$gp+$gpmp;
	} 

	$gval=0;
	if($view=="$lg_average_mark"){
		if($total_pelajar_amik>0)
			$gval=sprintf("%.02f",$jum_markah/$total_pelajar_amik); 
		$chart_note_left="$lg_average_mark : $year";
		if($clscode=="") $chart_note_top="$namatahap $clslevel"; else $chart_note_top="$clsname";
		$chart_note_bottom="";
		$chart_note_center="";
		$chart_decimal_value="2";
	}
	if($view=="$lg_grade_point"){
		if($total_hadir>0)
			$gval=sprintf("%.02f",$gpmp/$total_hadir); 
	}
	if($view=="$lg_pass_percentage"){
		if($total_hadir>0)
			$gval=sprintf("%.02f",$total_pass/$total_hadir*100); 
	}
	if($view=="$lg_total_a"){
		if($total_pelajar_amik>0)
			$gval=sprintf("%.02f",$grade_a); 
	}
	if($view=="$lg_percent_a"){
		if($total_pelajar_amik>0)
			$gval=sprintf("%.02f",$grade_a/$total_hadir*100); 
	}

	if($chart_item_group!="")
		$chart_item_group=$chart_item_group.",";
	$chart_item_group=$chart_item_group.$sn;
	
	if($chart_item_value!="")
		$chart_item_value=$chart_item_value.",";
	$chart_item_value=$chart_item_value.$gval;
}	

}elseif($report_type=="1"){
	$sql="select distinct(sub_code),sub_name from ses_sub where sch_id=$sid and year='$year' and cls_level=$clslevel $sqlclscode $sqlcons order by sub_grp,sub_name";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$i=0;$totalsub=0;
	while($row=mysql_fetch_assoc($res)){
		$sc=$row['sub_code'];
		$sn=strtoupper($row['sub_name']);
		$sn=str_replace("'","",$sn);
		$subcode[$i]=$sc;
		$i++;
		if($chart_item_group!="")
			$chart_item_group=$chart_item_group.",";
		$chart_item_group=$chart_item_group.$sn;
		$totalsub++;
	}
	
	//get the class..
	$sql="select * from ses_cls where sch_id=$sid and year='$year' and cls_level=$clslevel order by clsrank,cls_name";
	$resx=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($rowx=mysql_fetch_assoc($resx)){
		$cc=$rowx['cls_code'];
		$cn=strtoupper($rowx['cls_name']);
		$cn=str_replace("'","",$cn);
		if($chart_item_value!="")
			$chart_item_value=$chart_item_value.";";
		$chart_item_value=$chart_item_value.$cn;
		$j=0;
		while($j<$i){//get exam res for this class
					$sc=$subcode[$j];
					$j++;
					
					$total_fail=0;
					$total_pass=0;
				
					$sql="select count(*) from exam where sch_id=$sid and year='$year' and cls_level=$clslevel and cls_code='$cc' and sub_code='$sc' and examtype='$exam' and grade!='TT'";
					$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					$row2=mysql_fetch_row($res2);
					$total_stu=$row2[0];
					
					$sql="select count(*) from exam where sch_id=$sid and year='$year' and cls_level=$clslevel and cls_code='$cc' and sub_code='$sc' and examtype='$exam' and grade='TH'";
					$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					$row2=mysql_fetch_row($res2);
					$total_th=$row2[0];
					$total_hadir=$total_stu-$total_th;
					
					$jum_markah=0;$total_pelajar_amik=0;$grade_a=0;
					$sql="select point,grade from exam where sch_id=$sid and year='$year' and cls_level=$clslevel and cls_code='$cc' and sub_code='$sc' and examtype='$exam'";
					$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					while ($row2=mysql_fetch_row($res2)){
						$point=$row2[0];
						$grade=$row2[1];
						if((strtoupper($namatahap)=="TINGKATAN")&&($clslevel>3)){
							if(($grade=="A1")||($grade=="A2"))
								$grade_a++;
						}else{
							if($grade=="A")
								$grade_a++;
						}
						if(is_numeric($point)){
							if($si_exam_use_th){
								$jum_markah=$jum_markah+$point;
								$total_pelajar_amik++;
							}else{
								if($gred!="TH"){
									$jum_markah=$jum_markah+$point;
									$total_pelajar_amik++;
								}
							}
						}
					}
					
					$gp=0;$gpmp=0;
					$sql="select * from grading where name='$grading' and val>=0 order by val desc";
					$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					while($row2=mysql_fetch_assoc($res2)){
						$p=$row2['grade'];
						$gp=$row2['gp'];
						$isfail=$row2['sta'];
						$sql="select count(*) from exam where sch_id=$sid and year='$year' and cls_level=$clslevel and cls_code='$cc' and sub_code='$sc' and examtype='$exam' and grade='$p'";
						$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$row3=mysql_fetch_row($res3);
						$x=$row3[0];
						if($isfail)
							$total_fail=$total_fail+$x;
						else
							$total_pass=$total_pass+$x;
						//$gp++;
						$gpmp=$x*$gp+$gpmp;
					}
					
					$gval=0;
					if($view=="$lg_average_mark"){
						if($total_pelajar_amik>0)
							$gval=sprintf("%.02f",$jum_markah/$total_pelajar_amik); 
					}
					if($view=="$lg_grade_point"){
						if($total_hadir>0)
							$gval=sprintf("%.02f",$gpmp/$total_hadir); 
					}
					if($view=="$lg_pass_percentage"){
						if($total_hadir>0)
							$gval=sprintf("%.02f",$total_pass/$total_hadir*100); 
					}
					if($view=="$lg_total_a"){
						if($total_pelajar_amik>0)
							$gval=sprintf("%.02f",$grade_a);
					}
					if($view=="$lg_percent_a"){
						if($total_hadir>0)
							$gval=sprintf("%.02f",$grade_a/$total_hadir*100); 
					}
					if($chart_item_value!="")
						$chart_item_value=$chart_item_value.",";
					$chart_item_value=$chart_item_value.$gval;
					//$FC->addChartData($gval); 
		}
	}


	
	
	//echo $xml;
}




elseif($report_type=="4"){
	$sql="select distinct(sub_code),sub_name from ses_sub where sch_id=$sid and year='$year' and cls_level=$clslevel $sqlclscode $sqlcons order by sub_grp,sub_name";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$i=0;$totalsub=0;
	while($row=mysql_fetch_assoc($res)){
		$sc=$row['sub_code'];
		$sn=strtoupper($row['sub_name']);
		$sn=str_replace("'","",$sn);
		$subcode[$i]=$sc;
		$i++;
		if($chart_item_group!="")
			$chart_item_group=$chart_item_group.",";
		$chart_item_group=$chart_item_group.$sn;
		$totalsub++;
	}
	
	//get the exam..
	//$sql="select * from ses_cls where sch_id=$sid and year='$year' and cls_level=$clslevel order by cls_name";
	$sql="select * from type where grp='exam' and val=1 and (lvl=0 or lvl=$clslevel) and (sid=0 or sid=$sid) order by idx";
	$resx=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($rowx=mysql_fetch_assoc($resx)){
		$cc=$rowx['code'];
		$cn=strtoupper(stripslashes($rowx['prm']));
		if($chart_item_value!="")
			$chart_item_value=$chart_item_value.";";
		$chart_item_value=$chart_item_value.$cn;
		$j=0;
		while($j<$i){//get exam res for this class
					$sc=$subcode[$j];
					$j++;
					
					$total_fail=0;
					$total_pass=0;
				
					$sql="select count(*) from exam where sch_id=$sid and year='$year' and cls_level=$clslevel and sub_code='$sc' and examtype='$cc' and grade!='TT'";
					$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					$row2=mysql_fetch_row($res2);
					$total_stu=$row2[0];
					
					$sql="select count(*) from exam where sch_id=$sid and year='$year' and cls_level=$clslevel and sub_code='$sc' and examtype='$cc' and grade='TH'";
					$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					$row2=mysql_fetch_row($res2);
					$total_th=$row2[0];
					$total_hadir=$total_stu-$total_th;
					
					$jum_markah=0;$total_pelajar_amik=0;$grade_a=0;
					$sql="select point,grade from exam where sch_id=$sid and year='$year' and cls_level=$clslevel and sub_code='$sc' and examtype='$cc'";
					$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					while ($row2=mysql_fetch_row($res2)){
						$point=$row2[0];
						$grade=$row2[1];
						if((strtoupper($namatahap)=="TINGKATAN")&&($clslevel>3)){
							if(($grade=="A1")||($grade=="A2"))
								$grade_a++;
						}else{
							if($grade=="A")
								$grade_a++;
						}
						if(is_numeric($point)){
							if($si_exam_use_th){
								$jum_markah=$jum_markah+$point;
								$total_pelajar_amik++;
							}else{
								if($gred!="TH"){
									$jum_markah=$jum_markah+$point;
									$total_pelajar_amik++;
								}
							}
						}
					}
					
					$gp=0;$gpmp=0;
					$sql="select * from grading where name='$grading' and val>=0 order by val desc";
					$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					while($row2=mysql_fetch_assoc($res2)){
						$p=$row2['grade'];
						$gp=$row2['gp'];
						$isfail=$row2['sta'];
						$sql="select count(*) from exam where sch_id=$sid and year='$year' and cls_level=$clslevel and sub_code='$sc' and examtype='$cc' and grade='$p'";
						$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$row3=mysql_fetch_row($res3);
						$x=$row3[0];
						if($isfail)
							$total_fail=$total_fail+$x;
						else
							$total_pass=$total_pass+$x;
						//$gp++;
						$gpmp=$x*$gp+$gpmp;
					}
					
					$gval=0;
					if($view=="$lg_average_mark"){
						if($total_pelajar_amik>0)
							$gval=sprintf("%.02f",$jum_markah/$total_pelajar_amik); 
					}
					if($view=="$lg_grade_point"){
						if($total_hadir>0)
							$gval=sprintf("%.02f",$gpmp/$total_hadir); 
					}
					if($view=="$lg_pass_percentage"){
						if($total_hadir>0)
							$gval=sprintf("%.02f",$total_pass/$total_hadir*100); 
					}
					if($view=="$lg_total_a"){
						if($total_pelajar_amik>0)
							$gval=sprintf("%.02f",$grade_a);
					}
					if($view=="$lg_percent_a"){
						if($total_hadir>0)
							$gval=sprintf("%.02f",$grade_a/$total_hadir*100); 
					}
					if($chart_item_value!="")
						$chart_item_value=$chart_item_value.",";
					$chart_item_value=$chart_item_value.$gval;
					//$FC->addChartData($gval); 
		}
	}
	
	//echo $xml;
}


elseif($report_type=="2"){

	//get the subject first
	$sql="select distinct(sub_code),sub_name from ses_sub where sch_id=$sid and year='$year' and cls_level=$clslevel $sqlclscode $sqlcons order by sub_grp,sub_name";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$i=0;$totalsub=0;
	while($row=mysql_fetch_assoc($res)){
		$sc=$row['sub_code'];
		$sn=strtoupper($row['sub_name']);
		$subcode[$i]=$sc;
		$i++;
		if($chart_item_group!="")
			$chart_item_group=$chart_item_group.",";
		$chart_item_group=$chart_item_group.$sn;
		$totalsub++;
	}

$minyear=$year-5;
for($yy=$year;$yy>$minyear;$yy--){
	if($chart_item_value!="")
			$chart_item_value=$chart_item_value.";";
	$chart_item_value=$chart_item_value.$yy;
	$sql="select distinct(sub_code),sub_name from ses_sub where sch_id=$sid and year='$yy' and cls_level=$clslevel $sqlclscode $sqlcons order by sub_grp,sub_name";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$sc=$row['sub_code'];
		$sn=strtoupper($row['sub_name']);
		
				
		$total_fail=0;
		$total_pass=0;

		$sql="select count(*) from exam where sch_id=$sid and year='$yy' and cls_level=$clslevel $sqlclscode and sub_code='$sc' and examtype='$exam' and grade!='TT'";
		$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$total_stu=$row2[0];
		
		$sql="select count(*) from exam where sch_id=$sid and year='$yy' and cls_level=$clslevel $sqlclscode and sub_code='$sc' and examtype='$exam' and grade='TH'";
		$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$total_th=$row2[0];
		$total_hadir=$total_stu-$total_th;
		
		$jum_markah=0;$total_pelajar_amik=0;$grade_a=0;
		$sql="select point,grade from exam where sch_id=$sid and year='$yy' and cls_level=$clslevel $sqlclscode and sub_code='$sc' and examtype='$exam'";
		$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		while ($row2=mysql_fetch_row($res2)){
			$point=$row2[0];
			$grade=$row2[1];
			if((strtoupper($namatahap)=="TINGKATAN")&&($clslevel>3)){
				if(($grade=="A1")||($grade=="A2"))
					$grade_a++;
			}else{
				if($grade=="A")
					$grade_a++;
			}
			if(is_numeric($point)){
				if($si_exam_use_th){
					$jum_markah=$jum_markah+$point;
					$total_pelajar_amik++;
				}else{
					if($gred!="TH"){
						$jum_markah=$jum_markah+$point;
						$total_pelajar_amik++;
					}
				}
			}
		}

		//if($total_stu==0)
			//continue;
		
		$gp=0;$gpmp=0;
		$sql="select * from grading where name='$grading' and val>=0 order by val desc";
		$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		while($row2=mysql_fetch_assoc($res2)){
			$p=$row2['grade'];
			$gp=$row2['gp'];
			$isfail=$row2['sta'];
			$sql="select count(*) from exam where sch_id=$sid and year='$yy' and cls_level=$clslevel $sqlclscode and sub_code='$sc' and examtype='$exam' and grade='$p'";
			$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			$row3=mysql_fetch_row($res3);
			$x=$row3[0];
			if($isfail)
				$total_fail=$total_fail+$x;
			else
				$total_pass=$total_pass+$x;
			//$gp++;
			$gpmp=$x*$gp+$gpmp;
	
		} 
	
			$gval=0;
			if($view=="$lg_average_mark"){
						if($total_pelajar_amik>0)
							$gval=sprintf("%.02f",$jum_markah/$total_pelajar_amik); 
			}
			if($view=="$lg_grade_point"){
						if($total_hadir>0)
							$gval=sprintf("%.02f",$gpmp/$total_hadir); 
			}
			if($view=="$lg_pass_percentage"){
						if($total_hadir>0)
							$gval=sprintf("%.02f",$total_pass/$total_hadir*100); 
			}

			if($view=="$lg_total_a"){
						if($total_pelajar_amik>0)
							$gval=sprintf("%.02f",$grade_a);
					}
					if($view=="$lg_percent_a"){
						if($total_hadir>0)
							$gval=sprintf("%.02f",$grade_a/$total_hadir*100); 
					}
					if($chart_item_value!="")
						$chart_item_value=$chart_item_value.",";
					$chart_item_value=$chart_item_value.$gval;
			

		} //while

	}//for

	
}

elseif($report_type=="3"){
	$totalsub=0;
	//get the subject first
	$sql="select distinct(sub_code),sub_name from ses_sub where sch_id=$sid and year='$year' and cls_level=$clslevel $sqlclscode $sqlcons order by sub_grp,sub_name";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$i=0;
	while($row=mysql_fetch_assoc($res)){
		$sc=$row['sub_code'];
		$sn=strtoupper($row['sub_name']);
		$subcode[$i]=$sc;
		$i++;
		if($chart_item_group!="")
			$chart_item_group=$chart_item_group.",";
		$chart_item_group=$chart_item_group.$sn;
		$totalsub++;
	}
	
	//get the school..
	$sql="select * from sch where level='$slevel'";
	$resx=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($rowx=mysql_fetch_assoc($resx)){
		$xsname=$rowx['sname'];
		$xsid=$rowx['id'];
		if($chart_item_value!="")
			$chart_item_value=$chart_item_value.";";
		$chart_item_value=$chart_item_value.$xsname;
		$j=0;
		while($j<$i){//get exam res for this class

					$sc=$subcode[$j];
					$j++;
					
					
					$total_fail=0;
					$total_pass=0;
				
					$sql="select count(*) from exam where sch_id=$xsid and year='$year' and cls_level=$clslevel and sub_code='$sc' and examtype='$exam' and grade!='TT'";
					$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					$row2=mysql_fetch_row($res2);
					$total_stu=$row2[0];
					
					$sql="select count(*) from exam where sch_id=$xsid and year='$year' and cls_level=$clslevel and sub_code='$sc' and examtype='$exam' and grade='TH'";
					$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					$row2=mysql_fetch_row($res2);
					$total_th=$row2[0];
					$total_hadir=$total_stu-$total_th;
					
					$jum_markah=0;$total_pelajar_amik=0;$grade_a=0;
					$sql="select point,grade from exam where sch_id=$xsid and year='$year' and cls_level=$clslevel and sub_code='$sc' and examtype='$exam'";
					$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					while ($row2=mysql_fetch_row($res2)){
						$point=$row2[0];
						$grade=$row2[1];
						if((strtoupper($namatahap)=="TINGKATAN")&&($clslevel>3)){
							if(($grade=="A1")||($grade=="A2"))
								$grade_a++;
						}else{
							if($grade=="A")
								$grade_a++;
						}
						if(is_numeric($point)){
							if($si_exam_use_th){
								$jum_markah=$jum_markah+$point;
								$total_pelajar_amik++;
							}else{
								if($gred!="TH"){
									$jum_markah=$jum_markah+$point;
									$total_pelajar_amik++;
								}
							}
						}
					}
					
					$gp=0;$gpmp=0;
					$sql="select * from grading where name='$grading' and val>=0 order by val desc";
					$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					while($row2=mysql_fetch_assoc($res2)){
						$p=$row2['grade'];
						$gp=$row2['gp'];
						$isfail=$row2['sta'];
						$sql="select count(*) from exam where sch_id=$xsid and year='$year' and cls_level=$clslevel and sub_code='$sc' and examtype='$exam' and grade='$p'";
						$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$row3=mysql_fetch_row($res3);
						$x=$row3[0];
						if($isfail)
							$total_fail=$total_fail+$x;
						else
							$total_pass=$total_pass+$x;
						//$gp++;
						$gpmp=$x*$gp+$gpmp;
					}
					
					$gval=0;
					if($view=="$lg_average_mark"){
						if($total_pelajar_amik>0)
							$gval=sprintf("%.02f",$jum_markah/$total_pelajar_amik); 
					}
					if($view=="$lg_grade_point"){
						if($total_hadir>0)
							$gval=sprintf("%.02f",$gpmp/$total_hadir); 
					}
					if($view=="$lg_pass_percentage"){
						if($total_hadir>0)
							$gval=sprintf("%.02f",$total_pass/$total_hadir*100); 
					}
					if($view=="$lg_total_a"){
						if($total_pelajar_amik>0)
							$gval=sprintf("%.02f",$grade_a);
					}
					if($view=="$lg_percent_a"){
						if($total_hadir>0)
							$gval=sprintf("%.02f",$grade_a/$total_hadir*100); 
					}
					if($chart_item_value!="")
						$chart_item_value=$chart_item_value.",";
					$chart_item_value=$chart_item_value.$gval;
	 
		}
	}



		

}
?>

<?php if($totalsub>0) { 

if($view=="$lg_average_mark"){
		$chart_note_left="$lg_average_mark - $year";
		if($clscode=="") $chart_note_top="$namatahap $clslevel"; else $chart_note_top="$clsname";
		$chart_note_bottom="";
		$chart_note_center="";
		$chart_decimal_value="0";
	}
	if($view=="$lg_grade_point"){ 
		$chart_note_left="$lg_grade_point - $year";
		if($clscode=="") $chart_note_top="$namatahap $clslevel"; else $chart_note_top="$clsname";
		$chart_note_bottom="";
		$chart_note_center="";
		$chart_decimal_value="2";
	}
	if($view=="$lg_pass_percentage"){ 
		$chart_note_left="$lg_pass_percentage - $year";
		if($clscode=="") $chart_note_top="$namatahap $clslevel"; else $chart_note_top="$clsname";
		$chart_note_bottom="";
		$chart_note_center="";
		$chart_decimal_value="0";
	}
	if($view=="$lg_total_a"){
		$chart_note_left="$lg_total_a - $year";
		if($clscode=="") $chart_note_top="$namatahap $clslevel"; else $chart_note_top="$clsname";
		$chart_note_bottom="";
		$chart_note_center="";
		$chart_decimal_value="0";
	}
	if($view=="$lg_percent_a"){
		$chart_note_left="$lg_percent_a - $year";
		if($clscode=="") $chart_note_top="$namatahap $clslevel"; else $chart_note_top="$clsname";
		$chart_note_bottom="";
		$chart_note_center="";
		$chart_decimal_value="0";
	}
	
	/**
		$chart_item_group=BAHASA ARAB,BAHASA INGGERIS,BAHASA MELAYU,GEOGRAFI,KEMAHIRAN HIDUP-KEMAHIRAN TEKNIKAL,MATEMATIK,PENDIDIKAN ISLAM,SAINS,SEJARAH
		$chart_item_value=2009,1.39,1.61,1.11,1.11,1.54,1.25,1.04,1.50,1.32;2008;2007;2006;2005
	**/
	$xml="chart_column1.php?dat=$chart_item_group|$chart_item_value|$chart_note_left|$chart_note_top|$chart_note_bottom|$chart_note_center|$chart_note_right|$chart_decimal_value";
	//echo $xml;
	$xml=str_replace("&","",$xml);
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
			'width', '100%',
			'height', '500',
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

	<input name="exam" type="hidden" id="exam" value="<?php echo $exam;?>">
	<input name="sid" type="hidden" id="sid" value="<?php echo $sid;?>">
	<input name="clslevel" type="hidden" id="clscode" value="<?php echo $clslevel;?>">
	<input name="clscode" type="hidden" id="clscode" value="<?php echo $clscode;?>">
	<input name="year" type="hidden" id="year" value="<?php echo $year;?>">
	<input name="cons" type="hidden" id="cons" value="<?php echo $cons;?>">
	<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
	<input name="order" type="hidden" id="order" value="<?php echo $order;?>">
	<input name="report_type" type="hidden" id="report_type" value="<?php echo $report_type;?>">
	<input type="hidden" name="p" value="../examrep/rep_ana_sub_allg">
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