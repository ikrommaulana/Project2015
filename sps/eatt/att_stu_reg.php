<?php
//500 - 01/08/2010 - language set
$vmod="v5.0.0";
$vdate="21/07/2010";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
	verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');
	$username=$_SESSION['username'];
	$p=$_REQUEST['p'];
	$year=$_REQUEST['year'];
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
	if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
            mysql_free_result($res);					  
	}
	$cls=$_REQUEST['cls'];
	if($cls!=""){
			$sqlclscode="and ses_stu.cls_code='$cls'";
			$sql="select * from cls where sch_id=$sid and code='$cls'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=$row['name'];
	}
		
	$m=$_POST['month'];
	if($m=="")
		$m=date('m');
		
	$chm=$_REQUEST['chm'];
	if($chm!="")
		$m=$m+$chm;
	
	$d=date("d");     // Finds today's date
		

	$op=$_POST['op'];
	$dt=$_REQUEST['dt'];
	
	$yy=strtok($dt,"-");
	$mm=strtok("-");
	$dd=strtok("-");
	if($dd>0)
		$day = date('l',mktime(0,0,0,$m,$dd,$yy)); // get SUNDAY-SATURDAY
	
		$ds=date('N',mktime(0,0,0,$m,$dd,$yy)); // get day 1-7
			switch($ds){
				case 1 :
					$day="Senin";
				break;
				case 2 :
					$day="Selasa";
				break;
				case 3 :
					$day="Rabu";
				break;
				case 4 :
					$day="Kamis";
				break;
				case 5 :
					$day="Jum'at";
				break;
				case 6 :
					$day="Sabtu";
				break;
				case 7 :
					$day="Minggu";
				break;
			}
		
	$nd = date('t',mktime(0,0,0,$mm,1,$yy)); // This is to calculate number of days in a month
	//$mn=date('M',mktime(0,0,0,$m,1,$y)); // get JAN-DIS
	$mn=date('F',mktime(0,0,0,$mm,1,$yy)); // get JANUARY-DICEMBER

	$sqlmonth="select * from month where no='$mm'";
	$resmonth=mysql_query($sqlmonth)or die("$sqlmonth query failed:".mysql_error());
	$rowmonth=mysql_fetch_assoc($resmonth);
	$mn=$rowmonth['name'];

	$yn=date('Y',mktime(0,0,0,$mm,1,$yy)); // Year is calculated to display at the top of the calendar
	$j= date('w',mktime(0,0,0,$mm,1,$yy)); // This will calculate the week day of the first day of the month

if($op=="update"){
	$uid=$_POST['uid'];
	$des=$_POST['des'];
	$sta=$_POST['sta'];

	
		
	
	$sql="select * from stuatt where d='$dt' and stu_uid='$uid[0]'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$number=mysql_num_rows($res);
	
	
	if($number>0){
		for ($i=0; $i<count($uid); $i++) {
		$xuid=$uid[$i];
		$xsta=$sta[$xuid];
		//if($xsta=="") $xsta=0;
		if($xsta=="") 
			$xsta=1;
		else
			$xsta=0;
		$xdes=$des[$i];
		//
        $sql="UPDATE stuatt set des = '$xdes',  sta = '$xsta' where stu_uid='$xuid' and cls_code='$cls' and d = '$dt'; ";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		}
	}else{
		for ($i=0; $i<count($uid); $i++) {
			$xuid=$uid[$i];
			$xsta=$_POST["sta_$xuid"];
			//if($xsta=="") $xsta=0;
			if($xsta=="") 
				$xsta=1;
			else
				$xsta=0;
			$xdes=$des[$i];
			
			$sql="insert into stuatt(year,d,sch_id,cls_code,stu_uid,sta,des) values ('$year','$dt',$sid,'$cls','$xuid','$xsta','$xdes')";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
		}
	}
	$f="<font color=blue>&lt;SUCCESSFULLY UPDATED&gt</font>";
}elseif($op=="delete"){

		$sql="delete from stuatt where year='$year' and d='$dt' and sch_id=$sid and cls_code='$cls'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
		$f="<font color=blue>&lt;SUCCESSFULLY DELETE&gt</font>";
}

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
		$sqlsort="order by name $order";
	else
		$sqlsort="order by $sort $order, id desc";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
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
	}else if(action=='delete'){
		ret = confirm("Are you sure want to Delete all this record??");
		if (ret == true){
			document.myform.op.value=action;
			document.myform.submit();
		}
		return;
	}
	else{
		document.myform.dt.value=action;
		document.myform.submit();
	}
}
function process_change(action){
	document.myform.chm.value=action;
	document.myform.dt.value='';
	document.myform.submit();
}

