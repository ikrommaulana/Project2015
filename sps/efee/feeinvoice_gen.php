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
	if($PARENT_ALL_SCHOOL){
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
			$total=0;
			$pageno++;
			$accno=$acc[$numberofstudent];
			if($USE_PARENT_PAYMENT)
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
					$sql="insert into invoice (acc,ic,dt,year,month,name,addr,city,pcode,state,ts,adm,sid)values
					('$accno','$p1ic',now(),$year,$month,'$p1name','$addr','$city','$pcode','$state',now(),'$adm',$sid)";
					$res=mysql_query($sql)or die("$sql: query failed:".mysql_error());
					$invoice_num=mysql_insert_id();
					$invoice_date=date('Y-m-d');
			}else{
					$row=mysql_fetch_assoc($res);
					$invoice_num=$row['id'];
					$invoice_date=$row['dt'];
			}
?>

<table width="100%" cellspacing="0">
<?php 
		$totalpayable=0;
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
<tr id="mytitlebg">
		    <td id="myborder" colspan="6"><?php echo "$bilanak. $xuid - $xname : $xclsname";?></td>
</tr>
<tr bgcolor="#FAFAFA">
			<td id="myborder" width="5%" align="center">No</td>
    		<td id="myborder" width="30%">Item</td>
			<td id="myborder" width="3%" align="center">Status</td>
            <td id="myborder" width="10%" align="right" style="padding-right:20px">Amount RP</td>
            <td id="myborder" width="10%" align="right" style="padding-right:20px">Discount RP</td>
			<td id="myborder" width="10%" align="right" style="padding-right:20px">Total RP</td>
</tr>
<?php
	$bilyuran=0;
	$subtotal=0;
	$ispaid=0;
	$currentbill=0;
	$outbill=0;
	if($month==1)
		$sql="select * from feestu where uid='$xuid' and sid=$xsid and ses=$year and mon<=$month";
	else
		$sql="select * from feestu where uid='$xuid' and sid=$xsid and ses=$year and mon=$month";
		
	$sql="select * from feestu where uid='$xuid' and sid=$xsid and ses=$year and mon<=$month and invoice<=0";
	$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row3=mysql_fetch_assoc($res3)){
			$feename=$row3['fee'];
			$feeid=$row3['id']; 
			$rm=$row3['rm']; 
			if($rm=="") 
				$rm=0;
			$dis=$row3['dis']; 
			if($dis=="") 
					$dis=0;
			$disval=$row3['disval'];
			if($disval=="") 
				$disval=0;
			$val=$row3['val'];
			if($val=="") 
				$val=0;
			$ispaid=$row3['sta'];
			if($val<0)
				continue;
				
			//if($row3['invoice']!="")
				//continue;

			$subtotal=$subtotal+$val;
			$total=$total+$val;
				
			if(!$ispaid)				
				$currentbill=$currentbill+$val;

			if(!$ispaid)
				$totalpayable=$totalpayable+$val;
				
			$bilyuran++;
			
			$sql="update feestu set invoice='$invoice_num' where id=$feeid";
			mysql_query($sql)or die("$sql:query failed:".mysql_error());
			
?>
		  <tr>
			<td id="myborder" align="center"><?php echo "$bilanak.$bilyuran";?></td>
			<td id="myborder"><?php echo "$feename";?></td>
			<td id="myborder" align="center"><?php if($ispaid) echo "p";?></td>
            <td id="myborder" align="right" style="padding-right:20px"><?php echo number_format($rm,2,'.',',');?></td>
			<td id="myborder" align="right" style="padding-right:20px"><?php if($disval>0) echo number_format($disval,2,'.',',');else echo "&nbsp;";?></td>
			<td id="myborder" align="right" style="padding-right:20px"><?php echo number_format($val,2,'.',',');?></td>
		  </tr>
<?php } ?>
	
 
  <tr bgcolor="#FAFAFA">
  	<td id="myborder" align="right">&nbsp;</td>
    <td id="myborder" align="center">Total Amount</td>
    <td id="myborder" align="center">&nbsp;</td>
    <td id="myborder" align="center">&nbsp;</td>
    <td id="myborder" align="center">&nbsp;</td>
	<td id="myborder" align="right" style="padding-right:20px"><strong><?php echo number_format($subtotal,2,'.',',');?></strong></td>
  </tr>
 <?php } ?>

</table>
<?php 
$sql="update invoice set rm='$total' where id=$invoice_num";
mysql_query($sql)or die("$sql:query failed:".mysql_error());
?>

<?php } ?>

</div><!-- story -->
</div><!-- content -->
</form>
</body>
</html>
