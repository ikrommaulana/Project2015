<?php
	include_once('../etc/db.php');
	include_once('../etc/session.php');
$prm=$_REQUEST['prm'];
$chm=$_REQUEST['chm'];
$day=$_REQUEST['day'];
$tar=$_REQUEST['tar'];
if(isset($prm) and $prm > 0){
	$m=$prm+$chm;
	
}else{
	$m= date("m");
}

//new color set
$bghijau	="#00FF99"; //hadir
$bgkuning	="#FFFF99"; //cuti minggu
$bgmerah	="#FF9999"; //tak hadir
$bgbiru		="#99CCFF"; //cuti sem
$bgkelabu	="#999999"; //tak taksir
$bgpink		="#FFCCFF"; //cuti umum
$bgputih	="#FFFFFF"; //belum set

$d= date("d");     // Finds today's date
$y= date("Y");     // Finds today's year

$no_of_days = date('t',mktime(0,0,0,$m,1,$y)); // This is to calculate number of days in a month

$mn=date('M',mktime(0,0,0,$m,1,$y)); // Month is calculated to display at the top of the calendar

$yn=date('Y',mktime(0,0,0,$m,1,$y)); // Year is calculated to display at the top of the calendar

$j= date('w',mktime(0,0,0,$m,1,$y)); // This will calculate the week day of the first day of the month

for($k=1; $k<=$j; $k++){ // Adjustment of date starting
	$adj .="<td>&nbsp;</td>";
}

//apai
//apai get today

$currentdate=date('Y-m-d');
//echo "Current:$currentdate<br>";
$sysm=date('m',mktime(0,0,0,$m,1,$y)); // Month is calculated to display at the top of the calendar
//echo "System:$yn-$sysm<br>";
$day=date('d',mktime(0,0,0,$m,$day,$y));
//echo "User:$yn-$sysm-$day<br>";

/// Starting of top line showing name of the days of the week
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript">
function update(day){
		var month = document.mycal.month.value;
		if(month>12){
			var xmonth = month-12;
		}else{
			var xmonth = month;
		}
		document.mycal.month.value = xmonth;
		var date=document.mycal.year.value + "-" + document.mycal.month.value + "-" + day;
		var tar=document.getElementById("target").value;
		eval("self.opener.document.myform." + tar + ".value= date"); 
		window.close();
		return;

}
</script>

<style type="text/css">
<!--
TD
{
	BORDER-RIGHT: #3C5B98 thin solid;
	BORDER-TOP: #3C5B98 thin solid;
	BORDER-LEFT: #3C5B98 thin solid;
	BORDER-BOTTOM: #3C5B98 thin solid;
	PADDING-RIGHT: 2px;
	PADDING-LEFT: 2px;
	PADDING-TOP: 1px;
	PADDING-BOTTOM: 2px;
	FONT-FAMILY: Verdana, Arial;
	TEXT-ALIGN: center;
	color: #666666;
	FONT-SIZE: 10pt;
}

a
{
	FONT-SIZE: 10pt;
	FONT-FAMILY: Verdana, Arial;
	TEXT-DECORATION:underline;
	TEXT-ALIGN: left
}

select
{
    FONT-SIZE: 8pt;
    COLOR: #284281;
    FONT-FAMILY: Verdana, Arial;
    TEXT-DECORATION: none;
    TEXT-ALIGN: left

}
-->
</style>
<title> Calendar</title></head>
<body background="../img/bg_body.jpg">
	<form name="mycal" method="post">
	<input type="hidden" name="month" value="<?php echo $m;?>">
    <input type="hidden" name="year" value="<?php echo $yn;?>">
    <input type="hidden" name="target" id="target" value="<?php echo $tar;?>">
   
