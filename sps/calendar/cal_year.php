<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN');

$sid=$_REQUEST['sid'];
if($sid=="")
	$sid=$_SESSION['sid'];

if($sid!=0){
		$sql="select * from sch where id='$sid'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=$row['name'];
		$sname=stripslashes($sname);
}
$year=$_REQUEST['year'];
if($year=="")
	$year=date(Y);
	


$taqwim=$_REQUEST['taqwim'];
$op=$_REQUEST['op'];

if (($taqwim!="")) {
				$cal=explode("|",$taqwim);
				for ($i=0; $i<count($cal); $i++) {
					$dt=$cal[$i];
					list($yearadd,$monthadd,$dateadd)=explode("-",$dt);
					$sql="delete from ses_cal where year='$yearadd' and d='$dt' and sch_id=$sid";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					if($op>0){
						$sql="insert into ses_cal(year,d,sta,sch_id) values ('$yearadd','$dt',$op,$sid)";
						$res=mysql_query($sql)or die("query failed:".mysql_error());
					}
				}
}
		
//echo "X:".$taqwim;


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" type="text/css" href="<?php echo $MYOBJ;?>/popwin/sample.css" />
<script type="text/javascript" src="<?php echo $MYOBJ;?>/popwin/popup-window.js"></script>

<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<script language="JavaScript">
function myin(id,sta){
		/**id.style.backgroundColor='#66FF66';**/
		id.style.cursor='pointer';
		id.style.border='1px solid #333';
		
}
function myout(id,sta){
		/**id.style.backgroundColor='';**/
		id.style.cursor='default';
		id.style.border='1px solid #9CF';
}
function setcolor(id,sta,oricolor,dt){
	var myString;
	if(id.style.backgroundColor=='rgb(204, 204, 204)'){
		myString=document.myform.taqwim.value;
		myArray = myString.split("|");
		document.myform.taqwim.value='';
		for(i=0;i<myArray.length;i++){
			if(myArray[i]!=dt){
				if(document.myform.taqwim.value!='')
					document.myform.taqwim.value=document.myform.taqwim.value+'|';
				document.myform.taqwim.value=document.myform.taqwim.value+myArray[i];
			}
		}
		//alert(document.myform.taqwim.value);
		id.style.backgroundColor=oricolor;
		
	}else{
		id.style.backgroundColor='#CCCCCC';
		if(document.myform.taqwim.value!='')
					document.myform.taqwim.value=document.myform.taqwim.value+'|';
		document.myform.taqwim.value=document.myform.taqwim.value+dt;
	}
}
</script>

<title>Calendar</title></head>
<body>

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" >
    <input type="hidden" name="year" value="<?php echo $year;?>">
	<input type="hidden" name="taqwim">
	<input type="hidden" name="op">

<table width="100%" cellspacing="0">
      <tr>
              <td align=center background="<?php echo $MYLIB;?>/img/bg_title.jpg">
                    <font size='4' face='Comic Sans MS'>
                    <a href='../calendar/cal_year.php?<?php echo "sid=$sid";?>&year=<?php echo $year-1;?>'>
                    <img src="<?php echo $MYLIB;?>/img/goback.png" border="0"></a>
              </td>
              <td align=center background="<?php echo $MYLIB;?>/img/bg_title.jpg">
                <a href="#" onClick="popup_show('popup', 'popup_drag', 'popup_exit', 'screen-center',0,0);">
              		<font size='4' face='Comic Sans MS' color="#FFFFFF"><?php echo "Calendar $sname";?>
                    <img src="<?php echo $MYLIB;?>/img/edit16.png"></font>
                </a>

                
              </td>
              <td align=center background="<?php echo $MYLIB;?>/img/bg_title.jpg">
                    <font size='4' face='Comic Sans MS'>
                    <a href='../calendar/cal_year.php?<?php echo "sid=$sid";?>&year=<?php echo $year+1;?>'>
                    <img src="<?php echo $MYLIB;?>/img/gonext.png" border="0"></a>
              </td>
      </tr>
</table>
<table width="100%" cellspacing="0" cellpadding="0" style="font-size:10px">
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
if($monthno<7 && $idxno>6){
$yn=$year+1;
}else{
$yn=$year;
}

$no_of_days = date('t',mktime(0,0,0,$monthno,1,$year)); // This is to calculate number of days in a month
$no_of_days_last_month = date('t',mktime(0,0,0,$$monthno-1,1,$year)); // This is to calculate number of days in a month

$month_number=date('m',mktime(0,0,0,$tt,1,$year)); // Month is calculated to display at the top of the calendar
//$yn=date('Y',mktime(0,0,0,$tt,1,$year)); // Year is calculated to display at the top of the calendar

?>
		<td width="25%" valign="top" id="myborder" style="border-left:none; border-color:#96C;">
		
			<table width="100%" cellspacing="0">
            <tr>
              
              	<td colspan=7 height="20px" align=center background="<?php echo $MYLIB;?>/img/bg_title_dark2.jpg">
              		<font size='2' face='Comic Sans MS' color="#FFFFFF"><?php echo "$mn $yn";?>
            	</td>
              
        	</tr>
            <tr>
              <td align=center height="20px" width="14%" background="<?php echo $MYLIB;?>/img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="2">Ahad</font></b></td>
              <td align=center width="14%" background="<?php echo $MYLIB;?>/img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="2">Senin</font></b></td>
              <td align=center width="14%" background="<?php echo $MYLIB;?>/img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="2">Sel</font></b></td>
              <td align=center width="14%" background="<?php echo $MYLIB;?>/img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="2">Rab</font></b></td>
              <td align=center width="14%" background="<?php echo $MYLIB;?>/img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="2">Kamis</font></b></td>
              <td align=center width="14%" background="<?php echo $MYLIB;?>/img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="2">Jum</font></b></td>
              <td align=center width="14%" background="<?php echo $MYLIB;?>/img/bg_title_lite.jpg"><b><font face="Comic Sans MS" size="2">Sab</font></b></td>
        </tr><tr>
