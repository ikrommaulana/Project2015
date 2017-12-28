<?php
//101215 - patch subjek kosong
//110108 - tambah compare by exam
//110627 - sort by clsrank
$vmod="v6.0.0";
$vdate="110627";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN|HEP|HEP-OPERATOR');
$username=$_SESSION['username'];

	$showheader=$_REQUEST['showheader'];
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
		
	if($_SESSION['sid']!=0)
		$disabled="disabled";
		
	$namatahap="Level";
	if($sid!=0){
		$sql="select * from sch where id=$sid";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=$row['name'];
		$simg=$row['img'];
		$ssname=$row['sname'];
		$slevel=$row['level'];
		$namatahap=$row['clevel'];
     	mysql_free_result($res);					  
	}

	$xclslevel=$_POST['clslevel'];
	if($xclslevel!=""){
		$clslevel=$xclslevel;
		$sqlclslevel="and level='$clslevel'";
		$sql="select * from type where sid='$sid' and prm='$clslevel' and grp='classlevel'";
    	$res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$grading=$row['code'];
	}else{
		$clslevel=-1;
	}
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
	$year=$_POST['year'];
	if($year=="")
		$year=date('Y');
		
	$sql="select * from ses_cls where year='$year' and cls_code='$clscode' and sch_id=$sid ";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$row2=mysql_fetch_assoc($res2);
	$gurukelas=$row2['usr_name'];
		
	$exam=$_POST['exam'];
	if($exam!=""){
			$sql="select * from type where grp='exam' and code='$exam'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $examname=$row['prm'];
	}
	$cons=$_POST['cons'];
	//if($cons!="")
	$sqlcons="and sub_grp='$cons'";
		
	//if($cons!=""){
		$sql="select * from sub where level='$clslevel' and grp='$cons' and sch_id=$sid";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$grading=$row['grading'];
		$name=$row['name'];
		
		//echo "$grading:$name";
		
		
	//}
			
	$report_type=$_POST['report_type'];
	if($report_type=="")
		$report_type=0; 

	
	if($report_type=="0")
		$laporan=$lg_analysis_subject;
	if($report_type=="4")
		$laporan=$lg_analysis_by_exam;
	if($report_type=="1")
		$laporan=$lg_analysis_class;
	if($report_type=="2")
		$laporan=$lg_analysis_year;
	if($report_type=="3")
		$laporan=$lg_analysis_school;

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
		$sqlsort="order by sub_name $order";
	else
		$sqlsort="order by $sort $order, sub_name";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript">
function processform(operation){
		if((document.myform.sid.value=="")||(document.myform.sid.value==0)){
			alert("SSelect school..");
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
</head>
<body>

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="../examrep/rep_ana_sub_all">
	<input name="curr" type="hidden">
	<input name="sort" type="hidden" value="<?php echo $sort;?>">
	<input name="order" type="hidden" value="<?php echo $order;?>">
	
<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
		<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
		<a href="../examrep/rep_ana_sub_allg.php?<?php echo "exam=$exam&sid=$sid&year=$year&clslevel=$clslevel&clscode=$clscode&cons=$cons&report_type=$report_type";?>" title="Analysis"  onClick="return GB_showPage('<?php echo $laporan;?>',this.href)" target="_blank" id="mymenuitem"><img src="../img/graphbar.png"><br>Graph</a>
	</div> <!-- end mymenu -->
	<div id="viewpanel" align="right">
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
	</div>
</div><!-- end mypanel -->

<div id="story">

<div id="mytitlebg" align="right" class="printhidden">

	  <select name="sid" id="sid" onchange="document.myform.clscode[0].value='';document.myform.submit();">
        <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select $lg_school -</option>";
			else
                echo "<option value=$sid>$ssname</option>";
				
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=stripcslashes ($row['sname']);
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
				mysql_free_result($res);
			}							  
			
?>
	</select>
	 <select name="year" id="year" onChange="document.myform.submit();">
        <?php
            echo "<option value=$year>$year</option>";
			$sql="select * from type where grp='session' and prm!='$year' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        echo "<option value=\"$s\">$s</option>";
            }
            mysql_free_result($res);					  
