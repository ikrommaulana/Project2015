<?php
//10/05/2010 - repair header
//13/04/2010 - new loaded
$vmod="v6.0.0";
$vdate="111205";
include_once('../etc/db.php');
include_once('../etc/session.php');
require_once ("$MYOBJ/phpmailer/class.phpmailer.php");
include_once('../inc/xgate.php');

$adm=$_SESSION['username'];
$stu=$_REQUEST['stu'];
$sid=$_REQUEST['sid'];
$ismel=$_REQUEST['ismel'];
$letterid=$_REQUEST['letterid'];
if($sid=="")
		$sid=$_SESSION['sid'];
		
		
$uid=$_REQUEST['uid'];
if(uid!="")
	$stu[0]=$uid;
	
$lid=$_REQUEST['lid'];

if($lid!=""){
			$sql="select * from letter where name='$lid'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$letterid=$row['id'];
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
	<input type="hidden" name="id" value="<?php echo $id;?>" >
	<input type="hidden" name="op" value="">

<div id="content">
	<div id="mypanel">
		<div id="mymenu" align="center">
			<a href="../adm/letter_config.php?id=<?php echo $letterid;?>" id="mymenuitem"><img src="../img/option.png"><br>Configure</a>
			<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
			<a href="#" onClick="window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
		</div>
		<div align="right">
		 	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
		</div>
	</div>

<?php
		$totalpage=count($stu);
		for($numberofstudent=0;$numberofstudent<count($stu);$numberofstudent++){
			$pageno++;
			$uid=$stu[$numberofstudent];
			$sql="select * from stu where uid='$uid'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$sid=$row['sch_id'];
			$student_matric=$row['uid'];

			
			$system_date=date('j F Y');
			
			$student_name=stripslashes($row['name']);
			$student_ic=$row['ic'];
			$contact_hp=$row['hp'];
			$contact_mel=$row['mel'];
			
			$refno=stripslashes($row['id']);
			$father_name=stripslashes($row['p1name']);
			$father_ic=stripslashes($row['p1ic']);
			$father_hp=stripslashes($row['p1hp']);
			$father_mel=stripslashes($row['p1mel']);
			$mother_name=stripslashes($row['p2name']);
			$mother_ic=stripslashes($row['p2ic']);
			$mother_hp=stripslashes($row['p2hp']);
			$mother_mel=stripslashes($row['p2mel']);
			$address=str_replace(",",",<br>",$row['addr']);
			$city=$row['bandar'];
			$poscode=$row['poskod'];
			$state=$row['state'];
			
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$stype=$row['level'];
			$simg=$row['img'];
			$school_name=$row['name'];
			
			$y=date('Y');
			$sql="select * from ses_stu where stu_uid='$uid' and year='$y'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $student_clslevel=$row['cls_level'];
			$student_clsname=$row['cls_name'];
			
			$sql="select * from letter where id='$letterid'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$content=$row['content'];
			$isheader=$row['isheader'];
			$content=stripslashes($content);
?>
<div id="story" style="font-size:120%; border:none">
<div id="mytitlebg" class="printhidden" style="color:#CCCCCC" align="right">PAGE <?php echo "$pageno/$totalpage";?></div>
<div id="letter" style="display:block; border:none; padding:10px 10px 10px 10px">
<?php 
	
	if($isheader){ 
					include("../inc/school_header.php");
					//include("../inc/header_global.php");
					echo "<div id=myborder></div>";
	}

	$content=str_replace("{refno}",$refno,$content);
	$content=str_replace("{system_date}",$system_date,$content);
	$content=str_replace("{school_name}",$school_name,$content);
	$content=str_replace("{organization_name}",$organization_name,$content);
	
	$content=str_replace("{father_name}",$father_name,$content);
	$content=str_replace("{father_ic}",$father_ic,$content);
	$content=str_replace("{father_hp}",$father_hp,$content);
	$content=str_replace("{father_mel}",$father_mel,$content);
	
	$content=str_replace("{mother_name}",$mother_name,$content);
	$content=str_replace("{mother_ic}",$mother_ic,$content);
	$content=str_replace("{mother_hp}",$mother_hp,$content);
	$content=str_replace("{mother_mel}",$mother_mel,$content);
	
	$content=str_replace("{address}",$address,$content);
	$content=str_replace("{city}",$city,$content);
	$content=str_replace("{poscode}",$poscode,$content);
	$content=str_replace("{state}",$state,$content);
	
	$content=str_replace("{student_name}",$student_name,$content);
	$content=str_replace("{student_ic}",$student_ic,$content);
	$content=str_replace("{student_matric}",$student_matric,$content);
	$content=str_replace("{student_class}",$student_class,$content);
	$content=str_replace("{content_report}",$content_report,$content);
	
	$content=str_replace("{student_clslevel}",$student_clslevel,$content);
	$content=str_replace("{student_clsname}",$student_clsname,$content);

	
	echo $content;
	
	/** SEND EMAIL **/
?> 
	<div id="tipsdiv" style="background-color:#FAFAFA">
<?php
	$ret=0;
	if($ismel==1){
			if($contact_mel!=""){
					$mail = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch
					if($MEL_ISSMTP){
									$mail->IsSMTP(); // enable SMTP
									$mail->SMTPAuth = true;  // authentication enabled
									//$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
									$mail->SMTPDebug = 0;  // debugging: 1 = errors and messages, 2 = messages only
									$mail->Host = 'awfatech.net';
									$mail->Port = 587; 
									$mail->Username = "support@awfatech.net";  
									$mail->Password = "awfatech@2003"; 
					} 
					$mail->AddReplyTo($MEL_REPLY,$organization_name);
					if($contact_mel!="")
								$mail->AddAddress($contact_mel);
								
					$mail->SetFrom($MEL_REPLY,$organization_name);
					$mail->Subject = $organization_name." - ". "Status Permohonan";
					$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate auto
					
					$header="<div style=\"font-size:16px; font-family:'Palatino Linotype';\" align=center>
								<img src=\"$GLO_LOGO\" alt=\"Organization Logo\">
								<br><strong>$organization_name</strong>
								<font style=\"font-size:13px\">
								<br>$GLO_ADD<br>Tel:$GLO_TEL&nbsp;&nbsp;Fax:$GLO_FAX&nbsp;&nbsp;Http://$GLO_WEB
								</font>
								<div style=\"border:1px solid #F1F1F1\"></div>
							</div>";
					
		
					$mail->MsgHTML("$header.$content");
					if(!$mail->Send())
						echo 'Mailer error: ' . $mail->ErrorInfo;
					else
						echo "Send Email to: $contact_mel &nbsp;&nbsp;";
					
			}else{
						echo "No email address set &nbsp;&nbsp;";
			}
}


?>
</div><!-- mel alert -->
</div>
</div><!-- story -->

<?php if($pageno!=$totalpage){?>
<div style="page-break-after:always"></div>
<?php } ?>
<?php } ?>
</div>
</form>
</body>
</html>
