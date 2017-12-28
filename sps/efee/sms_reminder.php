 <?php
//10/05/2010 - repair header
//13/04/2010 - new loaded
//120609 - send sms
$vmod="v6.2.0";
$vdate="120609";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
include_once("$MYLIB/inc/xgate.php");
verify('ADMIN|KEUANGAN');
	$adm=$_SESSION['username'];
	$stu=$_REQUEST['studentout'];
	$checker=$_REQUEST['checker'];
	$sid=$_REQUEST['sid'];
	$op=$_REQUEST['op'];
	$smsmsg=addslashes($_REQUEST['smsmsg']);
	if($sid=="")
		$sid=$_SESSION['sid'];
		
	
	if($op=='save'){
		$sql="delete from type where grp='sms_fee_reminder' and sid='$sid'";
		mysql_query($sql,$link)or die("query failed:".mysql_error());
		$sql="insert into type (grp,des,sid) values ('sms_fee_reminder','$smsmsg',$sid)"; 	
		mysql_query($sql,$link)or die("query failed:".mysql_error());
	}
	
	$sql="select * from type where grp='sms_fee_reminder' and sid='$sid'";
	$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$msgtemplate=stripslashes($row['des']);
	if($msgtemplate=="")
		$msgtemplate="Anak/Jagaan tuan \"{STUDENT}\" mempunyai tunggakan yuran RP{TOTAL}. Adalah diharap pihak tuan dapat menjelaskan tunggakan ini segera. Perjalanan operasi sekolah ini adalah amat bergantung kepada kutipan yuran. Jika pembayaran telah dibuat sila hubungi sekolah untuk mengemaskini akaun anda. Segala kesulitan amatlah dikesali. Terima Kasih";
		
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

function process_form(op){
	if(op=='sendsms'){
		ret = confirm("Send this reminder??");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
	}else{
		ret = confirm("Save this information??");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
	}
		return;
}


function kira(field,countfield,maxlimit){
        var y=field.value.length+1;
        if(y>=maxlimit){
                field.value=field.value.substring(0,maxlimit);
                alert("1000 maximum character..");
                return true;
        }else{
				xx=maxlimit-y;
                countfield.value=xx;
        }
}
</script>
</head>
<body style="background:none;">
<script type="text/javascript" src="<?php echo $MYOBJ;?>/wz_tooltip531/wz_tooltip.js"></script>
<form name="myform" action="" method="post">
	<input type="hidden" name="sid" value="<?php echo $sid;?>" >
	<input type="hidden" name="op">

<div id="content">
	<div id="mypanel">
		<div id="mymenu" align="center">
        	<a href="#" onClick="showhide('sms','')" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/edit22.png"><br>Template</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
			<a href="#" onClick="window.print()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br>Print</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
			<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br>Refresh</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
			<a href="#" onClick="window.close()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/close.png"><br>Close</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
		</div>
		<div align="right">
	 	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
		</div>
	</div>
<div id="story">
<div id="sms" style="display:none">
<div id="mytitle2">SMS Template</div>
<font style="color:#00F";>
Note:<br>
You can change the strukture of message. {WORD} is "Reserve Word". Do not change. 
</font><br>
			<textarea name="smsmsg" style="width:80%; font-size:18px; color:#0C0; font-weight:bold;" rows="6" id="smsmsg" 
            onkeypress="kira(this,this.form.jum,1000);"><?php echo $msgtemplate;?></textarea>
            <br>
            <input type="text" name="jum" value="1000" size="4" style="border:none; font-size:16px" onBlur="kira(this.form.jum,this,1000);" disabled>
            <input type="button" value="Save Template" onClick="process_form('save');">
<br><br>

</div>
<div id="mytitle2">SMS Reminder</div>
<?php 
if(!$ON_XGATE){
	echo "<font color=red size=2><b>SMS NOT ACTIVATED. PLEASE CALL AWFATECH SUPPORT. TQ</b></font>";
}elseif($op!="sendsms"){
?>
<div>
<input type="button" value="Click to Confirm and Send This SMS.." onClick="process_form('sendsms');" style="font-size:14px; font-weight:bold;">
</div>
<?php } ?>