?>
    </select>
	<select name="clslevel" id="clslevel" onchange="document.myform.clscode[0].value='';document.myform.submit();">
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
					$sql="select * from type where grp='subtype' and prm!='$cons' and (sid=0 or sid=$sid) order by idx";
            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=$row['prm'];
                        echo "<option value=\"$b\">$b</option>";
            		}
			?>
       </select>
	  
	<select name="clscode" id="clscode" onchange="document.myform.submit();">
        <?php	
      				if($clscode=="")
						echo "<option value=\"\">- $lg_all $lg_class -</option>";
					else
						echo "<option value=\"$clscode\">$clsname</option>";
					$sql="select * from cls where sch_id=$sid and code!='$clscode' $sqlclslevel order by level";
            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=$row['name'];
						$a=$row['code'];
                        echo "<option value=\"$a\">$b</option>";
            		}
					if($clscode!="")
            			echo "<option value=\"\">- $lg_all -</option>";
			?>
      </select>
   
      <input type="button" name="Submit" value="View" onClick="processform()" >
	  <br>
	  <input type="radio" name="report_type" value="0" <?php if($report_type=="0"){$laporan=$lg_analysis_subject; echo "checked";}?> onClick="document.myform.submit()"><?php echo $lg_analysis_subject;?>
	  <input type="radio" name="report_type" value="4" <?php if($report_type=="4"){$laporan=$lg_analysis_by_exam; echo "checked";}?> onClick="document.myform.submit()"><?php echo "Analisa Perbandingan Ujian";?>
	  <input type="radio" name="report_type" value="1" <?php if($report_type=="1"){$laporan=$lg_analysis_class; echo "checked";}?> onClick="document.myform.submit()"><?php echo $lg_analysis_class;?>
	  <input type="radio" name="report_type" value="2" <?php if($report_type=="2"){$laporan=$lg_analysis_year; echo "checked";}?> onClick="document.myform.submit()"><?php echo $lg_analysis_year;?>
<?php if($SI_REPORT_COMPARE_SCHOOL){ ?>
	  <input type="radio" name="report_type" value="3" <?php if($report_type=="3"){$laporan=$lg_analysis_school; echo "checked";}?> onClick="document.myform.submit()"><?php echo $lg_analysis_school;?>
<?php } ?>

		
</div>

<div id="mytitlebg">
	<?php echo strtoupper($laporan);?>
</div>
<table width="100%"  id="mytabletitle">
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

<table width="100%"  cellspacing="0" cellpadding="2">
  <tr>
    <td id="mytabletitle" width="1%" align="center" rowspan="2"><?php echo strtoupper($lg_no);?></td>
    <td id="mytabletitle" width="2%" rowspan="2">&nbsp;<a href="#" onClick="formsort('sub_code','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_code);?></a></td>
    <td id="mytabletitle" width="13%" rowspan="2">&nbsp;<a href="#" onClick="formsort('sub_name','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_subject);?></a></td>
	<td id="mytabletitle" width="2%" align="center" rowspan="2"><?php echo strtoupper($lg_registered_student);?></td>
	<td id="mytabletitle" width="2%" align="center" rowspan="2"><?php echo strtoupper($lg_absence);?></td>
	<td id="mytabletitle" width="2%" align="center" rowspan="2"><?php echo strtoupper($lg_total_student);?></td>
<?php 
	$sql="select * from grading where name='$grading' and val>=0 order by val desc";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row2=mysql_fetch_assoc($res2)){
		$p=$row2['grade'];
?>
	<td id="mytabletitle" width="4%" align="center" colspan="2"><?php echo "$p";?></td>
<?php } ?>
	<td id="mytabletitle" width="4%" align="center" colspan="2"><?php echo strtoupper($lg_pass);?></td>
	<td id="mytabletitle" width="4%" align="center" colspan="2"><?php echo strtoupper($lg_fail);?></td>
	<td id="mytabletitle" width="2%" align="center" rowspan="2"><?php echo " Gred Rata-rata Sekola (GSP) ";?></td>
	<td id="mytabletitle" width="2%" align="center" rowspan="2"><?php echo strtoupper($lg_gp_subject);?></td>
  </tr>
 <tr>
