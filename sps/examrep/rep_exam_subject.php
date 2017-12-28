<?php
	include_once('../etc/db.php');
	include_once('../etc/session.php');
	verify('ADMIN|AKADEMIK|KEWANGAN|GURU');
	$username=$_SESSION['username'];
	$isprint=$_REQUEST['isprint'];
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
		
	$slvl=0;
	if($sid!=0){
		$sql="select * from sch where id='$sid'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=$row['name'];
		$slevel=$row['level'];
		$slvl=$row['lvl'];
		$addr=$row['addr'];
		$state=$row['state'];
		$tel=$row['tel'];
		$fax=$row['fax'];
		$web=$row['url'];
		$school_img=$row['img'];
     	mysql_free_result($res);					  
	}
	$clvl=0;
	$clscode=$_REQUEST['clscode'];
	if($clscode!=""){
			//$sqlclscode="and ses_stu.cls_code='$clscode'";
			$sqlclscode="and exam.cls_code='$clscode'";
			$sql="select * from cls where sch_id=$sid and code='$clscode'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=$row['name'];
			$clslevel=$row['level'];
			$clvl=$clslevel;
	}
	else
		$clslevel=0;
	$year=$_REQUEST['year'];
	if($year=="")
		$year=date('Y');
		
	$exam=$_REQUEST['exam'];
	if($exam!=""){
			$sql="select * from type where grp='exam' and code='$exam'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $examname=$row['prm'];
	}

	$subcode=$_REQUEST['subcode'];
	$sql="select * from ses_sub where year='$year' and cls_code='$clscode' and sub_code='$subcode' and sch_id=$sid ";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$row2=mysql_fetch_assoc($res2);
	$subname=$row2['sub_name'];
	$gurusubjek=$row2['usr_name'];
	
	$sql="select * from ses_cls where year='$year' and cls_code='$clscode' and sch_id=$sid ";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$row2=mysql_fetch_assoc($res2);
	$gurukelas=$row2['usr_name'];
	
	$sql="select * from sub where code='$subcode' and level=$clslevel and sch_id=$sid";
    $res=mysql_query($sql)or die("$sql failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
	$gradingset=$row['grading'];
	$sql="select * from grading where name='$gradingset' order by val desc";
	$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
	$i=1;$j=1;
	while($row3=mysql_fetch_assoc($res3)){
		$xg=$row3['grade'];
		$isfail=$row3['sta'];
		$arr_grading_info[$xg]['grade']=0;
		$arr_grading_info[$xg]['point']=$j++;
		$arr_grading_info[$xg]['isfail']=$isfail;
	}
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>
<body style="background-image:url(img/bg_white.jpg);">

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="../examrep/rep_exam_subject">
	<input name="exam" type="hidden" id="exam" value="<?php echo $exam;?>">
	<input name="sid" type="hidden" id="sid" value="<?php echo $sid;?>">
	<input name="clscode" type="hidden" id="clscode" value="<?php echo $clscode;?>">
	<input name="subcode" type="hidden" id="subcode" value="<?php echo $subcode;?>">
	<input name="year" type="hidden" id="year" value="<?php echo $year;?>">
	
<div id="content">
<div id="mypanel"  class="printhidden">
<div id="mymenu" align="center">
<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
</div> <!-- end mymenu -->
</div><!-- end mypanel -->
<div id="story">

<div id="mytitle" align="center">ANALISA LAPORAN SUBJEK</div>
<table width="100%" id="mytitle">
  <tr>
    <td width="50%">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="20%"><?php echo $lg_sekolah;?></td>
			<td width="1%">:</td>
			<td><?php echo $sname;?></td>
		  </tr>
		  <tr>
			<td>Kelas</td>
			<td>:</td>
			<td><?php echo "$clsname / $year";?></td>
		  </tr>
		  <tr>
			<td>Guru Kelas</td>
			<td>:</td>
			<td><?php echo $gurukelas;?></td>
		  </tr>
		</table>
	</td>
    <td width="50%">
		<table width="100%"  border="0" cellpadding="0">
		<!-- 
		  <tr>
			<td width="20%">Peperiksaan</td>
			<td width="1%">:</td>
			<td><?php echo $examname;?></td>
		  </tr>
		 -->
		  <tr>
			<td>Subjek</td>
			<td>:</td>
			<td><?php echo $subname;?></td>
		  </tr>
		  <tr>
			<td>Guru Subjek</td>
			<td>:</td>
			<td><?php echo $gurusubjek;?></td>
		  </tr>
		  <tr>
			<td>Tarikh</td>
			<td>:</td>
			<td><?php echo date("d-m-Y");?></td>
		  </tr>
		</table>
	
	</td>
  </tr>
</table>

<table width="100%" id="mytable">
<tr>
<td width="60%" valign="top"> <!-- start right side -->

<div id="myborder" style="margin:0px 2px 0px 2px; font-weight:bold " align="center">PENILAIAN</div>
<table width="100%"  border="0" id="mytable">
  <tr>
    <td id="mytabletitle" width="3%" align="center">NO</td>
    <td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="sort">PELAJAR</a></td>
	<td id="mytabletitle" width="2%" align="center"><a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="sort">L/P</a></td>
    <td id="mytabletitle" width="40%" ><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="sort">NAMA</a></td>
  </tr>
<?php 
if($clscode!=""){
	$q=0;
	$markah_tertinggi=0;
	$markah_terendah=100;
	$markah_jumlah=0;
	$jumlah_gp=0;
	$jumlah_fail=0;
	//$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid and ses_stu.year='$year' $sqlclscode $sqlsort";
	$sql="select stu.*,exam.cls_name from stu INNER JOIN exam ON stu.uid=exam.stu_uid where exam.sch_id=$sid and exam.year='$year' $sqlclscode $sqlsort";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$num=mysql_num_rows($res);
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$name=$row['name'];
		$sex=$row['sex'];
		if($q++%2==0)
			$bg="bgcolor=#FAFAFA";
		else
			$bg="";
?>
  <tr <?php echo $bg?>>
  	<td id="myborder" align="center"><?php echo $q?></td>
	<td id="myborder" align="center"><?php echo $uid?></td>
	<td id="myborder" align="center"><?php if($sex=="Lelaki")echo "L";if($sex=="Perempuan")echo "P";?></td>
	<td id="myborder"><?php echo $name?></td>
	
  </tr>
<?php } }?>
 </table> <!-- end of student name -->
 <table id="mytable" width="100%"> <!-- start of ringkasan title -->
  <tr>
    <td id="mytabletitle" align="right">RINGKASAN PENCAPAIAN</td>
  </tr>
  <tr>
    <td id="myborder" align="right">Bilangan Pelajar</td>
  </tr>
  <tr>
    <td id="myborder" align="right">Tidak Ditaksir</td>
  </tr>
  <tr>
    <td id="myborder" align="right">Tidak Hadir</td>
  </tr>
  <tr>
    <td id="myborder" align="right">Pelajar Hadir</td>
  </tr>
