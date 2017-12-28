<?php
$vmod="v5.0.0";
$vdate="160910";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|GURU|HR|SOKONGAN');
$username = $_SESSION['username'];
$isprint=$_REQUEST['isprint'];

		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
		$sql="select * from sch where id='$sid'";
        $res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=stripcslashes($row['name']);
           
		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
			
		$uid=$_REQUEST['uid'];
		if($uid!=""){
			$sql="select * from stu where sch_id='$sid' and uid='$uid'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $xname=$row['name'];
			$xic=$row['ic'];
			$file=$row['file'];
			
			$sql="select * from ses_stu where stu_uid='$uid' and year='$year'";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			if($row2=mysql_fetch_assoc($res2))
				$cname=$row2['cls_name'];
			
			
		}
		$lvl=$_REQUEST['lvl'];
		$blk=$_REQUEST['blk'];
		$rom=$_REQUEST['rom'];
		$bed=$_REQUEST['bed'];
		$op=$_REQUEST['op'];
		$schid=$_REQUEST['sid'];
if($op=='save'){
	        $lvl=$_REQUEST['lvl'];
		$blk=$_REQUEST['blk'];
		$rom=$_REQUEST['rom'];
		$bed=$_REQUEST['bed'];
		$schid=$_REQUEST['sid'];
	$sqlc="select count(*) from hos_room where uid='$uid' and sid='$sid'";
	$resc=mysql_query($sqlc)or die("query failed:".mysql_error());
	$rowc=mysql_fetch_row($resc);
	$retc=$rowc[0];
	$sqlc2="select count(*) from hos_room where block='$blk' and level='$lvl' and roomno='$rom' and stuno='$bed' and sid='$schid' and sta='1'";
	$resc2=mysql_query($sqlc2)or die("query failed:".mysql_error());
	$rowc2=mysql_fetch_row($resc2);
	$retc2=$rowc2[0];
	
	if($retc=='0' || $retc2=='0' ){
		$sql="update hos_room set sta='1',uid='$uid' where block='$blk' and level='$lvl' and roomno='$rom' and stuno='$bed' and sid='$schid'";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$f="<font color=blue>&lt;SUCCESSFULLY UPDATED&gt</font>";
	}else{
		$f="<font color=red>&lt;USER ALREADY EXIST&gt</font>";
	}
	$blk="";
	$lvl="";
	$rom="";
	$bed="";
}
if($op=='delete'){
	$sql="update hos_room set sta='0',uid='' where block='$blk' and level='$lvl' and roomno='$rom' and stuno='$bed' and sid='$schid'";
	$res=mysql_query($sql)or die("$sql failed:".mysql_error());
	$f="<font color=blue>&lt;SUCCESSFULLY UPDATED&gt</font>";
	$blk="";
	$lvl="";
	$rom="";
	$bed="";
}

if($uid!=""){
	$sql="select * from hos_room where uid='$uid' and sid='$sid'";
	$res=mysql_query($sql)or die("$sql failed:".mysql_error());
	if($row=mysql_fetch_assoc($res)){
		$xblock=$row['block'];
		$xroom=$row['roomno'];
		$xbed=$row['stuno'];
		$xlevel=$row['level'];
	}
}
/** paging control **/
	$curr=$_POST['curr'];
    if($curr=="")
    	$curr=0;
    $MAXLINE=$_POST['maxline'];
	if($MAXLINE==0)
		$MAXLINE=30;
/** sorting control **/
	$order=$_POST['order'];
	if($order=="")
		$order="desc";
		
	if($order=="desc")
		$nextdirection="asc";
	else
		$nextdirection="desc";
		
	$sort=$_POST['sort'];
	if($sort=="")
		$sqlsort="order by id $order";
	else
		$sqlsort="order by $sort $order, id desc";


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>

<?php include("$MYOBJ/calender/calender.htm")?>
<script language="JavaScript">
function process(op,val)
{

	if (val == 0){
		alert('Maaf. Nombor bilik tidak sah');
		return;
	}
	ret = confirm("Save the configuration??");
	if (ret == true){
		document.myform.op.value=op;
		document.myform.bed.value=val;
		document.myform.submit();
	}
}


</script>
</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="hos_stu_reg">
	<input type="hidden" name="isprint">
	<input type="hidden" name="op">
	<input type="hidden" name="uid" value="<?php echo $uid;?>">
	<input type="hidden" name="sid" value="<?php echo $sid;?>">
	<input type="hidden" name="lvl" value="<?php echo $lvl;?>">
	<input type="hidden" name="blk" value="<?php echo $blk;?>">
	<input type="hidden" name="rom" value="<?php echo $rom;?>">
	<input type="hidden" name="bed">
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="javascript: href='hos_stu_reg.php?sid=<?php echo $sid;?>&uid=<?php echo $uid;?>'" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
</div>
<div align="right"><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a></div>
</div><!-- end mypanel-->
<div id="story">