<table align=center width='100%'>
	<tr>
      <td bgcolor="<?php echo $bghijau;?>">Hari Sekolah</td> <!-- hijau -->
      <td bgcolor="<?php echo $bgkuning;?>">Cuti Mingguan</td> <!-- kuning -->
      <td bgcolor="<?php echo $bgbiru;?>">Cuti Penggal</td> <!-- orange -->
      <td bgcolor="<?php echo $bgpink;?>">Cuti Umum</td> <!-- pink -->
    </tr>
</table>

<table align=center width='100%'>
	<tr>
	  <td align=center bgcolor="#6699CC"><font size='4' face='Tahoma'> <a href='mycal.php?prm=<?php echo "$m";?>&chm=-1&tar=<?php echo "$tar";?>'><img src="../img/goback.png" border="0"></a> </td>
	  <td colspan=5 align=center bgcolor="#6699CC"><font size='6' face='Palatino Linotype' color="#FFFFFF"><?php echo "$mn $yn";?></td>
	  <td align=center bgcolor="#6699CC"><font size='4' face='Tahoma'> <a href='mycal.php?prm=<?php echo "$m";?>&chm=1&tar=<?php echo "$tar";?>'><img src="../img/gonext.png" border="0"></a> </td>
</tr>
	<tr>
	  <td align=center><font size='4' face='Palatino Linotype'><b>Sun</b></font></td>
	  <td align=center><font size='4' face='Palatino Linotype'><b>Mon</b></font></td>
	  <td align=center><font size='4' face='Palatino Linotype'><b>Tue</b></font></td>
	  <td align=center><font size='4' face='Palatino Linotype'><b>Wed</b></font></td>
	  <td align=center><font size='4' face='Palatino Linotype'><b>Thu</b></font></td>
	  <td align=center><font size='4' face='Palatino Linotype'><b>Fri</b></font></td>
	  <td align=center><font size='4' face='Palatino Linotype'><b>Sat</b></font></td>
</tr><tr>
<?php
////// End of the top line showing name of the days of the week//////////

//////// Starting of the days//////////
for($i=1;$i<=$no_of_days;$i++){
	echo "$adj";
	$thisdate=sprintf("$yn-$sysm-%02d",$i);
	$dt=date('Y-m-d',mktime(0,0,0,$m,$i,$y)); // get date 2008-12-31 
	$sql="select * from ses_cal where d='$dt'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$sta=$row['sta'];
	if($sta=="") $bg="bgcolor=$bgputih"; //sekolah - putih
	if($sta=="0") $bg="bgcolor=$bghijau"; //sekolah - hijau
	if($sta=="1") $bg="bgcolor=$bgkuning"; //cuti biasa - kuning
	if($sta=="2") $bg="bgcolor=$bgbiru"; //cuti penggal - biru
	if($sta=="3") $bg="bgcolor=$bgpink"; //public holiday - pink
	if($thisdate==$currentdate)
			echo "<td valign=top align=center width='40px' $bg><a style=\"color:red; text-decoration:none; text-decoration:blink;\" href=\"javascript:update('".str_pad($i, 2, "0", STR_PAD_LEFT)."')\"><font size='4' face='tahoma'><b>$i</a></td>"; // This will display the date inside the calendar cell
	elseif($thisdate>$currentdate)
			echo "<td valign=top align=center width='40px' $bg><a style=\"color:blue; text-decoration:none\" href=\"javascript:update('".str_pad($i, 2, "0", STR_PAD_LEFT)."')\"><font size='4' face='tahoma'><b>$i</a></td>"; // This will display the date inside the calendar cell
	else
			echo "<td valign=top align=center width='40px' $bg><a style=\"color:gray; text-decoration:none\" href=\"javascript:update('".str_pad($i, 2, "0", STR_PAD_LEFT)."')\"><font size='4' face='tahoma'><b>$i</a></td>"; // This will display the date inside the calendar cell
		$adj='';
		$j++;
		if($j==7){
			echo "</tr><tr>";
			$j=0;
		}
}

echo "</table>";
?>
</body>
</html>