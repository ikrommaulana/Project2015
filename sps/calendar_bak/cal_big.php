<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
	$adm = $_SESSION['username'];
	
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
	
		
	$xdate=$_REQUEST['xdate'];
	if($xdate==""){
		$xdate=date(Y."-".m."-".d);
		
	}
	$prm=$_REQUEST['prm'];
	$chm=$_REQUEST['chm'];
	$day=$_REQUEST['day'];
	$tar=$_REQUEST['tar'];
	$dis=$_REQUEST['dis'];

	if($dis)
		$display="block";
	else
		$display="none";
	
	if(isset($prm) and $prm > 0){
		$month=$prm+$chm;
	}else{
		$month= date("m");
	}
	$savedate=$_REQUEST['savedate'];
	$eventname=$_REQUEST['eventname'];
	$eventdes=$_REQUEST['eventdes'];
	$stime=$_REQUEST['stime'];
	if($stime!=""){
		$sql="select * from time where time='$stime'";
     	$res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $start=$row['val'];
	}
	$opx=$_REQUEST['opx'];
	$evtnote=addslashes($_REQUEST['evtnote']);
	$evtdate=$_REQUEST['evtdate'];
	$isreminder=$_REQUEST['isreminder'];
	$isprivate=$_REQUEST['isprivate'];
	$xid=$_REQUEST['xid'];
	if($opx=='save'){
		$sql="insert into calendar_event(dt,event,isprivate,isreminder,adm,ts,sid)values('$evtdate','$evtnote','$isprivate','$isreminder','$adm',now(),$sid)";
		mysql_query($sql);
		$stime="";
		$f="Updated";
	}
	if($opx=='delete'){
		$sql="delete from calendar_event where id=$xid";
		mysql_query($sql);
		$stime="";
		$f="Deleted";
	}
//new color set
$bghijau	="#00FF99"; //hadir
$bgkuning	="#FFFF99"; //cuti minggu
$bgmerah	="#FF9999"; //tak hadir
$bgbiru		="#99CCFF"; //cuti sem
$bgkelabu	="#999999"; //tak taksir
$bgpink		="#FFCCFF"; //cuti umum
$bgputih	="#FFFFFF"; //belum set

$d=date("d");     // Finds today's date
$year=date("Y");     // Finds today's year

$no_of_days = date('t',mktime(0,0,0,$month,1,$year)); // This is to calculate number of days in a month
$no_of_days_last_month = date('t',mktime(0,0,0,$month-1,1,$year)); // This is to calculate number of days in a month

$mn=date('F',mktime(0,0,0,$month,1,$year)); // Month is calculated to display at the top of the calendar

$yn=date('Y',mktime(0,0,0,$month,1,$year)); // Year is calculated to display at the top of the calendar

$j= date('w',mktime(0,0,0,$month,1,$year)); // This will calculate the week day of the first day of the month

	list($evt_y,$evt_m,$evt_d)=explode("-",$xdate);
	$event_day_name=date("l", mktime(0, 0, 0, $evt_m, $evt_d, $evt_y));


for($k=1; $k<=$j; $k++){ // Adjustment of date starting
	$last_month_date=$no_of_days_last_month-($j-$k);
	$adj .="<td  id=myborder  style=\"border-right:none;border-top:none;\" valign=top height='78px' width='60px' bgcolor=#DDDDDD><font size='2' face=\"Comic Sans MS\">$last_month_date</font></td>";
	$number_of_cell++;
}

//apai
//apai get today

$currentdate=date('Y-m-d');
//echo "Current:$currentdate<br>";
$sysm=date('m',mktime(0,0,0,$month,1,$year)); // Month is calculated to display at the top of the calendar
//echo "System:$yn-$sysm<br>";
//$day=date('d',mktime(0,0,0,$month,$day,$year));
//echo "User:$yn-$sysm-$day<br>";

/// Starting of top line showing name of the days of the week
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" type="text/css" href="<?php echo $MYOBJ;?>/popwin/sample.css" />
<script type="text/javascript" src="<?php echo $MYOBJ;?>/popwin/popup-window.js"></script>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript">
function process_save(op)
{
			document.myform.opx.value=op;
			document.myform.submit();
}
function process_delete(op)
{
		ret = confirm("Delete this event??");
		if (ret == true){
			document.myform.opx.value=op;
			document.myform.submit();
		}
}
function myin(id,sta){
		id.style.backgroundColor='#999999';
		id.style.color='#FFFFFF';
		id.style.cursor='pointer';
}
function myout(id,sta){
		id.style.backgroundColor='';
		id.style.cursor='default';
		id.style.color='#000000';
}

