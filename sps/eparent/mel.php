<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
require_once ("$MYOBJ/phpmailer/class.phpmailer.php");
verify('ADMIN|AKADEMIK|HEP');
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
<div id="story_title">STATUS BROADCAST EMAIL</div>
	<table width="100%" cellpadding="2" cellspacing="0">
      <tr id="mytabletitle">
        <td width="5%" align="center">NO</td>
        <td width="20%">NAME</td>
		<td width="70%">EMAIL</td>
        <td width="5%" align="center">STATUS</td>
      </tr>
<?php
		for($numberofstudent=0;$numberofstudent<count($cblist);$numberofstudent++){
			$id=$cblist[$numberofstudent];
			$sql="select * from stu where id='$id'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$name=strtoupper(stripslashes($row['p1name']));
			$mel=$row['p1mel'];
			
			$mail = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch
			try {
					if($MEL_ISSMTP){
							$mail->IsSMTP(); // enable SMTP
							$mail->SMTPAuth = true;  // authentication enabled
							//$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
							//$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
							$mail->Host = 'awfatech.net';
							$mail->Port = 587; 
							$mail->Username = "support@awfatech.net";  
							$mail->Password = "awfatech.net@2003"; 
					} 
					$mail->AddReplyTo($MEL_REPLY,$organization_name);
					$mail->AddAddress($mel);
					$mail->SetFrom($MEL_REPLY,$organization_name);
					$mail->Subject = $organization_name." - ". $melsub;
					$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate auto
					$header="<div style=\"font-size:18px; font-family:'Palatino Linotype';background-color:#FAFAFA\" align=center>
								<br><strong>$organization_name</strong>
								<font style=\"font-size:13px\">
								<br>$GLO_ADD<br>Tel:$GLO_TEL&nbsp;&nbsp;Fax:$GLO_FAX&nbsp;&nbsp;Http://$GLO_WEB
								</font><br><br>
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
			<td align="center" id="myborder"><?php echo "$q"; ?></td>
	        <td id="myborder"><?php echo "$name"; ?></td>
			<td id="myborder"><?php echo "$mel"; ?></td>
			<td id="myborder" align="center"><?php echo "$rets"; ?></td>
      	</tr>
	 
<?php } ?>       	  
	</table>
</div><!-- story -->

</div>
</form>
</body>
</html>
