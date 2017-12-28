<?php
$vmod="v5.3.0";
$vdate="110306";

include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');

		$username = $_SESSION['username'];

		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];

		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
		
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
            mysql_free_result($res);					  
		}
		
		$uid=$_REQUEST['uid'];
		if($uid!=""){
			$sql="select * from stu where sch_id=$sid and uid='$uid'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $xname=$row['name'];
			$xic=$row['ic'];
			$file=$row['file'];
			
			$cname="Tiada";
			$sql="select * from ses_stu where stu_uid='$uid' and year='$year' and sch_id=$sid";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			if($row2=mysql_fetch_assoc($res2)){
				$clsname=$row2['cls_name'];
				$clscode=$row2['cls_code'];
				$clslevel=$row2['cls_level'];
			}
			
		
			$dt=$_POST['dt'];
			if($dt=="")
				$dt=date("Y-m-d");
			
			list($y,$m,$d)=explode("-",$dt);
			
			$sql="select count(*) from syaathir_rec where sid=$sid and uid='$uid'";
			$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			$row2=mysql_fetch_row($res2);
			$jumms=$row2[0];
			//if($jumms>=603)
			//	$ULANG=1;
			//else
			$ULANG=0;
			
			$op=$_POST['op'];
			/**
			if($op=="savereport"){
				$ulasan=$_POST['ulasan'];
				$masalah=$_POST['masalah'];
				$cadangan=$_POST['cadangan'];
				$sql="update hafazan_rep set ulasan='$ulasan',cadangan='$cadangan',masalah='$masalah' where uid='$uid' and year=$y and month=$m and sid=$sid";
				mysql_query($sql);
			}
			**/
			if($op=="save"){
				$f="save";
				$ms=$_POST['ms'];
				if($ms<37) $jk=1;
				elseif($ms<73) $jk=2;
				else $jk=3;
				
				$sql="select * from syaathir_rec where sid=$sid and uid='$uid' order by id desc limit 1";
				$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				$row2=mysql_fetch_assoc($res2);
				$lastpage=$row2['ms'];
				if($lastpage=="")
					$lastpage=1;
				//echo $lastpage;
				for($i=$lastpage;$i<=$ms;$i++){
					$sql="insert into syaathir_rec (dt,year,month,sid,cls_code,cls_level,uid,ms,adm,ts,jk,ulang) 
							values ('$dt',$y,'$m',$sid,'$clscode','$clslevel','$uid',$i,'$username',now(),$jk,$ULANG)";
					$res3=mysql_query($sql)or die("$sql query failed:".mysql_error());
				}
			}//if save
		
			
			
		}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
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

<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">

<?php include("$MYOBJ/datepicker/dp.php")?>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<script language="JavaScript">
function process_form(op){
	ret = confirm("Save reports??");
	if (ret == true){
		document.myform.op.value=op
		document.myform.submit();
	}
}

function process_mouse(id,sta){
	xx=document.myform.ms.value;
	yy=document.myform.mss.value;
	zz=document.myform.no.value;
	
	if(sta){
		document.myform.ms.value=id;
		document.myform.mss.value=id;
	}
	else{
		tmp=id-1;
		if((tmp==1)||(tmp==581))
			tmp=0;
		document.myform.ms.value=tmp;
		document.myform.mss.value=id;
	}
	document.myform.no.value=0;
	
	if(document.myform.uid.value==""){
		alert('Sila pilih pelajar');
		return;
	}
	if(document.myform.ms.value==""){
		alert('Sila masukkan muka surat');
		document.myform.ms.focus();
		return;
	}
	var str=document.myform.ms.value;
	if ((isNaN(str)) || (str.length == 0)){
		alert('Maaf. Muka surat tidak sah');
		document.myform.ms.focus();
		return;
	}
	if(document.myform.ms.value>604){
		alert('Maaf. Muka surat tidak sah');
		document.myform.ms.focus();
		return;
	}
	if(document.myform.mss.value==""){
		alert('Sila masukkan muka semasa surat');
		document.myform.mss.focus();
		return;
	}
	var str=document.myform.mss.value;
	if ((isNaN(str)) || (str.length == 0)){
		alert('Maaf. Muka surat semasa tidak sah');
		document.myform.mss.focus();
		return;
	}
	if(document.myform.mss.value>604){
		alert('Maaf. Muka surat semasa tidak sah');
		document.myform.mss.focus();
		return;
	}
	if(document.myform.no.value==""){
		alert('Sila masukkan nombor ayat');
		document.myform.no.focus();
		return;
	}
	var str=document.myform.no.value;
	if ((isNaN(str)) || (str.length == 0)){
		alert('Maaf. Nombor ayat tidak sah');
		document.myform.no.focus();
		return;
	}
	document.myform.op.value='save';
	document.myform.submit();
}

function myin(ele){
		/**ele.style.backgroundColor='#66FF66';**/
		ele.style.cursor='pointer';
		ele.style.border='1px solid #333333';
}
function myout(ele){
		/**ele.style.backgroundColor='';**/
		ele.style.cursor='default';
		ele.style.border='1px solid #F1F1F1';
}
</script>
</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="hafazan_stu_reg">
	<input type="hidden" name="isprint">
	<input type="hidden" name="op">
	<input type="hidden" name="uid" value="<?php echo $uid;?>">
	<input type="hidden" name="sid" value="<?php echo $sid;?>">
	<input type="hidden" name="clslvl" value="<?php echo $clslvl;?>">
	<input type="hidden" name="clscode" value="<?php echo $clscode;?>">