<?php
$sql="select * from grading where name='$gradingset' and val>=0 order by val desc";
$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
while($row3=mysql_fetch_assoc($res3)){
	$xg=$row3['grade'];
?>
  <tr>
    <td id="myborder" align="right">Jumlah <?php echo $xg;?></td>
  </tr>
<?php } ?>
  <tr>
    <td id="myborder" align="right">Pelajar Gagal</td>
  </tr>
  <tr>
    <td id="myborder" align="right">Pelajar Lulus</td>
  </tr>
  <tr>
    <td id="myborder" align="right">Nilai Tertinggi</td>
  </tr>
  <tr>
    <td id="myborder" align="right">Nilai Terendah</td>
  </tr>
  <tr>
    <td id="myborder" align="right">Nilai Purata</td>
  </tr>
  <tr>
    <td id="myborder" align="right">GPMP</td>
  </tr>
</table>

</td><!-- end right side -->

<?php
	$sql="select * from type where grp='exam' and (lvl=0 or lvl=$clvl) and (sid=0 or sid=$sid) order by idx";
  	$resx=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$num=mysql_num_rows($resx);
	$width=50/$num;
   	while($rowx=mysql_fetch_assoc($resx)){
		$examcode=$rowx['code'];
		$sql="select * from grading where name='$gradingset' order by val desc";
		$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
		$i=1;$j=1;
		while($row3=mysql_fetch_assoc($res3)){
			$xg=$row3['grade'];
			$isfail=$row3['sta'];
			$arr_grading_info[$xg]['grade']=0;
			$arr_grading_info[$xg]['point']=$j++;
			$arr_grading_info[$xg]['isfail']=$isfail;
		}
?>
<td valign="top" align="center"><!-- start left side -->

<div id="myborder" style="margin:0px 2px 0px 2px; font-weight:bold "><a href="../examrep/rep_exam_subject_one.php?exam=<?php echo $examcode;?>&year=<?php echo $year;?>&sid=<?php echo $sid;?>&clscode=<?php echo $clscode;?>&subcode=<?php echo $subcode;?>"><?php echo $examcode;?></a></div>
<table width="100%" id="mytable">
  <tr>
    <td id="mytabletitle" align="center" width="50%">M</td>
    <td id="mytabletitle" align="center" width="50%">G</td>
  </tr>
  
<?php 
if($clscode!=""){
	$q=0;
	$markah_tertinggi=0;
	$markah_terendah=100;
	$markah_jumlah=0;
	$jumlah_gp=0;
	$jumlah_fail=0;
	$markah_purata=0;
	//$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid and ses_stu.year='$year' $sqlclscode $sqlsort";
	$sql="select stu.*,exam.cls_name,point,grade from stu INNER JOIN exam ON stu.uid=exam.stu_uid where exam.sch_id=$sid and exam.year='$year' and sub_code='$subcode' and cls_code='$clscode' and year='$year' and examtype='$examcode' $sqlsort";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$num=mysql_num_rows($res);
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$name=$row['name'];
		$point=$row['point'];
		$grade=$row['grade'];
		if($point==""){
			$point="";
			$grade="TT";
		}
		if(($grade=="TT")||($grade=="TH"))
			$point=0;
		//$arr_grade[$grade]++;
		$arr_grading_info[$grade]['grade']=$arr_grading_info[$grade]['grade']+1;
		if($arr_grading_info[$grade]['isfail'])
			$jumlah_fail++;
		if(($grade!="TT")&&($grade!="TH")){
			if($markah_terendah>$point)
				$markah_terendah=$point;
			if($markah_tertinggi<$point)
				$markah_tertinggi=$point;
			$markah_jumlah=$markah_jumlah+$point;
			$jumlah_gp=$jumlah_gp+$arr_grading_info[$grade]['point'];
		}
		if($q++%2==0)
			$bg="bgcolor=#FAFAFA";
		else
			$bg="";
?>
  <tr <?php echo $bg?>>
		<td id="myborder" align="center" ><?php echo "$point";?></td>
		<td id="myborder" align="center" ><?php echo "$grade";?></td>
  </tr>
<?php } } ?>
</table>
<?php $jum_pelajar_ditaksir=$q-$arr_grading_info['TT']['grade']-$arr_grading_info['TH']['grade']; ?>
<table id="mytable" width="100%">
  <tr><!-- Jumlah Pelajar -->
    <td id="mytabletitle" align="center" width="50%">%</td>
    <td id="mytabletitle" align="center" width="50%">BIL</td>
  </tr>
  <tr><!-- Jumlah Pelajar -->
  	<td id="myborder" align="center">100</td>
    <td id="myborder" align="center"><?php echo $q;?></td>
  </tr>
  <tr><!-- Tidak Ditaksir -->
    <td id="myborder" align="center"><?php if($q>0) echo round($arr_grading_info['TT']['grade']/$q*100);else echo "&nbsp;"?></td>
    <td id="myborder" align="center"><?php echo $arr_grading_info['TT']['grade'];?></td>
  </tr>
    <tr><!-- Tidak Hadir -->
    <td id="myborder" align="center"><?php if($q>0) echo round($arr_grading_info['TH']['grade']/$q*100);else echo "&nbsp;"?></td>
	<td id="myborder" align="center"><?php echo $arr_grading_info['TH']['grade'];?></td>
  </tr>
  <tr style="font-weight:bold "><!-- Jumlah Pelajar -->
    <td id="myborder" align="center"><?php if($q>0) echo round($jum_pelajar_ditaksir/$q*100);else echo "&nbsp;"?></td>
    <td id="myborder" align="center"><?php echo $jum_pelajar_ditaksir;?></td>
  </tr>
 
 <?php
