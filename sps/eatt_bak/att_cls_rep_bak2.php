<?php
//22/04/2010 - update view by STUDENT
$vmod="v5.0.0";
$vdate="21/07/2010";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
	verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');
	$username=$_SESSION['username'];
	$p=$_REQUEST['p'];
	
	$year=$_REQUEST['year'];
	if($year)
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
	if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$ssname=$row['sname'];
            mysql_free_result($res);					  
	}
	$cls=$_REQUEST['cls'];
	if($cls!=""){
			$sqlcls="and ses_stu.cls_code='$cls'";
			$sql="select * from cls where sch_id=$sid and code='$cls'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=stripslashes($row['name']);
	}
	//$y=$_POST['year'];
	//if($y=="")
		$y=date('Y');
		
	$m=$_REQUEST['month'];
	if($m=="")
		$m=date('m');
		
	$chm=$_REQUEST['chm'];
	if($chm!="")
		$m=$m+$chm;

	if($m<=0){
		$y=date('Y')-1;
		$mm=$m+12;
	}elseif($m>12){
		$y=date('Y')+1;
		$mm=$m-12;
	}else{
		$mm=$m;
	}
	
	
	//echo $chm.":".$m.":".$y;
	
	$d=date("d");     // Finds today's date
		
	$nd = date('t',mktime(0,0,0,$mm,1,$y)); // This is to calculate number of days in a month
	//$mn=date('M',mktime(0,0,0,$m,1,$y)); // get JAN-DIS
	$mn=date('F',mktime(0,0,0,$mm,1,$y)); // get JANUARY-DICEMBER
	$yn=date('Y',mktime(0,0,0,$mm,1,$y)); // Year is calculated to display at the top of the calendar
	$j= date('w',mktime(0,0,0,$mm,1,$y)); // This will calculate the week day of the first day of the month

	$op=$_POST['op'];
	$dt=$_POST['dt'];
	
	$dd=strtok($dt,"-");
	$dd=strtok("-");
	$dd=strtok("-");
	if($dd>0)
		$day = date('l',mktime(0,0,0,$mm,$dd,$y)); // get SUNDAY-SATURDAY


/** sorting control **/
	$order=$_POST['order'];
	if($order=="")
		$order="asc";
		
	if($order=="desc")
		$nextdirection="asc";
	else
		$nextdirection="desc";
		
	$sort=$_POST['sort'];
	if($sort=="")
		$sqlsort="order by name asc";
	else
		$sqlsort="order by $sort $order, name";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>KEDATANGAN <?php echo strtoupper($clsname);?></title>
<script language="javascript">
function process_form(action){
		document.myform.p.value='../eatt/att_stu_reg';
		document.myform.dt.value=action;
		document.myform.submit();
}
function process_change(action){
	document.myform.p.value='../eatt/att_cls_rep';
	document.myform.chm.value=action;
	document.myform.dt.value='';
	document.myform.submit();
}


function xxx(action){
	for (i=0; i<document.getElementById('mytablex').rows.length; i++)
		document.getElementById('mytablex').rows[i].cells[0].style.display="none";
	
}

</script>


<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<!-- SETTING GRAY BOX -->
<script type="text/javascript"> var GB_ROOT_DIR = "<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/"; </script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_scripts.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />
<!-- apai remark
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/static_files/help.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/static_files/help.css" rel="stylesheet" type="text/css" media="all" />
-->
</head>
<body >
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="op">
	<input type="hidden" name="dt" value="<?php echo $dt;?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<input type="hidden" name="month" value="<?php echo $m;?>">
	<input type="hidden" name="chm" >
	<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
	<input name="order" type="hidden" id="order" value="<?php echo $order;?>">
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
	<a href="#" onClick="showhide('tipsdiv')" id="mymenuitem"><img src="../img/help22.png"><br>HowTo</a>
<?php if($p==""){?>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
<?php } ?>
<!-- 
	<a href="#" onClick="show('divshowhide')" id="mymenuitem"><img src="../img/warning.png"><br>Reminder</a>
 -->
</div>
<div align="right">
	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br>
