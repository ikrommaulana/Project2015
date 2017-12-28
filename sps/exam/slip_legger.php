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
<div id="mytitlebg" align="center">CATATAN HASIL BELAJAR</div>

<table width="100%" style="font-size:12px">
  <tr>
  	<td width="8%" align="center" id="myborder">
		<img name="picture" src="<?php if($file!="") echo "$dir_image_student$file"; ?>"  width="70" height="80" style="padding:3px 3px 3px 3px ">
	</td>
    <td width="50%" valign="top">	
	<table width="100%" >
      <tr>
        <td width="30%" valign="top"><?php echo strtoupper($lg_name);?></td>
        <td width="1%" valign="top">:</td>
        <td width="70%"><?php echo "$name";?></td>
      </tr>
      <tr>
        <td><?php echo strtoupper($lg_matric);?></td>
        <td>:</td>
        <td><?php echo "$uid";?> </td>
      </tr>
      <tr>
        <td><?php echo strtoupper($lg_ic_number);?></td>
        <td>:</td>
        <td><?php echo "$ic";?> </td>
      </tr>
       <tr>
        <td><?php echo strtoupper($lg_register_date);?></td>
        <td>:</td>
        <td><?php list($xy,$xm,$xd)=split('[-]',$rdate); echo "$xd-$xm-$xy";?> </td>
      </tr>
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
        <td width="20%" valign="top"><?php echo strtoupper($lg_school);?></td>
        <td width="1%" valign="top">:</td>
        <td width="79%"><?php echo strtoupper("$sname");?></td>
      </tr>
	  <tr>
        <td><?php echo strtoupper($lg_exam);?></td>
        <td>:</td>
        <td><?php echo strtoupper("$examname");?></td>
      </tr>
      <tr>
        <td><?php echo strtoupper($lg_class);?></td>
        <td>:</td>
        <td><?php echo strtoupper("$cname / $year");?> </td>
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

<table width="100%" cellpadding="3" cellspacing="0" style="font-size:12px">
    <tr>
            <td id="mytabletitle" width="5%">No</td>
            <td id="mytabletitle" width="20%">Mata Pelajaran</td>
            <td id="mytabletitle" width="10%" align="center">Nilai Smt.1</td>
            <td id="mytabletitle" width="30%">Catatan Guru</td>
            <td id="mytabletitle" width="10%" align="center">Nilai Smt.2</td>
            <td id="mytabletitle" width="30%">Catatan Guru</td>
    </tr>