<?php 
	$sql="select * from grading where name='$grading' and val>=0 order by val desc";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row2=mysql_fetch_assoc($res2)){
		$p=$row2['grade'];
?>
	<td id="mytabletitle" width="2%" align="center"><?php echo $lg_num;?></td>
	<td id="mytabletitle" width="2%" align="center">%</td>
<?php } ?>
	<td id="mytabletitle" width="2%" align="center"><?php echo $lg_num;?></td>
	<td id="mytabletitle" width="2%" align="center">%</td>
	<td id="mytabletitle" width="2%" align="center"><?php echo $lg_num;?></td>
	<td id="mytabletitle" width="2%" align="center">%</td>
 </tr>


<?php 
	if($report_type=="4"){
	$gps=0;$jum_subjek=0;
	$sql="select distinct(sub_code),sub_name from ses_sub where sch_id=$sid and year='$year' and cls_level=$clslevel $sqlclscode $sqlcons $sqlsort";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$sc=$row['sub_code'];
		$sn=strtoupper($row['sub_name']);
		$q++;
	?>
		  <tr bgcolor="#FAFAFA">
			<td id="myborder" align="center"><?php echo $q?></td>
			<td id="myborder"><?php echo $sc?></td>
			<td id="myborder"><?php echo "$sn";?></td>
			<td id="myborder" align="center"></td>
			<td id="myborder" align="center"></td>
			<td id="myborder" align="center"></td>
			<?php 
				$sql="select * from grading where name='$grading' and val>=0 order by val desc";
				$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				while($row2=mysql_fetch_assoc($res2)){
			?>
				<td id="myborder" align="center"></td>
				<td id="myborder" align="center"></td>
			<?php } ?>
			<td id="myborder" align="center"></td>
			<td id="myborder" align="center"></td>
			<td id="myborder" align="center"></td>
			<td id="myborder" align="center"></td>
			<td id="myborder" align="center"></td>
			<td id="myborder" align="center"></td>
		  </tr>
<?php 
	$j=0;
	/**
	$sql="select * from ses_cls where sch_id=$sid and year='$year' and cls_level=$clslevel order by cls_name";
	$resx=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($rowx=mysql_fetch_assoc($resx)){
		$cc=$rowx['cls_code'];
		$cn=strtoupper($rowx['cls_name']);
	**/
	$sql="select * from type where grp='exam' and val=1 and (lvl=0 or lvl=$clslevel) and (sid=0 or sid=$sid) order by idx";
	$resxxx=mysql_query($sql)or die("query failed:".mysql_error());
	while($rowxxx=mysql_fetch_assoc($resxxx)){
		$examname=$rowxxx['prm'];
		$examcode=$rowxxx['code'];
		
		$total_fail=0;
		$total_pass=0;
	
		$sql="select count(*) from exam where sch_id=$sid and year='$year' and cls_level=$clslevel and sub_code='$sc' and examtype='$examcode' and grade!='TT'";
		$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$total_stu=$row2[0];
		
		$sql="select count(*) from exam where sch_id=$sid and year='$year' and cls_level=$clslevel and sub_code='$sc' and examtype='$examcode' and grade='TH'";
		$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$total_th=$row2[0];
		$total_hadir=$total_stu-$total_th;
		
		$jum_markah=0;$jum_pelajar=0;
		$sql="select point from exam where sch_id=$sid and year='$year' and cls_level=$clslevel and sub_code='$sc' and examtype='$examcode'";
		$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		while ($row2=mysql_fetch_row($res2)){
		$point=$row2[0];
			if(is_numeric($point)){
				if($si_exam_use_th){
					$jum_markah=$jum_markah+$point;
					$jum_pelajar++;
				}else{
					if($gred!="TH"){
						$jum_markah=$jum_markah+$point;
						$jum_pelajar++;
					}
				}
			}
		}

		$bg=$bglyellow;
?>
  <tr bgcolor="<?php echo $bg;?>" style="cursor:default;color:#6633FF" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
  	<td id="myborder" align="right">&nbsp;</td>
    <td id="myborder" align="right">&nbsp;</td>
	<td id="myborder" align="left">&nbsp;&nbsp;-&nbsp;<?php echo "$examname";?></td>
	<td id="myborder" align="center"><?php echo "$total_stu";?></td>
	<td id="myborder" align="center"><?php echo "$total_th";?></td>
	<td id="myborder" align="center"><?php echo "$total_hadir";?></td>
<?php 
	$gp=0;$totalgp=0;
	$sql="select * from grading where name='$grading' and val>=0 order by val desc";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row2=mysql_fetch_assoc($res2)){
		$p=$row2['grade'];
		$gp=$row2['gp'];
		$isfail=$row2['sta'];
		$sql="select count(*) from exam where sch_id=$sid and year='$year' and cls_level=$clslevel and sub_code='$sc' and examtype='$examcode' and grade='$p'";
		$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$row3=mysql_fetch_row($res3);
		$x=$row3[0];
		if($isfail)
			$total_fail=$total_fail+$x;
		else
			$total_pass=$total_pass+$x;
		//$gp++;
		$totalgp=$x*$gp+$totalgp;
?>
	<td id="myborder" align="center"><?php echo "$x";?></td>
	<td id="myborder" align="center"><?php if($total_hadir>0) echo round($x/$total_hadir*100); else echo "0"; ?></td>
<?php } ?>
	<td id="myborder" align="center"><?php echo "$total_pass";?></td>
	<td id="myborder" align="center" style="background-color:#33FFCC"><?php if($total_hadir>0) echo round($total_pass/$total_hadir*100); else echo "0"; ?>%</td>
	<td id="myborder" align="center"><?php echo "$total_fail";?></td>
	<td id="myborder" align="center" <?php if($total_fail>0){?> style="background-color:#FFCCCC" <?php } ?>><?php if($total_hadir>0) echo round($total_fail/$total_hadir*100); else echo "0"; ?>%</td>
	<td id="myborder" align="center"><?php if($jum_pelajar>0) printf("%.02f",$jum_markah/$jum_pelajar); else echo "0";  ?></td>
	<td id="myborder" align="center"><?php if($total_hadir>0) printf("%.02f",$totalgp/$total_hadir); else echo "0";  ?></td>
  </tr>
  <?php 
  
  } //while?>
