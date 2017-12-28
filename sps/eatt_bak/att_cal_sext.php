<?php
//new color set
$bghijau	="#00FF99"; //hadir
$bgkuning	="#FFFF99"; //cuti minggu
$bgmerah	="#FF9999"; //tak hadir
$bgbiru		="#99CCFF"; //cuti sem
$bgkelabu	="#999999"; //tak taksir
$bgpink		="#FFCCFF"; //cuti umum
$bgputih	="#FFFFFF"; //belum set

	include_once('../etc/db.php');
	include_once('../etc/session.php');
	verify('ADMIN|HR');
	$op=$_POST['op'];
	$year=$_POST['year'];
	if($year=="")
			$year=date('Y');
	
	$sql="select count(*) from ses_cal where year='$year' and sta=0";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_row($res);
	$jumlah=$row[0];
	
	if($op=="update"){
		
		$cal=$_POST['sescal'];
		
		if (count($cal)>0) {
			$sql="delete from ses_cal where year='$year'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
				for ($i=0; $i<count($cal); $i++) {
					$data=$cal[$i];
					list($xdt,$xst)=split('[/.|]',$data);
					///echo "Dt:$xdt St:$xst<br>";
            		$sql="insert into ses_cal(year,d,sta) values ('$year','$xdt',$xst)";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
				}
		}
		//$sql="select * from type where id=$id";
		//$res=mysql_query($sql)or die("query failed:".mysql_error());
		//$row=mysql_fetch_assoc($res);
		$prm=$row['prm'];
		$val=$row['val'];
	}
	
	$m= date("m");
	$d= date("d");     // Finds today's date
	$y= date("Y");     // Finds today's year
	
	//echo "$d $m $y <br>";
	$no_of_days = date('t',mktime(0,0,0,$m,1,$y)); // This is to calculate number of days in a month
	$mn=date('M',mktime(0,0,0,$m,1,$y)); // Month is calculated to display at the top of the calendar
	$yn=date('Y',mktime(0,0,0,$m,1,$y)); // Year is calculated to display at the top of the calendar
	$j= date('w',mktime(0,0,0,$m,1,$y)); // This will calculate the week day of the first day of the month
	//echo "$no_of_days $mn $yn $j<br>";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>	


<script language="javascript">
function process_form(action){
	var ret="";
	var cflag=false;
	if(action=='update'){
		ret = confirm("Are you sure want to SAVE??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
		return;
	}
}

</script>
<style type="text/css">
<!--
body {
	
	background-image:url(../img/bg_white.jpg);
	
}
#bor{
border-top: 1px solid #99BBFF;
border-bottom: 1px solid #99BBFF;
border-left: 1px solid #99BBFF;
border-right: 1px solid #99BBFF;
}
-->
</style>

</head>
<body>
<div id="content">


<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="process_form('update')"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
<a href="#" onClick="window.close()"id="mymenuitem"><img src="../img/close.png"><br>Close</a>
</div> <!-- end mymenu -->

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="op">
<table width="100%" border="0">
  <tr>
    <td align="right"><select name="year" id="year" onchange="document.myform.submit();">
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
    </select></td>
  </tr>
</table>
</div><!-- end mypanel -->

<div id="story">
<table width="100%" border="0" id="mytitle">
  <tr>
    <td>PERSEKOLAHAN SESI <?php echo "$year : $jumlah";?> HARI</td>
    <td align="right">
      <table  border="0" bgcolor="#666666">
        <tr>
          <td bgcolor="<?php echo $bgputih;?>" align="center">Belum Set</td> <!-- putih -->
          <td bgcolor="<?php echo $bghijau;?>" align="center">Hari Sekolah(0)</td> <!-- hijau -->
          <td bgcolor="<?php echo $bgkuning;?>" align="center">Cuti Mingguan(1)</td> <!-- kuning -->
          <td bgcolor="<?php echo $bgbiru;?>" align="center">Cuti Penggal(2)</td> <!-- orange -->
          <td bgcolor="<?php echo $bgpink;?>" align="center">Cuti Umum(3)</td> <!-- pink -->
        </tr>
      </table></td>
  </tr>
</table>

