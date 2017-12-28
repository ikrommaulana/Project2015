<?php
	include_once('../etc/db.php');
	include_once('../etc/session.php');
	include_once("$MYLIB/inc/language_$LG.php");
	$adm = $_SESSION['username'];

	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
	
	
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
	$adj .="<td id=\"myborder\" style=\"border-color:#999999;border-right:none;border-top:none;border-right:none;\" bgcolor=\"#DDDDDD\" align=\"center\"><font size='1' face='Tahoma'>$last_month_date</font></td>";
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
<!-- SETTING GRAYBOX -->
<script type="text/javascript"> var GB_ROOT_DIR = "<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/"; </script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_scripts.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />

<script language="JavaScript">
function myin(id,sta){
		///**id.style.backgroundColor='#66FF66';**/
		id.style.cursor='pointer';
		id.style.border='1px solid #333333';
		
}
function myout(id,sta){
		///**id.style.backgroundColor='';**/
		id.style.cursor='default';
		id.style.border='1px solid #F1F1F1';
}
function myinbg(id,sta){
		id.style.backgroundColor='#999999';
		id.style.color='#FFFFFF';
		id.style.cursor='pointer';
		////id.style.border='1px solid #333333';*
}
function myoutbg(id,sta){
		id.style.backgroundColor='';
		id.style.cursor='default';
		id.style.color='#000000';
		//id.style.border='1px solid #F1F1F1';
}
</script>
</head>
<title>Calendar</title></head>
<body>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/wz_tooltip531/wz_tooltip.js"></script>

<a id="bigcal" href="../calendar/cal_big.php?xdate=<?php echo "$dt&prm=$prm&chm=$chm";?>" style="display:none;">Big Calendar</a>
<table align=center width='100%' cellspacing="0">
   <tr>
   		<td align=center id="masthead_title" style="padding:3px;border-right:none; border-top:none;">
			<a style="cursor:pointer" onClick="process_calendar('<?php echo "$month";?>','-1','<?php echo "$tar";?>')">
			<img src="<?php echo $MYLIB;?>/img/goback.png" height="20" width="20" border="0"></a>
		</td>
	  	<td colspan=5 align=center id="masthead_title" style="padding:3px;border:none;">
			<?php echo "$d $mn $yn";?>
	  	</td>
	  	<td align=center id="masthead_title" style="padding:3px;border-left:none; border-top:none;">
			<a style="cursor:pointer"  onClick="process_calendar('<?php echo "$month";?>','1','<?php echo "$tar";?>')">
			<img src="<?php echo $MYLIB;?>/img/gonext.png" height="20" width="20" border="0"></a>
		</td>
	</tr>
</table>

<table align="center" width='100%' cellspacing="0" cellpadding="3px"  onClick="return GB_showPage('Calendar',document.getElementById('bigcal').href)">
	<tr>
	  <td width="14%" id="myborder" style="border-color:#999999;border-right:none;font-size:11px;" align="center"  background="<?php echo $MYLIB;?>/img/bg_title_lite.jpg"><b>Sun</b></td>
	  <td width="14%" id="myborder" style="border-color:#999999;border-right:none;font-size:11px;" align="center"  background="<?php echo $MYLIB;?>/img/bg_title_lite.jpg"><b>Mon</b></td>
	  <td width="14%" id="myborder" style="border-color:#999999;border-right:none;font-size:11px;" align="center"  background="<?php echo $MYLIB;?>/img/bg_title_lite.jpg"><b>Tue</b></td>
	  <td width="14%" id="myborder" style="border-color:#999999;border-right:none;font-size:11px;" align="center"  background="<?php echo $MYLIB;?>/img/bg_title_lite.jpg"><b>Wed</b></td>
	  <td width="14%" id="myborder" style="border-color:#999999;border-right:none;font-size:11px;" align="center"  background="<?php echo $MYLIB;?>/img/bg_title_lite.jpg"><b>Thu</b></td>
	  <td width="14%" id="myborder" style="border-color:#999999;border-right:none;font-size:11px;" align="center"  background="<?php echo $MYLIB;?>/img/bg_title_lite.jpg"><b>Fri</b></td>
	  <td width="14%" id="myborder" style="border-color:#999999;font-size:11px;" align="center"  background="<?php echo $MYLIB;?>/img/bg_title_lite.jpg"><b>Sat</b></td>
</tr><tr>
<?php
////// End of the top line showing name of the days of the week//////////

