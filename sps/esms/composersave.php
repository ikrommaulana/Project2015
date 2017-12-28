<?php 
include_once('../etc/db.php');
include_once('etc/session_sms.php');
include_once('../inc/xgate.php');
verify('ADMIN');
$adm=$_SESSION['username'];
$sid=$_SESSION['sid'];
$admsid=$_SESSION['sid'];

$msg=$_POST['msg'];
$tel=$_POST['tel'];
$grp=$_POST['grp'];

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
		<a href="p.php?p=composer" id="mymenuitem"><img src="../img/new.png"><br>New</a>
	</div> <!-- end mymenu -->
</div> <!-- end mypanel -->
 
<div id="story">


	<div id="mytitle"> SENT STATUS </div>
	<table width="100%" id="mytable">
      <tr id="mytabletitle">
        <td width="5%">No</td>
        <td width="20%">Telefon</td>
		<td width="30%">Name</td>
		<td width="20%">Group</td>
        <td width="5%" align="center">Status</td>
      </tr>
<?php

	$arr = explode(",",$tel);
	for ($i=0; $i<count($arr); $i++) {
		$tmp=$arr[$i];
		if(strlen($tmp)<1)
			continue;
		$xname=strtok($tmp, "<");
		$xtel=strtok(">");
		if($q++%2==0)
			$bg="bgcolor=\"#FAFAFA\"";
		else
			$bg="";
		
		$ret=1;
		$xtel=trim($xtel);
        if(strlen($xtel) != 11)
			$ret=-1;
        $net=substr($xtel,0,4);
        if(($net!="6012")&&($net!="6013")&&($net!="6016")&&($net!="6017")&&($net!="6019")&&($net!="6014")&&($net!="6018")&&($net!="6010"))
			$ret=-1;
		
		if($ret){
			$xm=addslashes($msg);
			$sql="insert into sms_log(dt,sid,app,typ,tel,msg,des,adm,ts)values(now(),$admsid,'COMPOSE','MT','$xtel','$xm','$to','$adm',now())";
			$res3=mysql_query($sql)or die("$sql - failed:".mysql_error());
			$xid=mysql_insert_id($link);
			$xmsg="$xgatekey - $msg";
			if($ON_XGATE)
				$ret=xgate_send_sms($xgateip,$xgateport,$xgateuser,$xgatepass,$xgategw,$xtel,$xid,0,0,$xmsg,0,$xgatekey,10);
			else
				$ret=-1;
		}
		
?>
      <tr <?php echo $bg;?>>
        <td><?php echo $q;?></td>
        <td><?php echo $xtel;?></td>
		<td><?php $xn=stripslashes($xname); echo $xn;?></td>
		<td><?php echo "$xg"; ?></td>
        <td align="center"><?php echo $ret;?></td>
      </tr>
	 
<?php }?>       	
</div><!-- end of story -->
</div><!--end of content-->   

</body>
</html>
