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
	$year=$_REQUEST['year'];
	$month=$_REQUEST['month'];

	$sid=$_REQUEST['sid'];
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
			$sql="select * from invoice where acc='$accno' and year='$year' and month='$month' $sqlsid";
			$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			$found=mysql_num_rows($res);
			$row=mysql_fetch_assoc($res);
			$invoice_num=$row['id'];
			$invoice_date=$row['dt'];
			$invoice_id=$row['id'];
			$sid=$row['sid'];
			$name=stripslashes($row['name']);
			$addr=ucwords(strtolower(stripslashes($row['addr'])));
			$xaddr=str_replace(",","<br>",$addr);
			$bandar=ucwords(strtolower(stripslashes($row['city'])));
			$poskod=ucwords(strtolower(stripslashes($row['pcode'])));
			$state=ucwords(strtolower(stripslashes($row['state'])));
?>

<div id="mytitlebg" class="printhidden" style="color:#CCCCCC" align="right">PAGE <?php echo "$pageno/$totalpage";?></div>
<?php 
//include("../inc/header_global.php");
include ('../inc/header_school.php');
?>
<div id="mytabletitle" align="center" style="background:none; border:none; font-size:14px; border-top:1px solid #F1F1F1; font-family:'Palatino Linotype'">INVOICE</div>
<table width="100%" cellspacing="0">
  <tr>
    <td width="2%" valign="top">M/s</td>
    <td width="50%" valign="top">
			<?php echo $name;?><br>
			<?php echo $xaddr;?><br>
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
<table width="100%" cellspacing="0" cellpadding="3">
<?php 
		$totalpayable=0;
		$outbill=0;
		$currentbill=0;
		$bilanak=0;
		if($USE_PARENT_PAYMENT)
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

 <?php if($bilanak==1){?>
  	<tr bgcolor="#FAFAFA">
    		<td id="myborder" width="5%" align="center">No</td>
    		<td id="myborder" width="30%">Item</td>
			<td id="myborder" width="3%" align="center">Receipt&nbsp;No.</td>
            <td id="myborder" width="10%" align="right" style="padding-right:20px">Amount RP</td>
        	<td id="myborder" width="10%" align="right" style="padding-right:20px">Discount RP</td>
			<td id="myborder" width="10%" align="right" style="padding-right:20px">Payable RP</td>
  </tr>
<?php }?>
	<tr>
		<td id="myborder" colspan="6"><?php echo "$bilanak. $xuid - $xname : $xclsname";?></td>
	</tr>
<?php
	$bilyuran=0;
	$subtotal=0;
	$totalfee=0;
	$ispaid=0;
	$sql="select * from feestu where uid='$xuid' and sid=$xsid and ses=$year and invoice='$invoice_num' order by mon,fee";
	$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$found=mysql_num_rows($res3);
	if($found<1){
?>
		<tr>
			<td id="myborder" align="center">&nbsp;</td>
            <td id="myborder">&nbsp;- none -</td>
            <td id="myborder" align="center" style="padding-right:20px">&nbsp;</td>
            <td id="myborder" align="right" style="padding-right:20px">-</td>
            <td id="myborder" align="right" style="padding-right:20px">-</td>
            <td id="myborder" align="right" style="padding-right:20px">-</td>
		  </tr>

<?php 	
	}
	while($row3=mysql_fetch_assoc($res3)){
			$feename=$row3['fee'];
			$rm=$row3['rm'];
			$dis=$row3['dis'];
			$disval=$row3['disval'];
			$val=$row3['val'];
			$resitno=$row3['resitno'];
			$ispaid=$row3['sta'];
			if($val<0)
				continue;
			
			$subtotal=$subtotal+$val;
				
			if(!$ispaid)				
				$currentbill=$currentbill+$val;

			if(!$ispaid)
				$totalpayable=$totalpayable+$val;
				
			$bilyuran++;

?>
		  <tr>
			<td id="myborder" align="center"><?php echo "$bilanak.$bilyuran";?></td>
			<td id="myborder"><?php echo "$feename";?></td>
			<td id="myborder" align="center"><?php echo $resitno;//if($ispaid) echo "P"; ?></td>
            <td id="myborder" align="right" style="padding-right:20px">&nbsp;<?php echo number_format($rm,2,'.',',');?></td>
            <td id="myborder" align="right" style="padding-right:20px">&nbsp;<?php if($disval>0) echo number_format($disval,2,'.',',');?></td>
			<td id="myborder" align="right" style="padding-right:20px">&nbsp;<?php echo number_format($val,2,'.',',');?></td>
		  </tr>
<?php } ?>

  <!-- END OF CURRENT INVOICE. NOW CULCULATE OUTSTANDING -->

  
