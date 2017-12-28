<?php
$vmod="v4.1.0";
$vdate="10/05/2010";
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|KEUANGAN');
	$adm = $_SESSION['username'];
	$acc=$_REQUEST['xacc'];//kalau klik view
	$sid=$_REQUEST['sid'];
	$month=$_REQUEST['month'];

	$year=$_REQUEST['year'];
	if($year=="")
		$year=date("Y");
	$paydate=$_POST['paydate'];
	if($paydate=="")
		$paydate=date("Y-m-d");
	$cbox=$_POST['cbox'];
	$total=$_POST['total'];
	$paytype=$_POST['paytype'];
	$nocek=$_POST['nocek'];
	$jumbayar=$_POST['jumbayar'];
	$payadvance=$_POST['payadvance'];
	$lainlainval=$_POST['lainlainval'];
	$lainlaindes=$_POST['lainlaindes'];
	$lainlain=$_POST['lainlain'];
	$onadvance=$_POST['onadvance'];

	$rujukan=$_POST['rujukan'];
	if($onadvance=="")
		$onadvance=0;
	$ontebus=$_POST['ontebus'];
	$jumtebus=$_POST['jumtebus'];
	
if ((count($cbox)>0)||($ontebus>0)||($jumbayar>0)) {
	
	$sql="insert into feetrans (cdate,year,sch_id,stu_id,stu_uid,acc,rm,admin,paytype,nocek,paydate,rujukan)values
	(now(),$year,0,0,'','$acc',$jumbayar,'$username','$paytype','$nocek','$paydate','$rujukan')";
   	mysql_query($sql)or die("$sql - query failed:".mysql_error());
	$feeid=mysql_insert_id($link);
	if (count($cbox)>0) {
			for ($i=0; $i<count($cbox); $i++) {
				$data=$cbox[$i];
				list($prm,$val,$xrm,$dis,$disval,$xuid)=explode("|",$data);
				$sql="select * from stu where uid='$xuid'";
				$res=mysql_query($sql) or die("$sql - query failed:".mysql_error());
				$row=mysql_fetch_assoc($res);
				$stu_id=$row['id'];
				$sch_id=$row['sch_id'];

				$sql="insert into feepay (cdate,year,sch_id,stu_id,stu_uid,acc,rm,fee,tid,admin,xrm,dis,disval)values
				(now(),$year,$sch_id,$stu_id,'$xuid','$acc',$val,'$prm',$feeid,'$username',$xrm,$dis,$disval)";
				mysql_query($sql)or die("$sql - query failed:".mysql_error());
				//echo "$sql<br>";
				$sql="update feestu set sta=1,val=$val,rno=$feeid,pdt='$paydate',adm='$username' where uid='$xuid' and ses='$year' and sid='$sch_id' and fee='$prm'";
				mysql_query($sql)or die("$sql - failed:".mysql_error());
			}
	}
	if($sch_id=="")
		$sch_id="0";
	if($onadvance){
		$sql="insert into feepay (cdate,year,sch_id,acc,rm,fee,tid,admin)values(now(),$year,$sch_id,'$acc',$payadvance,'ADVANCE',$feeid,'$username')";
		mysql_query($sql)or die("$sql - query failed:".mysql_error());
		$sql="update parent set fee_advance=fee_advance+$payadvance where acc='$acc'";
		mysql_query($sql)or die("$sql - query failed:".mysql_error());
	}
	if($jumtebus>0){
		$jumteb="-$jumtebus";
		$sql="insert into feepay (cdate,year,sch_id,acc,rm,fee,tid,admin)values(now(),$year,$sch_id,'$acc',$jumteb,'TEBUS',$feeid,'$username')";
		mysql_query($sql)or die("$sql - query failed:".mysql_error());
		$sql="update parent set fee_advance=fee_advance-$jumtebus where acc='$acc'";
		mysql_query($sql)or die("$sql - query failed:".mysql_error());
	}

	if($lainlainval>0){
		$sql="insert into feepay (cdate,year,sch_id,acc,rm,fee,etc,tid,admin)values(now(),$year,$sch_id,'$acc',$lainlainval,'Lain-Lain','$lainlaindes',$feeid,'$username')";
		mysql_query($sql)or die("$sql - query failed:".mysql_error());
	}
	
	

	echo "<script language=\"javascript\">location.href='$FN_FEERESIT.php?id=$feeid'</script>";
	
	

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
<?php include("$MYOBJ/calender/calender.htm")?>
<SCRIPT LANGUAGE="JavaScript">
function check_all(){
	var checked=false;
	for (var i=0;i<document.form1.elements.length;i++){
		var e=document.form1.elements[i];
        if ((e.type=='checkbox')&&(e.id=='cb')){
			e.checked=true;			
        }
	}
}
function clear_all(){
	var checked=false;
	for (var i=0;i<document.form1.elements.length;i++){
		var e=document.form1.elements[i];
        if ((e.type=='checkbox')&&(e.id=='cb')){
			e.checked=false;			
        }
	}
}
function check(){
	var sum=0;
	var lain=0;
	var totalall=0;
	
	var checked=false;
	for (var i=0;i<document.form1.elements.length;i++){
		var e=document.form1.elements[i];
        if ((e.type=='checkbox')&&(e.id=='cb')){
			if(e.checked==true){
				var str=e.value
				var arr=str.split("|")
				sum=sum+parseFloat(arr[1]);
				checked=true;
			}
			
        }
	}
	lain=parseFloat(document.form1.lainlainval.value);
	tebus=parseFloat(document.form1.jumtebus.value);
	totalall=sum+lain-tebus;
	document.form1.total.value=sum.toFixed(2);
	document.form1.jumbayar.value=totalall.toFixed(2);
	
	document.form1.jumbayar.readOnly=true;
	document.form1.onadvance.checked=false;
	document.form1.onadvance.disabled=false;
	//document.form1.ontebus.checked=false;
	if(checked)
		document.form1.btnpayment.disabled=false;
	else
		document.form1.btnpayment.disabled=true;
	if(totalall>0)
		document.form1.btnpayment.disabled=false;
}

function process_form(){
	total_fee=parseFloat(document.form1.total.value);
	total_pay=parseFloat(document.form1.jumbayar.value);
	total_lain=parseFloat(document.form1.lainlainval.value);
	total_tebus=parseFloat(document.form1.jumtebus.value);
	
	
	total_allpay=total_fee+total_lain-total_tebus;
	advance=total_pay-total_fee-total_lain+total_tebus;
	document.form1.payadvance.value=0;
	if(total_lain>0){
		if(document.form1.lainlaindes.value==""){
			alert("Sila masukkan maklumat pembayaran lain");
			document.form1.lainlaindes.focus();
			return;
		}
		if(document.form1.lainlaindes.value=="(Masukkan Keterangan)"){
			alert("Sila masukkan maklumat pembayaran lain");
			document.form1.lainlaindes.value="";
			document.form1.lainlaindes.focus();
			return;
		}
	}
	if(document.form1.onadvance.checked){
		if(advance<=0){
			alert('Jumlah bayaran mesti melebihi RP'+total_allpay+ ' untuk advance' );
			document.form1.jumbayar.value=total_allpay.toFixed(2);
			document.form1.jumbayar.focus();
			return;
		}
		document.form1.payadvance.value=advance;
	}
	

		if(total_pay<total_allpay){
			alert('Jumlah bayaran mesti sama atau lebih RP'+total_allpay );
			document.form1.jumbayar.value=total_allpay.toFixed(2);
			document.form1.onadvance.checked=false;
			document.form1.jumbayar.focus();
			return;
		}
		//if(total_pay==0){
			//alert('Sila tandakan pembayaran.');
			//return;
		//}
		if((total_fee==0)&&(total_lain==0)&&(advance==0)){
			alert('Sila tandakan pembayaran');
			return;
		}
		ret = confirm("Yuran      RP "+total_fee+ "\r\nLain-Lain RP "+total_lain+ "\r\nAdvance RP "+advance+"\r\nTebus     RP "+total_tebus+ "\r\nJUMLAH BAYARAN RP "+total_pay+ ". Confirm??");
		if (ret == true){
				document.form1.submit();
				return true;
		}else{
				document.form1.jumbayar.focus();
				return false;
		}
}


function kira(ele,no){
	total=parseFloat(document.form1.total.value);
	lain=parseFloat(document.form1.lainlainval.value);
	tebus=parseFloat(document.form1.jumtebus.value);
	totalall=total+lain-tebus;
	document.form1.jumbayar.value=totalall.toFixed(2);
}
function kiratebus(ele,no){
	balance=parseFloat(document.form1.ontebus.value);
	total=parseFloat(document.form1.total.value);
	lain=parseFloat(document.form1.lainlainval.value);
	tebus=parseFloat(document.form1.jumtebus.value);
	if(tebus > balance){
		alert('Maaf. Jumlah tebus melebihi had RP'+balance);
		document.form1.jumtebus.value="0.00";
		return;
	}
	totalall=total+lain-tebus;
	document.form1.jumbayar.value=totalall.toFixed(2);
}
function process_advance(){
	if(document.form1.onadvance.checked){
		document.form1.jumbayar.readOnly=false;
		document.form1.jumbayar.focus();
	}else{
		total_fee=parseFloat(document.form1.total.value);
		total_pay=parseFloat(document.form1.jumbayar.value);
		total_lain=parseFloat(document.form1.lainlainval.value);
		total_allpay=total_fee+total_lain;
		document.form1.jumbayar.readOnly=true;
		document.form1.jumbayar.value=total_allpay.toFixed(2);
	}
}

function process_tebus(){
	document.form1.jumtebus.readOnly=false;
	document.form1.btnpayment.disabled=false;
}
</script>
</head>
<body>
<form name="form1" action="" method="post">
	<input type="hidden" name="id" value="<?php echo $id;?>" >
	<input type="hidden" name="op" value="">
	<input type="hidden" name="year" value="<?php echo $year;?>" >
	<input type="hidden" name="xacc" value="<?php echo $acc;?>" >

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
<div id="story" style="font-size:120%; border:none">
<?php
			$accno=$acc;
			$invoice_id=0;
			$sql="select * from invoice where acc='$accno' and year='$year' and month='$month'";
			$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			$found=mysql_num_rows($res);
			$row=mysql_fetch_assoc($res);
			$invoice_num=$row['id'];
			$invoice_date=$row['dt'];
			$invoice_id=$row['id'];
			
			$sql="select * from parent where acc='$accno' and status=0";
			$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$p1name=ucwords(strtolower(stripslashes($row['p1name'])));
			$advance=$row['fee_advance'];
			
			
?>

<table width="100%"cellspacing="0" ><tr>
		<td width="20%"><font size="4">SESI <?php echo $year;?></font></td>
		<td width="40%"><font size="4">Acc Name : <?php echo $p1name;?></font></td>
		<td width="20%"><font size="4">Acc Number : <?php echo $accno;?></font></td>
		<td width="20%" align="right"><font size="4">Advance : RP<?php echo number_format($advance,2,'.',',');?></font></td>
</tr></table>
      <table width="100%" cellspacing="0" bgcolor="#FFFFCC" style="border:1px solid #333333 ">  
	  	<tr >
		 <td id="myborder" width="15%"><b>Jumlah Yuran</b></td>
		 <td id="myborder"><input name="total" type="text" id="total" value="0.00" size="8" readonly style="font-size:large; text-align:right ">   
		 </td>
       </tr>  
	  <tr >
		 <td id="myborder"><b>Lain-Lain Bayaran</b></td>
		 <td id="myborder">
		 <input name="lainlainval" type="text" value="0.00" size="8" style="font-size:large; text-align:right " onKeyUp="kira(this)">
		 <input name="lainlaindes" type="text" size="38" style="color:#999999 " value="(Masukkan Keterangan)" onMouseDown="document.form1.lainlaindes.value='';document.form1.lainlaindes.focus();" >
		 <input type="hidden" name="lainlain" value="0">
		 </td>
       </tr>  
	   <tr >
		 <td id="myborder" ><b>Jumlah Bayaran</b></td>
		 <td id="myborder">
		 <input name="jumbayar" type="text" size="8" value="0.00" readonly style="font-size:large; text-align:right ">
		 <input type="hidden" name="payadvance">
		 <input name="onadvance" type="checkbox" value="1" readonly onClick="process_advance()">
		  <b>Advance</b>
		   <input name="ontebus" type="checkbox" value="<?php echo $advance; ?>" onClick="process_tebus()" <?php if($advance<=0)echo "disabled";?>>
		  <b>Tebus</b>
		  <input name="jumtebus" type="text" size="8" value="0.00" readonly style="font-size:large; text-align:right " onKeyUp="kiratebus(this)">	 
		 </td>
       </tr>
	    <tr >
		 <td id="myborder"><b>Tarikh Pembayaran</b></td>
		 <td id="myborder">
		 	<input name="paydate" type="text" id="paydate" size="12" readonly value="<?php echo "$paydate";?>">
		   	<input name=" cal" type="button" id=" cal" value="-" onClick="c2.popup('paydate')">
		 </td>
       </tr>  
	    <tr >
			 <td id="myborder"><b>Maklumat Pembayaran</b></td>
			 <td id="myborder">
					   <input name="paytype" type="radio" value="TUNAI" checked >
					   <b>Tunai</b>
					   <input name="paytype" type="radio" value="CEK" >
					   <b>Cek</b>
					   <input name="paytype" type="radio" value="K.Kredit" >
					   <b>Kad Kredit</b>
					   <input name="paytype" type="radio" value="DLL">
					   <b>Lain-Lain</b>
					   <input name="nocek" type="text" id="nocek" size="32">
			 </td>
       </tr>  
	   <tr >
			 <td id="myborder"><b>Rujukan Pejabat</b></td>
			 <td id="myborder"><input name="rujukan" type="text" id="rujukan" size="32"></td>
       </tr>  
	   <tr>
			 <td id="myborder">&nbsp;</td>
			 <td id="myborder"><input type="button" name="btnpayment" value="Confirm Bayaran" onClick="process_form()"></td>
       </tr>  
	</table>



<div id="mytabletitle">
INVOICE DETAIL : <a href="#" onClick="check_all();check();">Pay All</a> | <a href="#" onClick="clear_all();check();">Clear All</a>
</div>

<table width="100%" bgcolor="#FAFAFA">
  <tr>
    <td width="2%" valign="top">M/s</td>
    <td width="50%" valign="top">
	<?php echo $p1name;?><br>
	<?php echo $p1addr;?><br>
	<?php echo "$p1poskod $p1bandar";?><br>
	<?php echo $p1state;?><br>
	</td>
    <td width="20%" valign="top">
		Account&nbsp;No&nbsp;: <?php echo $accno;?><br>
		Invoice&nbsp;&nbsp;No&nbsp;: <?php echo $invoice_num;?><br>
		Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo $invoice_date;?><br>
	</td>
  </tr>
</table>
<table width="100%" cellspacing="0" cellpadding="0" style="font-size:9px ">
  <tr>
    <td id="mytabletitle" width="5%" align="center">Item</td>
    <td id="mytabletitle" width="50%" >&nbsp;Description</td>
    <td id="mytabletitle" width="10%" align="center">Amount RP</td>
    <td id="mytabletitle" width="10%" align="center">Discount RP</td>
    <td id="mytabletitle" width="10%" align="center">Payable RP</td>
  </tr>
<?php 
		$totalpayable=0;
		$bilanak=0;
		$sql="select * from stu where acc='$accno' and status=6";
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
  <tr bgcolor="#FAFAFA">
    <td id="myborder" align="center"><?php echo $bilanak;?></td>
    <td id="myborder"><?php echo "$xuid $xname ($xclsname)";?></td>
    <td id="myborder" align="right"></td>
    <td id="myborder" align="center"></td>
    <td id="myborder" align="right"></td>
  </tr>
<?php
	$bilyuran=0;
	$subtotal=0;
	$ispaid=0;
	$sql="select * from invoice_item where xid=$invoice_num and sta=0 and uid='$xuid'";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row2=mysql_fetch_assoc($res2)){
			$feename=$row2['fee'];
			$sql="select * from feestu where uid='$xuid' and sid=$xsid and ses=$year and fee='$feename'";
			$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			$row3=mysql_fetch_assoc($res3);
			$rm=$row3['rm'];
			$dis=$row3['dis'];
			$disval=$row3['disval'];
			$val=$row3['val'];
			$ispaid=$row3['sta'];
			if($val==0)
				continue;
				
			$subtotal=$subtotal+$val;
			if(!$ispaid)
				$totalpayable=$totalpayable+$val;
				
			$bilyuran++;
?>
		  <tr>
			<td id="myborder" align="center">
				<?php if(!$ispaid){?>
					<input name="cbox[]" type="checkbox" id="cb" value="<?php echo "$feename|$val|$rm|$dis|$disval|$xuid";?>" onClick="check()">
				<?php } else {echo "Paid";}?>
			</td>
			<td id="myborder"><?php echo "$bilanak.$bilyuran - $feename";?></td>
			<td id="myborder" align="right"><?php echo number_format($rm,2,'.',',');?></td>
			<td id="myborder" align="right"><?php echo number_format($disval,2,'.',',');?></td>
			<td id="myborder" align="right"><?php echo number_format($val,2,'.',',');?></td>
		  </tr>
<?php } ?>
  <tr bgcolor="#FAFAFA">
    <td id="myborder" colspan="4" align="right"><strong>Sub Total&nbsp;&nbsp;&nbsp;</strong></td>
	<td id="myborder" align="right"><strong><?php echo number_format($subtotal,2,'.',',');?></strong></td>
  </tr>
 <?php } ?>
   <tr>
			<td id="mytabletitle" colspan="4" align="right">Total Amount&nbsp;&nbsp;&nbsp;</td>
			<td id="mytabletitle" align="right"><?php echo number_format($totalpayable,2,'.',',');?></td>
  </tr>
  <!-- END OF CURRENT INVOICE. NOW CULCULATE OUTSTANDING -->
  <tr>
			<td id="mytabletitle" width="4%" align="center">Item</td>
			<td id="mytabletitle" width="50%" colspan="4">&nbsp;Outstanding Balance</td>
  </tr>
  <?php 
		$bilanak=0;
		$totalout=0;
		$sql="select * from stu where acc='$accno' and status=6";
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
  <tr bgcolor="#FAFAFA">
    <td id="myborder" align="center"><?php echo $bilanak;?></td>
    <td id="myborder"><?php echo "$xuid $xname ($xclsname)";?></td>
    <td id="myborder" align="right"></td>
    <td id="myborder" align="center"></td>
    <td id="myborder" align="right"></td>
  </tr>