<?php if(is_verify("ADMIN|AKADEMIK")){?>
		<select name="year" id="year" onchange="document.myform.submit();">
<?php		$sql="select * from type where grp='session' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
			$v=$row['val'];
                        /*$xx=$s+1;
						if($issemester)
								$xsesyear="$s/$xx";
						else
								$xsesyear=$s;
			*/
			if($s==$year){$selected="selected";}else{$selected="";}
			echo "<option value=\"$s\" $selected>$lg_session $s</option>";
			//}
            }
            mysql_free_result($res);					  
?>
		</select>
      <select name="sid" id="sid" onchange="document.myform.cls[0].value='';document.myform.submit();">
        <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select $lg_school -</option>";
			else
                echo "<option value=$sid>$ssname</option>";
				
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['sname'];
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
				mysql_free_result($res);
			}							  
			
?>
      </select>
      <select name="cls" id="cls" onchange="document.myform.submit();">
        <?php	
      				if($cls=="")
						echo "<option value=\"\">- $lg_class -</option>";
					else
						echo "<option value=\"$cls\">$clsname</option>";

					$sql="select * from ses_cls where sch_id=$sid and cls_code!='$cls'  and year='$year' order by cls_level";
            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=stripslashes($row['cls_name']);
						$a=$row['cls_code'];
                        echo "<option value=\"$a\">$b</option>";
            		}	
			?>
      </select>
	  <input type="submit" value="View">
	  </a>
	 <?php } else {?>
				<input type="hidden" name="cls" value="<?php echo $cls;?>">
				<input type="hidden" name="sid" value="<?php echo $sid;?>">
	<?php } ?>
</div>
</div><!-- end mypanel -->
<div id="story">
<div id="story_title"><?php echo strtoupper($lg_attendance);?>&nbsp;:&nbsp;<?php echo strtoupper("$sname - $clsname");?></div>
<div id="tipsdiv" style="display:none">
<?php if($LG=="BM"){?>
	Tips&raquo;<br>
	1. Pilih Sekolah dan pilih kelas yang berkenaan.<br>
	2. Klik pada TARIKH untuk memasukkan kedatangan.<br>
	3. Klik pada NAMA pelajar untuk melihat rekod tahunan pelajar.<br>
	4. Optional. Sila print jadual kosong mengikut bulan dan tandakan secara manual dahulu.<br>
	5. *Jadual taqwim sekolah perlulah di siapkan dahulu untuk mengguna servis ini.<br>
<?php }else{?>
	Tips&raquo;<br>
	1. Select the particular school and class.<br>
	2. Clik the DATE to key-in the attendance.<br>
	3. Click at the NAME to see the student report.<br>
	4. For manual key-in, you may print the blank sheet to do the weekly mark first.<br>
	5. TAQWIN need to be set first before can use this module.
<?php } ?>
	<br>
</div>
<table width="100%" border="0" id="mytitle">
	  <tr>
			<td width="5%"><a href="#" onClick="process_change('-1')"><strong>PREV MONTH</strong></a></td>
			<td align="center"><?php echo strtoupper("$mn $y");?></td>
			<td width="5%" align="right"><a href="#" onClick="process_change('1')"><strong>NEXT MONTH</strong></a></td>
	  </tr>
</table>

<table width="100%" cellspacing="0" cellpadding="2">
  <tr>
  	<!-- 
	<td id="mytabletitle" width="1%"  style="display:none "><input type=checkbox name=checkall value="0" onClick="check(1)"></td>
	 -->
  	<td id="mytabletitle" width="2%" align="center" id="myborder"><?php echo strtoupper($lg_no);?></td>
	<td id="mytabletitle" width="2%" align="center" id="myborder"><a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_mf);?></a></td>
    <td id="mytabletitle" width="17%" id="myborder"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></td>
    
<?php
		for($i=1;$i<=$nd;$i++){
			$dn=date('D',mktime(0,0,0,$mm,$i,$y)); // get days MON, TUE etc
			$dt=date('Y-m-d',mktime(0,0,0,$mm,$i,$y)); // get date 2008-12-31
			//echo "$y-$mm-$i;";
			$bg="";
			$sql="select * from ses_cal where d='$dt'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sta=$row['sta'];
			if($sta=="") $bg=$bgkuning; //belum tanda 
			if($sta=="0") $bg=$bgkuning; //sekolah - hijau
			if($sta=="1") $bg=$bgbiru; //cuti biasa - kuning
			if($sta=="2") $bg=$bgpink; //cuti penggal -biru
			if($sta=="3") $bg=$bgoren; //public holiday - pink
			if($sta=="4") $bg=$bglgray; //public holiday - pink
			$size=80/31;
			echo "<td width=\"$size%\" bgcolor=$bg align=\"center\" style=\"border:1px solid #DDDDDD\"><a href=\"../eatt/att_stu_reg.php?sid=$sid&cls=$cls&dt=$dt&year=$year\" title=\"$lg_attendance\" onClick=\"return GB_showPage('$lg_attendance',this.href)\" target=\"_blank\">$i<br>$dn</a></td>";
		}
