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
$warning_message=$_REQUEST['warning_message'];
$stu=$_REQUEST['stu'];
$sid=$_REQUEST['sid'];
$absence=$_REQUEST['absence'];
$year=$_REQUEST['year'];
$ismel=$_REQUEST['ismel'];
$student_class=$_REQUEST['class_name'];
$letterid=$_REQUEST['letterid'];
if($sid=="")
		$sid=$_SESSION['sid'];
		
	
$uid=$_REQUEST['uid'];
if((uid!="")&&(count($stu)==0))
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
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>

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

			function str_replace_nth($search, $replace, $subject, $nth)
			{
				$found = preg_match_all('/'.preg_quote($search).'/', $subject, $matches, PREG_OFFSET_CAPTURE);
				if (false !== $found && $found > $nth) {
					return substr_replace($subject, $replace, $matches[0][$nth][1], strlen($search));
				}
				return $subject;
			}
		
			$sql="select * from stu where uid='$uid'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$sid=$row['sch_id'];
			
			$sql="select * from sch where id='$sid'";
			$res2=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row2=mysql_fetch_assoc($res2);
			$school_name=$row2['name'];
			
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
			$address=str_replace_nth(',', ',<br>', $row['addr'], 1);
			$city=$row['bandar'];
			$poscode=$row['poskod'];
			$state=$row['state'];
			$clssession=$row['clssession'];
			
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$stype=$row['level'];
			$simg=$row['img'];
			
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

	$sql="select * from ses_cls where cls_name='$student_class' and year='$year'";
	$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$class_teacher_name=$row['usr_name'];
	
	
	if($isheader){ 
					include("../inc/school_header.php");
					//include("../inc/header_global.php");
					echo "<div id=myborder></div>";
	}

	$content=str_replace("{Number}",$refno,$content);
	$content=str_replace("{Current_Date}",$system_date,$content);
	$content=str_replace("{Current_Year}",date('Y'),$content);
	$content=str_replace("{School_Name}",$school_name,$content);
	$content=str_replace("{organization_name}",$organization_name,$content);
	
	if($father_name == '' || $father_name =='-' || $father_name == ' '){
		$content=str_replace("{Parents_Name}",ucwords(strtolower($mother_name)),$content);
		$content=str_replace("{Parents_Email}",$mother_mel,$content);
	}else{
		$content=str_replace("{Parents_Name}",ucwords(strtolower($father_name)),$content);
		$content=str_replace("{Parents_Email}",$father_mel,$content);
	}
	
	
	$content=str_replace("{Address}",ucwords(strtolower($address)),$content);
	$content=str_replace("{City}",ucwords($city),$content);
	$content=str_replace("{Postcode}",$poscode,$content);
	$content=str_replace("{State}",ucwords(strtolower($state)),$content);
	$content=str_replace("{Total_Days}",$absence,$content);
	$content=str_replace("{Student_Name}",ucwords(strtolower($student_name)),$content);
	$content=str_replace("{student_ic}",$student_ic,$content);
	$content=str_replace("{Student_Class}",$student_class,$content);
	$content=str_replace("{Matrix_Number}",$uid,$content);
	$content=str_replace("{Class_Teacher_Name}",$class_teacher_name,$content);
	$content=str_replace("{Letter_Message}",$warning_message,$content);
	
	
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
</div>
</form>
</body>
</html>
