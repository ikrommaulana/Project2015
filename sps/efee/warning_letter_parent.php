 <?php
//10/05/2010 - repair header
//13/04/2010 - new loaded
$vmod="v5.3.3";
$vdate="10/05/2010";
include_once('../etc/db.php');
include_once('../etc/session.php');
include("$MYOBJ/fckeditor/fckeditor.php") ;
require_once ("$MYOBJ/phpmailer/class.phpmailer.php");
include_once('../inc/xgate.php');

	verify('ADMIN|AKADEMIK|KEUANGAN|HEP|HR|HEP-OPERATOR');
	$adm=$_SESSION['username'];
	$stu=$_REQUEST['checker'];
	$sid=$_REQUEST['sid'];
	$op=$_REQUEST['op'];
	$month=$_REQUEST['month'];
	$year=$_REQUEST['year'];
	$letterid=$_REQUEST['letterid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
		
			
		
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<script language="JavaScript">
<!--
function process_save(op){
		ret = confirm("Are you sure want to save??");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
		return;
}
//-->
</script>
</head>
<body style="background:none;">
<form name="myform" action="" method="post">
	<input type="hidden" name="id" value="<?php echo $id;?>" >
	<input type="hidden" name="op" value="">

<div id="content">
	<div id="mypanel">
		<div id="mymenu" align="center">
			<a href="letter_config.php?id=<?php echo $letterid;?>" id="mymenuitem"><img src="../img/option.png"><br>Configure</a>
			<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
			<a href="#" onClick="window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
		</div>
		<div align="right">
		<br>
		&nbsp;&nbsp;
	 	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
		</div>
	</div>

<?php
		$totalpage=count($stu);
		for($numberofstudent=0;$numberofstudent<count($stu);$numberofstudent++){
			$pageno++;
			$uid=$stu[$numberofstudent];
			$pp1ic=$stu[$numberofstudent];

			//$sql="select * from stu where uid='$uid'";
			$sql="select * from stu where p1ic='$pp1ic' and sch_id=$sid and status=6";
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
            $sname=stripslashes($row['name']);
			$stype=$row['level'];
			$simg=$row['img'];
			
			$sql="select * from letter where id='$letterid'";
			$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$content=$row['content'];
			$isheader=$row['isheader'];
			$content=stripslashes($content);
			$j=0;$rm=0;$totalrm=0;
							
					$content_report="<table width=\"90%\" cellspacing=0>
							<tr>
								<td bgcolor=#F1F1F1 width=\"10%\" style=\"border:1px solid #F1F1F1\" align=center>No</td>
								<td bgcolor=#F1F1F1 width=\"50%\" style=\"border:1px solid #F1F1F1\">Student</td>
								<td bgcolor=#F1F1F1 width=\"30%\" style=\"border:1px solid #F1F1F1\">Fees Information</td>
								
								<td bgcolor=#F1F1F1 width=\"10%\" style=\"border:1px solid #F1F1F1\" align=center>Amount (RP)</td>
							</tr>";
							
			$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where stu.sch_id=$sid $sqlstudent and p1ic='$pp1ic' and ses_stu.year='$year'";
			$res4=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			while($row4=mysql_fetch_assoc($res4)){
					$uid=$row4['uid'];
					$stuname=stripslashes($row4['name']);
			
					$sql="select feestu.*,type.code from feestu INNER JOIN type ON feestu.fee=type.prm where feestu.sta=0 and feestu.val>0 and feestu.ses=$year and feestu.uid='$uid' and feestu.sid='$sid' and type.grp='yuran' and type.code<=$month and type.grp='yuran' order by type.code";
					$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
						$feename=$row['fee'];
						$rm=number_format($row['val'],2,'.',',');
						$totalrm=$totalrm+$rm;
						$j++;
						$content_report=$content_report."<tr>
							<td id=myborder align=center style=\"border:1px solid #F1F1F1\">$j</td>
							<td id=myborder style=\"border:1px solid #F1F1F1\">$uid : $stuname</td>
							<td id=myborder style=\"border:1px solid #F1F1F1\">$year-$feename</td>
							<td id=myborder align=right style=\"border:1px solid #F1F1F1\">$rm</td>
							</tr>";
					}
					
			}
			$totalrm=number_format($totalrm,2,'.',',');
					$content_report=$content_report."<tr>
							<td bgcolor=#F1F1F1 style=\"border:1px solid #F1F1F1\" align=center>&nbsp;</td>
							<td bgcolor=#F1F1F1 style=\"border:1px solid #F1F1F1\" align=center colspan=2>Total Amount (RP)</td>
							<td bgcolor=#F1F1F1 style=\"border:1px solid #F1F1F1\" align=right>$totalrm</td>
							</tr></table>";
?>
<div id="story" style="font-size:120%; border:none">
<div id="mytitlebg" class="printhidden" style="color:#CCCCCC" align="right">PAGE <?php echo "$pageno/$totalpage";?></div>
<div id="letter" style="display:block; border:none; padding:10px 10px 10px 10px">
<?php 
if(($op=="surat")||($op=="email")){
	if($isheader){ 
				include("../inc/school_header.php"); 
				echo "<div id=myborder></div>";
	}
}
?>
<?php 
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
	$content=str_replace("{student_ic}",$student_id,$content);
	$content=str_replace("{student_class}",$student_class,$content);
	$content=str_replace("{content_report}",$content_report,$content);
	if(($op=="surat")||($op=="email")){
		echo $content;
	}
	/** SEND EMAIL **/
?> 
	<div id="tipsdiv" style="background-color:#FAFAFA">
<?php
			$ret=0;
	if($op=="email"){
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
					$mail->Subject = $organization_name." - ". "Fees Statement";
					$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate auto
							
					$mail->MsgHTML($content);
					if(!$mail->Send())
						echo 'Mailer error: ' . $mail->ErrorInfo;
					else
						echo "Send Email to: $contact_mel &nbsp;&nbsp;";
					
			}else{
						echo "No email address set &nbsp;&nbsp;";
			}
}
if($op=="sms"){
	if($ON_XGATE){
			$xmsg="$xgatekey : Tunggakan yuran pelajar dibawah jagaan tuan ($father_name) berjumlah RP$totalrm . Sila hubungi pihak sekolah untuk maklumat lanjut. Sebarang kesulitan amatlah dikesali. TQ";
			if(strlen($contact_hp)>9){
				$sql="insert into sms_log(dt,sid,app,typ,tel,msg,des,adm,ts)values(now(),$sid,'FEE OUTSTANDING','MT','$contact_hp','$xmsg','BROADCAST','$adm',now())";
				$res3=mysql_query($sql)or die("$sql - failed:".mysql_error());
				$xid=mysql_insert_id($link);
				$ret=xgate_send_sms($xgateip,$xgateport,$xgateuser,$xgatepass,$xgategw,$contact_hp,$xid,0,0,$xmsg,0,$xgatekey,10);
				echo "Send SMS to: $contact_hp";
			}
	}else{
			echo "SMS NOT ACZTIVATED. PLEASE CALL AWFATECH SUPPORT. TQ";
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