?>
		<td id="mytabletitle" width="2%" align="center" id="myborder"><?php echo strtoupper($lg_this_monthabsence);?></td>
		<td id="mytabletitle" width="2%" align="center" id="myborder"><?php echo strtoupper($lg_total_absence);?></td>
  </tr>
<?php 
if($cls!=""){
	$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid and status=6 $sqlisyatim $sqlisstaff $sqliskawasan $sqlcls $sqlsearch and year='$year' $sqlsort";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$num=mysql_num_rows($res);
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$name=ucwords(strtolower(stripslashes($row['name'])));
		$sex=$row['sex'];
		if($q++%2==0)
			$bg="#FAFAFA";
		else
			$bg="";
?>
  <tr bgcolor="<?php echo $bg;?>" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
  	<?php
	
	 //echo "<td ><input type=checkbox name=del[] value=\"$xid\" onClick=\"check(0)\"></td>";
	
	?>
  	<td align="center" id="myborder"><?php echo $q?></td>
	<td align="center" id="myborder"><?php echo $lg_sexmf[$sex];?></td>
    <td id="myborder" style="font-size:95%"><a href="../eatt/att_stu_rep.php?uid=<?php echo "$uid&sid=$sid"?>" title="<?php echo $lg_student_record;?>" onClick="return GB_showPage('<?php echo $lg_attendance;?>',this.href)" target="_blank"><?php echo $name?></a></td>
<?php
		
		for($i=1;$i<=$nd;$i++){
			$dn=date('D',mktime(0,0,0,$mm,$i,$y)); // get days MON, TUE etc 
			$dt=date('Y-m-d',mktime(0,0,0,$mm,$i,$y)); // get date 2008-12-31
			//echo "$y-$mm-$i:";
			$bg="";
			$sql="select * from ses_cal where d='$dt'";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
            $row2=mysql_fetch_assoc($res2);
            $sta=$row2['sta'];
			
				$xdes="";
				$bg="";
				$sql="select * from stuatt where d='$dt' and stu_uid='$uid'";
				$res2=mysql_query($sql)or die("query failed:".mysql_error());
				$row2=mysql_fetch_assoc($res2);
				$sta=$row2['sta'];
				$des=$row2['des'];
				if($sta=="") 
					$bg=""; //belum set
				else if($sta=="0"){ 
					$bg=$bgmerah; //sekolah - red
					if($des!="")
							$xdes="<a href=\"#\" title=\"$des\"><font color=#FFFFFF><strong>??</strong></font></a>";
				}
				else 
					$bg=$bghijau; //hijau - hadir
			//}
			echo "<td id=myborder width=\"$size%\" bgcolor=\"$bg\" align=\"center\">$xdes</td>";
			
			
		}
		//kira jum tak hadir
			$thyear="";
			$thmonth="";
			$thy=date('Y',mktime(0,0,0,$m,1,$y)); // get date 2008-12-31 
			$thm=date('Y-m',mktime(0,0,0,$m,1,$y)); // get date 2008-12-31 
			$sql="select count(*) from stuatt where stu_uid='$uid' and sta=0 and d like '$thy%' ";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			$row2=mysql_fetch_row($res2);
			$thyear=$row2[0];
			
			$sql="select count(*) from stuatt where stu_uid='$uid' and sta=0 and d like '$thm%' ";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			$row2=mysql_fetch_row($res2);
			$thmonth=$row2[0];
?>
		<td align="center" id="myborder"><?php echo $thmonth;?></td>
		<td align="center" id="myborder"><?php echo $thyear;?></td>
  </tr>
 <?php }}?>
</table>

<div width="100%" align="right">
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
 </div></div>
</form>



</body>
</html>