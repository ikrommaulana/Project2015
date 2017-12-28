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
			
			$sql="select count(*) from hafazan_rec where sid=$sid and uid='$uid'";
			$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			$row2=mysql_fetch_row($res2);
			$jumms=$row2[0];
			if($jumms>=603)
				$ULANG=1;
			else
				$ULANG=0;
			
			$op=$_POST['op'];
			if($op=="savereport"){
				$ulasan=$_POST['ulasan'];
				$masalah=$_POST['masalah'];
				$cadangan=$_POST['cadangan'];
				$sql="update hafazan_rep set ulasan='$ulasan',cadangan='$cadangan',masalah='$masalah' where uid='$uid' and year=$y and month=$m and sid=$sid";
				mysql_query($sql);
			}
			if($op=="save"){
				$f="save";
				$ms=$_POST['ms'];
				$no=$_POST['no'];
				$mss=$_POST['mss'];
				$xms=$ms-1;//start no page 2
				
				$tmp=$xms/20;
				for($i=1;$i<=31;$i++){
					if($tmp<=$i){
						$this_jk=$i;
						break;
					}
				}
				if($this_jk>30)
					$this_jk=30;
				$first_page_jk=$this_jk*20+2-20;
				
				$sql="select * from hafazan_rec where uid='$uid' and ulang=$ULANG order by id desc limit 1";
				$res3=mysql_query($sql)or die("$sql - query failed:".mysql_error());
				if($row3=mysql_fetch_assoc($res3))
						$last_ms=$row3['ms'];
				else
						$last_ms=0;
						
				$sql="select * from hafazan_rec where uid='$uid' and jk=$this_jk and ulang=$ULANG order by id desc limit 1";
				$res3=mysql_query($sql)or die("$sql - query failed:".mysql_error());
				if($row3=mysql_fetch_assoc($res3))
						$last_ms_this_jk=$row3['ms']+1;
				else
						$last_ms_this_jk=$first_page_jk;
						
				$sql="insert into hafazan(dt,uid,tid,sid,ms,juzuk,prev_page)values('$dt','$uid','$username',$sid,$ms,$this_jk,$last_ms)";
				mysql_query($sql)or die("$sql:query failed:".mysql_error());
				$xid=mysql_insert_id($link);
				
				for($i=$last_ms_this_jk;$i<=$ms;$i++){
						$sql="insert into hafazan_rec (dt,year,month,sid,cls_code,cls_level,uid,ms,xid,adm,ts,jk,ulang) values ('$dt',$y,'$m',$sid,'$clscode','$clslevel','$uid',$i,$xid,'$username',now(),$this_jk,$ULANG)";
						$res3=mysql_query($sql)or die("$sql query failed:".mysql_error());
				}
				
				
				//save to report
					$total_ms_month=0;
					$sql="select count(*) from hafazan_rec where uid='$uid' and year=$y and month=$m";
					$res3=mysql_query($sql)or die("$sql - query failed:".mysql_error());
					if($row3=mysql_fetch_row($res3))
						$total_ms_month=$row3[0];
					
					$total_ms_year=0;
					$sql="select count(*) from hafazan_rec where uid='$uid' and year=$y";
					$res3=mysql_query($sql)or die("$sql - query failed:".mysql_error());
					if($row3=mysql_fetch_row($res3))
						$total_ms_year=$row3[0];
			
					$total_juzuk_year=sprintf("%d",$total_ms_year/20);
					$mar=0;
					
					$sql="select * from grading where name='Hafazan' and point<=$total_ms_month order by point desc limit 1";
					$res3=mysql_query($sql)or die("$sql query failed:".mysql_error());
					$row3=mysql_fetch_assoc($res3);
					$gred=$row3['grade'];
					$mar=$row3['val'];
					if($clslevel=="")
						$clslevel=0;
					$sql="delete from hafazan_rep where uid='$uid' and year=$y and month=$m and sid=$sid";
					$res3=mysql_query($sql)or die("$sql query failed:".mysql_error());
					$sql="insert into hafazan_rep (year,month,sid,cls_code,cls_level,uid,totalms,mg,mp,totaljk,currjk,currms,adm,ts) values 
												  ($y,'$m',$sid,'$clscode','$clslevel','$uid',$total_ms_month,'$gred',$mar,$total_juzuk_year,$this_jk,$ms,'$username',now())";
					$res3=mysql_query($sql)or die("$sql query failed:".mysql_error());
					
					$sql="delete from hafazan_sem where uid='$uid' and year=$y and sid=$sid";
					$res3=mysql_query($sql)or die("$sql query failed:".mysql_error());
					$sql="insert into hafazan_sem (year,sid,cls_code,cls_level,uid,currms,totalms,currjk,totaljk,adm,ts) values 
												  ($y,$sid,'$clscode','$clslevel','$uid',$ms,$total_ms_year,$this_jk,$total_juzuk_year,'$username',now())";
					$res3=mysql_query($sql)or die("$sql query failed:".mysql_error());
			}//if save
			
			$ms=0;$juz=0;
			$sql="select * from hafazan where sid=$sid and uid='$uid' and curr_juzuk<30 order by id desc limit 1";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			$row2=mysql_fetch_assoc($res2);
			$xms=$row2['ms'];
			$xmss=$row2['mss'];
			$xno=$row2['no'];
			$xjk=$row2['juzuk'];
			
			/**
			$sql="select * from hafazan where sid=$sid and uid='$uid' and curr_juzuk=30 order by id desc limit 1";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			$row2=mysql_fetch_assoc($res2);
			$xms30=$row2['ms'];
			$xmss30=$row2['mss'];
			$xno30=$row2['no'];
			$xjk30=$row2['juzuk'];
			**/
			
			$i=0;$hafazanterkini=0;$hafazanterakhir=0;

			//$sql="select * from hafazan where sid=$sid and uid='$uid' and dt like '$y-$m%' order by dt desc limit 1";
			$sql="select * from hafazan where sid=$sid and uid='$uid' order by id desc limit 1";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			if($row2=mysql_fetch_assoc($res2))
					$hafazanterkini=$row2['ms'];

			$mm=date('Y-m', mktime(0, 0, 0, $m-1, 1, $y));
			$sql="select * from hafazan where sid=$sid and uid='$uid' and dt like '$mm%' order by dt,id desc limit 1";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			if($row2=mysql_fetch_assoc($res2))
					$hafazanterakhir=$row2['ms'];
					
			$i=0;$msbulanini=0;$msbulanlepas=0;
			$sql="select * from hafazan_rep where sid=$sid and uid='$uid' and year<=$y and month<=$m order by year desc,month desc limit 2";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			while($row2=mysql_fetch_assoc($res2)){
				$i++;
				if($i==1)
					$msbulanini=$row2['totalms'];
				else
					$msbulanlepas=$row2['totalms'];
			}
			
			
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
<table width="100%" style="font-size:12px"><tr><td width="30%">
<table width="100%" cellpadding="0">
  	<tr>
		<td width="60%">MS Hafalan Terakhir</td>
		<td width="1%">:</td>
		<td style="font-weight:bold "><?php echo "$hafazanterakhir";?></td>
  	</tr>
  	<tr>
		<td>MS Hafalan Terkini</td>
		<td>:</td>
		<td style="font-weight:bold "><?php echo "$hafazanterkini";?></td>
  	</tr>
