<?php
//121101 - new
$vmod="v6.0.0";
$vdate="121101";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("");

	$adm=$_SESSION['username'];
	$p=$_REQUEST['p'];
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
	if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=stripslashes($row['name']);
            mysql_free_result($res);					  
	}
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
	
	$sql="select * from usr where uid='$adm' order by name";
    $res=mysql_query($sql)or die("$sql failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
    $teaname=stripslashes($row['name']);
	
	$y=$_REQUEST['year'];
	if($y=="")
		$y=date('Y');
		
	$m=$_POST['month'];
	if($m=="")
		$m=date('m');
		
	$chm=$_REQUEST['chm'];
	if($chm!="")
		$m=$m+$chm;
	
	$d=date("d");     // Finds today's date
		
	$nd = date('t',mktime(0,0,0,$m,1,$y)); // This is to calculate number of days in a month
	//$mn=date('M',mktime(0,0,0,$m,1,$y)); // get JAN-DIS
	$mn=date('F',mktime(0,0,0,$m,1,$y)); // get JANUARY-DICEMBER
	$yn=date('Y',mktime(0,0,0,$m,1,$y)); // Year is calculated to display at the top of the calendar
	$j= date('w',mktime(0,0,0,$m,1,$y)); // This will calculate the week day of the first day of the month

	$op=$_POST['op'];
	$dt=$_REQUEST['dt'];
	
	$dd=strtok($dt,"-");
	$dd=strtok("-");
	$dd=strtok("-");
	if($dd>0)
		$day = date('l',mktime(0,0,0,$m,$dd,$y)); // get SUNDAY-SATURDAY

if($op=="update"){
	$uid=$_POST['uid'];
	$des=$_POST['des'];
	$ms=$_POST['ms'];
	$sql="delete from dailyread where sid=$sid and dt='$dt' and cls='$cls' and sub='$sub'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	for ($i=0; $i<count($uid); $i++) {
		$xuid=$uid[$i];
		$xsta=$_POST["sta_$xuid"];
		//if($xsta=="") $xsta=0;
		if($xsta=="") 
			$xsta=1;
		else
			$xsta=0;
		$xdes=$des[$i];
		$xms=$ms[$i];
		if($xms=="")
			$xms=0;
		$sql="insert into dailyread(year,dt,sid,cls,sub,stu,sta,des,tea,ms) values ('$y','$dt',$sid,'$cls','$sub','$xuid','$xsta','$xdes','$adm','$xms')";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
	}
	$f="<font color=blue>&lt;SUCCESSFULLY UPDATED&gt</font>";
}elseif($op=="delete"){
		$sql="delete from dailyread where year='$y' and dt='$dt' and sid=$sid and cls='$cls' and sub='$sub'";
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
		document.myform.op.value=action;
		document.myform.submit();
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
<body <?php if($f!=""){?> onLoad="top.document.myform.submit();window.close();parent.parent.GB_hide();"<?php }?>>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="op">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<input type="hidden" name="dt" value="<?php echo $dt;?>">
	<input type="hidden" name="month" value="<?php echo $m;?>">
	<input type="hidden" name="chm">
	<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
	<input name="order" type="hidden" id="order" value="<?php echo $order;?>">
	<input name="sid" type="hidden" value="<?php echo $sid;?>">
	<input name="cls" type="hidden" value="<?php echo $cls;?>">
    <input name="sub" type="hidden" value="<?php echo $sub;?>">
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="process_form('update')"id="mymenuitem"><img src="../img/save.png"><br>Save</a>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
		<div id="mymenu_seperator"></div>
		<div id="mymenu_space">&nbsp;&nbsp;</div>
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

<div id="mytitle2"> <?php echo $lg_daily_reading;?> <?php echo $f;?></div>
<table width="100%" id="mytitle">
  <tr>
    <td width="50%">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="20%"><?php echo strtoupper($lg_school);?></td>
			<td width="1%">:</td>
			<td><?php echo strtoupper($sname);?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_class);?></td>
			<td>:</td>
			<td><?php echo strtoupper("$clsname / $y");?></td>
		  </tr>
		</table>
	</td>
    <td width="50%">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td><?php echo strtoupper($lg_subject);?></td>
			<td>:</td>
			<td><?php echo strtoupper($subname);?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_teacher);?></td>
			<td>:</td>
			<td><?php echo strtoupper($teaname);?></td>
		  </tr>
		</table>
	
	</td>
  </tr>
</table>
<table width="100%" cellpadding="2" cellspacing="0" style="font-size:11px;">
  <tr>
        <td id="mytabletitle" width="3%" align="center"><?php echo $lg_no;?></td>
        <td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="Sort"><?php echo $lg_matric;?></a></td>
        <td id="mytabletitle" width="30%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo $lg_name;?></a></td>
        <td id="mytabletitle" width="5%" align="center"><?php echo $lg_page;?></td>
        <td id="mytabletitle" width="50%"><?php echo $lg_remark;?></td>
  </tr>
<?php 
if($cls!=""){
	$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid and status=6 $sqlisyatim $sqlisstaff $sqliskawasan $sqlclscode $sqlsearch and year='$y' $sqlsort";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$num=mysql_num_rows($res);
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$name=stripslashes($row['name']);
		$sex=$row['sex'];
		$sql="select * from dailyread where dt='$dt' and stu='$uid' and cls='$cls' and sub='$sub'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		$row2=mysql_fetch_assoc($res2);
		$sta=$row2['sta'];
		$des=$row2['des'];
		$ms=$row2['ms'];
				
		if($q++%2==0)
			$bg="#FAFAFA";
		else
			$bg="";
?>
  <tr bgcolor="<?php echo $bg;?>" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
  	<td id="myborder" align="center"><?php echo $q?></td>
    <td id="myborder" align="center"><?php echo $uid?></td>
	<td id="myborder"><?php echo $name?></td>
    <td id="myborder"><input name="ms[]" type="text" size="10" value="<?php echo $ms;?>"></td>
    <td id="myborder">
    <!--
		<input type="checkbox" name="sta_<?php echo $uid?>" value="1" onClick="check(0)" <?php if($sta=="0") echo "checked";?>>
	 -->        
		<input name="des[]" type="text" size="48" value="<?php echo $des?>">
		<input name="uid[]" type="hidden" value="<?php echo $uid?>">
	</td>
  </tr>
<?php } }?>
</table>

 </div></div>
</form>

</body>
</html>