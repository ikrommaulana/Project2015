<?php
//16/05/2010 - patch jumlah subjek
$vmod="v4.1.0";
$vdate="16/05/2010";

include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK|KEWANGAN|GURU');
$username=$_SESSION['username'];
$isprint=$_REQUEST['isprint'];
$curryear=date('Y');

$showheader=$_REQUEST['showheader'];
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
	$slvl=0;
	if($sid!=0){
		$sql="select * from sch where id=$sid";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=$row['name'];
		$slevel=$row['level'];
		$slvl=$row['lvl'];
		$simg=$row['img'];
		$addr=$row['addr'];
		$state=$row['state'];
		$tel=$row['tel'];
		$fax=$row['fax'];
		$web=$row['url'];
		$school_img=$row['img'];
     	mysql_free_result($res);					  
	}

		
	$clvl=0;
	$clscode=$_POST['clscode'];
	if($clscode!=""){
			$sqlclscode="and ses_stu.cls_code='$clscode'";
			//$sqlclscode="and exam.cls_code='$clscode'";
			$sql="select * from cls where sch_id=$sid and code='$clscode'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=$row['name'];
			$clslevel=$row['level'];
			$clvl=$clslevel;
			$sql="select * from type where sid='$sid' and prm='$clslevel' and grp='classlevel'";
    		$res=mysql_query($sql)or die("query failed:".mysql_error());
        	$row=mysql_fetch_assoc($res);
			$grading=$row['code'];
	}
	$year=$_POST['year'];
	if($year=="")
		$year=date('Y');
		
	if($curryear==$year)
		$sqlstatus="and stu.status=6";
		
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
	if($cons!="")
		$sqlcons="and sub_grp='$cons'";
		
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
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
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
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>
<body>

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="../examrep/repexamsppmskor">
	<input name="curr" type="hidden">
	<input name="sort" type="hidden" value="<?php echo $sort;?>">
	<input name="order" type="hidden" value="<?php echo $order;?>">
	<input type="hidden" name="isprint" value="<?php echo $isprint;?>">
	
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
</div> <!-- end mymenu -->

<div id="viewpanel" align="right">
      <select name="year" id="year" onChange="document.myform.submit();">
        <?php
            echo "<option value=$year>SESI $year</option>";
			$sql="select * from type where grp='session' and prm!='$year' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        echo "<option value=\"$s\">SESI $s</option>";
            }
            mysql_free_result($res);					  
?>
      </select>
	  <select name="sid" id="sid" onchange="document.myform.clscode[0].value='';document.myform.exam[0].value='';document.myform.submit();">
        <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- Pilih Sekolah -</option>";
			else
                echo "<option value=$sid>$sname</option>";
				
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['name'];
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
				mysql_free_result($res);
			}							  
			
?>
	</select>
	  <select name="clscode" id="clscode" onchange="document.myform.submit();">
        <?php	
      				if($clscode=="")
						echo "<option value=\"\">- Pilih Kelas -</option>";
					else
						echo "<option value=\"$clscode\">$clsname</option>";
				
					$sql="select * from cls where sch_id=$sid and code!='$clscode' order by level";
            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=$row['name'];
						$a=$row['code'];
                        echo "<option value=\"$a\">$b</option>";
            		}
            		
			?>
      </select>
      
      
      
      <select name="cons" id="cons" onchange="document.myform.submit();">
        <?php	
      				if($cons=="")
						echo "<option value=\"\">- Semua $lg_subjek -</option>";
					else
						echo "<option value=\"$cons\">$cons</option>";
					$sql="select * from type where grp='subtype' and prm!='$cons' and val=1 order by idx";
            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=$row['prm'];
                        echo "<option value=\"$b\">$b</option>";
            		}
            		if($cons!="")
						echo "<option value=\"\">- Semua $lg_subjek -</option>";

			?>
      </select>
		<select name="exam" id="exam" onchange="document.myform.submit();">
        <?php	
      				if($exam=="")
						echo "<option value=\"\">- Pilih Ujian -</option>";
					else
						echo "<option value=\"$exam\">$examname</option>";
					$sql="select * from type where grp='exam' and code!='$exam' and (lvl=0 or lvl=$clvl) and (sid=0 or sid=$sid) order by idx";
            		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $a=$row['prm'];
						$b=$row['code'];
                        echo "<option value=\"$b\">$a</option>";
            		}
            		mysql_free_result($res);	
			?>
      </select>
      <input type="button" name="Submit" value="View" onClick="processform()" >
	  <br>
		<input type="checkbox" name="showheader" value="1"  onClick="document.myform.submit();" <?php if($showheader) echo "checked";?>>Show Header 
		&nbsp;&nbsp;<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>

