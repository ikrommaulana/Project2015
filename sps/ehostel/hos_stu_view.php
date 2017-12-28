<?php
$vmod="v5.0.0";
$vdate="160910";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|GURU|HR|SOKONGAN|HEP|HEP-OPERATOR');
$username = $_SESSION['username'];
$isprint=$_REQUEST['isprint'];

		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
		$sql="select * from sch where id='$sid'";
        $res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=$row['name'];
           
		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
			
		$uid=$_REQUEST['uid'];
		if($uid!=""){
			$sql="select * from stu where sch_id=$sid and uid='$uid'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $xname=$row['name'];
			$xic=$row['ic'];
			$file=$row['file'];
			
			$sql="select * from ses_stu where stu_uid='$uid' and year='$year'";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			if($row2=mysql_fetch_assoc($res2)){
				$clsname=$row2['cls_name'];
				$clslevel=$row2['cls_level'];
			}
				
			
			
		}
		$lvl=$_REQUEST['lvl'];
		$blk=$_REQUEST['blk'];
		$rom=$_REQUEST['rom'];
		$bed=$_REQUEST['bed'];
		$op=$_REQUEST['op'];
if($op=='save'){
	//$sql="select count(*) from hos_room where uid='$uid' and sid=$sid";
	$sql="select count(*) from hos_room where uid='$uid'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_row($res);
	$ret=$row[0];
	if($ret==0){
		//$sql="update hos_room set sta=1,uid='$uid' where block='$blk' and level=$lvl and roomno=$rom and stuno=$bed and sid=$sid";
		$sql="update hos_room set sta=1,uid='$uid' where block='$blk' and level=$lvl and roomno=$rom and stuno=$bed";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$f="<font color=blue>&lt;SUCCESSFULLY UPDATED&gt</font>";
		echo "<script language=\"javascript\">location.href='letter.php?uid=$uid&st=ASRAMA_DAFTAR'</script>";
	}else{
		$f="<font color=red>&lt;USER ALREADY EXIST&gt</font>";
	}
	$blk="";
	$lvl="";
	$rom="";
	$bed="";
}
if($op=='delete'){
	//$sql="update hos_room set sta=0,uid='' where block='$blk' and level=$lvl and roomno=$rom and stuno=$bed and sid=$sid";
	$sql="update hos_room set sta=0,uid='' where block='$blk' and level=$lvl and roomno=$rom and stuno=$bed";
	$res=mysql_query($sql)or die("$sql failed:".mysql_error());
	$f="<font color=blue>&lt;SUCCESSFULLY UPDATED&gt</font>";
	$blk="";
	$lvl="";
	$rom="";
	$bed="";
}

if($uid!=""){
	//$sql="select * from hos_room where uid='$uid' and sid=$sid";
	$sql="select * from hos_room where uid='$uid'";
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

</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="sid" value="<?php echo $sid;?>">
	<input type="hidden" name="lvl" value="<?php echo $lvl;?>">
	<input type="hidden" name="blk" value="<?php echo $blk;?>">
	<input type="hidden" name="rom" value="<?php echo $rom;?>">
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="window.print();" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
	<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
</div>
<div align="right"><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br></div>
</div><!-- end mypanel-->
<div id="story">

<div id="story_title"><?php echo strtoupper($lg_room_no);?> : <?php echo "$blk-$lvl-$rom";?></div>
<table width="100%" id="mytable">
	
<?php
	$sql="select * from hos_room where (sid=0 or sid=$sid) and block='$blk' and level=$lvl and roomno=$rom order by block, level";
	$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
			$xx=$row['uid'];
			
			
			$sql="select * from stu where uid='$xx'";
			$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
			$row2=mysql_fetch_assoc($res2);
			$file=$row2['file'];
			$name=strtoupper(stripslashes($row2['name']));
			$status=$row2['status'];
			$schid=$row2['sch_id'];
			
			$sql="select * from type where grp='stusta' and val='$status'";
            $res2=mysql_query($sql)or die("query failed:".mysql_error());
            $row2=mysql_fetch_assoc($res2);
            $status=$row2['prm'];
						
			$sql="select * from sch where id='$schid'";
			$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
			$row2=mysql_fetch_assoc($res2);
			$sname=$row2['sname'];
			$clevel=$row2['clevel'];
			
			$sql="select * from ses_stu where stu_uid= '$xx'order by id desc";
            $res2=mysql_query($sql)or die("query failed:".mysql_error());
            $row2=mysql_fetch_assoc($res2);
            $clslevel=$row2['cls_level'];
			$sesi=$row2['year'];
	
			if($sta==1)
				$bg=$bgmerah;
			else
				$bg=$bghijau;
			$i++;
?>
		<tr>
			<td id="mytabletitle" width="10%" align="center"><?php echo "$lg_bed_no($i)";?></td>
			<td id="myborder" width="80" height="80"><img src="<?php echo "$dir_image_student$file";?>" alt="Picture" height="80" width="80"></td>
			<td id="myborder">
				<table width="100%"  border="0" cellpadding="0">
					  <tr>
						<td width="10%"><?php echo strtoupper($lg_name);?></td>
						<td width="1%">:</td>
						<td><?php echo $name;?></td>
					  </tr>
					  <tr>
						<td><?php echo strtoupper($lg_matric);?></td>
						<td>:</td>
						<td><?php echo $xx;?></td>
					  </tr>
					  <tr>
						<td><?php echo strtoupper($lg_status);?></td>
						<td>:</td>
						<td><?php echo $status;?></td>
					  </tr>
					  <tr>
						<td><?php echo strtoupper($lg_school);?></td>
						<td>:</td>
						<td><?php echo $sname;?></td>
					  </tr>
					  <tr>
						<td><?php echo strtoupper($lg_level);?></td>
						<td>:</td>
						<td><?php echo "$clslevel / $sesi";?></td>
					  </tr>
					</table>
			</td>
		</tr>
<?php } ?>
	
</table>

</div></div>

</form> <!-- end myform -->


</body>
</html>
<!-- 
V.1
Author: razali212@yahoo.com
 -->