<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="#" onClick="javascript: href='hafazan_stu_month.php?sid=<?php echo $sid;?>&uid=<?php echo $uid;?>'" id="mymenuitem"><img src="../img/graphbar.png"><br>Report</a>
		<a href="#" onClick="process_form('savereport')"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
		<a href="#" onClick="javascript: href='hafazan_stu_edit.php?sid=<?php echo $sid;?>&uid=<?php echo $uid;?>'" id="mymenuitem"><img src="../img/tool.png"><br>Edit</a>
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
		<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
		<a href="#" onClick="<?php if($f!=""){?>top.document.myform.submit();<?php }?>window.close();parent.parent.GB_hide()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
	</div>
	<div id="right" align="right"><?php echo $vmod;?></div>
</div><!-- end mypanel-->
<div id="story">

<table width="100%" id="mytitlebg">
  <tr>
    <td width="100%" align="left">HAFAZAN - <?php echo strtoupper($sname);?></td>
  </tr>
</table>

<table width="100%" border="0"  style="background:none; font-size:12px">
  <tr>
	 <td valign="top" id="myborderfull" align="center" width="90px">
		<?php if(($file!="")&&(file_exists("$dir_image_user$file"))){?>
				<img src="<?php echo "$dir_image_user$file";?>" width="90" height="100">
		<?php } else echo "&nbsp;";?>
	 </td>
  	<td  valign="top">	
	<table width="100%" >
      <tr>
        <td width="14%">Nama</td>
        <td width="1%">:</td>
        <td width="85%"><?php echo "$xname";?></td>
      </tr>
      <tr>
        <td>Matrik</td>
        <td>:</td>
        <td><?php echo "$uid";?> </td>
      </tr>
      <tr>
        <td>K.Pengenalan</td>
        <td>:</td>
        <td><?php echo "$xic";?> </td>
      </tr>
	  <tr>
        <td><?php echo "$lg_sekolah";?></td>
        <td>:</td>
        <td><?php echo "$sname";?></td>
      </tr>
	  <tr>
        <td>Kelas</td>
        <td>:</td>
        <td><?php echo "$clsname/$year";?> </td>
      </tr>
    </table>


	</td>
    <td width="50%" valign="top">
	
	
 	</td>
  </tr>
</table>

<table width="100%"><tr><td width="70%" valign="top">


<div id="mytitlebg">LAPORAN BULANAN <?php echo strtoupper(date('M Y', mktime(0, 0, 0, $m, 1, $y)));?> </div>

<table width="100%" style="font-size:12px">
<!-- 
	<tr>
		<td width="10%">Ulasan</td>
		<td width="1%">:</td>
		<td><input type="text" name="ulasan" size="120" value="<?php echo $ulasan;?>"></td>
	</tr>
	<tr>
		<td>Masalah</td>
		<td>:</td>
		<td><input type="text" name="masalah" size="120" value="<?php echo $masalah;?>"></td>
	</tr>
	<tr>
		<td>Cadangan</td>
		<td>:</td>
		<td><input type="text" name="cadangan" size="120" value="<?php echo $cadangan;?>"></td>
	</tr>
 -->
	  	<tr>
<td width="10%">Tarikh Bacaan</td>
		<td width="1%">:</td>
    	<td>
		<input name="dt" type="text" id="dt" value="<?php echo "$dt";?>" size="12" onClick="displayDatePicker('dt');" readonly style="font-size:14px; font-weight:bold">
		<input name="ms"  type="hidden">
		<input name="mss" type="hidden">
		<input name="no"  type="hidden" size="1" value="<?php echo "$xno";?>">
		</td>
  	</tr>
</table>
</td>

<div id="tipsdiv">
<!-- 
Tips:<br>
- Sila tetapkan tarikh yang betul untuk tasmiq dan ulasan. Untuk ulasan bulanan pastikan tarikh pada hujung bulan, dan bukan di awal bulan.<br>
- Hafazan ulangan hanya boleh dibuat apabila 30 juzuk pertama telah tamat.<br>
- Pastikan rekod hafazan di rekodkan setiap bulan.<br>
 -->
</div>
<?php
if ($ULANG>0){
?>
<div id="mytitlebg">SYAATHIR ULANGAN <?php echo $ULANG;?></div>
<?php } ?>
<table width="100%" cellspacing="0">
	<tr>
		<td width="100%" id="mytabletitle" style="border-left:none " align="center">MUKASURAT</td>
	</tr>
</table>
<table width="100%" cellspacing="0"  cellpadding="2px" style="font-size:10px">
<?php 
	$j=1;
	for($i=1;$i<=3;$i++){
?>
	<tr>
		<td width="3%" id="mytabletitle">ASY-SYAATHIR&nbsp;<?php echo $i;?></td>
		<?php 	for($ii=0;$ii<36;$ii++,$j++){ 
			$sql="select * from syaathir_rec where sid=$sid and uid='$uid' and ms=$j order by id desc limit 1";
			$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			if($row2=mysql_fetch_assoc($res2)){
				$cc=$row2['ms'];
				$ENABLE=0;
				if($row2['ulang']==0){
					$bgc="bgcolor=#66FF66";
					if($ULANG==1)
						$ENABLE=1;
				}
				if($row2['ulang']==1){
					$bgc="bgcolor=#CCCC66";
				}
			}else{
				$cc=0;
				$bgc="";
				$ENABLE=1;
			}
		?>
		<td width="3%" id="myborder" <?php echo $bgc;?> align="center" <?php if($ENABLE){?>onMouseOver="myin(this);" onMouseOut="myout(this);" 
        	onClick="process_mouse(<?php echo $j;?>,1)"<?php }?>>
			<?php 
				if($i==1) echo $j; elseif ($i==2) echo $j-36; else echo $j-72;?>
		</td>
		<?php } ?>
	</tr>
<?php } ?>
	
</table>

</div></div>

</form> <!-- end myform -->


</body>
</html>
<!-- 
V.1
Author: razali212@yahoo.com
 -->