</div><!-- end viewpanel -->
</div><!-- end mypanel -->

<div id="story">
<?php if($showheader) include('../inc/school_header.php')?>

<div id="mytitle" style="border:none">LAPORAN PEPERIKSAAN SPPM (SKOR)</div>
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

<table width="100%" cellpadding="2" cellspacing="0">
  <tr>
    <td id="mytableheader" width="1%" align="center">BIL</td>
    <td id="mytableheader" style="border-left:none; " width="2%" align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="sort">MATRIK</a></td>
	<td id="mytableheader" style="border-left:none; " width="1%" align="center"><a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="sort">L/P</a></td>	
    <td id="mytableheader" style="border-left:none; " width="15%" >&nbsp;<a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="sort">NAMA</a></td>
<?php 
	$sql="select * from ses_sub where year='$year' and sch_id=$sid and cls_code='$clscode' $sqlcons and sub_grptype=1 order by sub_grp";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$total_all_sub=mysql_num_rows($res2);
	if($total_all_sub>0)
		$size=round(70/$total_all_sub);
	while($row2=mysql_fetch_assoc($res2)){
		$p=$row2['sub_code'];
		$n=$row2['sub_name'];
		$sql="select sname from sub where code='$p'";
		$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$row3=mysql_fetch_assoc($res3);
		$sn=$row3['sname'];
		
?>
		<td id="mytableheader" style="border-left:none; " width="2%" colspan="1" align="center">
		<a href="<?php echo "../examrep/rep_exam_subject_one.php?exam=$exam&year=$year&sid=$sid&clscode=$clscode&subcode=$p&isprint=1";?>" title="<?php echo $n;?>" style="text-decoration:none" target="_blank">
		<?php echo "$p";?><br><div style="font-weight:normal"><?php echo "$sn";?></div></a>
		</td>
<?php } ?>
	<td id="mytableheader" style="border-left:none; " width="2%" align="center">JUM<br>SUB</td>
	<td id="mytableheader" style="border-left:none; " width="2%" align="center">JUM<br>SKOR</td>
	<td id="mytableheader" style="border-left:none; " width="2%" align="center">PURATA<br>SKOR</td>
<!--
	<td id="mytableheader" style="border-left:none; " width="2%" align="center">JUM<br>GRED</td>
	<td id="mytableheader" style="border-left:none; " width="2%" align="center"><br>%</td>
	<td id="mytableheader" style="border-left:none; " width="2%" align="center"><br>GPP</td>
 -->
  </tr>
