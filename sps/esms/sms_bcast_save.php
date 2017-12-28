<?php 
include_once('../etc/db.php');
include_once('etc/session_sms.php');
include_once('../inc/xgate.php');
verify('ADMIN');
$adm=$_SESSION['username'];
$admsid=$_SESSION['sid'];

$cyear=date('Y');
$sid=$_POST['sid'];
if($sid=="")
	$sid=$_SESSION['sid'];

if($sid>0)
	$sqlsid=" and sch_id='$sid'";
if($sid>0){
	$sqlsid=" and sch_id='$sid'";
	$sqlsid2=" and stu.sch_id='$sid'";
}

$job=$_POST['job'];
if($job!="")
	$sqljob=" and job='$job'";
	
$div=$_POST['div'];
if($div!=""){
	$xdiv=addslashes($div);
	$sqldiv=" and jobdiv='$xdiv'";
}
$sex=$_POST['sex'];
if($sex!="")
	$sqlsex=" and sex='$sex'";
	
$msg=$_POST['msg'];

$to=$_POST['to'];
if($to=="")
	$to="STAFF";
$clslevel=0;
$clslevel=$_POST['clslevel'];
$clscode=$_POST['clscode'];
if($clscode!=""){
	$sqlclscode="and ses_stu.cls_code='$clscode'";
	$sql="select * from cls where sch_id=$sid and code='$clscode'";
	$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
    $clsname=$row['name'];
	$clslevel=$row['level'];
}
if($clslevel>0)
	$sqlclslevel="and ses_stu.cls_level='$clslevel'";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SSS</title>
</head>
<body>
 
<div id="panelleft"> 
	<?php include('inc/mymenu.php');?>
</div><!--end pageNav-->

<div id="content2">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="p.php?p=sms_bcast" id="mymenuitem"><img src="../img/new.png"><br>New</a>
	</div> <!-- end mymenu -->
</div> <!-- end mypanel -->
 
<div id="story">


	<div id="mytitle"> SMS BROADCAST STATUS </div>
	<table width="100%" id="mytable">
      <tr id="mytabletitle">
        <td width="5%">No</td>
        <td width="20%">Telefon</td>
		<td width="30%">Name</td>
		<td width="20%">Kumpulan</td>
        <td width="5%" align="center">Status</td>
      </tr>
     	
<?php
if($to=="STAFF"){
	$sql="select distinct(hp) from usr where status=0 $sqlsid $sqljob $sqldiv $sqlsex order by name";
}else{
	if(($clslevel==0)&&($clscode==""))
		$sql="select distinct(hp) from stu where status=6";
	else
		$sql="select distinct(hp) from stu LEFT JOIN ses_stu ON (stu.uid=ses_stu.stu_uid) where status=6 $sqlsid2 $sqlsex $sqlclscode $sqlclslevel and ses_stu.year='$cyear'";
}		

	$res=mysql_query($sql,$link)or die("$sql query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$xtel=$row['hp'];
		if($to=="STAFF"){
				$sql="select name,jobdiv from usr where hp='$xtel' $sqlsid $sqljob $sqldiv $sqlsex";
				$res2=mysql_query($sql,$link)or die("query failed:".mysql_error());
				$row2=mysql_fetch_assoc($res2);
				$xname=stripslashes($row2['name']);
				$xdiv=stripslashes($row2['jobdiv']);
		}else{
				$sql="select name from stu where status=6 and hp='$xtel' $sqlsid2";
				$res2=mysql_query($sql,$link)or die("query failed:".mysql_error());
				$row2=mysql_fetch_assoc($res2);
				$xname=stripslashes($row2['name']);
		
				$sql="select cls_name from stu LEFT JOIN ses_stu ON (stu.uid=ses_stu.stu_uid) where status=6 $sqlsid2 $sqlsex $sqlclscode $sqlclslevel and ses_stu.year='$cyear' and hp='$xtel'";
				$res2=mysql_query($sql,$link)or die("query failed:".mysql_error());
				$row2=mysql_fetch_assoc($res2);
				$xdiv=stripslashes($row2['cls_name']);
		}
		
		$ret=1;
		$xtel=trim($xtel);
        if(strlen($xtel) != 11)
			$ret=0;
        $net=substr($xtel,0,4);
        if(($net!="6012")&&($net!="6013")&&($net!="6016")&&($net!="6017")&&($net!="6019")&&($net!="6014")&&($net!="6018")&&($net!="6010"))
			$ret=0;
		
		if($ret){
			$xm=addslashes($msg);
			$sql="insert into sms_log(dt,sid,app,typ,tel,msg,des,adm,ts)values(now(),$admsid,'BROADCAST','MT','$xtel','$xm','$to','$adm',now())";
			$res3=mysql_query($sql)or die("$sql - failed:".mysql_error());
			$xid=mysql_insert_id($link);
			$xmsg="$xgatekey - $msg";
			if($ON_XGATE)
				$ret=xgate_send_sms($xgateip,$xgateport,$xgateuser,$xgatepass,$xgategw,$xtel,$xid,0,0,$xmsg,0,$xgatekey,10);
			else
				$ret=-1;
		}
		if($ret==0)
			$rets="reject";
		elseif($ret==1)
			$rets="sent";
		else
			$rets="undeliver";
		
		if($q++%2==0)
			$bg="bgcolor=#FAFAFA";
		else
			$bg="bgcolor=#FAFAFA";
		if($ret!=1)
			$bg="bgcolor=$bglred";
?>
      	<tr <?php echo "$bg"; ?>>
        <td><?php echo "$q"; ?></td>
        <td><?php echo "$xtel"; ?></td>
		<td><?php echo "$xname"; ?></td>
		<td><?php echo "$xdiv"; ?></td>
        <td align="center"><?php echo "$rets"; ?></td>
      	</tr>
	 
<?php } ?>       	  
	</table>
</div><!-- end of story -->
</div><!--end of content-->   

</body>
</html>