</script>
<title>Program Calendar</title>
</head>
<body>

<form name="myform">
    <input type="hidden" name="savedate"  value="<?php echo $xdate;?>">
	<input type="hidden" name="month" value="<?php echo $m;?>">
    <input type="hidden" name="year" value="<?php echo $yn;?>">
    <input type="hidden" name="target" value="<?php echo $tar;?>">
	<input type="hidden" name="prm" value="<?php echo $month;?>">
	<input type="hidden" name="xdate" value="<?php echo $xdate;?>">
	<input type="hidden" name="opx">
	<input type="hidden" name="xid">


<table width="100%" cellspacing="0">
		<tr>
              <td align=center background="<?php echo $MYLIB;?>/img/bg_title_dark.jpg">
                    <font size='3' face='Comic Sans MS'>
                    <a href='../calendar/cal_big.php?xdate=<?php echo $xdate; ?>&prm=<?php echo "$month";?>&chm=-1&tar=<?php echo "$tar";?>'>
                    <img src="<?php echo $MYLIB;?>/img/goback.png" border="0"></a>
              </td>
              <td colspan=5 align=center background="<?php echo $MYLIB;?>/img/bg_title_dark.jpg" style="font-weight:bold;">
                  <font size='4' face='Comic Sans MS'>
                  <?php echo strtoupper("$mn $yn");?></font> <a style="text-decoration:none; " href="../calendar/cal_year.php?year=<?php echo $yn;?>">
                  <br>
                  <font size='2' face='Comic Sans MS' color="#FAFAFA">(<?php echo $lg_setting;?>)</font></a>
              </td>
              <td align=center background="<?php echo $MYLIB;?>/img/bg_title_dark.jpg">
                    <font size='4' face='Comic Sans MS'>
                    <a href='../calendar/cal_big.php?xdate=<?php echo $xdate; ?>&prm=<?php echo "$month";?>&chm=1&tar=<?php echo "$tar";?>'>
                    <img src="<?php echo $MYLIB;?>/img/gonext.png" border="0"></a>
               </td>
		</tr>
		<tr>
              <td id="myborder" style="border-right:none;" align=center height="30px" width="14%" background="<?php echo $MYLIB;?>/img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="3">Sunday</font></b></td>
              <td id="myborder" style="border-right:none;" align=center width="14%" background="<?php echo $MYLIB;?>/img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="3">Monday</font></b></td>
              <td id="myborder" style="border-right:none;" align=center width="14%" background="<?php echo $MYLIB;?>/img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="3">Tuesday</font></b></td>
              <td id="myborder" style="border-right:none;" align=center width="14%" background="<?php echo $MYLIB;?>/img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="3">Wednesday</font></b></td>
              <td id="myborder" style="border-right:none;" align=center width="14%" background="<?php echo $MYLIB;?>/img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="3">Thursday</font></b></td>
              <td id="myborder" style="border-right:none;" align=center width="14%" background="<?php echo $MYLIB;?>/img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="3">Friday</font></b></td>
              <td id="myborder" style="border-right:none;" align=center width="14%" background="<?php echo $MYLIB;?>/img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="3">Saturday</font></b></td>
		</tr>
		<tr>
        <?php
        ////// End of the top line showing name of the days of the week//////////
        
        //////// Starting of the days//////////
        for($i=1;$i<=$no_of_days;$i++){
            echo "$adj";
            $thisdate=sprintf("$yn-$sysm-%02d",$i);
            $dt=date('Y-m-d',mktime(0,0,0,$month,$i,$year)); // get date 2008-12-31 
           // $sql="select * from ses_cal where d='$dt'";
		   	if($CALENDAR_GLOBAL)
				$sql="select * from ses_cal where d='$dt'";
			else
		   		$sql="select * from ses_cal where d='$dt' and sch_id=$sid";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sta=$row['sta'];
            if($sta=="") $bg=""; //sekolah - putih
            if($sta=="0") $bg=""; //sekolah - hijau
            if($sta=="1") $bg="bgcolor=$bgbiru"; //cuti biasa - kuning
            if($sta=="2") $bg="bgcolor=$bgpink"; //cuti penggal - pink
            if($sta=="3") $bg="bgcolor=$bgoren"; //public holiday - pink
			            
            $evt="";
            $des="";

			if($CALENDAR_GLOBAL)
				$sql="select * from calendar_event where dt='$dt' and (isprivate=0 or adm='$adm')";
			else
				$sql="select * from calendar_event where dt='$dt' and (sid=0 or sid=$sid) and (isprivate=0 or adm='$adm')";
		
            $res=mysql_query($sql)or die($sql.mysql_error());
			$num=mysql_num_rows($res);
			while($row=mysql_fetch_assoc($res)){
				 	$xid=stripslashes($row['id']);
				 	$e=stripslashes($row['event']);
				 	$d=stripslashes($row['des']);
					$isprivate=$row['isprivate'];
				 	$evt=$evt."&bull;$e";
					if($isprivate)
						$evt=$evt." <img src=\"$MYLIB/img/user12.png\" height=\"10\" title=\"Personal Reminder\">";
					                    
					$xuid=stripslashes($row['adm']);
					if((is_verify('ADMIN'))||($xuid==$_SESSION['username']))
				 		 $evt=$evt." <a hef=\"#\" onclick=\"document.myform.xid.value='$xid';return process_delete('delete');\"><img src=\"$MYLIB/img/close12.png\" height=\"10\" title=\"Delete\"></a><br>";
			}
			if($num=="0"){
				$evt="";
			}
			
			if($thisdate==$currentdate)
				$today="&lt;Today&gt;";
			else
				$today="";

        ?>

                    <td id=myborder style="border-right:none; border-top:none;" valign=top height='78px' <?php echo "$bg";?>
						onMouseOver="myin(this,1)" onMouseOut="myout(this,1)"
						onclick="document.myform.evtdate.value='<?php echo $dt;?>';
									document.myform.evtdays.value='<?php echo date('l', strtotime($dt))."&nbsp;&nbsp;" . $dt;?>';
									popup_show('popup', 'popup_drag', 'popup_exit', 'screen-center',0,0);
									document.myform.evtnote.focus();">
								
                          	<font size='2' face="Comic Sans MS">&nbsp;<b><?php echo "$i $today";?></b><br></font>
							<div  style=" font-family:verdana; font-size:10px;border-right:none;border-top:none; overflow:auto; min-height:60px; height:60px;">
								<?php echo $evt;?>
						 	</div>
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
                echo "<td  id=myborder style=\"border-right:none; border-top:none;\" valign=top height='78px'  width='60px' bgcolor=#DDDDDD><font size='2' face=\"Comic Sans MS\">$date_of_next_month</font></td>";
            }
        }
		while ($number_of_cell<42){ // Adjustment cell to be balance
                if($j==7){
                    echo "</tr><tr>";
                    $j=0;
                }
				$date_of_next_month++;
				echo "<td id=myborder style=\"border-right:none; border-top:none;\" valign=top height='78px'  width='60px' bgcolor=#DDDDDD><font size='2' face=\"Comic Sans MS\">$date_of_next_month</font></td>";
				$j++;
				$number_of_cell++;
        }
        ?>

