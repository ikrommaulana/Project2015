<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/xgate.php");
include_once("$MYLIB/inc/language_$LG.php");

//verify('ADMIN|CEO');
$adm=$_SESSION['username'];
$msg=$_POST['msg'];
$cblist=$_POST['cblist'];
$sid=$_POST['sid'];
if($sid=="")
		$sid=$_SESSION['sid'];

$hp1=$_POST['father'];
$hp2=$_POST['mother'];
if($hp1!="" && $hp2!=""){
$countsms=2;
$phone=array($hp1,$hp2);
}elseif($hp1=="" && $hp2!=""){
$countsms=1;
$phone=array($hp2);
}elseif($hp1!="" && $hp2==""){
$countsms=1;
$phone=array($hp1);
}
		
if($SMS_BILL_BYSCHOOL){
	$sql="select * from type where grp='sms_setting' and sid=$sid";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$xgateuser=$row['prm'];
	$xgatepass=$row['code'];
	$xgatekey=$row['etc'];
}
	
		
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
<form name="myform" action="" method="post">
	<input type="hidden" name="op" value="">

<div id="content">
	<div id="mypanel">
		
	</div>
<div id="story">
<div id="story_title">STATUS BROADCAST SMS</div>
	<table width="100%" cellspacing="0">
      <tr id="mytabletitle">
        <td width="5%" align="center">BIL</td>
     		<?php 
		if($hp1!="" && $hp2!=""){
?>
<td width="10%">FATHER PHONE #</td>
<td width="10%">FATHER NAME</td>
<td width="10%">MOTHER PHONE #</td>
<td width="10%">MOTHER NAME</td>
<?php
}elseif($hp1=="" && $hp2!=""){
?>
<td width="10%">MOTHER PHONE #</td>
<td width="20%">MOTHER NAME</td>
<?php
}elseif($hp1!="" && $hp2==""){
?>
<td width="10%">FATHER PHONE #</td>
<td width="20%">FATHER NAME</td>
<?php
}
?>
		<td width="60%">MESSAGE</td>
        <td width="5%" align="center">STATUS</td>
      </tr>
     	
<?php
		for($numberofstudent=0;$numberofstudent<count($cblist);$numberofstudent++){
			$id=$cblist[$numberofstudent];
			$sql="select * from stu where id='$id'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$p1name=strtoupper(stripslashes($row['p1name']));
			$p2name=strtoupper(stripslashes($row['p2name']));
			$p1hp=$row['p1hp'];
			$p2hp=$row['p2hp'];
			$xmsg=addslashes("$xgatekey - $msg");
	
			$ret=1;	

			if( (strlen($p1hp) < 10) || (strlen($p2hp) < 10) ){
				$ret=0;
					$rets="Invalid Number";
			}else{
					for($a=0;$a<$countsms;$a++){
					if("p1hp" == $phone[$a]){
						$hp=$p1hp;
					}
					if("p2hp" == $phone[$a]){
						$hp=$p2hp;
					}
					$rets="New";
					$sql="insert into sms_log(dt,sid,typ,app,tel,msg,des,adm,ts)values(now(),$sid,'SMS','ORANGTUA','$hp','$xmsg','$rets','$adm',now())";
					$res3=mysql_query($sql)or die("$sql - failed:".mysql_error());
					$mid=mysql_insert_id();
					//echo "SSS:$mid";
					$ret=xgate_send_sms($xgateip,$xgateport,$xgateuser,$xgatepass,$xgategw,$hp,$mid,0,0,$xmsg,0,$xgatekey,10);
					
					if($ret=="001"){
						$rets="Reject";
						$sql="update sms_log set des ='$rets',ts=now() where id='$mid'";
						$res3=mysql_query($sql)or die("$sql - failed:".mysql_error());
					}
					elseif($ret=="000"){
						$rets="Sent";
						$sql="update sms_log set des ='$rets',ts=now() where id='$mid'";
						$res3=mysql_query($sql)or die("$sql - failed:".mysql_error());
					}
					elseif($ret=="101"){
						$rets="Expired";
						$sql="update sms_log set des ='$rets',ts=now() where id='$mid'";
						$res3=mysql_query($sql)or die("$sql - failed:".mysql_error());
					}
					else{
						$rets="Undeliver";
						$sql="update sms_log set des ='$rets',ts=now() where id='$mid'";
						$res3=mysql_query($sql)or die("$sql - failed:".mysql_error());
					}
					} // end for($a=0
			}
			
			if($q++%2==0)
				$bg="bgcolor=#FAFAFA";
			else
				$bg="bgcolor=#FAFAFA";
			if($ret!="000")
				$bg="bgcolor=$bglred";
				
			if($q%10==0){
				echo "*";
				$link=mysql_pconnect($DB_HOST,$DB_USER,$DB_PASS)or die("could not connect:".mysql_error());
				$db=mysql_select_db($DB_NAME,$link) or die("setting:".mysql_error());
			}
?>
      	<tr <?php echo "$bg"; ?>>
			<td id="myborder" align="center"><?php echo "$q"; ?></td>
					<?php 
		if($hp1!="" && $hp2!=""){
?>
<td id="myborder"><?php echo $p1hp;?></td>
<td id="myborder"><?php echo $p1name;?></td>
<td id="myborder"><?php echo $p2hp;?></td>
<td id="myborder"><?php echo $p2name;?></td>
<?php
}elseif($hp1=="" && $hp2!=""){
?>
<td id="myborder"><?php echo $p2hp;?></td>
<td id="myborder"><?php echo $p2name;?></td>
<?php
}elseif($hp1!="" && $hp2==""){
?>
<td id="myborder"><?php echo $p1hp;?></td>
<td id="myborder"><?php echo $p1name;?></td>
<?php
}
?>
			<td id="myborder"><?php echo "$xmsg"; ?></td>
			<td id="myborder" align="center"><?php echo "$rets"; ?></td>
      	</tr>
	 
<?php } ?>       	  
	</table>
</div><!-- story -->

</div>
</form>
</body>
</html>