<?php
	$bilyuran=0;
	$sql="select * from feestu where uid='$xuid' and sid=$xsid and ses=$year and sta=0 and val>0 and (invoice<$invoice_num and invoice!=0) order by mon,fee";
	//echo $sql;
	$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$found_outstanding=mysql_num_rows($res3);
	while($row3=mysql_fetch_assoc($res3)){
			$feename=$row3['fee'];
			$ispaid=$row3['sta'];
			$rm=$row3['rm'];
			$dis=$row3['dis'];
			$disval=$row3['disval'];
			$val=$row3['val'];
			$resitno=$row3['resitno'];
			if($val==0)
				continue;
			
			$bilyuran++;
			//$subtotal=$subtotal+$val;
			if(!$ispaid)
				$outbill=$outbill+$val;
			if(!$ispaid)
				$totalpayable=$totalpayable+$val;
?>
<!--
		  <tr>
			<td id="myborder" align="center"><?php echo "$bilanak.$bilyuran";?></td>
			<td id="myborder">*<?php echo "$feename";?></td>
			<td id="myborder" align="center"><?php echo $resitno //if($ispaid) echo "P";else echo "O";?></td>
            <td id="myborder" align="right" style="padding-right:20px">&nbsp;<?php echo number_format($val,2,'.',',');?></td>
            <td id="myborder" align="right" style="padding-right:20px">&nbsp;<?php if($disval>0) echo number_format($disval,2,'.',',');?></td>
			<td id="myborder" align="right" style="padding-right:20px">&nbsp;<?php echo number_format($val,2,'.',',');?></td>
		  </tr>
-->
<?php } ?>

  <tr bgcolor="#FAFAFA">
  	<td id="myborder" colspan="4" align="right"></td>
    <td id="myborder" align="right" style="padding-right:20px">Current Bill</td>
	<td id="myborder" align="right" style="padding-right:20px"><?php echo number_format($subtotal,2,'.',',');?></td>
  </tr>
 <?php } ?>
	<tr>
    <td >&nbsp;</td>
    <td colspan="4" >STATEMENT SUMMARY</td>
    <td >&nbsp;</td>
    </tr>
   	<tr bgcolor="#FAFAFA">
		<td id="myborder" colspan="5" align="right" style="padding-right:20px">Total Bill</td>
		<td id="myborder" align="right" style="padding-right:20px"><?php echo number_format($currentbill,2,'.',',');?></td>
	</tr>
   	<tr bgcolor="#FAFAFA">
		<td id="myborder" colspan="5" align="right" style="padding-right:20px">Outstanding Bill</td>
		<td id="myborder" align="right" style="padding-right:20px"><?php echo number_format($outbill,2,'.',',');?></td>
	</tr>
  	<tr bgcolor="#FAFAFA">
		<td id="myborder" align="right" colspan="5" style="padding-right:20px">TOTAL PAYABLE</td>
		<td id="myborder" align="right" style="padding-right:20px"><strong><?php echo number_format($totalpayable,2,'.',',');?></strong></td>
  	</tr>

</table>
<table width="100%" cellspacing="0" cellpadding="10px"><tr>
<td id="myborder" width="71%" valign="top">
<strong>Maklumat Bank</strong><br>
Untuk Cek, sila bayar kepada: IIUM SDN BHD<br>
Untuk Bank-in: BIMB 1416 ++++ ++++ ++++<br><br>
</td>
<td id="myborder" width="29%" valign="top">
<strong>Pegawai KEUANGAN</strong><br>
Puan Hayati<br>
IIUM SDN BHD
</td></tr>
<tr>
<td>
<strong>Peringatan:</strong> <br>
Sila jelaskan bayaran dalam tempoh 30 hari dari tarikh invoice.<br>
</td>
<td align="right">

        	<a href="<?php echo "../efee/$FN_FEEPAY.php?uid=$xuid&year=$year&sid=$sid";?>" style="color:#0033FF" target="_blank">PAY ANY AMOUNT</a>
            |
        	<a href="<?php echo "../efee/$FN_FEEPAY.php?uid=$xuid&year=$year&sid=$sid";?>" style="color:#0033FF" target="_blank">PAY ALL</a>

</td>


</table>






<?php if($pageno!=$totalpage){?>
<div style="page-break-after:always"></div>
<?php } ?>
<?php } ?>
</div><!-- story -->
</div><!-- content -->
</form>
</body>
</html>
