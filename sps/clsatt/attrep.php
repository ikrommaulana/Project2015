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
	$file=$row['file'];
		
	$sid=$_REQUEST['sid'];
	$sql="select * from sch where id='$sid'";
    $res=mysql_query($sql)or die("query failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
    $sname=$row['name'];
	$slevel=$row['level'];
    mysql_free_result($res);
	
	$cls=$_REQUEST['cls'];
	if($cls!=""){
			$sqlclscode="and ses_stu.cls_code='$cls'";
			$sql="select * from cls where sch_id=$sid and code='$cls'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=stripslashes($row['name']);
	}
	$sub=$_REQUEST['sub'];
	if($sub!=""){
			$sql="select * from sub where sch_id=$sid and code='$sub'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $subname=stripslashes($row['name']);
	}	
	
	$year=$_REQUEST['year'];
	if($year=="")
			$year=date('Y');
			
				
	$sql="select * from ses_stu where stu_uid='$uid' and year='$year'";
	$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$clsname=$row['cls_name'];
	$clslevel=$row['cls_level'];
	
	$sql="select count(*) from ses_cal where year='$year' and sta=0";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_row($res);
	$jumlah=$row[0];
	
	$sql="select count(*) from attcls where stu='$uid' and sta=0 and year='$year' and cls='$cls' and sub='$sub'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_row($res);
	$tidakhadir=$row[0];
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>
<body>
<div id="content">
<div id="mypanel" class="printhidden">
	<div id="mymenu" align="center">
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
		<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
	</div>
	<div align="right"><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?><br></div>
</div>

<div id="story">

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
        <td width="20%" ><?php echo $lg_name;?></td>
        <td width="1">:</td>
        <td width="79%">&nbsp;<?php echo "$name";?></td>
      </tr>
      <tr>
        <td><?php echo $lg_matric;?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$uid";?> </td>
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
        <td width="20%"><?php echo $lg_class;?></td>
        <td width="1%">:</td>
        <td width="79%">&nbsp;<?php echo "$clsname";?></td>
      </tr>
      <tr>
        <td><?php echo $lg_subject;?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$subname";?></td>
      </tr>
	  <tr>
        <td><?php echo $lg_absence;?></td>
        <td>:</td>
        <td >&nbsp;<?php echo "$tidakhadir";?></td>
      </tr>
    </table>
 	</td>
  </tr>
</table>
<table width="100%"  cellspacing="0" bgcolor="#666666">
	<tr>
<?php for($tt=1;$tt<=12;$tt++){

	$number_of_cell=0;
	$date_of_next_month=0;

$no_of_days = date('t',mktime(0,0,0,$tt,1,$year)); // This is to calculate number of days in a month
$no_of_days_last_month = date('t',mktime(0,0,0,$tt-1,1,$year)); // This is to calculate number of days in a month

$mn=date('F',mktime(0,0,0,$tt,1,$year)); // Month is calculated to display at the top of the calendar
$month_number=date('m',mktime(0,0,0,$tt,1,$year)); // Month is calculated to display at the top of the calendar
$yn=date('Y',mktime(0,0,0,$tt,1,$year)); // Year is calculated to display at the top of the calendar

?>
		<td width="25%" valign="top">
		
			<table width="100%" cellspacing="0">
            <tr>
              
              <td colspan=7 align=center background="../img/bg_title_dark.jpg"><font size='4' face='Comic Sans MS' color="#FFFFFF"><?php echo "$mn";?></td>
              
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
        $j= date('w',mktime(0,0,0,$tt,1,$year)); // This will calculate the week day of the first day of the month



for($k=1; $k<=$j; $k++){ // Adjustment of date starting
	$last_month_date=$no_of_days_last_month-($j-$k);
	$adj .="<td valign=top height='20px' id=myborder width='20px' bgcolor=#EEEEEE><font size='1' face=\"Comic Sans MS\"  color=#999999>$last_month_date</font></td>";
	$number_of_cell++;
}

$currentdate=date('Y-m-d');
$sysm=date('m',mktime(0,0,0,$tt,1,$year)); // Month is calculated to display at the top of the calendar
        for($i=1;$i<=$no_of_days;$i++){
            echo "$adj";
            $thisdate=sprintf("$yn-$sysm-%02d",$i);
            $dt=date('Y-m-d',mktime(0,0,0,$tt,$i,$year)); // get date 2008-12-31 
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
				$sql="select * from attcls where dt='$dt' and stu='$uid' and cls='$cls' and sub='$sub'";
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


      <table width="100%" border="0" bgcolor="#666666">
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
</div>
</body>
</html>