<?php 
if($clscode!=""){
	$q=0;
	$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid  and year='$year' $sqlclscode $sqlstatus $sqlsort";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$num=mysql_num_rows($res);
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$name=$row['name'];
		$name=stripslashes(strtoupper($name));
		if(strlen($name)>30){
			$aname=explode(" ", $name);
			$name=$aname[0]." ".$aname[1]." ".$aname[2];
		}
		$sex=$row['sex'];
		if($q++%2==0)
			$bg="#FAFAFA";
		else
			$bg="";
?>
  <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
  	<td id="myborder_lrb" align="center"><?php echo $q?></td>
    <td id="myborder_rb" align="center"><?php echo $uid?></td>
	<td id="myborder_rb" align="center"><?php if($sex=="Lelaki")echo "L";if($sex=="Perempuan")echo "P";?></td>
	<td id="myborder_rb" ><?php echo "<a href=\"#\" title=\"Report Card\" onClick=\"newwindow('../exam/slip_exam.php?uid=$uid&year=$year&exam=$exam&sid=$sid',0)\">$name</a>";?></td>
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
	$jum_gp=0;
	$jum_gpsubjek=0;
	$sql="select * from ses_sub where year='$year' and sch_id=$sid and cls_code='$clscode' $sqlcons and sub_grptype=1 order by sub_grp";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row2=mysql_fetch_assoc($res2)){
		$p=$row2['sub_code'];
		$n=$row2['sub_name'];
		//$sql4="select * from exam where stu_uid='$uid' and sub_code='$p' and cls_code='$clscode' and year='$year' and examtype='$exam'";
		$sql4="select * from exam where stu_uid='$uid' and sub_code='$p' and year='$year' and examtype='$exam'";
		$res4=mysql_query($sql4)or die("query failed:".mysql_error());
		$row4=mysql_fetch_assoc($res4);
		$point=$row4['point'];
		$gred=$row4['grade'];
		
		if($gred=="TT"){
			$point="";
			$gred="";
		}else if($gred=="TH"){
			$point=0;
		}else{
			if(strlen($point)>0){
			$jum_markah=$jum_markah+$gred;
			$jum_subjek++;
			}
		}
		/**
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
		**/
?>
	<!-- 
		<td align="center"><?php if(!is_numeric($point))$p="-"; else $p=$point; echo "$p";?></td>
	 -->
		<td id="myborder_rb" align="center"><?php if($gred=="") echo "-"; else echo "$gred";?></td>
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
	//$sql="select * from grading where name='$grading' and point<=$markah_purata order by val desc limit 1";
	//$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
	//$row3=mysql_fetch_assoc($res3);
	//$gred_purata=$row3['grade'];
}else{
	$markah_purata=0;
	$gred_purata="";
}
if($jum_gpsubjek>0) 
	$gpp=$jum_gp/$jum_gpsubjek;
else
	$gpp=0;
?>
		<td id="myborder_rb" align="center" ><?php echo "$jum_subjek/$total_all_sub";?></td>
		<td id="myborder_rb" align="center" ><?php echo $jum_markah;?></td>
		<td id="myborder_rb" align="center" ><?php printf("%.02f",$markah_purata); ?></td>
<!--
		<td id="myborder_rb" align="center" ><?php echo $sumgred;?></td> 
		<td id="myborder_rb" align="center" ><?php printf("%.02f",$gpp); ?></td>
-->
  </tr>
<?php } }?>
</table>

<table width="100%" id="mytitle">
  <tr>
    <td align="center" width="33%">Setiausaha HEA</td>
    <td align="center" width="33%">Penolong Pengetua</td>
    <td align="center" width="33%">Pengerusi Lembaga Pengarah</td>
  </tr>
</table>



</div></div>
</form>
<form name="formwindow" method="post" action="../examrep/repexamsppmskor.php" target="newwindow">
	<input name="isprint" type="hidden" id="isprint" value="1">
	<input name="exam" type="hidden" id="exam" value="<?php echo $exam;?>">
	<input name="sid" type="hidden" id="sid" value="<?php echo $sid;?>">
	<input name="clscode" type="hidden" id="clscode" value="<?php echo $clscode;?>">
	<input name="year" type="hidden" id="year" value="<?php echo $year;?>">
	<input name="cons" type="hidden" id="cons" value="<?php echo $cons;?>">
	<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
	<input name="order" type="hidden" id="order" value="<?php echo $order;?>">
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