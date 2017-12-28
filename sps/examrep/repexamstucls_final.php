<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
	verify('ADMIN|AKADEMIK|KEWANGAN|GURU');
	$username=$_SESSION['username'];
	$isprint=$_REQUEST['isprint'];
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['schid'];
	if($sid!=0){
		$sql="select * from sch where id=$sid";
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
	if($slvl=="")
		$slvl=0;
	$clscode=$_REQUEST['clscode'];
	if($clscode!=""){
			$sqlclscode="and ses_stu.cls_code='$clscode'";
			$sql="select * from cls where sch_id=$sid and code='$clscode'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=$row['name'];
			$clslevel=$row['level'];
			
			$sql="select * from type where val='$sid' and prm='$clslevel' and grp='classlevel'";
    		$res=mysql_query($sql)or die("query failed:".mysql_error());
        	$row=mysql_fetch_assoc($res);
			$grading=$row['code'];
			
	}
	$year=$_REQUEST['year'];
	if($year=="")
		$year=date('Y');
		
	$sql="select * from ses_cls where year='$year' and cls_code='$clscode' and sch_id=$sid ";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$row2=mysql_fetch_assoc($res2);
	$gurukelas=$row2['usr_name'];
		
	$examp1="PS1";
	$examp2="PS2";
	if(($sid==2)&&($clslevel==3))
		$examname="Peperiksaan Syahadah I'dadi";
	else
		$examname="Peperiksaan Penggal 2";
	
		
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<script language="JavaScript">
function processform(operation){
		if(document.myform.exam.value==""){
			alert("Please select exam");
			document.myform.exam.focus();
			return;
		}
		if(document.myform.sid.value==""){
			alert("Please select school");
			document.myform.sid.focus();
			return;
		}
		document.myform.submit();
		
}

</script>

<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="my.js" type="text/javascript"></script>
</head>
<body style="width:100% ">

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input name="clscode" type="hidden" id="clscode" value="<?php echo $clscode;?>">
	<input name="year" type="hidden" id="year" value="<?php echo $year;?>">
	<input name="sid" type="hidden" id="year" value="<?php echo $sid;?>">
	
<div id="content">
<div id="mypanel"  class="printhidden">
<div id="mymenu" align="center">
<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
<a href="#" onClick="window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
</div> <!-- end mymenu -->

</div><!-- end mypanel -->

<div id="story">

<div id="mytitle" align="center">ANALISA LAPORAN AKADEMIK KELAS - AKHIR</div>
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
		</table>
	</td>
    <td width="50%">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="20%">Peperiksaan</td>
			<td width="1%">:</td>
			<td><?php echo $examname;?></td>
		  </tr>
		 <tr>
			<td>Guru Kelas</td>
			<td>:</td>
			<td><?php echo $gurukelas;?></td>
		  </tr>
		</table>
	
	</td>
  </tr>
</table>


<div id="fontsize" style="font-size:100%">

<table width="100%"  border="0" id="mytable">
  <tr>

    <td id="mytabletitle" rowspan="2" width="2%" align="center">NO</td>
    <td id="mytabletitle" rowspan="2" width="4%" align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="sort">PELAJAR</a></td>
	<td id="mytabletitle" rowspan="2" width="2%" align="center"><a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="sort">L/P</a></td>	
    <td id="mytabletitle" rowspan="2" width="20%" align="center"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="sort">NAMA</a></td>

<?php 
	$sql="select * from ses_sub where year='$year' and cls_code='$clscode' $sqlcons and sub_grptype=0 order by sub_grp";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$total_all_sub=mysql_num_rows($res2);
	if($total_all_sub>0)
		$size=round(50/$total_all_sub);
	while($row2=mysql_fetch_assoc($res2)){
		$p=$row2['sub_code'];
		$n=$row2['sub_name'];
?>
		<td id="mytabletitle" align="center" width="2%"><?php echo "$p";?></td>
<?php } ?>
	<td id="mytabletitle" rowspan="2" width="2%" align="center">JUM<br>SUB</td>
	<td id="mytabletitle" rowspan="2" width="2%" align="center">M<br>P1</td>
	<td id="mytabletitle" rowspan="2" width="2%" align="center">M<br>P2</td>
	<td id="mytabletitle" rowspan="2" width="2%" align="center">JUM<br>MAR</td>
	<td id="mytabletitle" rowspan="2" width="2%" align="center">%<br>P1</td>
	<td id="mytabletitle" rowspan="2" width="2%" align="center">%<br>P2</td>
	<td id="mytabletitle" rowspan="2" width="2%" align="center">%<br>AKHIR</td>
  </tr>
  <tr>
  <?php 
	$sql="select * from ses_sub where year='$year' and cls_code='$clscode' $sqlcons and sub_grptype=0 order by sub_grp";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row2=mysql_fetch_assoc($res2)){
		$p=$row2['sub_code'];
		$n=$row2['sub_name'];
?>
		<td id="mytabletitle" align="center" width="2%">P1:P2</td>
		<!-- <td id="mytabletitle" align="center" width="2%">P2</td>  -->
<?php } ?>
  </tr>