<?php 
	$sql="select * from sub where level='$clslevel'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
    while($row=mysql_fetch_assoc($res)){
        	$subname=$row['name'];
			$subcode=$row['code'];
			$sql="select * from exam where sch_id='$sid' and stu_uid='$uid' and year='$year' and examtype='PS1' and sub_code='$subcode';";
			$res2=mysql_query($sql);
   			$row2=mysql_fetch_assoc($res2);
        	$p1total=$row2['point'];
			
			$sql="select * from exam where sch_id='$sid' and stu_uid='$uid' and year='$year' and examtype='PS2' and sub_code='$subcode';";
			$res2=mysql_query($sql);
   			$row2=mysql_fetch_assoc($res2);
        	$p2total=$row2['point'];
			
			$sql="select * from sub_stu_summary where sid='$sid' and uid='$uid' and year='$year' and exam='PS1' and sub_code='$subcode';";
			$res2=mysql_query($sql);
   			$row2=mysql_fetch_assoc($res2);
        	$p1rem=$row2['sub_msg'];

			$sql="select * from sub_stu_summary where sid='$sid' and uid='$uid' and year='$year' and exam='PS2' and sub_code='$subcode';";
			$res2=mysql_query($sql);
   			$row2=mysql_fetch_assoc($res2);
        	$p2rem=$row2['sub_msg'];
			
			
			$q++;
?>    
    <tr>
            <td id="myborder"><?php echo $q;?></td>
            <td id="myborder"><?php echo $subname;?></td>
            <td id="myborder" align="center"><?php echo $p1total;?></td>
            <td id="myborder"><?php echo $p1rem;?></td>
            <td id="myborder" align="center"><?php echo $p2total;?></td>
            <td id="myborder"><?php echo $p2rem;?></td>
    </tr>
<?php } ?>
	
    <tr>
            <td id="myborder" rowspan="3" colspan="2">Kegiatan Extrakurikuler</td>        
            <td id="myborder">&nbsp;</td>
            <td id="myborder"></td>
            <td id="myborder"></td>
            <td id="myborder"></td>
    </tr>
    <tr>
            <td id="myborder">&nbsp;</td>
            <td id="myborder"></td>
            <td id="myborder"></td>
            <td id="myborder"></td>
    </tr>
    <tr>
            <td id="myborder">&nbsp;</td>
            <td id="myborder"></td>
            <td id="myborder"></td>
            <td id="myborder"></td>
    </tr>
    
        <tr>
            <td id="myborder" rowspan="3" colspan="2">Ketidakhadiran</td>
            
            <td id="myborder">Sakit</td>
            <td id="myborder"></td>
            <td id="myborder"></td>
            <td id="myborder"></td>
    </tr>
    <tr>
            <td id="myborder">Dengan Izin</td>
            <td id="myborder"></td>
            <td id="myborder"></td>
            <td id="myborder"></td>
    </tr>
    <tr>
            <td id="myborder">Tanpa Keterangan</td>
            <td id="myborder"></td>
            <td id="myborder"></td>
            <td id="myborder"></td>
    </tr>
    
        <tr>
            <td id="myborder" rowspan="3" colspan="2">Perilaku</td>
            
            <td id="myborder">&nbsp;</td>
            <td id="myborder"></td>
            <td id="myborder"></td>
            <td id="myborder"></td>
    </tr>
    <tr>
            <td id="myborder">&nbsp;</td>
            <td id="myborder"></td>
            <td id="myborder"></td>
            <td id="myborder"></td>
    </tr>
    <tr>
            <td id="myborder">&nbsp;</td>
            <td id="myborder"></td>
            <td id="myborder"></td>
            <td id="myborder"></td>
    </tr>
    
</table>

<?php

			$sql="select * from exam_stu_summary where sid='$sid' and uid='$uid' and year='$year' and exam='PS2'";
			$res2=mysql_query($sql);
   			$row2=mysql_fetch_assoc($res2);
        	$p2msg=$row2['msg'];
			$sql="select * from exam_stu_summary where sid='$sid' and uid='$uid' and year='$year' and exam='PS1'";
			$res2=mysql_query($sql);
   			$row2=mysql_fetch_assoc($res2);
        	$p1msg=$row2['msg'];
?>
<table width="100%" style="font-size:12px">
	<tr>
    	<td width="50%" id="mytabletitle">Kegiatan Belajar Pembiasaan Semester 1</td>
        <td width="50%" id="mytabletitle">Kegiatan Belajar Pembiasaan Semester 2</td>
    </tr>
    <tr>
    	<td id="myborder" style="height:80px" valign="top"><?php echo $p1msg;?></td>
        <td id="myborder" style="height:80px" valign="top"><?php echo $p2msg;?></td>
    </tr>
</table>

<table width="100%" style="font-size:12px">
	<tr>
    	<td width="25%" id="mytabletitle">&nbsp;</td>
        <td width="25%" id="mytabletitle">&nbsp;</td>
        <td width="25%" id="mytabletitle">&nbsp;</td>
        <td width="25%" id="mytabletitle">&nbsp;</td>
    </tr>
    <tr>
    	<td id="myborder" valign="top">KEPUTUSAN AKHRIR TAHUN:<br><br>
						NAIK/TIDAK:................................<br><br>
                        TANGGAL:...................................<br><br>
						LULUS/TIDAK LULUS:...................<br><br>
                        TANGGAL:...................................</td>
        <td  id="myborder" align="center" valign="top"><br>Wali Kelas Semester 1<br><br><br><br><br><br>
        .....................................<br>NIP:
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td id="myborder" align="center" valign="top"><br>Wali Kelas Semester 2<br><br><br><br><br><br>
        .....................................<br>NIP:
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td  id="myborder" align="center" valign="top"><br>Mengetahui<br>
							Kepala Sekolah<br><br><br><br><br>
                            .....................................<br>NIP:
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr>
</table>


</div> <!-- story -->
<?php if($pageno!=$totalpage){?>
<div style="page-break-after:always"></div>
<?php } ?>
<?php }//end for loop ?>


</div> <!-- content -->


</form>

</body>
</html>
