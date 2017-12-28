<?php
	include_once('../etc/db.php');
	$xsid=$_SESSION['sid'];
	
	$prm=$_REQUEST['prm'];
	$chm=$_REQUEST['chm'];
	$day=$_REQUEST['day'];
	$tar=$_REQUEST['tar'];
	if(isset($prm) and $prm > 0){
		$month=$prm+$chm;
	}else{
		$month= date("m");
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
$year= date("Y");     // Finds today's year

$no_of_days = date('t',mktime(0,0,0,$month,1,$year)); // This is to calculate number of days in a month
$no_of_days_last_month = date('t',mktime(0,0,0,$month-1,1,$year)); // This is to calculate number of days in a month

$mn=date('M',mktime(0,0,0,$month,1,$year)); // Month is calculated to display at the top of the calendar

$yn=date('Y',mktime(0,0,0,$month,1,$year)); // Year is calculated to display at the top of the calendar

$j= date('w',mktime(0,0,0,$month,1,$year)); // This will calculate the week day of the first day of the month

for($k=1; $k<=$j; $k++){ // Adjustment of date starting
	$last_month_date=$no_of_days_last_month-($j-$k);
	$adj .="<td id=myborder bgcolor=#DDDDDD valign=top ><font size='1' face='Tahoma'>$last_month_date</font></td>";
	$number_of_cell++;
}

//apai
//apai get today

$currentdate=date('Y-m-d');
//echo "Current:$currentdate<br>";
$sysm=date('m',mktime(0,0,0,$month,1,$year)); // Month is calculated to display at the top of the calendar
//echo "System:$yn-$sysm<br>";
$day=date('d',mktime(0,0,0,$month,$day,$year));
//echo "User:$yn-$sysm-$day<br>";

/// Starting of top line showing name of the days of the week
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript">
function myin(id,sta){
		/**id.style.backgroundColor='#66FF66';**/
		id.style.cursor='pointer';
		id.style.border='1px solid #333333';
		
}
function myout(id,sta){
		/**id.style.backgroundColor='';**/
		id.style.cursor='default';
		id.style.border='1px solid #F1F1F1';
}
function myinbg(id,sta){
		id.style.backgroundColor='#00FF66';
		id.style.cursor='pointer';
		id.style.border='1px solid #333333';
}
function myoutbg(id,sta){
		id.style.backgroundColor='';
		id.style.cursor='default';
		id.style.border='1px solid #F1F1F1';
}
</script>
</head>
<title>Program Calendar</title></head>
<body>
<script type="text/javascript" src="../../../myobj/wz_tooltip/wz_tooltip.js"></script>
	<form name="mycal" method="post">
	<input type="hidden" name="month" value="<?php echo $month;?>">
    <input type="hidden" name="year" value="<?php echo $yn;?>">
    <input type="hidden" name="target" id="target" value="<?php echo $tar;?>">
<table align=center width='100%' cellspacing="0" style="border: 1px solid #99BBFF;">
   <tr>
   		<td align=center background="../img/bg_title_lite.jpg">
			<a href='index.php?prm=<?php echo "$month";?>&chm=-1&tar=<?php echo "$tar";?>'><img src="../img/goback.png" height="20" width="20" border="0"></a>
		</td>
	  	<td colspan=5 align=center  background="../img/bg_title_lite.jpg">
			<font size='3' face='Arial' color="#FFFFFF"><b><?php echo "$d $mn $yn";?></b>
	  	</td>
	  	<td align=center background="../img/bg_title_lite.jpg"><a href='index.php?prm=<?php echo "$month";?>&chm=1&tar=<?php echo "$tar";?>'>
			<img src="../img/gonext.png" height="20" width="20" border="0"></a>
		</td>
	</tr>
</table>

<table align=center width='100%' cellspacing="0">
	<tr>
	  <td width="14%" id=myborder align=center  background="../img/bg_title_lite.jpg"><font size='1' face='Verdana' color="#FFFFFF"><b>Sun</b></font></td>
	  <td width="14%" id=myborder align=center  background="../img/bg_title_lite.jpg"><font size='1' face='Verdana' color="#FFFFFF"><b>Mon</b></font></td>
	  <td width="14%" id=myborder align=center  background="../img/bg_title_lite.jpg"><font size='1' face='Verdana' color="#FFFFFF"><b>Tue</b></font></td>
	  <td width="14%" id=myborder align=center  background="../img/bg_title_lite.jpg"><font size='1' face='Verdana' color="#FFFFFF"><b>Wed</b></font></td>
	  <td width="14%" id=myborder align=center  background="../img/bg_title_lite.jpg"><font size='1' face='Verdana' color="#FFFFFF"><b>Thu</b></font></td>
	  <td width="14%" id=myborder align=center  background="../img/bg_title_lite.jpg"><font size='1' face='Verdana' color="#FFFFFF"><b>Fri</b></font></td>
	  <td width="14%" id=myborder align=center  background="../img/bg_title_lite.jpg"><font size='1' face='Verdana' color="#FFFFFF"><b>Sat</b></font></td>
</tr><tr>
<?php
////// End of the top line showing name of the days of the week//////////

//////// Starting of the days//////////
for($i=1;$i<=$no_of_days;$i++){
	echo "$adj";
	$thisdate=sprintf("$yn-$sysm-%02d",$i);
	$dt=date('Y-m-d',mktime(0,0,0,$month,$i,$year)); // get date 2008-12-31 
	$sql="select * from ses_cal where d='$dt'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$sta=$row['sta'];
	if($sta=="") $bg="bgcolor=$bgkuning"; //sekolah - putih
	if($sta=="0") $bg="bgcolor=$bgkuning"; //sekolah - hijau
	if($sta=="1") $bg="bgcolor=$bgbiru"; //cuti biasa - kuning
	if($sta=="2") $bg="bgcolor=$bgpink"; //cuti penggal - biru
	if($sta=="3") $bg="bgcolor=$bgoren"; //public holiday - pink
			
	$evt="";
	$des="";
	if($xsid)
		$sql="select * from calendar_event where dt='$dt'";
	else
		$sql="select * from calendar_event where dt='$dt' and (sid=0 or sid=$xsid)";
    $res=mysql_query($sql)or die("query failed:".mysql_error());
    while($row=mysql_fetch_assoc($res)){
				$evt=$evt."&bull;".stripslashes($row['event'])."<br>";
				$des=stripslashes($row['des']);
	}

	$emark="";
	if($evt!="")
		$emark="<strong><font color=red>!</font></strong>";

	$adj='';$j++;
	if($j==7)
			$border_right="";
	else
			$border_right="border-right:none;";
	
	
	if($thisdate==$currentdate)
		$bg="bgcolor=$bghijau";
	
?>
		<td id="myborder"  <?php echo "$bg";?>  align="center" style="font-size:12px; font-family:verdana;" onMouseOver="myinbg(this,1)" onMouseOut="myoutbg(this,1)">
			<div style="padding:4px 4px 4px 4px"><a href="#"><?php echo "$i$emark";?></a></div>
		</td>
<?php	
		$number_of_cell++;
		if($j==7){
			echo "</tr><tr>";
			$j=0;
		}
}
		if($j!=0){
            while ($j<7){ // Adjustment of date starting
				$date_of_next_month++;
				$number_of_cell++;
				$j++;
				if($j==7)
					$border_right="";
				else
					$border_right="border-right:none;";
                echo "<td id=myborder valign=top bgcolor=#DDDDDD><font size='1' face='Verdana'>$date_of_next_month</font></td>";
            }
        }
		
echo "</table>";
?>
	<div align="center" style="font-size:12px; padding:2px; font-family:Arial; color:#FFFFFF; background-color:#666666;border: 1px solid #99BBFF;">
		<a href="#" style="font-family:Arial; color:#FFFFFF; text-decoration:none;">
			<strong><?php echo date('F',mktime(0,0,0,$month,1,$year));?>'s Program &amp; Event </strong>
		</a>
	</div>
	<div id="myborder" style="height:120px; min-height:120px; overflow-y: auto; background-color:#FFFF99;">
	
<?php
		$i=0;
		$dt=date('Y-m',mktime(0,0,0,$month,1,$year));
		$currdt=date('Y-m-d');
		if($ONLINE_MSG_GLOBAL)
				$sql="select * from calendar_event where dt like '$dt%' and dt>='$currdt' order by dt";
		else
				$sql="select * from calendar_event where dt like '$dt%' and (sid=0 or sid=$xsid) and dt>='$currdt'  by dt";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        while($row=mysql_fetch_assoc($res)){
				$evt=stripslashes($row['event']);
				$xdt=stripslashes($row['dt']);
				list($xyy,$xmm,$xdd)=split("-",$xdt);
				$i++;
				if($i>1){
?>
			<div id="myborder" style="border-top:1px solid #DDDDDD "></div>
<?php } ?>
			<div  style="font-size:9px; padding:5px 4px 5px 4px">
					<font face="Verdana" color="#996600"><strong>&bull;&nbsp;<?php echo "$evt";?></strong><br><?php echo date('l',mktime(0,0,0,$xmm,$xdd,$xyy)); echo " $xdd/$xmm/$xyy";?></font>
			</div>
<?php } ?>
	
	</div>

</body>
</html>