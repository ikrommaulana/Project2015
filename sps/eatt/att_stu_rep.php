<?php
//500 - 01/08/2010 - language set
$vmod="v5.0.0";
$vdate="21/07/2010";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");

	$op=$_POST['op'];
	$uid=$_REQUEST['uid'];
	$sql="select * from stu where uid='$uid'";
	$res=mysql_query($sql) or die(mysql_error());
	$row=mysql_fetch_assoc($res);
	$name=$row['name'];
	$stu_uid=$row['uid'];
	$ic=$row['ic'];
	$sid=$row['sch_id'];
	$file=$row['file'];
	$rdate=$row['rdate'];
		
	$sql="select * from sch where id='$sid'";
    $res=mysql_query($sql)or die("query failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
    $sname=$row['name'];
	$slevel=$row['level'];
	$issemester=$row['issemester'];
	$startsemester=$row['startsemester'];
    mysql_free_result($res);
	
	$year=$_REQUEST['year'];
	if($year=="")
			$year=date('Y');

	$nyear=$year+1;
	if($issemester){
		$year="$year/$nyear";
	}
			
				
	$sql="select * from ses_stu where stu_uid='$stu_uid' and year='$year'";
	$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$clsname=$row['cls_name'];
	$clslevel=$row['cls_level'];

$totaljumlah=0;$schday=0;$totalabsence=0;$absence=0;
for($tt=1;$tt<=12;$tt++){
$sqlmonth="select * from month where idx='$tt'";
$resmonth=mysql_query($sqlmonth)or die("$sqlmonth query failed:".mysql_error());
$rowmonth=mysql_fetch_assoc($resmonth);
$mn=$rowmonth['name'];
$monthno=$rowmonth['no'];
$idxno=$rowmonth['idx'];
if($startsemester==""){
	if($idxno==1){
		$startsemester=$monthno;
	}
}
if($monthno<$startsemester){
$yn=$year+1;
}else{
$yn=$year;
}

$cyear=date('Y');
//echo $yn;

	$sql="select etc from type where grp='attendance_set' and code='in';";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_row($res);
	$clockin=$row[0];
	$clockin=str_pad($clockin, 5, '0', STR_PAD_LEFT);


for($a=$cyear;$a<=$yn;$a++){
$yn=date('Y',mktime(0,0,0,$monthno,1,$yn));
$mn=date('m',mktime(0,0,0,$monthno,1,$yn));
	$sql="select count(*) from ses_cal where year='$yn' and d like '$yn-$mn%' and sta!=0";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_row($res);
	$schday=$row[0];
	$totaljumlah=$totaljumlah+$schday;
	
	
	$sql="select count(*) from stuatt where stu_uid='$stu_uid' and sta=0 and year='$yn' and d like '$yn-$mn%'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_row($res);
	$absence=$row[0];
	$totalabsence=$totalabsence+$absence;
	
	$sql="select count(*) from stuatt where stu_uid='$stu_uid' and att_in>'$clockin' and year='$yn' and d like '$yn-$mn%'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_row($res);
	$late=$row[0];
	$totallate=$totallate+$late;
}

}
$lewat=$totallate;
$tidakhadir=$totalabsence;
$jumlah=$totaljumlah;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>	

<script language="javascript">
function process_letter(){
	if(document.myform.letterid.value==''){
		alert('Please select template first');
		document.myform.letterid.focus();
		return;
	}
	document.myform.target="newwindow";
	document.myform.action='letter.php';
    newwin = window.open("","newwindow","HEIGHT=600,WIDTH=1000,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar");
	var a = window.setTimeout("document.myform.submit();",500);
    newwin.focus();
}
</script>

</head>
<body>
<div id="content">
<div id="mypanel" class="printhidden">
	<div id="mymenu" align="center">
		<a href="#" onClick="hide('letter','');window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="javascript: href='att_stu_rep.php?sid=<?php echo $sid;?>&uid=<?php echo $uid;?>&sid=<?php echo $sid;?>'" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="showhide('letter','');" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/mail22.png"><br><?php echo $lg_letter;?></a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="window.close();top.$.fancybox.close();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
	</div>
	<div align="right"><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?><br></div>
</div>

<div id="story">

<form name="myform" method="post" action="" enctype="multipart/form-data">
	<input type="hidden" name="id" value="<?php echo $id;?>">
	<input type="hidden" name="uid" value="<?php echo $uid;?>">
	<input type="hidden" name="op" value="">
	<input type="hidden" name="p" value="disreg">
	<input type="hidden" name="class_name" value="<?php echo $clsname ?>">
	<input type="hidden" name="year" value="<?php echo $year ?>">
	<input type="hidden" name="absence" value="<?php echo $tidakhadir ?>">


<!--  letter -->
<div id="letter" class="printhidden" style="display:none">
<div id="mytitlebg">LETTER SETTING</div>
<div id="mytitlebg">    
	<select name="letterid">
		<?php
					echo "<option value=\"\">- Select Letter Template -</option>";
					$sql="select * from letter where type='attendance_letter' order by id";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
								$lid=$row['id'];
								$lname=$row['name'];
								echo "<option value=\"$lid\">$lname</option>";
					}	  
		?>	
		</select>
		<?php if(is_verify("ADMIN|AKADEMIK")){?>
        	&lt;<a href="../adm/letter_config.php?sid=<?php echo $sid;?>&type=attendance_letter"  target="_blank" title="Config Letter">
        	 Edit Template
			</a>&gt;
	<?php } ?>
    <br>
    <select name="warning_message">
	<?php
					echo "<option value=\"\">- Select Letter Message  -</option>";
					$sql="select * from type where grp='letter_warning'";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
								$prm=$row['prm'];
								echo "<option value=\"$prm\">$prm</option>";
					}	  
	?>	
	</select>
		<?php if(is_verify("ADMIN|AKADEMIK")){?>
		&lt;<a href="#" onclick="newwindow('../adm/prm.php?grp=letter_warning',0)">
        	 Edit Message
		</a>&gt;
		
		<?php } ?>
		<br>
	<label style="cursor:pointer">
    <input type="checkbox" name="ismel" value="1"> 
	Do you want this letter send as an email??
    
    </label>
    <br>
	