<?php
	$bilyuran=0;
	$subtotal=0;
	$sql="select * from invoice_item where xid=$invoice_num and sta=1 and uid='$xuid'";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row2=mysql_fetch_assoc($res2)){
			$feename=$row2['fee'];
			$sql="select * from feestu where uid='$xuid' and sid=$xsid and ses=$year and fee='$feename'";
			$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			$row3=mysql_fetch_assoc($res3);
			$ispaid=$row3['sta'];
			$rm=$row3['rm'];
			$dis=$row3['dis'];
			$disval=$row3['disval'];
			$val=$row3['val'];
			if($val==0)
				continue;
			
			if(!$ispaid)
				$totalpayable=$totalpayable+$val;
				
			$bilyuran++;
			$subtotal=$subtotal+$val;
			$totalout=$totalout+$val;
?>
		  <tr>
			<td id="myborder" align="center">
				<?php if(!$ispaid){?>
					<input name="cbox[]" type="checkbox" id="cb" value="<?php echo "$feename|$val|$rm|$dis|$disval|$xuid";?>" onClick="check()" style="font-size:9px ">
				<?php } else { echo "Paid";}?>
			</td>
			<td id="myborder"><?php echo "$bilanak.$bilyuran - $feename";?></td>
			<td id="myborder" align="right"><?php echo number_format($rm,2,'.',',');?></td>
			<td id="myborder" align="right"><?php echo number_format($disval,2,'.',',');?></td>
			<td id="myborder" align="right"><?php echo number_format($val,2,'.',',');?></td>
		  </tr>
<?php } ?>
  <tr bgcolor="#FAFAFA">
    <td id="myborder" colspan="4" align="right"><strong>Sub Total&nbsp;&nbsp;&nbsp;</strong></td>
	<td id="myborder" align="right"><strong><?php echo number_format($subtotal,2,'.',',');?></strong></td>
  </tr>
 <?php } ?>
   <tr>
			<td id="mytabletitle" colspan="4" align="right">Total Outstanding&nbsp;&nbsp;&nbsp;</td>
			<td id="mytabletitle" align="right"><?php echo number_format($totalout,2,'.',',');?></td>
  </tr>
  <tr>
    <td id="mytabletitle" colspan="4" align="right">TOTAL AMOUNT PAYABLE&nbsp;&nbsp;&nbsp;</td>
    <td id="mytabletitle" align="right"><u><?php echo number_format($totalpayable,2,'.',',');?></u></td>
  </tr>
</table>



	
</div><!-- story -->
</div><!-- content -->
</form>
</body>
</html>
