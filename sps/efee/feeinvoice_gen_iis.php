<?php
$vmod="v4.1.0";
$vdate="10/05/2010";
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK|KEUANGAN|HEP|HR|HEP-OPERATOR');
	$adm = $_SESSION['username'];
	$acc=$_REQUEST['acc'];
	$xacc=$_REQUEST['xacc'];//kalau klik view
	if($xacc!="")
		$acc[0]=$xacc;
	$sid=$_REQUEST['sid'];
	$month=$_REQUEST['month'];
	$year=$_REQUEST['year'];
	$month=$_REQUEST['month'];
	if($sid=="")
		$sid=$_SESSION['sid'];
	
	$sqlschid="and sch_id=$sid";
	$sqlsid="and sid=$sid";
	if($INVOICE_PARENT_ALL_SCHOOL){
		$sqlschid="";
		$sqlsid="";
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>INVOICE</title>
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
<body>
<form name="myform" action="" method="post">
	<input type="hidden" name="id" value="<?php echo $id;?>" >
	<input type="hidden" name="op" value="">

<div id="content">
IIS
	<div id="mypanel">
		<div id="mymenu" align="center">
			<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
			<a href="#" onClick="window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
		</div>
		<div align="right">
		<br>
		&nbsp;&nbsp;
	 	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
		</div>
	</div>
<div id="story" style="border:none; font-size:9px">
<?php
		$totalpage=count($acc);
		for($numberofstudent=0;$numberofstudent<count($acc);$numberofstudent++){
			$pageno++;
			$accno=$acc[$numberofstudent];
			$invoice_id=0;
			if($INVOCE_BY_PARENT)
				$sql="select * from stu where acc='$accno' $sqlschid";
			else
				$sql="select * from stu where uid='$accno' $sqlschid";
			$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$name=addslashes($row['name']);
			$ic=$row['ic'];
			$p1name=addslashes($row['p1name']);
			$p1ic=$row['p1ic'];
			$addr=addslashes($row['addr']);
			$city=$row['bandar'];
			$pcode=$row['poskod'];
			$state=$row['state'];
			$sponser=$row['sponser'];

			$sql="select * from invoice where acc='$accno' and year='$year' and month='$month'  $sqlsid";
			$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			$found=mysql_num_rows($res);
			if(!$found){
			
				$sql="insert into invoice (acc,ic,dt,year,month,name,addr,city,pcode,state,ts,adm,sid)values('$accno','$p1ic',now(),$year,$month,'$p1name','$addr','$city','$pcode','$state',now(),'$adm',$sid)";
				$res=mysql_query($sql)or die("$sql: query failed:".mysql_error());
				$invoice_num=mysql_insert_id();
				$invoice_date=date('Y-m-d');
			}else{
				$row=mysql_fetch_assoc($res);
				$invoice_num=$row['id'];
				$invoice_date=$row['dt'];
				$invoice_id=$row['id'];
			}
?>

<div id="mytitlebg" class="printhidden" style="color:#CCCCCC" align="right">PAGE <?php echo "$pageno/$totalpage";?></div>
<?php include("../inc/header_global.php");?>
<div id="mytabletitle" align="center" style="background:none; border:none; font-size:14px; border-top:1px solid #F1F1F1; font-family:'Palatino Linotype'">INVOICE</div>

<table width="100%" cellspacing="0">
  <tr>
    <td width="2%" valign="top">M/s</td>
    <td width="50%" valign="top">
			<?php echo stripslashes($p1name);?><br>
			<?php $xaddr=str_replace(",","<br>",$addr); echo $xaddr;?><br>
			<?php echo "$pcode $city";?><br>
			<?php echo $state;?><br><br>
			SPONSERED: <?php echo $sponser;?>
	</td>
    <td width="20%" valign="top">
		<table width="100%" cellspacing="0">
			<tr>
				<td width="50%">Account&nbsp;No</td>
				<td width="1%">:</td>
				<td width="49%"><?php echo $accno;?></td>
			</tr>
			<tr>
				<td width="50%">Invoice&nbsp;No</td>
				<td width="1%">:</td>
				<td width="49%"><?php echo $invoice_num;?></td>
			</tr>
			<tr>
				<td width="50%">Date</td>
				<td width="1%">:</td>
				<td width="49%"><?php echo $invoice_date;?></td>
			</tr>
		</table>
	</td>
  </tr>
</table>

<br><br>
<table width="100%" cellspacing="0">
<?php 
		$totalpayable=0;
		$bilanak=0;
		if($INVOCE_BY_PARENT)
			$sql="select * from stu where acc='$accno' and status=6 $sqlschid";
		else
			$sql="select * from stu where uid='$accno' and status=6 $sqlschid";
		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
				$bilanak++;
				$xname=ucwords(strtolower(stripslashes($row['name'])));
				$xuid=$row['uid'];
				$xsid=$row['sch_id'];
				$sql="select * from ses_stu where stu_uid='$xuid' and year='$year'";
				$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				$row2=mysql_fetch_assoc($res2);
				$xclsname=ucwords(strtolower(stripslashes($row2['cls_name'])));
?>
<tr id="mytitlebg">
				<td id="myborder" colspan="5"><?php echo "$bilanak. $xuid - $xname : $xclsname";?></td>
</tr>
<tr bgcolor="#FAFAFA">
			<td id="myborder" width="5%" align="center">No</td>
    		<td id="myborder" width="30%">Item</td>
			<td id="myborder" width="50%">Description</td>
			<td id="myborder" width="3%" align="center">Status</td>
			<td id="myborder" width="10%" align="right" style="padding-right:20px">Amount RP</td>
</tr>
<?php
	$bilyuran=0;
	$subtotal=0;
	$ispaid=0;
	$currentbill=0;
	$outbill=0;
if(!$found){
	if($month==1)
		$sql="select * from type where grp='yuran' and code<=$month order by val";
	else
		$sql="select * from type where grp='yuran' and code=$month order by val";
}
else
	$sql="select * from invoice_item where xid=$invoice_num and sta=0 and uid='$xuid'";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row2=mysql_fetch_assoc($res2)){
		if(!$found)
			$feename=$row2['prm'];
		else
			$feename=$row2['fee'];
			$sql="select * from feestu where uid='$xuid' and sid=$xsid and ses=$year and fee='$feename'";
			$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			$row3=mysql_fetch_assoc($res3);
			$rm=$row3['rm']; if($rm=="") $rm=0;
			$dis=$row3['dis']; if($dis=="") $dis=0;
			$disval=$row3['disval'];if($disval=="") $disval=0;
			$val=$row3['val'];if($val=="") $val=0;
			$ispaid=$row3['sta'];
			if($val<=0)
				continue;

			$subtotal=$subtotal+$val;
				
			if(!$ispaid)				
				$currentbill=$currentbill+$val;

			if(!$ispaid)
				$totalpayable=$totalpayable+$val;
				
			$bilyuran++;
			if(!$found){
				$sql="insert into invoice_item (xid,year,month,ts,adm,fee,rm,val,dis,disval,uid,acc)values 
				($invoice_num,$year,$month,now(),'$adm','$feename','$rm','$val','$dis','$disval','$xuid','$accno')";
				mysql_query($sql)or die("$sql:query failed:".mysql_error());
			}
?>
		  <tr>
			<td id="myborder" align="center"><?php echo "$bilanak.$bilyuran";?></td>
			<td id="myborder"><?php echo "$feename";?></td>
			<td id="myborder"><?php if($disval>0) echo "Discount RP". number_format($disval,2,'.',',');else echo "&nbsp;";?></td>
			<td id="myborder" align="center"><?php if($ispaid) echo "P";?></td>
			<td id="myborder" align="right" style="padding-right:20px"><?php echo number_format($val,2,'.',',');?></td>
		  </tr>
<?php } ?>


  <!-- END OF CURRENT INVOICE. NOW CULCULATE OUTSTANDING -->


<?php

if(!$found)
	if($month==1)
		$sql="select * from type where grp='yuran' and code='noneedtoculculate' order by val";
	else
		$sql="select * from type where grp='yuran' and code<$month order by val";
else
	$sql="select * from invoice_item where xid=$invoice_num and sta=1 and uid='$xuid'";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$found_outstanding=0;
	while($row2=mysql_fetch_assoc($res2)){
			if(!$found)
				$feename=$row2['prm'];
			else
				$feename=$row2['fee'];
			if(!$found)
				$sql="select * from feestu where uid='$xuid' and sid=$xsid and ses=$year and fee='$feename' and sta=0";
			else
				$sql="select * from feestu where uid='$xuid' and sid=$xsid and ses=$year and fee='$feename'";
			$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			$row3=mysql_fetch_assoc($res3);
			$ispaid=$row3['sta'];
			
			$rm=$row3['rm']; 
				if($rm=="") $rm=0;
			$dis=$row3['dis']; 
				if($dis=="") $dis=0;
			$disval=$row3['disval'];
				if($disval=="") $disval=0;
			$val=$row3['val'];
				if($val=="") $val=0;
			
			if($val==0)
				continue;
			if($val<0)
				continue;
			
			
				
			$bilyuran++;
			
			$subtotal=$subtotal+$val;
			if(!$ispaid)
				$outbill=$outbill+$val;
			if(!$ispaid)
				$totalpayable=$totalpayable+$val;

			if(!$found){
				$sql="insert into invoice_item (xid,year,month,ts,adm,fee,rm,val,dis,disval,uid,acc,sta)values 
				($invoice_num,$year,$month,now(),'$adm','$feename','$rm','$val','$dis','$disval','$xuid','$accno',1)";
				mysql_query($sql)or die("$sql:query failed:".mysql_error());
			}
			$found_outstanding++;
?>
		  <tr>
			<td id="myborder" align="center"><?php echo "$bilanak.$bilyuran";?></td>
			<td id="myborder"><?php echo "$feename";?></td>
			<td id="myborder"><?php if($disval>0) echo "Discount RP". number_format($disval,2,'.',',');else echo "&nbsp;";?></td>
			<td id="myborder" align="center"><?php if($ispaid) echo "P";else echo "O";?></td>
			<td id="myborder" align="right" style="padding-right:20px"><?php echo number_format($val,2,'.',',');?></td>
		  </tr>
<?php } ?>

 
  <tr bgcolor="#FAFAFA">
  	<td id="myborder" colspan="3" align="right"></td>
    <td id="myborder" align="center">Total<br>Amount</td>
	<td id="myborder" align="right" style="padding-right:20px"><strong><?php echo number_format($subtotal,2,'.',',');?></strong></td>
  </tr>
 <?php } ?>
 	<tr>
		<td id="myborder" colspan="5" align="center">Invoice Summary</td>
	</tr>
   	<tr bgcolor="#FAFAFA">
		<td id="myborder" colspan="4"align="right">Current Fee</td>
		<td id="myborder" align="right" style="padding-right:20px"><strong><?php echo number_format($currentbill,2,'.',',');?></strong></td>
	</tr>
   	<tr bgcolor="#FAFAFA">
		<td id="myborder" colspan="4" align="right">Outstanding Fee</td>
		<td id="myborder" align="right" style="padding-right:20px"><strong><?php echo number_format($outbill,2,'.',',');?></strong></td>
	</tr>
  	<tr bgcolor="#FAFAFA">
		<td id="myborder" align="right" colspan="4">TOTAL PAYABLE</td>
		<td id="myborder" align="right" style="padding-right:20px"><strong><?php echo number_format($totalpayable,2,'.',',');?></strong></td>
  	</tr>
	<tr>
		<td colspan="4" align="right"><strong><a href="<?php echo "../efee/$FN_FEEPAY.php?uid=$xuid&year=$year&sid=$sid";?>" style="color:#0033FF" target="_blank">PAY ANY AMOUNT</a></strong></td>
		<td align="right"><strong><a href="#" style="color:#0033FF;padding-right:20px">PAY ALL</strong></a></td>
  	</tr>
</table>

<strong>Peringatan:</strong> Sila jelaskan bayaran dalam tempoh 30 hari dari tarikh invoice.
<br>
<br>
 
<table width="100%"><tr>
<td width="70%" valign="top">
<strong>Maklumat Bank</strong><br>
Untuk Cek, sila bayar kepada: IIUM SDN BHD<br>
Untuk Bank-in: BIMB 1416 ++++ ++++ ++++<br><br>
</td>
<td width="30%" valign="top">
<strong>Pegawai KEUANGAN</strong><br>
Puan Hayati<br>
IIUM SDN BHD
</td></tr></table>




<?php if($pageno!=$totalpage){?>
<div style="page-break-after:always"></div>
<?php } ?>
<?php } ?>
</div><!-- story -->
</div><!-- content -->
</form>
</body>
</html>