<br>
    <input type="button" value="Generate This Letter" style="width:200px; font-size:18px; color:#00F;" onClick="process_letter();">
<br><br>
</div>
</div>
<!-- letter -->

<div id="mytitlebg"><?php echo $lg_student_attendance_report;?></div>
<table width="100%" id="mytabletitle">
  <tr>
  	<td width="75" align="center" id="myborderfull">
		 <?php if($file!=""){?>
		<img name="picture" src="<?php if($file!="") echo "$dir_image_student$file"; ?>" width="75" height="80">
		<?php }else echo "&nbsp;"; ?>
	</td>
    <td valign="top" width="60%">	
	<table width="100%" >
      <tr>
        <td width="102" ><?php echo $lg_name;?></td>
        <td width="2">:</td>
        <td >&nbsp;<?php echo "$name";?></td>
      </tr>
      <tr>
        <td><?php echo $lg_matric;?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$uid";?> </td>
      </tr>
      <tr>
        <td><?php echo $lg_ic;?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$ic";?> </td>
      </tr>
	  <tr>
        <td ><?php echo $lg_school;?></td>
        <td >:</td>
        <td >&nbsp;<?php echo "$sname";?></td>
      </tr>
	  <tr>
        <td><?php echo $lg_class;?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$clsname / $year";?></td>
      </tr>
    </table>


	</td>
    <td valign="top">
	
	<table width="100%">
      <tr>
        <td width="60%"><?php echo $lg_total_school_day;?></td>
        <td width="1%">:</td>
        <td >&nbsp;<?php echo "$jumlah";?></td>
      </tr>
	  <tr>
        <td width="40%"><?php echo $lg_total_absence;?></td>
        <td>:</td>
        <td >&nbsp;<?php echo "$tidakhadir";?></td>
      </tr>
	  <tr>
        <td width="40%"><?php echo 'Late';?></td>
        <td>:</td>
        <td >&nbsp;<?php echo "$lewat";?></td>
      </tr>
    </table>
 	</td>
  </tr>