<?php } }?>


<?php 
	if(($report_type=="0")||($report_type=="1")){
	$gps=0;$jum_subjek=0;
	$sql="select distinct(sub_code),sub_name from ses_sub where sch_id=$sid and year='$year' and cls_level=$clslevel $sqlclscode $sqlcons $sqlsort";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$sc=$row['sub_code'];
		$sn=strtoupper($row['sub_name']);
			
		$sql="select count(*) from exam where sch_id=$sid and year='$year' and cls_level=$clslevel $sqlclscode and sub_code='$sc' and examtype='$exam' and grade!='TT'";
		$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$total_stu=$row2[0];
		if($total_stu==0)
			continue;
		$total_fail=0;
		$total_pass=0;
		$jum_subjek++;
		
		$sql="select count(*) from exam where sch_id=$sid and year='$year' and cls_level=$clslevel $sqlclscode and sub_code='$sc' and examtype='$exam' and grade='TH'";
		$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$total_th=$row2[0];
		$total_hadir=$total_stu-$total_th;
		
		$jum_markah=0;$jum_pelajar=0;
		$sql="select point from exam where sch_id=$sid and year='$year' and cls_level=$clslevel $sqlclscode and sub_code='$sc' and examtype='$exam'";
		$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		while ($row2=mysql_fetch_row($res2)){
		$point=$row2[0];
			if(is_numeric($point)){
				if($si_exam_use_th){
					$jum_markah=$jum_markah+$point;
					$jum_pelajar++;
				}else{
					if($gred!="TH"){
						$jum_markah=$jum_markah+$point;
						$jum_pelajar++;
					}
				}
			}
		}

		
		$q++;
		$bg=$bglyellow;
		
		if($report_type)
			$bg="#FAFAFA";
?>
  <tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
  	<td id="myborder" align="center"><?php echo $q?></td>
    <td id="myborder"><?php echo $sc?></td>
	<td id="myborder"><?php echo "$sn";?></td>
	<td id="myborder" align="center"><?php echo "$total_stu";?></td>
	<td id="myborder" align="center"><?php echo "$total_th";?></td>
	<td id="myborder" align="center"><?php echo "$total_hadir";?></td>
<?php 
	$gp=0;$totalgp=0;
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
		$totalgp=$x*$gp+$totalgp;
?>
	<td id="myborder" align="center"><?php echo "$x";?></td>
	<td id="myborder" align="center"><?php if($total_hadir>0) echo round($x/$total_hadir*100); else echo "0"; ?></td>
<?php } 

	//kira gpmp n gps
	if($total_hadir>0){
		$gpmp=$totalgp/$total_hadir;
		$totalgpmp=$totalgpmp+$gpmp;
		$gps=round($totalgpmp/$jum_subjek,2);
		$perlulus=$total_pass/$total_hadir*100;
		
		$totalperlulus=$totalperlulus+$perlulus;
		$avgperlulus=round($totalperlulus/$jum_subjek,2);
	}
		
	
