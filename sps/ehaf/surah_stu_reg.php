<?php
//500 - 27/03/2010 - repair paydate kasi betul kat table feestu
$vmod="v5.0.0";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');
		
		$adm=$_SESSION['username'];
		$sid=$_REQUEST['sid'];
		$uid=$_REQUEST['uid'];
		$surahno=$_REQUEST['surah'];
		$surahname=$_REQUEST['surahname'];
		$ayatno=$_REQUEST['ayatno'];
		$reading=$_REQUEST['reading'];
		$totalayat_thissurah=$_REQUEST['totalayat_thissurah'];
		$dt=$_POST['dt'];
		$op=$_POST['op'];
		$year=$_REQUEST['year'];
				
		if($dt=="")
			$dt=date("Y-m-d");
		list($y,$m,$d)=explode("-",$dt);
		
		if($reading=="")
			$reading="0";
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=stripslashes($row['name']);			  
		}
		if($uid!=""){
			$sql="select * from stu where uid='$uid'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $xname=$row['name'];
			$xic=$row['ic'];
			$file=$row['file'];
			
			$sql="select * from ses_stu where stu_uid='$uid' and year='$year' and sch_id=$sid";
			$res2=mysql_query($sql)or die("query failed: $sql".mysql_error());
			if($row2=mysql_fetch_assoc($res2)){
				$clsname=$row2['cls_name'];
				$clscode=$row2['cls_code'];
				$clslevel=$row2['cls_level'];
			}
		}
			

		$surah=addslashes($surahname);
		$sql="select * from type where grp='surahhafazan' and prm='$surah'";
		$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$row2=mysql_fetch_assoc($res2);
		$surahname=$row2['prm'];
		$surah=addslashes($row2['prm']);
		$surahno=addslashes($row2['val']);
		$totalayat_thissurah=$row2['etc'];
		
		if($op=="save"){
				$status=0;
				$sql="insert into surah_stu_read (ts,adm,dt,uid,sid,surahno,surahayat,surahname,reading,year) values (now(),'$adm','$dt','$uid',$sid,$surahno,$ayatno,'$surah',$reading,'$year')";
				$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
				if($totalayat_thissurah==$ayatno)
					$status=1;
					
				$sql="delete from surah_stu_status where uid='$uid' and surahname='$surah'";
				$res=mysql_query($sql)or die("query failed: $sql".mysql_error());
				$sql="insert into surah_stu_status(ts,adm,dt,uid,sid,surahno,surahayat,surahname,reading,status,year) 
					values (now(),'$adm','$dt','$uid',$sid,$surahno,$ayatno,'$surah',$reading,$status,'$year')";
				$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
				$f="Successfully Save";
		}
		elseif($op=="delete"){
				$sql="select max(id) from surah_stu_read where uid='$uid' and surahname='$surah' and reading='$reading'";
				$res=mysql_query($sql)or die("query failed: $sql".mysql_error());
				$row=mysql_fetch_row($res);
				$xid=$row[0];
				$sql="delete from surah_stu_read where id='$xid'";
				$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
				$sql2="select max(id) from surah_stu_read where uid='$uid' and surahname='$surah' and reading='$reading'";
				$res2=mysql_query($sql2)or die("query failed: $sql".mysql_error());
				$row2=mysql_fetch_row($res2);
				$xid2=$row2[0];

				$sql3="select * from surah_stu_read where id='$xid2'";
				$res3=mysql_query($sql3)or die("$sql3 query failed:".mysql_error());
				$row3=mysql_fetch_assoc($res3);
				$surahayat=$row3['surahayat'];
				$sql="update surah_stu_status set status=0,surahayat='$surahayat' where uid='$uid' and surahname='$surah' and reading='$reading'";
				$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
				$f="Successfully Deleted";
		}
		$surahname=stripslashes($surahname);
		
		$sql="select * from surah_stu_read where uid='$uid' and surahname='$surah' and reading='$reading' order by id desc";
		$res2=mysql_query($sql)or die("query failed: $sql".mysql_error());
		$row2=mysql_fetch_assoc($res2);
		$last_surahayat=$row2['surahayat'];
		if($last_surahayat=="")
			$last_surahayat=0;
		$last_surahno=$row2['surahno'];
		$last_surahname=stripslashes($row2['surahname']);
		
		
		$sql="select * from surah_stu_read where uid='$uid' and surahname='$surah' and reading='1' order by id desc";
		$res2=mysql_query($sql)or die("query failed: $sql".mysql_error());
		$row2=mysql_fetch_assoc($res2);
		$last_tilawahayat=$row2['surahayat'];
		if($last_tilawahayat=="")
			$last_tilawahayat=0;
		$last_tilawahno=$row2['surahno'];
		$last_tilawahname=stripslashes($row2['surahname']);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>	

<?php include("$MYOBJ/datepicker/dp.php")?>

<script language="JavaScript">
function process_form(op,ayatno,surahname){
	if(op=="save"){
		document.myform.op.value=op;
		document.myform.submit();
		/**
		ret = confirm("Confirm Save Ayat "+ayatno+" - Surah "+surahname+" ??");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}else{
			document.myform.submit();//refresh back.. submit with no operation
		}
		**/
	}else if(op=="delete"){
		ret = confirm("Confirm to undo last record ??");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
	}
}

