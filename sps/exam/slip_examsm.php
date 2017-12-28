<?php
//29/03/2010 - patch clsname
//02/07/2010 - update logo
//110626   - use credit hr
$vmod="v6.0.2";
$vdate="110626";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("");
$username = $_SESSION['username'];

		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
			
		$stu=$_REQUEST['stu'];//case of multi student
		$uid=$_REQUEST['uid'];//case of one student
		$exam=$_REQUEST['exam'];
		$year=$_REQUEST['year'];
		$clscode=$_REQUEST['clscode'];
				$clslevel="0";
	
		if(count($stu)==0)
			$stu[0]=$uid;
		if(count($stu)==1)
			$uid=$stu[0];
	
		$sql="select * from type where grp='reportcard' and sid=$sid";
		$res=mysql_query($sql) or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$prm=$row['prm'];
			$CONFIG[$prm]['val']=$row['val'];
			$CONFIG[$prm]['code']=$row['code'];
			$CONFIG[$prm]['etc']=$row['etc'];
			$CONFIG[$prm]['des']=$row['des'];
		}
		$sql="select * from sch where id=$sid";
        $res=mysql_query($sql)or die("$sql failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=$row['name'];
		$schlevel=$row['level']; 
		$addr=$row['addr'];
		$state=$row['state'];
		$tel=$row['tel'];
		$fax=$row['fax'];
		$web=$row['url'];
		$school_img=$row['img'];
		$daerah=$row['daerah'];
        mysql_free_result($res);
		
		if($year=="")
			$year=date('Y');
		
		if($uid!=""){
			$sql="select * from ses_stu where stu_uid='$uid' and year='$year'";
			$res=mysql_query($sql) or die("$sql failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$clslevel=$row['cls_level'];
		}
		if($clscode!=""){
			$sql="select * from cls where sch_id='$sid' and code='$clscode'";
			$res=mysql_query($sql)or die("$sql failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$clslevel=$row['level'];
		}
		$sql="select * from type where sid='$sid' and prm='$clslevel' and grp='classlevel'";
        $res=mysql_query($sql)or die("$sql failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$grading=$row['code'];
		$sql="select * from grading where name='$grading' order by val desc";
		$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
		$i=1;
		while($row3=mysql_fetch_assoc($res3)){
			$xg=$row3['grade'];
			$xgp=$row3['gp'];
			$arr_grade[$xg]=0;
			$arr_point[$xg]=$xgp;
		}
		
		$sqlkepalasekolah="select * from usr where job='Kepala Sekolah' and sch_id='1'";
		$reskepalasekolah=mysql_query($sqlkepalasekolah)or die("$sqlkepalasekolah query failed:".mysql_error());
		$rowkepalasekolah=mysql_fetch_assoc($reskepalasekolah);
		$kepalasekolah=$rowkepalasekolah['name'];
		
		 $sqltname="select * from ses_cls where cls_code='$clscode' and year='$year' and sch_id='$sid'";
		$restname=mysql_query($sqltname)or die("$sqltname query failed:".mysql_error());
		$rowtname=mysql_fetch_assoc($restname);
		$tname=$rowtname['usr_name'];
		
		if($exam!="")
			$sql="select * from type where grp='exam' and code='$exam' and (lvl=0 or lvl='$clslevel') and (sid=0 or sid=$sid) order by idx";
		else
			$sql="select * from type where grp='exam' and (lvl=0 or lvl='$clslevel') and (sid=0 or sid=$sid) order by idx";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$examname=$row['prm'];
		$exam=$row['code'];
		
			$sql="select * from type where grp='examconf' and sid=$sid and prm='RANKING_FLOW'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $rankingflow=$row['val'];
			
			if($rankingflow=="1")	
				$sqlsort_ranking="order by gpk asc,avg desc";//rendah terbaik - default
			elseif($rankingflow=="2")	
				$sqlsort_ranking="order by gpk desc,avg desc";//tinggi terbaik
			elseif($rankingflow=="3")	
				$sqlsort_ranking="order by avg desc";//percentage
			elseif($rankingflow=="4")	
				$sqlsort_ranking="order by total_point desc";//total mark
			else
				$sqlsort_ranking="order by gpk asc,avg desc"; //rendah terbaik
				
		if(($schlevel=="Menengah")&&($clslevel==6))
			$sqlsort_ranking="order by gpk desc,avg desc";
		
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<input type="hidden" name="p" value="../exam/slip_exam">
		<input type="hidden" name="sid" value="<?php echo "$sid";?>">
		<input type="hidden" name="uid" value="<?php echo "$uid";?>">
		<input type="hidden" name="clscode" value="<?php echo "$clscode";?>">
		
<div id="content">

<div id="mypanel" class="printhidden">
	<div id="mymenu" align="center">
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
		<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
		<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
	</div>
	
	<div id="mytable" align="right">

<?php if(count($stu)<=1){?>
<select name="year" id="year" onchange="document.myform.submit();">
<?php
            echo "<option value=$year>$lg_session $year</option>";
			$sql="select year from ses_stu where stu_uid='$uid' and year!='$year' order by year desc";
            $res=mysql_query($sql)or die("$sql failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['year'];
                        echo "<option value=\"$s\">$lg_session $s</option>";
            }
            mysql_free_result($res);					  
?>
</select>

<select name="exam" onchange="document.myform.submit();">
<?php	
      				if($exam=="")
						echo "<option value=\"\">- $lg_select -</option>";
			//		else
			//			echo "<option value=\"$exam\">$examname</option>";
			$sql="select * from type where grp='exam' and (sid=0 or sid=$sid) order by idx";
            		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $a=$row['prm'];
			$b=$row['code'];
			if($b==$exam){$selected="selected";}else{$selected="";}
                        echo "<option value=\"$b\" $selected>$a</option>";
            		}
            		mysql_free_result($res);	
?>
</select>
<?php }else{ ?>
<input type="hidden" name="year" value="<?php echo $year;?>">
<input type="hidden" name="exam" value="<?php echo $exam;?>">
<?php }?>
<br>
<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
</div>

</div><!-- end mypanel -->
<?php
$totalpage=count($stu);
for($numberofstudent=0;$numberofstudent<count($stu);$numberofstudent++){
		$pageno++;
		$uid=$stu[$numberofstudent];
		echo "<input type=\"hidden\" name=\"stu[]\" value=\"$uid\">";
		
		$sql="select * from ses_stu where stu_uid='$uid' and year='$year'";
		$res=mysql_query($sql) or die(mysql_error());
		$row=mysql_fetch_assoc($res);
		$ccode=$row['cls_code'];
		$cname=stripslashes($row['cls_name']);
		
		$sql="select * from stu where uid='$uid' and sch_id=$sid";
		$res=mysql_query($sql) or die("$sql failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$name=stripslashes($row['name']);
		$ic=$row['ic'];
		$sex=$row['sex'];
		$mentor=$row['mentor'];
		$rdate=$row['rdate'];
		$file=$row['file'];
		$totaljk=$row['hafazan_totaljk'];
		$p1name=$row['p1name'];

		$sql="select * from usr where uid='$mentor'";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$mentorname=$row['name'];

		
		$sql="select stu.*,examrank.* from stu INNER JOIN examrank ON stu.uid=examrank.stu_uid where examrank.sch_id=$sid and year='$year' and examrank.cls_level='$clslevel' and exam='$exam' and gpk>0 $sqlsort_ranking";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$total_tahap=mysql_num_rows($res);
		$kedudukan_tahap = 0;
		while($row=mysql_fetch_assoc($res)){
			$xuid=$row['uid'];
			$kedudukan_tahap++;
			if($xuid==$uid)
				break;
		}
		
		$sql="select stu.*,examrank.* from stu INNER JOIN examrank ON stu.uid=examrank.stu_uid where examrank.sch_id=$sid and year='$year' and cls_code='$ccode' and exam='$exam' and gpk>0 $sqlsort_ranking";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$total_kelas=mysql_num_rows($res);
		$kedudukan_kelas = 0;
		while($row=mysql_fetch_assoc($res)){
			$xuid=$row['uid'];
			$purata_markah=$row['avg'];
			$jumlah_subjek=$row['total_sub'];
			$kedudukan_kelas++;
			if($xuid==$uid)
				break;
		}
		
		$sql="select stu.*,examrank.* from stu INNER JOIN examrank ON stu.uid=examrank.stu_uid where examrank.sch_id=$sid and year='$year' and cls_level='$clslevel' and sex='$sex' and exam='$exam' and gpk>0 $sqlsort_ranking";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$total_sex=mysql_num_rows($res);
		$kedudukan_sex = 0;
		while($row=mysql_fetch_assoc($res)){
			$xuid=$row['uid'];
			$kedudukan_sex++;
			if($xuid==$uid)
				break;
		}


?>
<div id="story" >
<div id="mytitlebg" class="printhidden" style="color:#CCCCCC" align="right">PAGE <?php echo "$pageno/$totalpage";?></div>
<?php 
if($CONFIG['FILE_HEADER']['val'])
	include ($CONFIG['FILE_HEADER']['etc']); 
else
	include ('../inc/header_school.php');
?>
<div id="mytitlebg" align="center"><?php echo "LAPORAN HASIL BELAJAR PESERTA DIDIK";?> </div>

<table width="100%" >
  <tr>
  <?php if(($file!="")&&($CONFIG['SHOW_PICTURE']['val'])){?>
  	<td width="8%" align="center">
		<img name="picture" src="<?php if($file!="") echo "$dir_image_student$file"; ?>"  width="70" height="75" id="myborderfull" style="padding:3px 3px 3px 3px ">
	</td>
 <?php } ?>
    <td width="50%" valign="top">	
	<table width="100%" >
		<tr>
        <td width="20%" valign="top"><?php echo "NAMA SEKOLAH";?></td>
        <td width="1%" valign="top">:</td>
        <td width="79%"><?php $sname=stripslashes($sname);echo strtoupper("$sname");?></td>
      </tr>
		<tr>
        <td width="20%" valign="top"><?php echo "ALAMAT SEKOLAH";?></td>
        <td width="1%" valign="top">:</td>
        <td width="79%"><?php echo strtoupper("$addr $state");?></td>
      </tr>
      <tr>
        <td width="30%" valign="top"><?php echo "NAMA PESERTA DIDIK";?></td>
        <td width="1%" valign="top">:</td>
        <td width="70%"><?php echo "$name";?></td>
      </tr>
      <tr>
        <td><?php echo "NOMOR INDUK";?></td>
        <td>:</td>
        <td><?php echo "$uid";?> </td>
      </tr>
      <!--<tr>
        <td><?php echo strtoupper($lg_ic_number);?></td>
        <td>:</td>
        <td><?php echo "$ic";?> </td>
      </tr>
       <tr>
        <td><?php echo strtoupper($lg_register_date);?></td>
        <td>:</td>
        <td><?php list($xy,$xm,$xd)=split('[-]',$rdate); echo "$xd-$xm-$xy";?> </td>
      </tr>-->
	   <tr>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </table>


	</td>
    <td width="50%" valign="top">
	
	<table width="100%" >
      <tr>
        <td><?php echo strtoupper($lg_class);?></td>
        <td>:</td>
        <td><?php echo strtoupper("$cname");?> </td>
      </tr>
      <tr>
        <td><?php echo "SEMESTER";?></td>
        <td>:</td>
        <td><?php if($exam=='UN'||$exam=='UAS'){$sem="-";}if($exam=='PS1'||$exam=='UTSGA'){$sem="1 (SATU)";}if($exam=='PS2'||$exam=='UTSGE'){$sem="2 (DUA)";} echo $sem;?> </td>
      </tr>
      <tr>
        <td><?php echo "TAHUN PENGAJIAN";?></td>
        <td>:</td>
        <td><?php echo strtoupper("$year");?> </td>
      </tr>
	  <tr>
        <td><?php echo strtoupper($lg_exam);?></td>
        <td>:</td>
        <td><?php echo strtoupper("$examname");?></td>
      </tr>
      
	  <?php if($CONFIG['SHOW_MENTOR']['val']){?>
	  <tr>
        <td>MENTOR</td>
        <td>:</td>
        <td><?php echo strtoupper("$mentorname");?> </td>
      </tr>
	  <?php } ?>
    </table>
 	</td>
  </tr>
</table>

<table width="100%" border="0">
  <tr>
    <td valign="top" id="myborder">
   <?php
		echo "<table width=\"100%\" cellspacing=0 border=\"0\">";
		echo "<tr id=mytitlebg style=font-size:100%>";
		echo "<td id=\"myborder\" width=\"5%\" rowspan=\"2\" align=\"center\">No</td>";
		//echo "<td id=\"myborder\" width=\"8%\" rowspan=\"2\" align=\"center\">&nbsp;$lg_code</td>";
		echo "<td id=\"myborder\" width=\"30%\" rowspan=\"2\">&nbsp;Mata Pelajaran</td>";
		echo "<td id=\"myborder\" width=\"5%\" rowspan=\"2\" align=\"center\">&nbsp;KKM</td>";
		echo "<td id=\"myborder\" width=\"20%\" colspan=\"2\" align=center>Nilai</td>";
		echo "<td id=\"myborder\" width=\"40%\" rowspan=\"2\" align=center>Deskripsi Kemajuan Belajar</td>";
		echo "</tr>";
		echo "<tr id=mytitlebg style=font-size:100%>";
		echo "<td id=\"myborder\" width=\"5%\" align=center>Angka</td>";
		echo "<td id=\"myborder\" width=\"15%\" align=center>Huruf</td>";
		echo "</tr>";
		$totalsubject=0;
		$totalpoint=0;
		$totalgp=0;
		$totalcredit=0;
		$xx=0;
		$subno=1;
		$subsubno=a;
		$rowspan=0;
		if($CONFIG['SHOW_SCORE']['val'])
			$sql3="select * from type where grp='subgroup' and code='1' and sid=$sid order by idx";
		else
			$sql3="select * from type where grp='subgroup' and val=0 and code='1'  and sid=$sid  order by idx";
        $res3=mysql_query($sql3)or die("query failed:".mysql_error());
        while($row3=mysql_fetch_assoc($res3)){
        	$contruct=$row3['prm'];
			$contype=$row3['val'];
			$conview=$row3['etc'];

			$sql4="select * from exam left join sub on exam.sub_code=sub.code where exam.sub_grp='$contruct' and exam.stu_uid='$uid' and exam.year='$year' and exam.examtype='$exam' and exam.sch_id='$sid' and sub.sch_id='$sid' group by sub.code order by sub.idx,exam.sub_code";
			$res4=mysql_query($sql4)or die("$sql4 query failed:".mysql_error());
			$q=0;
			while($row4=mysql_fetch_assoc($res4)){
				$sub_code=$row4['sub_code'];
				$sub_name=$row4['sub_name'];
				$point=$row4['point'];
				$grade=$row4['grade'];
				$credit=$row4['credit'];
				$kkm=$row4['kkm'];
				$gradingtype=$row4['gradingtype'];
				$gp=$row4['gp'];
				if($grade=="TT"){
					continue; //dont show tt
				}


				if($grade=="TH")
					$point=0;

				if($conview!='1'){

				if(($q++%2)==0)
					echo "<tr bgcolor=#FFFFFF>";
				else
					echo "<tr bgcolor=#FFFFFF>";
				
				
				echo "<td id=\"myborder\" align=\"center\">$subno</td>";
				//echo "<td id=\"myborder\" width=\"8%\">&nbsp;$sub_code</td>";
				echo "<td id=\"myborder\" width=\"30%\">&nbsp;";
				echo strtoupper($sub_name)."</td>";
				
				
				if($credit>0){
						$arr_grade[$grade]=$arr_grade[$grade]+1;
						if($gred!="TT"){
									if(is_numeric($point)){
										$totalpoint=$totalpoint+$point;
										$totalsubject=$totalsubject+1;
									}
									if($gp>=0){
										$totalgp=$gp*$credit+$totalgp;
										$totalcredit=$totalcredit+$credit;
									}
						}
				}
				if($gradingtype==1)
					$point="-";
				if($point!="" || $point!='-'){
					$point=number_format($point,2);
					list($satu,$dua)=explode(".",$point);
					if(substr($dua,-1)==0){$dua=substr($dua,-2,1);}
					if($dua=="0"){$pointv=$satu;}else{$pointv=$satu.'.'.$dua;}
					$sqlpchar="select * from number where digit='$pointv'";
					$respchar=mysql_query($sqlpchar)or die("$sqlpchar query failed:".mysql_error());
					$rowpchar=mysql_fetch_assoc($respchar);
					$point_char=$rowpchar['word'];
				}
				echo "<td id=\"myborder\" align=\"center\">$kkm</td>";
				echo "<td id=\"myborder\" align=\"center\">$point</td>";
				echo "<td id=\"myborder\">";
				echo strtoupper($point_char)."</td>";
				
				$sqlsubnote="select * from sub_stu_summary where uid='$uid' and year='$year' and exam='$exam' and cls='$ccode' and sub_code='$sub_code'";
				$ressubnote=mysql_query($sqlsubnote)or die("$sqlsubnote query failed:".mysql_error());
				$rowsubnote=mysql_fetch_assoc($ressubnote);
				$subnote=$rowsubnote['sub_msg'];
				$subnote=strtoupper($subnote);
				
				echo "<td id=\"myborder\">$subnote</td>";
				echo "</tr>";
				
				}else{
				
				
				//if(($q++%2)==0)
				//	echo "<tr bgcolor=#FFFFFF>";
				//else
					if($subsubno==a){
					echo "<tr bgcolor=#FFFFFF>";
					echo "<td id=\"myborder\" align=\"center\">$subno</td>";
					echo "<td id=\"myborder\" width=\"30%\">&nbsp;<b>";
					echo strtoupper($contruct)."</b></td>";
					echo "<td id=\"myborder\" align=\"center\">&nbsp;</td>";
					echo "<td id=\"myborder\" align=\"center\">&nbsp;</td>";
					echo "<td id=\"myborder\">&nbsp;</td>";
					echo "<td id=\"myborder\">&nbsp;</td>";
				echo "</tr>";
					}
					echo "<tr bgcolor=#FFFFFF>";
					
				echo "<td id=\"myborder\" align=\"center\"></td>";
				//echo "<td id=\"myborder\" width=\"8%\">&nbsp;$sub_code</td>";
				echo "<td id=\"myborder\" width=\"30%\">&nbsp;$subsubno. ";
				echo strtoupper($sub_name)."</td>";
				
				
				if($credit>0){
						$arr_grade[$grade]=$arr_grade[$grade]+1;
						if($gred!="TT"){
									if(is_numeric($point)){
										$totalpoint=$totalpoint+$point;
										$totalsubject=$totalsubject+1;
									}
									if($gp>=0){
										$totalgp=$gp*$credit+$totalgp;
										$totalcredit=$totalcredit+$credit;
									}
						}
				}
				if($gradingtype==1)
					$point="-";
				if($point!="" || $point!='-'){
					$point=number_format($point,2);
					list($satu,$dua)=explode(".",$point);
					if(substr($dua,-1)==0){$dua=substr($dua,-2,1);}
					if($dua=="0"){$pointv=$satu;}else{$pointv=$satu.'.'.$dua;}
					$sqlpchar="select * from number where digit='$pointv'";
					$respchar=mysql_query($sqlpchar)or die("$sqlpchar query failed:".mysql_error());
					$rowpchar=mysql_fetch_assoc($respchar);
					$point_char=$rowpchar['word'];
				}
				echo "<td id=\"myborder\" align=\"center\">$kkm</td>";
				echo "<td id=\"myborder\" align=\"center\">$point</td>";
				echo "<td id=\"myborder\">";
				echo strtoupper($point_char)."</td>";
				
				$sqlsubnote="select * from sub_stu_summary where uid='$uid' and year='$year' and exam='$exam' and cls='$ccode' and sub_code='$sub_code'";
				$ressubnote=mysql_query($sqlsubnote)or die("$sqlsubnote query failed:".mysql_error());
				$rowsubnote=mysql_fetch_assoc($ressubnote);
				$subnote=$rowsubnote['sub_msg'];
				$subnote=strtoupper($subnote);
				
				echo "<td id=\"myborder\">$subnote</td>";
				echo "</tr>";
				
				$subsubno++;
				}
			$subno++;	
			}
			
		}
		echo "</table>";
?>
    </td></tr></table>
<br>
<table width="100%" border="0">
		<tr><td valign="top" id="myborder">
<table width="100%" cellspacing=0 border="0">
<tr id="mytitlebg" style="font-size:100%">
<td id="myborder" width="5%" align="center">&nbsp;No.</td>
<td id="myborder" width="45%">&nbsp;Kegiatan Pengembangan Diri</td>
<td id="myborder" width="10%" align=center>Nilai</td>
<td id="myborder" width="40%" align=center>Keterangan</td>
</tr>
<?php
$koq=1;
$sqlkoqstu="select * from koq_stu where uid='$uid' and year='$year'";
$reskoqstu=mysql_query($sqlkoqstu)or die("$sqlkoqstu query failed:".mysql_error());
while($rowkoqstu=mysql_fetch_assoc($reskoqstu)){
		$koq_name=$rowkoqstu['koq_name'];
		$koq_nilai=$rowkoqstu['pos'];
		
		$sqldes="select * from type where grp='koq_jawatan' and code='setkoq' and prm='$koq_nilai'";
		$resdes=mysql_query($sqldes)or die("$sqldes query failed:".mysql_error());
		$rowdes=mysql_fetch_assoc($resdes);
		$koq_des=$rowdes['des'];
		echo "<tr>";
		echo "<td id=\"myborder\" align=\"center\">&nbsp;$koq</td>";
		echo "<td id=\"myborder\">&nbsp;$koq_name</td>";
		echo "<td id=\"myborder\" align=\"center\">$koq_nilai</td>";
		echo "<td id=\"myborder\">&nbsp;$koq_des</td>";
		echo "<tr>";
		$koq++;
}
?>
</table>
</td></tr></table>
<br>
<table width="100%" border="0">
		<tr><td valign="top" id="myborder" width="45%">
<table width="100%" cellspacing=0 border="0">
<tr id="mytitlebg" style="font-size:100%">
<td id="myborder" width="5%" align="center">&nbsp;No.</td>
<td id="myborder" width="45%">&nbsp;Akhlak Dan Kepribadian</td>
<td id="myborder" width="40%" align="center">Keterangan</td>
</tr>
<?php
$akhlakno=1;
//echo $sqlsubakhlak="select distinct(ses_sub.sub_code),sub.name from sub left join ses_sub on sub.sch_id=ses_sub.sch_id and sub.level=ses_sub.cls_level and sub.grp=ses_sub.sub_grp and sub.code=ses_sub.sub_code where ses_sub.sub_grp='Subjek Akhlak' and ses_sub.sch_id='$sid' and ses_sub.cls_level='$clslevel' and ses_sub.cls_code='$ccode'";
$sqlsubakhlak="select distinct(ses_sub.sub_code) from ses_sub where ses_sub.sub_grp='Subjek Akhlak' and ses_sub.sch_id='$sid' and ses_sub.cls_level='$clslevel' and ses_sub.cls_code='$ccode'";
$ressubakhlak=mysql_query($sqlsubakhlak)or die("$sqlsubakhlak query failed:".mysql_error());
while($rowsubakhlak=mysql_fetch_assoc($ressubakhlak)){
$scodeakhlak=$rowsubakhlak['sub_code'];
$sqlsnameakhlak="select * from sub where grp='Subjek Akhlak' and sch_id='$sid' and level='$clslevel' and code='$scodeakhlak'";
$ressnameakhlak=mysql_query($sqlsnameakhlak)or die("$sqlsnameakhlak query failed:".mysql_error());
$rowsnameakhlak=mysql_fetch_assoc($ressnameakhlak);
$snameakhlak=$rowsnameakhlak['name'];
$sqlakhlak="select * from sub_stu_summary where uid='$uid' and year='$year' and exam='$exam' and cls='$ccode' and sub_code='$scodeakhlak'";
$resakhlak=mysql_query($sqlakhlak)or die("$sqlakhlak query failed:".mysql_error());
$rowakhlak=mysql_fetch_assoc($resakhlak);
$subnoteakhlak=$rowakhlak['sub_msg'];
echo "<tr>";
echo "<td id=\"myborder\" align=\"center\">$akhlakno</td>";
echo "<td id=\"myborder\">$snameakhlak</td>";
echo "<td id=\"myborder\">: $subnoteakhlak</td>";
echo "</tr>";
$akhlakno++;
}
?>
</table>
</td>
<td valign="top" id="myborder" width="10%">
&nbsp;
</td>
<td valign="top" id="myborder" width="45%">
<table width="100%" cellspacing=0 border="0">
<tr id="mytitlebg" style="font-size:100%">
<td id="myborder" width="5%" align="center">&nbsp;No.</td>
<td id="myborder" width="45%">&nbsp;Ketidakhadiran</td>
<td id="myborder" width="40%" align=center>Keterangan</td>
</tr>
<?php
$sql="select * from exam_stu_summary where uid='$uid' and exam='$exam' and sid=$sid and year='$year'";
		$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
		$row3=mysql_fetch_assoc($res3);
		$mm=$row3['msg'];
		$kg=$row3['wg'];
		$cm=$row3['hg'];
		$at=$row3['totalatt'];
		$dy=$row3['totalday'];
		$sakit=$row3['sakit'];
		$izin=$row3['izin'];
		$alpa=$row3['noreason'];
?>
<tr>
<td id="myborder" width="5%" align="center">&nbsp;1.</td>
<td id="myborder" width="45%">&nbsp;Sakit</td>
<td id="myborder" width="40%" align=center><?php echo $sakit;?> Hari</td>
</tr>
<tr>
<td id="myborder" width="5%" align="center">&nbsp;2.</td>
<td id="myborder" width="45%">&nbsp;Izin</td>
<td id="myborder" width="40%" align=center><?php echo $izin;?> Hari</td>
</tr>
<tr>
<td id="myborder" width="5%" align="center">&nbsp;3.</td>
<td id="myborder" width="45%">&nbsp;Tanpa Keterangan</td>
<td id="myborder" width="40%" align=center><?php echo $alpa;?> Hari</td>
</tr>

<tr>
<td colspan="3">
<? if ($exam=="PS2" && $clslevel != 9){
	
?>
<table style="border:0 ;"align="left">
	<tr>
	<td id="myborder" align="left"> <table><tr><td><B>Keputusan :</B> Bedasarkan hasil yang dicapai pada <br> semester 1 dan 2, maka peserta didik ditetapkan :  </td></tr></table> <table><tr><td> <br> Naik ke kelas  <br> </td><td><br> :  ............................................. </td></tr><tr><td> <br> Tinggal ke kelas</td><td> <br> : .............................................</td></tr></table>   </td> 
	
	</tr>
</table>
<?

}
?>


<br>

<? if ($exam=="PS2" && $clslevel == 9){
	
?>
<table align="left">
	<tr>
	<td id="myborder" align="left" "> <table><tr><td><B>Keputusan :</B> Berdasarkan kriteria yang  berlaku, peserta didik ditetapkan : </td></tr></table> <table><tr><td>Lulus/Tidak Lulus </td> </tr></table>   
	
	</tr>
</table>
<?

}
?>




</td>
</tr>
<tr>
<td colspan="3">
<table align="left">
	<tr>
	<td id="myborder" align="left"> <br> Dikeluarkan di  <?  echo  $daerah ." Pada ".date("d/m/Y") ?> </td>    
	
	</tr>
</table>	
	
</td>	
</tr>

</table>
</td>
</tr>
</table>

<br> <br> 
	
	
<table width="100%">
<?php if($CONFIG['SHOW_CTT_GURU']['val']){?>
	<tr id="mytitlebg"><td id="myborder" width="75%" valign="top"><?php echo $CONFIG['SHOW_CTT_GURU']['etc'];?></td></tr>
	<tr><td><br><br><br><br><br><br></td></tr>
	
	
<?php }if($CONFIG['SHOW_COP']['val']){?>
	<tr id="mytitlebg"><td id="myborder" width="75%" valign="top"><?php echo $CONFIG['SHOW_COP']['etc'];?></td></tr>
	<tr><td><br><br><br><br><br><br></td></tr>
<?php }?>
</table>

<?php
if($exam=='PS2'){$CONFIG['SHOW_TT_PENGETUA']['val']=1;}

if($CONFIG['SHOW_SIGNING']['val']){?>
	<table width="100%">
		<tr id="mytitle">
		<?php if($CONFIG['SHOW_TT_PENJAGA']['val']){?>
		<td id="myborder" width="33%" valign="top"  align="center"><?php echo $CONFIG['SHOW_TT_PENJAGA']['etc'];?>
			<br><br><br><br><br><br><br>
			<?php echo "( $p1name )";?>
		</td>
	<?php }?>
	<?php if($CONFIG['SHOW_TT_GURU']['val']){?>
		<td id="myborder" width="33%" valign="top" align="center"><?php echo $CONFIG['SHOW_TT_GURU']['etc'];?>
			<br><br><br><br><br><br><br>
			<?php echo "( $tname )";?>
		</td>
	<?php }if($CONFIG['SHOW_TT_PENGETUA']['val']){?>
		<td id="myborder" width="33%" valign="top"  align="center"><?php echo $CONFIG['SHOW_TT_PENGETUA']['etc'];?>
			<?php 
			if($CONFIG['SHOW_TT_PENGETUA']['des']!=""){
				$sain=$CONFIG['SHOW_TT_PENGETUA']['des'];
				echo "<br><br><img src=\"$sain\">";
				
			}else{
				echo "<br><br><br><br><br><br><br>";
				echo "( $kepalasekolah )";
			}
	}?>
		</td>
		</tr>
	</table>
<?php } ?><!-- end signing -->
	</tr>
</table>


<?php if($CONFIG['SHOW_CETAKKAN']['val']){?>
<div align="center" style="font-size:60% "><?php echo $CONFIG['SHOW_CETAKKAN']['etc'];?></div>
<?php } ?>
</div> <!-- story -->
<?php if($pageno!=$totalpage){?>
<div style="page-break-after:always"></div>
<?php } ?>
<?php }//end for loop ?>


</div> <!-- content -->


</form>

</body>
</html>