</script>
</head>
<!--<body>-->
<body <?php if($f!=""){?> onLoad="top.document.myform.submit();window.close();parent.parent.GB_hide();"<?php }?>>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="op">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<input type="hidden" name="dt" value="<?php echo $dt;?>">
	<input type="hidden" name="month" value="<?php echo $m;?>">
	<input type="hidden" name="sid" value="<?php echo $sid;?>">
	<input type="hidden" name="cls" value="<?php echo $cls;?>">
	<input type="hidden" name="year" value="<?php echo $year;?>">
	<input type="hidden" name="chm">
	<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
	<input name="order" type="hidden" id="order" value="<?php echo $order;?>">
	<input name="sid" type="hidden" value="<?php echo $sid;?>">
	<input name="cls" type="hidden" value="<?php echo $cls;?>">
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="process_form('update')"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
<a href="#" onClick="process_form('delete')"id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="<?php if($f!=""){?>top.document.myform.submit();<?php }?>window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
</div> <!-- end mymenu -->
<div align="right">
<br><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
</div>
</div><!-- end mypanel -->
<div id="story">
<font color="#FF0000">
<?php if($LG=="BM"){?>
PANDUAN: <br>
-JIKA SEMUA HADIR, TERUS KLIK SAVE (TAK PERLU TICK).<br>
-TANDAKAN / TICK PADA YANG TIDAK HADIR SAHAJA, DAN KLIK SAVE. <br>
-WALAUPUN SEMUA HADIR, PERLU KLIK SAVE JUGA SEBAGAI PENGESAHAN.
<?php }else{?>
README: <br>
-"TICK" FOR "ABSENCE" STUDENT ONLY, AND WRITE THE REASON IF ANY.<br>
-IF NO ABSENCE STUDENT, YOU STILL NEED TO KLIK THE SAVE BUTTON FOR VERIFICATION.
<?php } ?>
</font>

<div id="mytitle"><?php echo strtoupper("$sname - $clsname ( $day $dd $mn $yy )");?>  <?php echo $f;?></div>
<table width="100%" cellpadding="0" cellspacing="0">
  <tr id="mytabletitle">
    <td width="3%" align="center"><?php echo strtoupper($lg_no);?></td>
	<td width="5%" align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_matrik);?></a></td>
	<td width="2%" align="center"><a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_mf);?></a></td>
    <td width="30%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></td>
    <td>
	<?php 	if($LG=="BM")
				echo "TICK PADA YANG \"TIDAK HADIR\" SAHAJA, DAN MASUKKAN SEBAB JIKA ADA";
			else
				echo "TICK FOR \"ABSENCE\" STUDENT ONLY, AND WRITE THE REASON IF ANY.";
	?>
	</td>
  </tr>
<?php 
if($cls!=""){
	$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid and status=6 $sqlisyatim $sqlisstaff $sqliskawasan $sqlclscode $sqlsearch and year='$year' $sqlsort";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$num=mysql_num_rows($res);
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$name=$row['name'];
		$sex=$row['sex'];
		$sql="select * from stuatt where d='$dt' and stu_uid='$uid'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		$row2=mysql_fetch_assoc($res2);
		$sta=$row2['sta'];
		$des=$row2['des'];
				
		if($q++%2==0)
			$bg="#FAFAFA";
		else
			$bg="";
?>
  <tr bgcolor="<?php echo $bg;?>" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
  	<td id="myborder" align="center"><?php echo $q?></td>
    <td id="myborder" align="center"><?php echo $uid?></td>
	<td id="myborder" align="center"><?php if($sex=="Lelaki")echo "L";if($sex=="Perempuan")echo "P";?></td>
	<td id="myborder"><?php echo $name?></td>
    <td id="myborder">
		<input type="checkbox" name="sta[<?php echo $uid;?>]" value="1" <?php if($sta=="0") echo "checked";?>>
		<select name="des[]" id="des[]">
				<option value="">Hadir</option>
				<option value="S" <?php if($des=="S"){ echo "selected";}?>>Sakit</option>
				<option value="I" <?php if($des=="I"){ echo "selected";}?>>Izin</option>
				<option value="A" <?php if($des=="A"){ echo "selected";}?>>Alpa</option>
		</select>
		<input name="uid[]" type="hidden" value="<?php echo $uid?>">
	</td>
  </tr>
<?php } }?>
</table>

 </div></div>
</form>

</body>
</html>