?>

	<td id="myborder" align="center"><?php echo "$total_pass";?></td>
	<td id="myborder" align="center" style="background-color:#33FFCC"><?php if($total_hadir>0) echo round($perlulus); else echo "0"; ?>%</td>
	<td id="myborder" align="center"><?php echo "$total_fail";?></td>
	<td id="myborder" align="center" <?php if($total_fail>0){?> style="background-color:#FFCCCC" <?php } ?>><?php if($total_hadir>0) echo round($total_fail/$total_hadir*100); else echo "0"; ?>%</td>
	<td id="myborder" align="center"><?php if($jum_pelajar>0) printf("%.02f",$jum_markah/$jum_pelajar); else echo "0";  ?></td>
	<td id="myborder" align="center"><?php if($total_hadir>0) echo round($gpmp,2); else echo "0";  ?></td>
  </tr>
  
  <!-- check class perbandingan -->
  <?php 
  if($report_type=="1"){
  	$j=0;
	$sql="select * from ses_cls where sch_id=$sid and year='$year' and cls_level=$clslevel order by clsrank,cls_name";
	$resx=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($rowx=mysql_fetch_assoc($resx)){
		$cc=$rowx['cls_code'];
		$cn=strtoupper(stripslashes($rowx['cls_name']));
		
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
		
		$jum_markah=0;$jum_pelajar=0;
		$sql="select point from exam where sch_id=$sid and year='$year' and cls_level=$clslevel and cls_code='$cc' and sub_code='$sc' and examtype='$exam'";
		$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		while ($row2=mysql_fetch_row($res2)){
		$point=$row2[0];
			if(is_numeric($point)){
				if($si_exam_use_th){
					$jum_markah=$jum_markah+$point;
					$jum_pelajar++;
				}else{
					if($gred!="TH"){
						$jum_markah=$jum_markah+$point;
						$jum_pelajar++;
					}
				}
			}
		}

		$bg=$bglyellow;
?>
  <tr bgcolor="<?php echo $bg;?>" style="cursor:default;color:#6633FF" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
  	<td id="myborder" align="right">&nbsp;</td>
    <td id="myborder" align="right">&nbsp;</td>
	<td id="myborder" align="left">&nbsp;&nbsp;-&nbsp;<?php echo "$cn";?></td>
	<td id="myborder" align="center"><?php echo "$total_stu";?></td>
	<td id="myborder" align="center"><?php echo "$total_th";?></td>
	<td id="myborder" align="center"><?php echo "$total_hadir";?></td>
<?php 
	$gp=0;$totalgp=0;
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
		$totalgp=$x*$gp+$totalgp;
?>
	<td id="myborder" align="center"><?php echo "$x";?></td>
	<td id="myborder" align="center"><?php if($total_hadir>0) echo round($x/$total_hadir*100); else echo "0"; ?></td>
<?php } ?>
	<td id="myborder" align="center"><?php echo "$total_pass";?></td>
	<td id="myborder" align="center" style="background-color:#33FFCC"><?php if($total_hadir>0) echo round($total_pass/$total_hadir*100); else echo "0"; ?>%</td>
	<td id="myborder" align="center"><?php echo "$total_fail";?></td>
	<td id="myborder" align="center" <?php if($total_fail>0){?> style="background-color:#FFCCCC" <?php } ?>><?php if($total_hadir>0) echo round($total_fail/$total_hadir*100); else echo "0"; ?>%</td>
	<td id="myborder" align="center"><?php if($jum_pelajar>0) printf("%.02f",$jum_markah/$jum_pelajar); else echo "0";  ?></td>
	<td id="myborder_lrb" align="center"><?php if($total_hadir>0) printf("%.02f",$totalgp/$total_hadir); else echo "0";  ?></td>
  </tr>
  <?php 
  
  } //while
  
  }  //if($report_type=="1"){
  
  }//while besaq

  } //if(($report_type=="0")||($report_type=="1")){