function myin(id,sta){
		id.style.backgroundColor='#00FF66';
		id.style.cursor='pointer';
}
function myout(id,sta){
		id.style.backgroundColor='';
		id.style.cursor='default';
}
function chgcolor(id,lastread){
	i=parseInt(lastread)+1;
	for(;i<=id;i++){
		document.getElementById('yyy'+i).style.backgroundColor='#00FF66';
	}
}
</script>
</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="surah_stu_reg">
	<input type="hidden" name="op">
	<input type="hidden" name="sid" value="<?php echo $sid;?>">
	<input type="hidden" name="uid" value="<?php echo $uid;?>">
	<input type="hidden" name="surah" value="<?php echo $surahno;?>">
    <input type="hidden" name="surahname" value="<?php echo $surahname;?>">
	<input name="ayatno" type="hidden">
	<input name="totalayat_thissurah" type="hidden" value="<?php echo $totalayat_thissurah;?>">
	<input name="reading" type="hidden" value="<?php echo $reading;?>">
	<input name="year" type="hidden" value="<?php echo $year;?>">
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="process_form('delete')"id="mymenuitem"><img src="../img/home.png"><br>Undo</a>
<a href="surah_edit.php?uid=<?php echo "$uid&sid=$sid";?>" id="mymenuitem" target="_blank"><img src="../img/tool.png"><br>Edit</a>
<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
<a href="#" onClick="<?php if($f!=""){?>top.document.myform.submit();<?php }?>window.close();top.$.fancybox.close();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
</div>
<div id="right" align="right"><?php echo $vmod;?></div>
</div><!-- end mypanel-->
<div id="story">

<div id="mytitlebg"><?php echo $lg_student;?></div>

<table width="100%" style="font-size:12px ">
  <tr>
	 <td valign="top" id="myborderfull" align="center" width="80px">
		<?php if(($file!="")&&(file_exists("$dir_image_student$file"))){?>
				<img src="<?php echo "$dir_image_student$file";?>" width="80" height="90">
		<?php } else echo "&nbsp;";?>
	 </td>
  	<td width="90%" valign="top">	
	<table width="100%" >
      <tr>
        <td width="20%"><?php echo $lg_name;?></td>
        <td width="1%">:</td>
        <td width="79%"><?php echo "$xname";?></td>
      </tr>
      <tr>
        <td><?php echo $lg_matric;?></td>
        <td>:</td>
        <td><?php echo "$uid";?> </td>
      </tr>
      <tr>
        <td><?php echo $lg_ic;?></td>
        <td>:</td>
        <td><?php echo "$xic";?> </td>
      </tr>
	  <tr>
        <td><?php echo $lg_school;?></td>
        <td>:</td>
        <td><?php echo "$sname";?></td>
      </tr>
	  <tr>
        <td><?php echo $lg_class;?></td>
        <td>:</td>
        <td><?php echo "$clsname";?> </td>
      </tr>
      <!--
	  <tr>
        <td>HAFALAN Terakhir</td>
        <td>:</td>
        <td><?php echo "Surah $last_surahname - Ayat $last_surahayat";?></td>
      </tr>
      <tr>
        <td>Tilawah Terakhir</td>
        <td>:</td>
        <td><?php echo "Surah $last_tilawahname - Ayat $last_tilawahayat";?></td>
      </tr>
      -->
    </table>
 	</td>
  </tr>
</table>
<table id="mytitlebg" style="font-size:12px " width="100%">
<tr>
<td width="33%">Surah : <?php echo strtoupper($surahname);?></td>
<td width="33%" style="display:none;">
<input type="radio" name="reading" value="0" <?php if($reading=="0") echo checked;?> onClick="document.myform.submit();"> HAFALAN 
<input type="radio" name="reading" value="1" <?php if($reading=="1") echo checked;?> onClick="document.myform.submit();"> TILAWAH
</td>
<td width="33%" align="center">Tanggal : <input name="dt" type="text" id="dt" value="<?php echo "$dt";?>" size="10" readonly style="background-color:#FAFAFA; border-style:none;font-size:14px; font-weight:bold"><input type="button" value="-" onClick="displayDatePicker('dt');"></td>
</tr></table>
<table width="100%"  cellspacing="0" cellpadding="2">
	<tr>
<?php 
	$surahno=1;
	for($i=1;$i<=$totalayat_thissurah;$i++){
				
		if($last_surahayat>=$i){
			if($reading=="0")
				$bg="#00FF66";
			else
				$bg="#FFCC99";
?>
		<td bgcolor="<?php echo $bg;?>" id="myborder" width="5%" align="center">
<?php }else{ ?>
		<td id="myborder" width="5%" align="center"  onMouseOver="myin(this,1)" onMouseOut="myout(this,1)" 
			onClick="chgcolor('<?php echo $i;?>','<?php echo $last_surahayat;?>');document.myform.ayatno.value='<?php echo $i;?>';process_form('save','<?php echo $i;?>','<?php echo addslashes($surahname);?>');">
<?php } ?>
			<div id="yyy<?php echo $i;?>" style="width:100%">
				<?php 
					if($juzukmula>0) 
							echo "<strong>&laquo;$juzukmula&raquo</strong>"; 
					echo "<br>$i";
				?>
			</div>
		</td>
<?php if($i%20==0) echo "</tr><tr>"; } ?>
	</tr>
</table>
<br>

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="printhidden">
<tr><td id="myborder" align="center">
<?php
if($totalayat_thissurah==$last_surahayat){
?>
<a href="../ehaf/hafaz_stu_mark.php?uid=<?php echo $uid;?>&cls=<?php echo $clscode;?>&lvl=<?php echo $clslevel;?>&year=<?php echo $year;?>&sid=<?php echo $sid;?>">Nilaikan Hafalan</a>
<?php }else{ ?>
Nilaikan Hafalan hanya boleh dibuat untuk hafalan yang cukup
<?php }?>
</td></tr>
</table>

</div></div>


</form> <!-- end myform -->


</body>
</html>
<!-- 
V.1
Author: razali212@yahoo.com
 -->