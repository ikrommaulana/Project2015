<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once('../inc/xgate.php');

$adm=$_SESSION['username'];

$msg=$_POST['msg'];
$stu=$_POST['stu'];
$sid=$_POST['sid'];
if($sid=="")
		$sid=$_SESSION['sid'];
		
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

<div id="content">
    <div id="mypanel">
    	<div id="mymenu" align="center">
            <a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
                <div id="mymenu_space">&nbsp;&nbsp;</div>
                <div id="mymenu_seperator"></div>
                <div id="mymenu_space">&nbsp;&nbsp;</div>
            <a href="#" onClick="window.close();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
                <div id="mymenu_space">&nbsp;&nbsp;</div>
                <div id="mymenu_seperator"></div>
                <div id="mymenu_space">&nbsp;&nbsp;</div>
        </div> <!-- end mymenu -->
        <div align="right">
            <a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
        </div>
    </div>
<div id="story">
<div id="story_title">STATUS BROADCAST SMS</div>
	<table width="100%" cellspacing="0">
      <tr id="mytabletitle">
        <td width="5%" align="center">BIL</td>
        <td width="10%">TELEFON</td>
		<td width="20%">NAME</td>
		<td width="60%">MESSAGE</td>
        <td width="5%" align="center">STATUS</td>
      </tr>
     	
<?php
		for($numberofstudent=0;$numberofstudent<count($stu);$numberofstudent++){
			$id=$stu[$numberofstudent];
			$sql="select * from stureg where id='$id'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$name=strtoupper(stripslashes($row['name']));
			$hp=$row['hp'];
			$xmsg=addslashes("$xgatekey - $msg");
	
			$ret=1;	

			if((strlen($hp) < 10)||($xgateuser==""))
				$ret=0;

			if($ret){
				$sql="insert into sms_log(dt,sid,app,typ,tel,msg,des,adm,ts)values(now(),$sid,'EREGISTER','MT','$hp','$xmsg','BROADCAST','$adm',now())";
				$res3=mysql_query($sql)or die("$sql - failed:".mysql_error());
				$xid=mysql_insert_id($link);
				$ret=xgate_send_sms($xgateip,$xgateport,$xgateuser,$xgatepass,$xgategw,$hp,$xid,0,0,$xmsg,0,$xgatekey,10);
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
				
			if($q%10==0){
				echo "*";
				$link=mysql_pconnect($DB_HOST,$DB_USER,$DB_PASS)or die("could not connect:".mysql_error());
				$db=mysql_select_db($DB_NAME,$link) or die("setting:".mysql_error());
			}
?>
      	<tr <?php echo "$bg"; ?>>
			<td id="myborder" align="center"><?php echo "$q"; ?></td>
			<td id="myborder"><?php echo "$hp"; ?></td>
			<td id="myborder"><?php echo "$name"; ?></td>
			<td id="myborder"><?php echo "$xmsg"; ?></td>
			<td id="myborder" align="center"><?php echo "$rets"; ?></td>
      	</tr>
	 
<?php } ?>       	  
	</table>
</div><!-- story -->

</div>
</body>
</html>