<?php 
if($clscode!=""){
	$q=0;
	//$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid $sqlstustatus $sqlisyatim $sqlisstaff $sqliskawasan $sqlclscode $sqlsearch and year='$year' $sqlsort";
	$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid $sqlstustatus $sqlisyatim $sqlisstaff $sqliskawasan $sqlclscode $sqlsearch and year='$year' $sqlsort";
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
  	<td align="center"><?php echo $q?></td>
    <td align="center"><?php echo $uid?></td>
	<td align="center"><?php if($sex=="1")echo "L";if($sex=="0")echo "P";?></td>
	<td ><?php echo "<a href=\"#\" title=\"Report Card\" onClick=\"newwindow('../examrep/repexamstuview.php?id=$uid&year=$year&exam=$examp2&sid=$sid',0)\">$name</a>";?></td>
	<?php
	$sql="select * from grading where name='$grading' and val>=0 order by val desc";
	$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
	$i=1;
	while($row3=mysql_fetch_assoc($res3)){
		$xg=$row3['grade'];
		$arr_grade[$xg]=0;
		$arr_gpoint[$xg]=$i++;
	}
	$jum_markah=0;
	$jum_subjek=0;
	$jum_markah1=0;
	$jum_subjek1=0;
	$jum_gp=0;
	$jum_gpsubjek=0;
	$sql="select * from ses_sub where year='$year' and cls_code='$clscode' $sqlcons and sub_grptype=0 order by sub_grp";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row2=mysql_fetch_assoc($res2)){
		$p=$row2['sub_code'];
		$n=$row2['sub_name'];
		//kira p1 sat dulu
		//$sql4="select * from exam where stu_uid='$uid' and sub_code='$p' and cls_code='$clscode' and year='$year' and examtype='$examp2'";
		$sql4="select * from exam where stu_uid='$uid' and sub_code='$p' and year='$year' and examtype='$examp1' and cls_code='$clscode'";
		$res5=mysql_query($sql4)or die("query failed:".mysql_error());
		$row5=mysql_fetch_assoc($res5);
		$point1=$row5['point'];
		$gred1=$row5['grade'];
		
		if($gred1=="TT"){
			$point1="";
			$gred1="";
		}
		if($gred1=="TH")
			$point1=0;
				
		if($gred1!=""){
			if(is_numeric($point1)){
				if($si_exam_use_th){
					$jum_markah1=$jum_markah1+$point1;
					$jum_subjek1++;
				}else{
					if($gred1!="TH"){
						$jum_markah1=$jum_markah1+$point1;
						$jum_subjek1++;
					}
				}
			}
		}

		
		//$sql4="select * from exam where stu_uid='$uid' and sub_code='$p' and cls_code='$clscode' and year='$year' and examtype='$examp2'";
		$sql4="select * from exam where stu_uid='$uid' and sub_code='$p' and year='$year' and examtype='$examp2' and cls_code='$clscode'";
		$res4=mysql_query($sql4)or die("query failed:".mysql_error());
		$row4=mysql_fetch_assoc($res4);
		$point=$row4['point'];
		$gred=$row4['grade'];
		
		if($gred=="TT"){
			$point="";
			$gred="";
		}
		if($gred=="TH")
			$point=0;
				
		if($gred!=""){
			if(is_numeric($point)){
				if($si_exam_use_th){
					$jum_markah=$jum_markah+$point;
					$jum_subjek++;
				}else{
					if($gred!="TH"){
						$jum_markah=$jum_markah+$point;
						$jum_subjek++;
					}
				}
			}
		}
		if(($gred!="")&&($gred!="TH")&&($gred!="TT")){
			$arr_grade[$gred]++;
			$jum_gpsubjek++;
			$jum_gp=$jum_gp+$arr_gpoint[$gred];
		}
?>
		<td ><?php echo "$point1:$point=";echo round(($point1+$point)/2)?></td>
		<!-- <td ><?php echo "$point";?></td> -->
<?php 	
} //habis loop subjeck

	$sql="select * from grading where name='$grading' and val>=0 order by val desc";
	$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
	$sumgred="";
	while($row3=mysql_fetch_assoc($res3)){
		$xg=$row3['grade'];
		if(	$arr_grade[$xg]>0)
			$sumgred=$sumgred.$arr_grade[$xg]."$xg";
	}
if($jum_subjek>0){
	$markah_purata=$jum_markah/$jum_subjek;
}else{
	$markah_purata=0;
}
//kira pulak p1 punya
if($jum_subjek1>0){
	$markah_purata1=$jum_markah1/$jum_subjek1;
}else{
	$markah_purata1=0;
}
//end kira p1
//kira final pulok
$markah_final=0;
if($jum_subjek1>0){
	if(($sid==2)&&($clslevel==3)){
		if($jum_subjek_idadi3>0)
			$markah_final=($jum_markah+$jum_markah1)/2/$jum_subjek_idadi3;
		else
			$markah_final=0;
	}
	else{
		$markah_final=($markah_purata+$markah_purata1)/2;
	}
}
?>
		<td align="center" ><?php if($total_all_sub>0)echo "$jum_subjek/$total_all_sub";?></td>
		<td align="center" ><?php echo $jum_markah1;?></td>
		<td align="center" ><?php echo $jum_markah;?></td>
		<td align="center" ><?php printf("%d",$jum_markah1+$jum_markah); ?></td>
		<td align="center" ><?php printf("%d",$markah_purata1); ?></td>
		<td align="center" ><?php printf("%d",$markah_purata); ?></td>		
		<td align="center" ><?php printf("%d",$markah_final);//echo "<br>$markah_final=($jum_markah+$jum_markah1)/2/$jum_subjek"; ?></td>	
  </tr>
<?php } }?>
</table>


</div><!-- end font size -->

<table width="100%" id="mytitle">
  <tr>
    <td align="center" width="33%">Setiausaha HEA</td>
    <td align="center" width="33%">Penolong Pengetua</td>
    <td align="center" width="33%">Pengerusi Lembaga Pengarah</td>
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
27/11/08: Gui
v2.6
15/11/08: fixed percent culculation
13/11/08: update interface
Author: razali212@yahoo.com
-->