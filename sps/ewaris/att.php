<?php
include_once('../etc/db.php');
include_once('session.php');
verify();

$sql="select * from type where grp='openexam' and prm='EKEDATANGAN'";
$res=mysql_query($sql)or die("query failed:".mysql_error());
$row=mysql_fetch_assoc($res);
$sta=$row['val'];
if($sta!="1")
	echo "<script language=\"javascript\">location.href='p.php?p=close'</script>";
	
	$uid=$_SESSION['uid'];
	$sql="select * from stu where uid='$uid'";
		$res=mysql_query($sql) or die(mysql_error());
		$row=mysql_fetch_assoc($res);
		$name=$row['name'];
		$stu_uid=$row['uid'];
		$ic=$row['ic'];
		$sch_id=$row['sch_id'];
		
	$sql="select * from sch where id='$sch_id'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=$row['name'];
		$slevel=$row['level'];
        mysql_free_result($res);
	
	$clockout=$_REQUEST['clockout'];
	
	$year=$_REQUEST['year'];
	if($year=="")
			$year=date('Y');
			

	$sql="select count(*) from stuatt where stu_uid='$uid' and year=$year and sta=0";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_row($res);
	$jumlah=$row[0];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>	

</head>
<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" >




    <input type="hidden" name="year" value="<?php echo $year;?>">
	<input type="hidden" name="taqwim">
	<input type="hidden" name="op">
	<input type="hidden" name="p" value="<?php echo $p;?>">


<div id="panelleft">
	<?php include('inc/lmenu.php');?>
</div>
<div id="content2"> 
<div id="masthead_title" style="border-right:none; border-top:none;" >
		<?php echo strtoupper($name);?>
</div>
<div style="font-size:11px; font-weight:bold; color:#333333; border-bottom:2px solid #666;"></div>
<strong><?php echo $lg_clockout; ?></strong> <input type="checkbox" name="clockout" value="1" onclick="document.myform.submit();" <?php if(isset($_REQUEST['clockout'])){ echo "checked"; }?>>


<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
<?php
$tgl=date("d-m-Y");
$pecah=explode("-",$tgl);
$bln=$pecah[1];
$thn=$pecah[2];
for($tt=1;$tt<=12;$tt++){

	$sqlmonth="select * from month where idx='$tt'";
	$resmonth=mysql_query($sqlmonth)or die("$sqlmonth query failed:".mysql_error());
	$rowmonth=mysql_fetch_assoc($resmonth);
	$mn=$rowmonth['name'];
	$monthno=$rowmonth['no'];
	if($bln>=07){
		if($tt<=7) {
			$yn=$year;
		}else{
			$yn=$year+1;
		}
	}
	else {
		if($tt<=7) {
			$yn=$year-1;
		}else{
			$yn=$year;
		}
	}
	
	$number_of_cell=0;
	$date_of_next_month=0;

	$no_of_days = date('t',mktime(0,0,0,$$monthno,1,$yn)); // This is to calculate number of days in a month
	$no_of_days_last_month = date('t',mktime(0,0,0,$monthno-1,1,$yn)); // This is to calculate number of days in a month
	
	//$mn=date('F',mktime(0,0,0,$tt,1,$yn)); // Month is calculated to display at the top of the calendar
	$month_number=date('m',mktime(0,0,0,$monthno,1,$yn)); // Month is calculated to display at the top of the calendar
	$yn=date('Y',mktime(0,0,0,$monthno,1,$yn)); // Year is calculated to display at the top of the calendar

	

?>
		<td width="25%" valign="top" style="border:1px #99BBFF solid;<?php echo $borderright;?><?php echo $borderbottom;?>">
		
			<table width="100%" cellspacing="0">
            <tr>
              
				  <td colspan=7 align=center background="../img/bg_title_dark.jpg"><b><font face='Comic Sans MS' size='2'><?php echo "$mn $yn";?></b></td>
              
        </tr>
            <tr>
              <td style="border-right:none; border-top:none;" align=center width="14%" background="../img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="2">Ahad</font></b></td>
              <td style="border-right:none; border-top:none;" align=center width="14%" background="../img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="2">Senin</font></b></td>
              <td style="border-right:none; border-top:none;" align=center width="14%" background="../img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="2">Sel</font></b></td>
              <td style="border-right:none; border-top:none;" align=center width="14%" background="../img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="2">Rab</font></b></td>
              <td style="border-right:none; border-top:none;" align=center width="14%" background="../img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="2">Kamis</font></b></td>
              <td style="border-right:none; border-top:none;" align=center width="14%" background="../img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="2">Jum</font></b></td>
              <td style=" border-top:none;" align=center width="14%" background="../img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="2">Sab</font></b></td>
        </tr><tr>
<?php
        $j= date('w',mktime(0,0,0,$monthno,1,$yn)); // This will calculate the week day of the first day of the month



for($k=1; $k<=$j; $k++){ // Adjustment of date starting
	$last_month_date=$no_of_days_last_month-($j-$k);
	$adj .="<td valign=top height='20px' id=myborder width='20px' bgcolor=#EEEEEE><font size='1' face=\"Comic Sans MS\"color=#BBBBBB>$last_month_date</font></td>";
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
			            
            $evt="";
            $des="";
 
 				$sql="select * from stuatt where d='$dt' and stu_uid='$uid'";
				$res2=mysql_query($sql)or die("query failed:".mysql_error());
				$row2=mysql_fetch_assoc($res2);
				$sta=$row2['sta'];
				$des=$row2['des'];
				if($clockout=='1')
					$xtime=$row2['att_out'];
				else
					$xtime=$row2['att_in'];
					
				if($sta=="0"){ 
					$bg="bgcolor=$bgmerah"; //sekolah - red
					$evt="<a href=\"#\" title=\"$des\"><font color=#FFFFFF><strong>??</strong></font></a>";
				}
				if($sta=="1") { 
					$bg="bgcolor=$bghijau"; //sekolah - red
					$evt="<a href=\"#\" title=\"Hadir\"><font color=#FFFFFF><strong>$xtime</strong></font></a>";
				}
			$todaymarkl="";
			$todaymarkr="";
			if($thisdate==$currentdate){
				//$bg="bgcolor=#339999";
				$todaymarkl="(";
				$todaymarkr=")";
			}
        ?>

                    <td id="myborder" valign=top height='20px'  width='20px'<?php echo "$bg";?> >
                          
						  <font size='1' face="Comic Sans MS" color="#0033FF">
						  <?php echo "$todaymarkl$i$todaymarkr$evt";?>
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
                echo "<td valign=top height='20px' id=myborder width='20px' bgcolor=#EEEEEE><font size='1' face=\"Comic Sans MS\" color=#BBBBBB>$date_of_next_month</font></td>";
            }
        }
		while ($number_of_cell<42){ // Adjustment cell to be balance
                if($j==7){
                    echo "</tr><tr>";
                    $j=0;
                }
				$date_of_next_month++;
				echo "<td valign=top height='20px'  width='20px' bgcolor=#EEEEEE id=myborder><font size='1' face=\"Comic Sans MS\" color=#BBBBBB>$date_of_next_month</font></td>";
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
</div>
</body>
</html>