if(($jum_pelajar_ditaksir>0)&&(is_numeric($markah_terendah))) {
	$sql="select * from grading where name='$gradingset' and point<=$markah_terendah order by val desc limit 1";
	$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
	$row3=mysql_fetch_assoc($res3);
	$gred_terendah=$row3['grade'];
	
	$sql="select * from grading where name='$gradingset' and point<=$markah_tertinggi order by val desc limit 1";
	$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
	$row3=mysql_fetch_assoc($res3);
	$gred_tertinggi=$row3['grade'];

	$markah_purata = $markah_jumlah/$jum_pelajar_ditaksir;
	$sql="select * from grading where name='$gradingset' and point<=$markah_purata order by val desc limit 1";
	$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
	$row3=mysql_fetch_assoc($res3);
	$gred_purata=$row3['grade'];
}else{
	$markah_terendah=0;
	$markah_tertinggi=0;
}
$sql="select * from grading where name='$gradingset' and val>=0 order by val desc";
$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
while($row3=mysql_fetch_assoc($res3)){
	$xg=$row3['grade'];
?>
  <tr>
    <td id="myborder" align="center"><?php if($jum_pelajar_ditaksir>0) echo round($arr_grading_info[$xg]['grade']/$jum_pelajar_ditaksir*100);?></td>
    <td id="myborder" align="center"><?php if($jum_pelajar_ditaksir>0) echo $arr_grading_info[$xg]['grade']; else echo "&nbsp;";?></td>
  </tr>
  
<?php } ?>
  <tr>
    <td id="myborder" align="center"><?php if($jum_pelajar_ditaksir>0)echo round($jumlah_fail/$jum_pelajar_ditaksir*100);?></td>
	<td id="myborder" align="center"><?php if($jum_pelajar_ditaksir>0) echo $jumlah_fail;else echo "&nbsp;"?></td>
  </tr>
  <tr>
    <td id="myborder" align="center"><?php if($jum_pelajar_ditaksir>0) echo round(($jum_pelajar_ditaksir-$jumlah_fail)/$jum_pelajar_ditaksir*100);?></td>
	<td id="myborder" align="center"><?php if($jum_pelajar_ditaksir>0) echo $jum_pelajar_ditaksir-$jumlah_fail;else echo "&nbsp;"?></td>
  </tr>
  <tr>
    <td id="myborder" align="center"><?php if($jum_pelajar_ditaksir>0) echo $markah_tertinggi; else echo "&nbsp;"?></td>
    <td id="myborder" align="center"><?php if($jum_pelajar_ditaksir>0) echo $gred_tertinggi; else echo "&nbsp;"?></td>
  </tr>
  <tr>
    <td id="myborder" align="center"><?php if($jum_pelajar_ditaksir>0) echo $markah_terendah; else echo "&nbsp;"?></td>
    <td id="myborder" align="center"><?php if($jum_pelajar_ditaksir>0) echo $gred_terendah; else echo "&nbsp;"?></td>
  </tr>
  <tr>
    <td id="myborder" align="center"><?php if($jum_pelajar_ditaksir>0) printf("%d",$markah_purata); else echo "&nbsp;"?></td>
    <td id="myborder" align="center"><?php if($jum_pelajar_ditaksir>0) echo $gred_purata; else echo "&nbsp;";?></td>
  </tr>
   <tr>
    <td id="myborder" align="center"><?php if($jum_pelajar_ditaksir>0) printf("%.02f",$jumlah_gp/$jum_pelajar_ditaksir); else echo "&nbsp;"?></td>
    <td id="myborder" align="center"></td>
  </tr>
</table>




</td><!-- end left side -->
<?php } ?>
</tr>
</table>


</div></div>
<input name="curr" type="hidden">
	<input name="sort" type="hidden" value="<?php echo $sort;?>">
	<input name="order" type="hidden" value="<?php echo $order;?>">
</form>
</body>
</html>
<!-- 
v2.7
27/11/08: culculate fail student
2.6
13/11/08: new module
Author: razali212@yahoo.com
-->