if($report_type=="3"){
	$sql="select distinct(sub_code),sub_name from ses_sub where sch_id=$sid and year='$year' and cls_level=$clslevel $sqlclscode $sqlcons $sqlsort";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$sc=$row['sub_code'];
		$sn=strtoupper($row['sub_name']);
		
		$total_fail=0;
		$total_pass=0;
	
		$sql="select count(*) from exam where year='$year' and cls_level=$clslevel $sqlclscode and sub_code='$sc' and examtype='$exam' and grade!='TT'";
		$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$total_stu=$row2[0];
		
		$sql="select count(*) from exam where year='$year' and cls_level=$clslevel $sqlclscode and sub_code='$sc' and examtype='$exam' and grade='TH'";
		$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$total_th=$row2[0];
		$total_hadir=$total_stu-$total_th;
		
		$jum_markah=0;$jum_pelajar=0;
		$sql="select point from exam where year='$year' and cls_level=$clslevel $sqlclscode and sub_code='$sc' and examtype='$exam'";
		$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		while ($row2=mysql_fetch_row($res2)){
		$point=$row2[0];
			if(is_numeric($point)){
				if($si_exam_use_th){
					$jum_markah=$jum_markah+$point;
					$jum_pelajar++;
				}else{
					if($gred!="TH"){
						$jum_markah=$jum_markah+$point;
						$jum_pelajar++;
					}
				}
			}
		}

		
		if($q++%2==0)
			$bg="bgcolor=#FAFAFA";
		else
			$bg="";

		//if($report_type)
			$bg="bgcolor=#FAFAFA";
?>
  <tr <?php echo $bg?>>
  	<td id="myborder" align="center"><?php echo $q?></td>
    <td id="myborder"><?php echo $sc?></td>
	<td id="myborder"><?php echo "$sn";?></td>
	<td id="myborder" align="center"><?php echo "$total_stu";?></td>
	<td id="myborder" align="center"><?php echo "$total_th";?></td>
	<td id="myborder" align="center"><?php echo "$total_hadir";?></td>
<?php 
	$gp=0;$totalgp=0;
	$sql="select * from grading where name='$grading' and val>=0 order by val desc";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row2=mysql_fetch_assoc($res2)){
		$p=$row2['grade'];
		$gp=$row2['gp'];
		$isfail=$row2['sta'];
		$sql="select count(*) from exam where year='$year' and cls_level=$clslevel $sqlclscode and sub_code='$sc' and examtype='$exam' and grade='$p'";
		$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$row3=mysql_fetch_row($res3);
		$x=$row3[0];
		if($isfail)
			$total_fail=$total_fail+$x;
		else
			$total_pass=$total_pass+$x;
		//$gp++;
		$totalgp=$x*$gp+$totalgp;
?>
	<td id="myborder" align="center"><?php echo "$x";?></td>
	<td id="myborder" align="center"><?php if($total_hadir>0) echo round($x/$total_hadir*100); else echo "0"; ?></td>
<?php } ?>
	<td id="myborder" align="center"><?php echo "$total_pass";?></td>
	<td id="myborder" align="center" style="background-color:#33FFCC"><?php if($total_hadir>0) printf("%.02f",$total_pass/$total_hadir*100); else echo "0"; ?></td>
	<td id="myborder" align="center"><?php echo "$total_fail";?></td>
	<td id="myborder" align="center" <?php if($total_fail>0){?> style="background-color:#FFCCCC" <?php } ?>><?php if($total_hadir>0) printf("%.02f",$total_fail/$total_hadir*100); else echo "0"; ?></td>
	<td id="myborder" align="center"><?php if($jum_pelajar>0) printf("%.02f",$jum_markah/$jum_pelajar); else echo "0";  ?></td>
	<td id="myborder_lrb" align="center"><?php if($total_hadir>0) printf("%.02f",$totalgp/$total_hadir); else echo "0";  ?></td>
  </tr>
  
  <!-- check class sekolah -->
  <?php 
  	$j=0;
	$sql="select * from sch where level='$slevel'";
	$resx=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($rowx=mysql_fetch_assoc($resx)){
		$xsname=$rowx['sname'];
		$xsid=strtoupper($rowx['id']);
		
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
		
		$jum_markah=0;$jum_pelajar=0;
		$sql="select point from exam where sch_id=$xsid and year='$year' and cls_level=$clslevel and sub_code='$sc' and examtype='$exam'";
		$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		while ($row2=mysql_fetch_row($res2)){
		$point=$row2[0];
			if(is_numeric($point)){
				if($si_exam_use_th){
					$jum_markah=$jum_markah+$point;
					$jum_pelajar++;
				}else{
					if($gred!="TH"){
						$jum_markah=$jum_markah+$point;
						$jum_pelajar++;
					}
				}
			}
		}

		
		$bg="$bglyellow";
?>
  <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
  	<td id="myborder" align="right"><?php //echo $q?></td>
    <td id="myborder" align="right"><?php //echo $sc?></td>
	<td id="myborder" align="left">&nbsp;&nbsp;-&nbsp;<?php echo "$xsname";?></td>
	<td id="myborder" align="center"><?php echo "$total_stu";?></td>
	<td id="myborder" align="center"><?php echo "$total_th";?></td>
	<td id="myborder" align="center"><?php echo "$total_hadir";?></td>
<?php 
	$gp=0;$totalgp=0;
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
		$totalgp=$x*$gp+$totalgp;
?>
	<td id="myborder" align="center"><?php echo "$x";?></td>
	<td id="myborder" align="center"><?php if($total_hadir>0) echo round($x/$total_hadir*100); else echo "0"; ?></td>
<?php } ?>
	<td id="myborder" align="center"><?php echo "$total_pass";?></td>
	<td id="myborder" align="center"><?php if($total_hadir>0) printf("%.02f",$total_pass/$total_hadir*100); else echo "0"; ?></td>
	<td id="myborder" align="center"><?php echo "$total_fail";?></td>
	<td id="myborder" align="center"><?php if($total_hadir>0) printf("%.02f",$total_fail/$total_hadir*100); else echo "0"; ?></td>
	<td id="myborder" align="center"><?php if($jum_pelajar>0) printf("%.02f",$jum_markah/$jum_pelajar); else echo "0";  ?></td>
	<td id="myborder_lrb" align="center"><?php if($total_hadir>0) printf("%.02f",$totalgp/$total_hadir); else echo "0";  ?></td>
  </tr>
  <?php 
  
  }//while
  
  }//while besaq

  }//if($report_type=="3"){

