<?php
//110610 - upgrade gui
//120609 - reconnect database
$vmod="v6.2.0";
$vdate="120609";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/xgate.php");
include_once("$MYLIB/inc/language_$LG.php");
$adm=$_SESSION['username'];
$msg=$_POST['msg'];
$cblist=$_POST['cblist'];
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
<body style="background:none;">
<form name="myform" action="" method="post">
	<input type="hidden" name="op" value="">

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
<div id="mytitle2">SMS Status</div>
	<table width="100%" cellspacing="0" cellpadding="2">
      <tr>
        	<td class="mytableheader" width="5%" align="center">NO</td>
        	<td class="mytableheader" width="10%">&nbsp;TELEFON</td>
			<td class="mytableheader" width="20%">&nbsp;NAME</td>
			<td class="mytableheader" width="60%">&nbsp;MESSAGE</td>
        	<td class="mytableheader" width="5%" align="center">STATUS</td>
      </tr>
     	
<?php
		for($numberofstudent=0;$numberofstudent<count($cblist);$numberofstudent++){
			$id=$cblist[$numberofstudent];
			$sql="select * from usr where id='$id'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$name=strtoupper(stripslashes($row['name']));
			$hp=$row['hp'];
			$xmsg=addslashes("$xgatekey - $msg");
	
			$ret=1;	
			if(strlen($hp) < 10){
					$ret=0;
					$rets="Invalid Number";
					$sql="insert into sms_log(dt,sid,typ,app,tel,msg,des,adm,ts)values(now(),$sid,'SMS','ESTAFF','$hp','$xmsg','$rets','$adm',now())";
					$res3=mysql_query($sql)or die("$sql - failed:".mysql_error());
			}
			else{
					$rets="New";
					$sql="insert into sms_log(dt,sid,typ,app,tel,msg,des,adm,ts)values(now(),$sid,'SMS','ESTAFF','$hp','$xmsg','$rets','$adm',now())";
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
			}
			
			if($q++%2==0)
				$bg="#FAFAFA";
			else
				$bg="#FAFAFA";
			if($ret!="000")
				$bg=$bglred;
				
			if($q%10==0){
				echo "*";
				$link=mysql_pconnect($DB_HOST,$DB_USER,$DB_PASS)or die("could not connect:".mysql_error());
				$db=mysql_select_db($DB_NAME,$link) or die("setting:".mysql_error());
			}
?>
      	<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
                <td class="myborder" align="center"><?php echo "$q"; ?></td>
                <td class="myborder"><?php echo "$hp"; ?></td>
                <td class="myborder"><?php echo "$name"; ?></td>
                <td class="myborder"><?php echo "$xmsg"; ?></td>
                <td class="myborder" align="center"><?php echo "$rets"; ?></td>
      	</tr>
<?php } ?>       	  
	</table>
</div><!-- story -->

</div>
</form>
</body>
</html>