<div id="story_title"><?php echo strtoupper($lg_hostel);?> - <?php echo strtoupper($lg_registration);?> <?php echo $f;?></div>
<table width="100%">
  <tr>
  	<td width="50%" valign="top">	
		<table width="100%" >
		  <tr>
			<td width="14%"><?php echo strtoupper($lg_name);?></td>
			<td width="1%">:</td>
			<td width="85%"><?php echo strtoupper("$xname");?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_matric);?></td>
			<td>:</td>
			<td><?php echo "$uid";?> </td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_school);?></td>
			<td>:</td>
			<td><?php echo strtoupper("$sname");?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_class);?></td>
			<td>:</td>
			<td><?php echo strtoupper("$cname/$year");?> </td>
		  </tr>
		</table>
	</td>
    <td width="50%" valign="top">
		<table width="100%">
		  <tr>
			<td width="24%" align="right"><?php echo strtoupper($lg_block);?></td>
			<td width="1%">:</td>
			<td width="75%"><?php echo $xblock;?></td>
		  </tr>
		   <tr>
			<td align="right"><?php echo strtoupper($lg_level);?></td>
			<td>:</td>
			<td><?php echo $xlevel;?></td>
		  </tr>
		  <tr>
			<td align="right"><?php echo strtoupper($lg_room_no);?></td>
			<td>:</td>
			<td><?php echo $xroom;?></td>
		  </tr>
		  <tr>
			<td align="right"><?php echo strtoupper($lg_bed_no);?></td>
			<td>:</td>
			<td><?php echo $xbed;?></td>
		  </tr>
		</table>
 	</td>
  </tr>
</table>



<?php if($rom>0){?>

<br>
<div id="mytitlebg"><?php echo strtoupper($lg_hostel);?></div>
<table width="100%" style="font-size:120% ">
		
<?php
$i=0;
	$sql="select * from hos_room where block='$blk' and level='$lvl' and roomno='$rom' and sid='$sid' order by block, level";
	$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
			$xx=$row['uid'];
			$sta=$row['sta'];
			if($sta==1)
				$bg=$bgmerah;
			else
				$bg=$bghijau;
			$i++;
?>
		<tr>
			<td id="mytabletitle" ><?php echo "$lg_bed_no($i)&nbsp;: " ;if($sta) echo "$xx"; else echo "<a href=\"#\" onClick=\"process('save',$i)\">$lg_register</a>";?></td>
			<td ><a href="#" onClick="process('delete',<?php echo $i;?>)">Clear</a></td>
		</tr>
<?php } ?>
	
</table>
<br>
<br>
<?php } ?>
<?php
	$sql="select distinct(block),name from hos where sid='$sid' order by block";
	$res3=mysql_query($sql)or die("$sql - query failed:".mysql_error());
	while($row3=mysql_fetch_assoc($res3)){
		$block=$row3['block'];
		$name=$row3['name'];
		$nobilik=0;
?>
<div id="mytitle"><?php echo "$block - $name";?></div>
<table width="100%" style="font-size:125% ">
<?php
	/*$sql="select * from hos where (sid=0 or sid=$sid) and block='$block' order by block, level";*/
	$sql="select * from hos where block='$block' and sid='$sid' order by block, level";
	$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$bk=$row['block'];
		$noroom=$row['noroom'];
		$nostu=$row['nostu'];
		$level=$row['level'];
		$usedroom=0;
		$totalroom=$noroom*$nostu;
		
?>
	<tr>
		<td width="3%" id="mytabletitle">Aras&nbsp;<?php echo $level; ?></td>
<?php for($i=1;$i<=$noroom;$i++){
		$nobilik++;
		/*$sql="select count(*),roomname from hos_room where (sid=0 or sid=$sid) and block='$bk' and level=$level and roomno=$nobilik and sta>0 order by block, level";*/
		$sql="select count(*) from hos_room where block='$bk' and level='$level' and roomno='$i' and sta>0 order by block, level";
		$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$ret=$row2[0];
		if($ret==0)
			$bg="";
		elseif($ret<$nostu)
			$bg=$bgkuning;
		else
			$bg=$bglred;
		$usedroom=$usedroom+$ret;
		
?>
		<td width="3%" id="myborder" bgcolor="<?php echo $bg;?>" align="center">
		<a href="hos_stu_reg.php?<?php echo "sid=$sid&uid=$uid&rom=$i&lvl=$level&blk=$bk"; ?>"><?php echo $i; echo "($ret/$nostu)"?></a>
		</td>
<?php }//level?>
	<td width="3%" id="mytabletitle" bgcolor="<?php echo $bg;?>" align="center"><?php echo "$usedroom/$totalroom";?></td>
	</tr>
<?php }//block?>
</table>
<?php } //whole block ?>
</div></div>

</form> <!-- end myform -->


</body>
</html>
<!-- 
V.1
Author: razali212@yahoo.com
 -->