</table>
</td><td>
<table width="100%" cellpadding="0">
    <tr>
		<td width="40%">Pertambahan Bulan <?php echo date('M Y', mktime(0, 0, 0, $m, 1, $y));?></td>
		<td width="1%">:</td>
		<td style="font-weight:bold "><?php echo "$msbulanini";?>  </td>
  	</tr>
    <tr>
		<td>Pertambahan Bulan <?php echo date('M Y', mktime(0, 0, 0, $m-1, 1, $y));?></td>
		<td>:</td>
		<td style="font-weight:bold "><?php echo "$msbulanlepas";?> </td>
  	</tr>
</table>
</td></tr></table>
<table width="100%" style="font-size:12px">
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
	  	<tr>
    	<td>Tanggal</td>
		<td>:</td>
    	<td>
		<input name="dt" type="text" id="dt" value="<?php echo "$dt";?>" size="12" onClick="displayDatePicker('dt');" readonly style="font-size:14px; font-weight:bold">
		<input name="ms"  type="hidden">
		<input name="mss" type="hidden">
		<input name="no"  type="hidden" size="1" value="<?php echo "$xno";?>">
		</td>
  	</tr>
</table>
</td>
<td width="40%" valign="top"><div id="mytitlebg">REKOD HAFAZAN SETIAP BULAN</div>