if($report_type=="2"){

	$sql="select distinct(sub_code),sub_name from ses_sub where sch_id=$sid and year='$year' and cls_level=$clslevel $sqlclscode $sqlcons order by sub_grp,sub_name";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$sc=$row['sub_code'];
		$sn=strtoupper(stripslashes($row['sub_name']));
		$q++;
?>
		<tr bgcolor="#FAFAFA">
			<td id="myborder" align="center"><?php echo $q?></td>
			<td id="myborder"><?php echo $sc?></td>
			<td id="myborder"><?php echo "$sn";?></td>
			<td id="myborder" align="center"></td>
			<td id="myborder" align="center"></td>
			<td id="myborder" align="center"></td>
			<?php 
				$sql="select * from grading where name='$grading' and val>=0 order by val desc";
				$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				while($row2=mysql_fetch_assoc($res2)){
			?>
				<td id="myborder" align="center"></td>
				<td id="myborder" align="center"></td>
			<?php } ?>
			<td id="myborder" align="center"></td>
			<td id="myborder" align="center"></td>
			<td id="myborder" align="center"></td>
			<td id="myborder" align="center"></td>
			<td id="myborder" align="center"></td>
			<td id="myborder" align="center"></td>
		  </tr>


<?php
		
		
		$bg="$bglyellow";
			
		$minyear=$year-5;
		$j=0;
		for($yy=$year;$yy>$minyear;$yy--,$j++){
			
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
		
		$jum_markah=0;$jum_pelajar=0;
		$sql="select point from exam where sch_id=$sid and year='$yy' and cls_level=$clslevel $sqlclscode and sub_code='$sc' and examtype='$exam'";
		$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		while ($row2=mysql_fetch_row($res2)){
		$point=$row2[0];
			if(is_numeric($point)){
				if($si_exam_use_th){
					$jum_markah=$jum_markah+$point;
					$jum_pelajar++;
				}else{
					if($gred!="TH"){
						$jum_markah=$jum_markah+$point;
						$jum_pelajar++;
					}
				}
			}
		}

		//if($total_stu==0)
			//continue;
		
		
?>
  <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
  	<td id="myborder" align="center"></td>
    <td id="myborder"></td>
	<td id="myborder">&nbsp;&nbsp;-&nbsp;<?php echo "$yy";?></td>
	<td id="myborder" align="center"><?php echo "$total_stu";?></td>
	<td id="myborder" align="center"><?php echo "$total_th";?></td>
	<td id="myborder" align="center"><?php echo "$total_hadir";?></td>
<?php 
	$gp=0;$totalgp=0;
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
		$totalgp=$x*$gp+$totalgp;
?>
	<td id="myborder" align="center"><?php echo "$x";?></td>
	<td id="myborder" align="center"><?php if($total_hadir>0) echo round($x/$total_hadir*100); else echo "0"; ?></td>
<?php } ?>
	<td id="myborder" align="center"><?php echo "$total_pass";?></td>
	<td id="myborder" align="center" style="background-color:#33FFCC"><?php if($total_hadir>0) echo round($total_pass/$total_hadir*100); else echo "0"; ?>%</td>
	<td id="myborder" align="center"><?php echo "$total_fail";?></td>
	<td id="myborder" align="center" <?php if($total_fail>0){?> style="background-color:#FFCCCC" <?php } ?>><?php if($total_hadir>0) echo round($total_fail/$total_hadir*100); else echo "0"; ?>%</td>
	<td id="myborder" align="center"><?php if($jum_pelajar>0) printf("%.02f",$jum_markah/$jum_pelajar); else echo "0";  ?></td>
	<td id="myborder_lrb" align="center"><?php if($total_hadir>0) printf("%.02f",$totalgp/$total_hadir); else echo "0";  ?></td>
  </tr>
  
<?php 

} 

}
  
}
 ?>
</table>
<?php if($report_type=="0"){ ?>
<table width="100%" style="font-size:120% " cellspacing="0">
  <tr>
    <td id="mytabletitle" align="right"  width="90%" colspan="2"><?php echo "Gred Rata-rata Sekola (GSP) " ;?> :</td>
    <td id="mytabletitle" align="center" width="10%"><?php echo number_format("$gps",2);?></td>
  </tr>
  <tr>
    <td id="mytabletitle" align="right" width="90%"  colspan="2"><?php echo $lg_pass_percentage ;?> :</td>
    <td id="mytabletitle" align="center" width="10%"><?php echo number_format("$avgperlulus",2);?>%</td>
  </tr>
</table>
<?php } ?>
<table width="100%" id="mytitle">
  <tr>
    <td align="center" width="33%"><?php echo "$lg_sekolah_tt1";?><br><br><br><br><br><br></td>
    <td align="center" width="33%"><?php echo "$lg_sekolah_tt2";?><br><br><br><br><br><br></td>
    <td align="center" width="33%"><?php echo "$lg_sekolah_tt3";?><br><br><br><br><br><br></td>
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