//////// Starting of the days//////////
for($i=1;$i<=$no_of_days;$i++){
	echo "$adj";
	$thisdate=sprintf("$yn-$sysm-%02d",$i);
	$dt=date('Y-m-d',mktime(0,0,0,$month,$i,$year)); // get date 2008-12-31 
	if($CALENDAR_GLOBAL)
		$sql="select * from ses_cal where d='$dt'";
	else
		$sql="select * from ses_cal where d='$dt' and sch_id=$sid";
		
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$sta=$row['sta'];
	if($sta=="")  $bg="bgcolor=\"\""; 		//unset 
	if($sta=="0") $bg="bgcolor=\"\""; 		//sekolah 
	if($sta=="1") $bg="bgcolor=\"$bgbiru\""; 	//cuti biasa - biru
	if($sta=="2") $bg="bgcolor=\"$bgpink\""; 	//cuti penggal - pink
	if($sta=="3") $bg="bgcolor=\"$bgoren\""; 	//public holiday - oren
	
	//$bg="bgcolor=\"$bgkuning\""; //ignore setting color set default yellow
			
	$evt="";
	$des="";
	if($CALENDAR_GLOBAL)
		$sql="select * from calendar_event where dt='$dt' and (isprivate=0 or adm='$adm')";
	else
		$sql="select * from calendar_event where dt='$dt' and (sid=0 or sid=$sid) and (isprivate=0 or adm='$adm')";
    $res=mysql_query($sql)or die("query failed:".mysql_error());
    while($row=mysql_fetch_assoc($res)){
				$evt=$evt."&bull;".stripslashes($row['event'])."<br>";
				$des=stripslashes($row['des']);
	}

	$emark="";$smark="";
	if($evt!=""){
		$bg="bgcolor=\"$bghijau\"";
		$tipss="Tip('".$evt."')";
		$untipss="UnTip();";
		$date="$i";
	}else{
		$tipss="";
		$untipss="";
		$date="$i";
	}

	$adj='';$j++;
	
	if($thisdate==$currentdate)
		$bg="bgcolor=\"#BBBBBB\"";
		
	if($j==7)
		$br="";
	else
		$br="border-right:none;";
	
?>
		<td id="myborder"  <?php echo "$bg";?>align="center" style="border-color:#999999;border-right:none;font-size:11px;border-top:none;<?php echo "$br";?>" 
			onMouseOver="myinbg(this,1);<?php echo "$tipss";?>" onMouseOut="myoutbg(this,1);<?php echo "$untipss";?>">
			<?php echo "$date";?>
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
					$br="";
				else
					$br="border-right:none;";
?>
           <td id="myborder" style="border-color:#999999;border-right:none;border-top:none;<?php echo $br;?>" valign="top" bgcolor="#DDDDDD" align="center"><font size='1' face='Verdana'><?php echo $date_of_next_month;?></font></td>
<?php
            }
        }
?>
</table>
	<div style="font-size:11px;  font-weight:bold; border:1px solid #ccc; padding:4px;background-image:url(<?php echo $MYLIB;?>/img/bg_panel2.jpg)">
			<?php echo date('F',mktime(0,0,0,$month,1,$year));?><?php if($LG=="BI") echo "'s ";?>
			<?php echo $lg_program_and_event;?>
    </div>
            
            
            
	<div id="myborder" style="max-height:230px; min-height:130px; overflow-y: auto;background-image:url(<?php echo $MYLIB;?>/img/bg_panel2.jpg);">
<?php
		$i=0;
		$dt=date('Y-m',mktime(0,0,0,$month,1,$year));
		$currdt=date('Y-m-d');
		if($CALENDAR_GLOBAL)
				$sql="select * from calendar_event where dt like '$dt%' and dt>='$currdt' and (isprivate=0 or adm='$adm') order by dt";
		else
				$sql="select * from calendar_event where dt like '$dt%' and (sid=0 or sid=$sid) and dt>='$currdt' and (isprivate=0 or adm='$adm') order by dt";
        $res=mysql_query($sql)or die("$sql query failed:".mysql_error());
        while($row=mysql_fetch_assoc($res)){
				$evt=stripslashes($row['event']);
				$xdt=stripslashes($row['dt']);
				$isprivate=$row['isprivate'];
				list($xyy,$xmm,$xdd)=split("-",$xdt);
?>
	<div  style="font-size:9px; padding:5px 4px 5px 4px">
			<font face="Verdana" color="#996600"><strong><?php echo "$evt";?></strong>
            <?php if($isprivate){?><img src="<?php echo $MYLIB;?>/img/user12.png" height="10" title="Personal Reminder"><?php } ?>
            <br><?php echo date('l',mktime(0,0,0,$xmm,$xdd,$xyy)); echo " $xdd/$xmm/$xyy";?></font>
	</div>
    <div id="myborder" style="border-top:1px solid #DDDDDD; border-bottom:none;"></div>
<?php } ?>


<?php
// list pass event this month
		$i=0;
		$dt=date('Y-m',mktime(0,0,0,$month,1,$year));
		$currdt=date('Y-m-d');
		if($CALENDAR_GLOBAL)
				$sql="select * from calendar_event where dt like '$dt%' and dt<'$currdt' and (isprivate=0 or adm='$adm') order by dt desc";
		else
				$sql="select * from calendar_event where dt like '$dt%' and (sid=0 or sid=$sid) and dt<'$currdt' and (isprivate=0 or adm='$adm') order by dt desc";
        $res=mysql_query($sql)or die("$sql query failed:".mysql_error());
        while($row=mysql_fetch_assoc($res)){
				$evt=stripslashes($row['event']);
				$xdt=stripslashes($row['dt']);
				$isprivate=$row['isprivate'];
				list($xyy,$xmm,$xdd)=split("-",$xdt);
?>
	<div  style="font-size:9px; padding:5px 4px 5px 4px">
			<font face="Verdana" color="#666666"><?php echo "$evt";?>
            <?php if($isprivate){?><img src="<?php echo $MYLIB;?>/img/user12.png" height="10" title="Personal Reminder"><?php } ?>
            <br><?php echo date('l',mktime(0,0,0,$xmm,$xdd,$xyy)); echo " $xdd/$xmm/$xyy";?></font>
	</div>
    	<div id="myborder" style="border-top:1px solid #DDDDDD; border-bottom:none;"></div>
<?php } ?>
	</div>
</body>
</html>