</table>
<!-- 
<table width="100%" id="mycal" cellspacing="0">
      <tr>
              <td style="border-right:none;" align=center background="../img/bg_title_dark.jpg"><font size='4' face='Comic Sans MS'> <a href='../calendar/cal_year.php?year=<?php echo $year-1;?>'><img src="../img/goback.png" border="0"></a> </td>
              <td style="border-right:none;"  colspan=5 align=center background="../img/bg_title_dark.jpg"><font size='4' face='Comic Sans MS' color="#FFFFFF"><?php echo strtoupper("$year");?></td>
              <td style="border-right:none;"  align=center background="../img/bg_title_dark.jpg"><font size='4' face='Comic Sans MS'> <a href='../calendar/cal_year.php?year=<?php echo $year+1;?>'><img src="../img/gonext.png" border="0"></a> </td>
      </tr>
</table>
 -->
<table width="100%"  cellspacing="0" bgcolor="#666666">
	<tr>
<?php for($tt=1;$tt<=12;$tt++){


	$number_of_cell=0;
	$date_of_next_month=0;

$sqlmonth="select * from month where idx='$tt'";
$resmonth=mysql_query($sqlmonth)or die("$sqlmonth query failed:".mysql_error());
$rowmonth=mysql_fetch_assoc($resmonth);
$mn=$rowmonth['name'];
$monthno=$rowmonth['no'];
$idxno=$rowmonth['idx'];
if($startsemester==""){
	if($idxno==1){
		$startsemester=$monthno;
	}
}

//$endsemester=$startsemester-1;
//echo "$monthno<$startsemester && $idxno>$endsemester";
//if($monthno<$startsemester && $idxno>$endsemester){
if($monthno<$startsemester){
$yn=$year+1;
}else{
$yn=$year;
}

$no_of_days = date('t',mktime(0,0,0,$monthno,1,$yn)); // This is to calculate number of days in a month
$no_of_days_last_month = date('t',mktime(0,0,0,$monthno-1,1,$yn)); // This is to calculate number of days in a month

$mn=date('F',mktime(0,0,0,$monthno,1,$yn)); // Month is calculated to display at the top of the calendar
$month_number=date('m',mktime(0,0,0,$monthno,1,$yn)); // Month is calculated to display at the top of the calendar
$yn=date('Y',mktime(0,0,0,$monthno,1,$yn)); // Year is calculated to display at the top of the calendar

?>
		<td width="25%" valign="top">
		
			<table width="100%" cellspacing="0">
            <tr>
              
              <td colspan=7 align=center background="../img/bg_title_dark.jpg"><font size='4' face='Comic Sans MS' color="#FFFFFF"><?php echo "$mn $yn";?></td>
              
        </tr>
            <tr>
              <td style="border-right:none; border-top:none;" align=center height="30px" width="14%" background="../img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="2">Sun</font></b></td>
              <td style="border-right:none; border-top:none;" align=center width="14%" background="../img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="2">Mon</font></b></td>
              <td style="border-right:none; border-top:none;" align=center width="14%" background="../img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="2">Tue</font></b></td>
              <td style="border-right:none; border-top:none;" align=center width="14%" background="../img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="2">Wed</font></b></td>
              <td style="border-right:none; border-top:none;" align=center width="14%" background="../img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="2">Thu</font></b></td>
              <td style="border-right:none; border-top:none;" align=center width="14%" background="../img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="2">Fri</font></b></td>
              <td style=" border-top:none;" align=center width="14%" background="../img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="2">Sat</font></b></td>
        </tr><tr>
<?php
        $j= date('w',mktime(0,0,0,$monthno,1,$yn)); // This will calculate the week day of the first day of the month



for($k=1; $k<=$j; $k++){ // Adjustment of date starting
	$last_month_date=$no_of_days_last_month-($j-$k);
	$adj .="<td valign=top height='20px' id=myborder width='20px' bgcolor=#EEEEEE><font size='1' face=\"Comic Sans MS\"  color=#999999>$last_month_date</font></td>";
	$number_of_cell++;
}

$currentdate=date('Y-m-d');
$sysm=date('m',mktime(0,0,0,$monthno,1,$yn)); // Month is calculated to display at the top of the calendar
        for($i=1;$i<=$no_of_days;$i++){
            echo "$adj";
            $thisdate=sprintf("$yn-$sysm-%02d",$i);
            $dt=date('Y-m-d',mktime(0,0,0,$monthno,$i,$yn)); // get date 2008-12-31 
            $sql="select * from ses_cal where d='$dt'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sta=$row['sta'];
            if($sta=="") $bg="bgcolor=$bgkuning"; //sekolah - putih
            if($sta=="0") $bg="bgcolor=$bgkuning"; //sekolah - hijau
            if($sta=="1") $bg="bgcolor=$bgbiru"; //cuti biasa - biru
            if($sta=="2") $bg="bgcolor=$bgpink"; //cuti penggal - kuning
            if($sta=="3") $bg="bgcolor=$bgoren"; //public holiday - pink
			
				$xdes="";
				$sql="select * from stuatt where d='$dt' and stu_uid='$uid'";
				$res2=mysql_query($sql)or die("query failed:".mysql_error());
				$row2=mysql_fetch_assoc($res2);
				$sta=$row2['sta'];
				$des=$row2['des'];

				if($sta=="0"){ 
					$bg="bgcolor=$bgmerah"; //sekolah - red
					if($des!="")
						$xdes=" <a href=\"#\" title=\"$des\"><font color=#FFFFFF><strong>??</strong></font></a>";
				}
			    if($sta=="1")
					$bg="bgcolor=$bglgreen"; //sekolah - red      
          
			$todaymarkl="";
			$todaymarkr="";
			if($thisdate==$currentdate){
				$todaymarkl="<strong>&bull;";//"&lsaquo;";
				$todaymarkr="</strong>";
			}
			if($rdate>$dt){$bg="#c0c0c0";}
        ?>

                    <td id="myborder" valign=top height='20px'  width='20px'<?php echo "$bg";?> >
                          
						  <font size='1' face="Comic Sans MS" color="#0033FF">
						  <?php echo "$todaymarkl$i$todaymarkr$xdes";?> 
						  </font>
        			</td><!-- This will display the date inside the calendar cell  --> 
        <?php
                $adj='';
                $j++;
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
                echo "<td valign=top height='20px' id=myborder width='20px' bgcolor=#EEEEEE><font size='1' face=\"Comic Sans MS\"  color=#999999>$date_of_next_month</font></td>";
            }
        }
		while ($number_of_cell<42){ // Adjustment cell to be balance
                if($j==7){
                    echo "</tr><tr>";
                    $j=0;
                }
				$date_of_next_month++;
				echo "<td valign=top height='20px'  width='20px' bgcolor=#EEEEEE id=myborder><font size='1' face=\"Comic Sans MS\" color=#999999>$date_of_next_month</font></td>";
				$j++;
				$number_of_cell++;
        }
        echo "</table>";
        ?>
		</td>
<?php 
	if($tt%4==0)
		echo "	</tr><tr>";

} ?>

	
</table>

<div align="right">
      <table width="50%" border="0" bgcolor="#666666">
        <tr>
          <td bgcolor="<?php echo $bgkuning;?>" align="center"><?php echo $lg_school_day;?></td> <!-- putih -->
          <td bgcolor="<?php echo $bgbiru;?>" align="center"><?php echo $lg_weekend;?></td> <!-- kuning -->
          <td bgcolor="<?php echo $bgpink;?>" align="center"><?php echo $lg_semester_break;?></td> <!-- orange -->
          <td bgcolor="<?php echo $bgoren;?>" align="center"><?php echo $lg_public_holiday;?></td> <!-- pink -->
		  <td bgcolor="<?php echo $bghijau;?>" align="center"><?php echo $lg_attend;?></td> <!-- hijau -->
		  <td bgcolor="<?php echo $bgmerah;?>" align="center"><?php echo $lg_absence;?></td> <!-- red -->
        </tr>
      </table>
</div>

<div>



</div>
</form>
</div>
</div>
</body>
</html>