<table width="100%" cellspacing="0" cellpadding="2">
	<tr>
         	<td  class="mytableheader" style="border-right:none;" width="2%" align="center">NO</td>
			<td  class="mytableheader" style="border-right:none;" width="5%" align="center">MATRIC</td>
			<td  class="mytableheader" style="border-right:none;" width="1%" align="center">PREVIEW</td>
			<td  class="mytableheader" style="border-right:none;" width="40%">NAME</td>
            <td  class="mytableheader" style="border-right:none;" width="10%">TELEPHONE</td>
			<td  class="mytableheader" style="border-right:none;" width="10%" align="center">AMOUNT</td>
            <td  class="mytableheader" style="border-right:none;" width="5%" align="center">STATUS</td>
	</tr>
	
<?php
		for($i=0;$i<count($stu);$i++){
				list($uid,$rm)=explode("|",$stu[$i]);
				$FOUND=0;
				for($j=0;$j<count($checker);$j++){
					list($xuid,$xrm)=explode("|",$checker[$j]);
					if($uid==$xuid){
						$FOUND=1;
						break;
					}
				}
				if(!$FOUND)
					continue;
				
				$sql="select * from stu where uid='$uid'";
				$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
				$row=mysql_fetch_assoc($res);
				$name=stripslashes($row['name']);
				$ic=$row['ic'];
				$hp=$row['hp'];
				$mel=$row['mel'];
				
			if(($q++%2)==0)
				$bg="";
			else
				$bg="";
				
				$xname=addslashes($name);
				$msg=$msgtemplate;
				$msg=str_replace("{STUDENT}",$xname,$msg);
				$msg=str_replace("{TOTAL}",$rm,$msg);
				$tips="";$j=1;
				$tok=strtok($msg," \n\t");
				while ($tok != false){
						if(($j++%8)==0)
							$tips=$tips."<br>".$tok;
						else
							$tips=$tips." ".$tok;
						$tok = strtok(" \n\t");
				}
				$tips=str_replace("\"","",$tips);
				$tips=str_replace("'"," ",$tips);
				if($op=="sendsms"){
						$xmsg=addslashes($msg);
						$ret=1;	
						if(strlen($hp) < 10)
								$ret=0;
						if($ret){
								$sql="insert into sms_log(dt,sid,app,typ,tel,msg,des,adm,ts)values(now(),$sid,'FEE','MT','$hp','$xmsg','FEE REMINDER','$adm',now())";
								$res3=mysql_query($sql)or die("$sql - failed:".mysql_error());
								$xid=mysql_insert_id($link);
								$ret=xgate_send_sms($xgateip,$xgateport,$xgateuser,$xgatepass,$xgategw,$hp,$xid,0,0,$msg,0,$xgatekey,10);
								//echo "xgate_send_sms($xgateip,$xgateport,$xgateuser,$xgatepass,$xgategw,$hp,$xid,0,0,$msg,0,$xgatekey,10)";
						}
						if($ret==0)
							$rets="Reject";
						elseif($ret==1)
							$rets="Sent";
						else
							$rets="Undeliver";
						
						if($ret!=1)
							$bg="$bglred";
							
						if($q%10==0){
							echo "*";
							$link=mysql_pconnect($DB_HOST,$DB_USER,$DB_PASS)or die("could not connect:".mysql_error());
							$db=mysql_select_db($DB_NAME,$link) or die("setting:".mysql_error());
						}
					
					
				}
?>
		<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
              	<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$q";?></td>
			  	<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$uid";?></td>
				<td class="myborder" style="border-right:none; border-top:none;" align="center"><a href="#" onMouseOver="Tip('<?php echo $tips;?>');" onMouseOut="UnTip()"><img src="<?php echo $MYLIB;?>/img/dialog16.png" height="12px" width="12px"></a></td>
			  	<td class="myborder" style="border-right:none; border-top:none;"><?php echo "$name";?></td>
                <td class="myborder" style="border-right:none; border-top:none;"><?php echo "$hp";?></td>
				<td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$rm";?></td>
                <td class="myborder" style="border-right:none; border-top:none;" align="center"><?php echo "$rets";?></td>
				<input name="studentout[]" type="hidden"  value="<?php echo "$uid|$rm";?>">
				<input name="checker[]" type="hidden"  value="<?php echo "$uid";?>">
         </tr>
<?php } ?>

</table>

</div><!-- story -->
</div>
</form>
</body>
</html>