<?php
$chart_note_center="2011";
$chart_note_left="Bilangan Muka Surat";
$chart_item_group="Jan,Feb, Mac, Apr, May, Jun, Jul, Ogo, Sep, Oct, Nov, Dis";
//$chart_item_value="Hafalan,12,20,11,4,7,12,20;";
$chart_item_value="Hafalan";
$mm=date('m');
$yy=date('Y');
for($jj=1;$jj<=$mm;$jj++){
$sql="select count(*) from hafazan_rec where uid='$uid' and year=$yy and month=$jj";
$res3=mysql_query($sql)or die("$sql - query failed:".mysql_error());
$row3=mysql_fetch_row($res3);
$total_ms_month=$row3[0];
if($total_ms_month=="")
	$total_ms_month=0;
$chart_item_value=$chart_item_value.",";
$chart_item_value=$chart_item_value.$total_ms_month;
}

$xml="chart_mline1.php?dat=$chart_item_group|$chart_item_value|$chart_note_left|$chart_note_top|$chart_note_bottom|$chart_note_center|$chart_note_right|$chart_decimal_value";
?>
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
						'height', '180',
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



</td></tr></table>
<div id="tipsdiv">
Tips:<br>
- Sila tetapkan tarikh yang betul untuk tasmiq dan ulasan. Untuk ulasan bulanan pastikan tarikh pada hujung bulan, dan bukan di awal bulan.<br>
- Hafalan ulangan hanya boleh dibuat apabila 30 juzuk pertama telah tamat.<br>
- Pastikan rekod hafazan di rekodkan setiap bulan.<br>
</div>
<?php
if ($ULANG>0){
?>
<div id="mytitlebg">HAFAZAN ULANGAN <?php echo $ULANG;?></div>
<?php } ?>
<table width="100%" cellspacing="0">
	<tr>
		<td width="8%"  id="mytabletitle" style="border-right:none ">NO JUZUK</td>
		<td width="92%" id="mytabletitle" style="border-left:none " align="center">MUKASURAT</td>
	</tr>
</table>
<table width="100%" cellspacing="0"  cellpadding="2px" style="font-size:10px">
<?php 
	$j=2;
	for($i=1;$i<=30;$i++){
?>
	<tr>
		<td width="3%" id="mytabletitle">Juzuk&nbsp;<?php echo $i;?></td>
		<?php 	for($ii=0;$ii<20;$ii++,$j++){ 
			$sql="select * from hafazan_rec where sid=$sid and uid='$uid' and ms=$j order by id desc limit 1";
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
		<td width="3%" id="myborder" <?php echo $bgc;?> align="center" <?php if($ENABLE){?>onMouseOver="myin(this);" onMouseOut="myout(this);" onClick="process_mouse(<?php echo $j;?>,1)"<?php }?>>
			<?php echo $j;?>
		</td>
		<?php } ?>
	</tr>
<?php } ?>
	<tr>
		<td width="3%" id="mytabletitle">Juzuk&nbsp;30</td>
		<?php 	
		for($ii=0;$ii<3;$ii++,$j++){ 
				$sql="select * from hafazan_rec where sid=$sid and uid='$uid' and ms=$j order by id desc limit 1";
				$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				if($row2=mysql_fetch_assoc($res2)){
					$cc=$row2['ms'];
					$ENABLE=0;
					if($row2['ulang']==0){
						$bgc="bgcolor=#66FF66";
						if($ULANG==1)
							$ENABLE=1;
					}
					if($row2['ulang']==1)
						$bgc="bgcolor=#CCCC66";
				}else{
					$cc=0;
					$bgc="";
					$ENABLE=1;
				}

		?>
		<td width="3%" id="myborder" <?php echo $bgc;?> align="center" <?php if($ENABLE){?>onMouseOver="myin(this);" onMouseOut="myout(this);" onClick="process_mouse(<?php echo $j;?>,1)"<?php }?>>
			<?php echo $j;?>
		</td>
		<?php } ?>
	</tr>
</table>

</div></div>

</form> <!-- end myform -->


</body>
</html>
<!-- 
V.1
Author: razali212@yahoo.com
 -->