<table width="100%" bgcolor="#99BBFF" id="bor">
  <tr>
    <td width="10%"valign="top">
	
	<?php 
		for($i=0;$i<=42;$i++){
			$x="";$bg="";
			if($i==0){ $x=$year;;$bg="bgcolor=#990000 id=\"bor\"";}
			elseif($i%7==0){ $x="Sabtu";$bg="bgcolor=#000099 height=50px id=\"bor\"";}
			elseif($i%7==6){ $x="Jumaat";$bg="bgcolor=#000099 height=50px id=\"bor\"";}
			elseif($i%7==5){ $x="Khamis";$bg="bgcolor=#000099 height=50px id=\"bor\"";}
			elseif($i%7==4){ $x="Rabu";$bg="bgcolor=#000099 height=50px id=\"bor\"";}
			elseif($i%7==3){ $x="Selasa";$bg="bgcolor=#000099 height=50px id=\"bor\"";}
			elseif($i%7==2){ $x="Isnin";$bg="bgcolor=#000099 height=50px id=\"bor\"";}
			elseif($i%7==1){ $x="Ahad"; $bg="bgcolor=#006633 height=50px id=\"bor\"";}
			
			echo "<table width=\"100%\" border=\"0\" $bg> <tr><td >&nbsp;<strong><font color=\"#FFFFFF\">$x</font></strong><br></td></tr></table>";
		}
	?>
	</td>
<?php 	for($a=1;$a<=12;$a++){ ?>
    <td valign="top" >
	<?php 
		//$m= date("m");
		$m=$a;
		$d= date("d");     // Finds today's date
		//$y= date("Y");     // Finds today's year
		$y=$year;
		$nd = date('t',mktime(0,0,0,$m,1,$y)); // This is to calculate number of days in a month
		$mn=date('M',mktime(0,0,0,$m,1,$y)); // Month is calculated to display at the top of the calendar
		$yn=date('Y',mktime(0,0,0,$m,1,$y)); // Year is calculated to display at the top of the calendar
		$j= date('w',mktime(0,0,0,$m,1,$y)); // This will calculate the week day of the first day of the month
		
		echo "<table width=\"100%\"  border=\"0\" bgcolor=\"#000099\" id=\"bor\"> <tr><td align=\"center\"><strong><font color=\"#FFFFFF\">$mn<br></font></strong></td></tr></table>";
		for($k=1; $k<=$j; $k++){ // Adjustment of date starting
			echo "<table width=\"100%\" border=\"0\" bgcolor=\"#CCCCCC\" height=50px id=\"bor\"> <tr><td>&nbsp;</td></tr></table>";
		}
		for($i=1;$i<=$nd;$i++){
			$dn=date('D',mktime(0,0,0,$m,$i,$y)); // get days MON, TUE etc 
			$dt=date('Y-m-d',mktime(0,0,0,$m,$i,$y)); // get date 2008-12-31 
			$bg="";
			$sql="select * from ses_cal where d='$dt'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sta=$row['sta'];
			if($sta=="") $bg="bgcolor=$bgputih"; //sekolah - putih
			if($sta=="0") $bg="bgcolor=$bghijau"; //sekolah - hijau
			if($sta=="1") $bg="bgcolor=$bgkuning"; //cuti biasa - kuning
			if($sta=="2") $bg="bgcolor=$bgbiru"; //cuti penggal - biru
			if($sta=="3") $bg="bgcolor=$bgpink"; //public holiday - pink
			echo "<table width=\"100%\"  border=\"0\" $bg height=50px id=\"bor\"> <tr><td align=center>&nbsp;$i<font size=\"1\">$dn</font><br>";
			echo "<select name=\"sescal[]\" id=\"smalltxt\">";
			if($sta!="")
				echo "<option value=\"$dt|$sta\">$sta</option>";
			echo "<option value=\"$dt|0\">0</option>";
			echo "<option value=\"$dt|1\">1</option>";
			echo "<option value=\"$dt|2\">2</option>";
			echo "<option value=\"$dt|3\">3</option>";
			echo "</select>";
			echo "</td></tr></table>";
		}
		for($z=0; $z<42-$j-$nd; $z++){ // Adjustment of date starting
			echo "<table width=\"100%\" border=\"0\" bgcolor=\"#CCCCCC\" height=50px id=\"bor\"> <tr><td>&nbsp;</td></tr></table>";
		}
	?>
	</td>
<?php }?>
    
  </tr>
</table>
</form>

</div>
</div>


</body>
</html>