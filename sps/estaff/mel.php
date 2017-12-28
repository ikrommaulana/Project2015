<?php
//110610 - upgrade gui
$vmod="v6.0.0";
$vdate="110610";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
require_once ("$MYOBJ/phpmailer/class.phpmailer.php");
$adm=$_SESSION['username'];
$melsub=$_POST['melsub'];
$melmsg=$_POST['melmsg'];
$cblist=$_POST['cblist'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Email</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>
<body style="background:none">
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
<div id="mytitle2">Email Status</div>
	<table width="100%" cellpadding="2" cellspacing="0">
      <tr>
        <td class="mytableheader" width="5%" align="center">NO</td>
        <td class="mytableheader" width="20%">&nbsp;NAME</td>
		<td class="mytableheader" width="70%">&nbsp;EMAIL</td>
        <td class="mytableheader" width="5%" align="center">STATUS</td>
      </tr>
<?php
		for($numberofstudent=0;$numberofstudent<count($cblist);$numberofstudent++){
			$id=$cblist[$numberofstudent];
			$sql="select * from usr where id='$id'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$name=strtoupper(stripslashes($row['name']));
			$mel=$row['mel'];
			$sid=$row['sch_id'];
			
			$mail = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch
			try {
					if($MEL_ISSMTP){
							$mail->IsSMTP(); // enable SMTP
							$mail->SMTPAuth = true;  // authentication enabled
							//$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
							$mail->SMTPDebug = 1;  // debugging: 1 = errors and messages, 2 = messages only
							$mail->Host = 'awfatech.net';
							$mail->Port = 587; 
							$mail->Username = "support@awfatech.net";  
							$mail->Password = "awfatech@2003"; 
					} 
					$mail->AddAddress($mel);
					$mail->SetFrom($MEL_REPLY,$MEL_REPLY_NAME);
					$mail->Subject = $melsub;
					$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate auto
					$header="<div style=\"font-size:16px; font-family:'Palatino Linotype';\" align=center>
								<img src=\"$GLO_LOGO\" alt=\"Organization Logo\">
								<br><strong>$organization_name</strong>
								<font style=\"font-size:13px\">
								<br>$GLO_ADD<br>Tel:$GLO_TEL&nbsp;&nbsp;Fax:$GLO_FAX&nbsp;&nbsp;Http://$GLO_WEB
								</font>
								<div style=\"border:1px solid #F1F1F1\"></div>
							</div>";
					
					$mail->MsgHTML("$header.$melmsg");
					
					$fn=basename($_FILES['file1']['name']);
					if($fn!=""){
						$target_path ="../tmp/$adm".time()."$fn";
						if(move_uploaded_file($_FILES['file1']['tmp_name'], $target_path))
							$mail->AddAttachment($target_path, $fn);
					}
					$fn=basename($_FILES['file2']['name']);
					if($fn!=""){
						$target_path ="../tmp/$adm".time()."$fn";
						if(move_uploaded_file($_FILES['file2']['tmp_name'], $target_path))
							$mail->AddAttachment($target_path, $fn);
					}
					$fn=basename($_FILES['file3']['name']);
					if($fn!=""){
						$target_path ="../tmp/$adm".time()."$fn";
						if(move_uploaded_file($_FILES['file3']['tmp_name'], $target_path))
							$mail->AddAttachment($target_path, $fn);
					}
					$mail->Send();
					$ret=1;
			} 
			catch (phpmailerException $e) {
					$ret=0;
			} 
			catch (Exception $e) {
					$ret=0;
			}
			if($ret==0){
				$rets="Reject";
				$sql="insert into sms_log(dt,sid,typ,app,tel,msg,des,adm,ts)values(now(),$sid,'EMAIL','ESTAFF','$mel','$melmsg','$rets','$adm',now())";
				$res3=mysql_query($sql)or die("$sql - failed:".mysql_error());
			}
			elseif($ret==1){
				$rets="Sent";
				$sql="insert into sms_log(dt,sid,typ,app,tel,msg,des,adm,ts)values(now(),$sid,'EMAIL','ESTAFF','$mel','$melmsg','$rets','$adm',now())";
				$res3=mysql_query($sql)or die("$sql - failed:".mysql_error());
			}
			else{
				$rets="Undeliver";
				$sql="insert into sms_log(dt,sid,typ,app,tel,msg,des,adm,ts)values(now(),$sid,'EMAIL','ESTAFF','$mel','$melmsg','$rets','$adm',now())";
				$res3=mysql_query($sql)or die("$sql - failed:".mysql_error());
			}
			
			if($q++%2==0)
				$bg="bgcolor=#FAFAFA";
			else
				$bg="bgcolor=#FAFAFA";
			if($ret!=1)
				$bg="bgcolor=$bglred";
?>
      	<tr <?php echo "$bg"; ?>>
			<td class="myborder" align="center"><?php echo "$q"; ?></td>
	        <td class="myborder"><?php echo "$name"; ?></td>
			<td class="myborder"><?php echo "$mel"; ?></td>
			<td class="myborder" align="center"><?php echo "$rets"; ?></td>
      	</tr>
<?php } ?>       	  
	</table>
</div><!-- story -->

</div>
</body>
</html>