<?php
        //$j= date('w',mktime(0,0,0,$tt,1,$year)); // This will calculate the week day of the first day of the month
		$j= date('w',mktime(0,0,0,$monthno,1,$yn)); // This will calculate the week day of the first day of the month



for($k=1; $k<=$j; $k++){ // Adjustment of date starting
	$last_month_date=$no_of_days_last_month-($j-$k);
	$adj .="<td valign=top height='20px' id=myborder width='20px' bgcolor=#EEEEEE style=\"border-color:#9CF;color:#BBB\">$last_month_date</td>";
	$number_of_cell++;
}

$currentdate=date('Y-m-d');
$sysm=date('m',mktime(0,0,0,$$monthno,1,$yn)); // Month is calculated to display at the top of the calendar
        for($i=1;$i<=$no_of_days;$i++){
            echo "$adj";
            $thisdate=sprintf("$yn-$sysm-%02d",$i);
            $dt=date('Y-m-d',mktime(0,0,0,$monthno,$i,$yn)); // get date 2008-12-31 
            $sql="select * from ses_cal where d='$dt' and sch_id=$sid";
		
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sta=$row['sta'];
			if($sta=="")  $bg="bgcolor=$bgkuning";	//unset-kira sekolah
			if($sta=="0") $bg="bgcolor=$bgkuning"; 	//sekolah 
			if($sta=="1") $bg="bgcolor=$bgbiru"; 	//cuti biasa - biru
			if($sta=="2") $bg="bgcolor=$bgpink"; 	//cuti penggal - pink
			if($sta=="3") $bg="bgcolor=$bgoren"; 	//public holiday - oren
			if($sta=="4") $bg="bgcolor=$bgkelabu"; 	//special holiday - kelabu
			            
            $evt="";
            $des="";
 
            $sql="select * from calendar_event where dt='$dt' and sid='$sid'";
            $res=mysql_query($sql)or die($sql.mysql_error());
			$evt=mysql_num_rows($res);
			if($evt=="0"){
				$evt="";
			}
			else{
				//$bg="bgcolor=$bghijau";
				$evt="<b><font color=black>*</font></b>";
			}
			$todaymarkl="";
			$todaymarkr="";
			if($thisdate==$currentdate){
				$bg="bgcolor=#000";				
			}
        ?>

                    <td id="myborder" style="border-color:#9CF;" valign=top height='20px'  width='20px' <?php echo "$bg";?>  
                    	onMouseOver="myin(this,1)" onMouseOut="myout(this,1)" onClick="setcolor(this,1,'<?php echo $bgcolor;?>','<?php echo $dt;?>');">
						  <?php echo "$todaymarkl$i$todaymarkr$evt";?>
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
                echo "<td valign=top height='20px' id=myborder style=\"border-color:#9CF;color:#BBB;\" width='20px' bgcolor=#EEEEEE>$date_of_next_month</td>";
            }
        }
		while ($number_of_cell<42){ // Adjustment cell to be balance
                if($j==7){
                    echo "</tr><tr>";
                    $j=0;
                }
				$date_of_next_month++;
				echo "<td valign=top height='20px'  width='20px' bgcolor=#EEEEEE id=myborder style=\"border-color:#9CF;color:#BBB;\">$date_of_next_month</td>";
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


<!-- ***** Popup Window **************************************************** -->

<div class="sample_popup" id="popup" style="display: none;">
        <div class="menu_form_header" id="popup_drag">
                <img class="menu_form_exit"  id="popup_exit" src="<?php echo $MYOBJ;?>/popwin/close.gif" alt="" />
                &nbsp;SETTING
        </div>
        <div class="menu_form_body">
<select name="sid" onChange="document.myform.submit();">
<?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select -</option>";
			else
                echo "<option value=$sid>$sname</option>";
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['sname'];
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
			}					  
?>
</select>    
<input type="button" value="View" onClick="document.myform.submit();">
<br><br>
<font color="#FAFAFA">
Tick date then clik event button.<br>
</font>
    
                <input type="button" onClick="document.myform.op.value=0;document.myform.submit()" value="Hari Efektif" style="background-color:#FFFF00; min-width:200px;">
				<input type="button" onClick="document.myform.op.value=1;document.myform.submit()" value="Akhir Pekan" style="background-color:#6699FF; min-width:200px;">
				<input type="button" onClick="document.myform.op.value=2;document.myform.submit()" value="Libur Semester" style="background-color:#FF99CC; min-width:200px;">
				<input type="button" onClick="document.myform.op.value=3;document.myform.submit()" value="Libur Nasional"  	style="background-color:#FFCC99; min-width:200px;">
                <input type="button" onClick="document.myform.op.value=4;document.myform.submit()" value="Libur Khusus"  style="background-color:#999; min-width:200px;">
        </div>
</div>

</body>
</html>