</table>

<!-- ***** Popup Window **************************************************** -->

<div class="sample_popup"     id="popup" style="display: none;">

        <div class="menu_form_header" id="popup_drag">
                <img class="menu_form_exit"  id="popup_exit" src="<?php echo $MYOBJ;?>/popwin/close.gif" alt="" />
                &nbsp;Event / Notes / Reminder :
                <input type="text"   name="evtdays" size="25" style="background-color:#000000; color:#FFFFFF; border:none">
                <input type="hidden" name="evtdate" style="background-color:#000000; color:#FFFFFF; border:none ">
        </div>

        <div class="menu_form_body">
                <table>
                  <tr><td colspan="2"><textarea name="evtnote" rows="4" cols="47"></textarea></td></tr>
                  <tr><td width="70%"> 
						<label style="cursor:pointer; color:#FFF; font-size:9px">
                        	<input type="radio" value="1" name="isprivate">Personal Note
                        	<img src="<?php echo $MYLIB;?>/img/user12.png" height="10">
                        </label>
                        <label style="cursor:pointer; color:#FFF; font-size:9px">      
                        	<input type="radio" value="0" name="isprivate" checked>Staff View
                        </label>
							<br>
 						<label style="cursor:pointer; color:#FFF; font-size:9px" title="Display in reminder..">
                        	<input type="checkbox" value="1" name="isreminder">Add to Reminder
                        </label>
                                  
                        </td>
                        <td width="30%" align="right" style="padding-right:4px;">
                        	<input type="button"  onClick="process_save('save')" value="Save">
                    </td>
                  </tr>
                </table>
        </div>
</div>
</form>
</body>
</html>