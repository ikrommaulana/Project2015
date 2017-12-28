<?php
/**
ver 2.6
list all fee
**/
include_once('../etc/db.php');
include_once('session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify();

$sql="select * from type where grp='openexam' and prm='EYURAN'";
$res=mysql_query($sql)or die("query failed:".mysql_error());
$row=mysql_fetch_assoc($res);
$sta=$row['val'];
if($sta!="1")
	echo "<script language=\"javascript\">location.href='p.php?p=close'</script>";


$sql="select * from type where grp='openexam' and prm='ONLINEFEE'";
$res=mysql_query($sql)or die("query failed:".mysql_error());
$row=mysql_fetch_assoc($res);
$ONLINEFEE=$row['val'];

	
	$uid = $_SESSION['uid'];
	$sid = $_SESSION['sid'];
	
	$sql="select * from type where grp='fee_interface' and (sid=0 or sid='$sid')";
	$res=mysql_query($sql) or die("$sql failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
			$prm=$row['prm'];
			$CONFIG[$prm]['val']=$row['val'];
			$CONFIG[$prm]['code']=$row['code'];
			$CONFIG[$prm]['etc']=$row['etc'];
	}
	
	$paydate=$_POST['paydate'];
	if($paydate=="")
		$paydate=date("Y-m-d");

	$feetype=$_REQUEST['feetype'];
	if($feetype=="")
		$feetype=0;
	$sql="select * from type where grp='feetype' and val=$feetype order by idx";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$feetypename=$row['prm']; 
				
	$year=$_REQUEST['year'];
	if($year=="")
		$year=date("Y");
		
	$sql="select * from stu where uid='$uid' and sch_id=$sid";
	$res=mysql_query($sql) or die(mysql_error());
	if($row=mysql_fetch_assoc($res)){
			$stu_name=$row['name'];
			$mel=$row['mel'];
			$hp=$row['hp'];
	}		

	$checkbox=$_POST['checkbox'];
	
if (count($checkbox)>0) {
	$stu_id=$_POST['stu_id'];
	$year=$_POST['year'];
	$total=$_POST['total'];
	$bank=$_POST['bank'];
	$bankno=$_POST['bankno'];
	$mel=$_POST['mel'];
	$hp=$_POST['hp'];
	$checkbox=$_POST['checkbox'];
		
	$sql="select * from stu where uid='$uid' and sch_id=$sid";
	$res=mysql_query($sql) or die(mysql_error());
	if($row=mysql_fetch_assoc($res)){
			$stu_name=$row['name'];
			$stu_ic=$row['ic'];
			$stu_id=$row['id'];
	}
	mysql_free_result($res);
	$sql="insert into feeonlinetrans (cdate,year,sch_id,stu_id,stu_uid,rm,stu_ic,bank,bankno,paydate)
	values(now(),$year,$sid,$stu_id,'$uid',$total,'$stu_ic','$bank','$bankno','$paydate')";
	
	//echo $sql;
   	mysql_query($sql)or die("query failed:".mysql_error());
	$feeid=mysql_insert_id($link);
	if (count($checkbox)>0) {
			for ($i=0; $i<count($checkbox); $i++) {
				$data=$checkbox[$i];
				$prm=strtok($data,"|");
				$val=strtok("|");
				$sql="insert into feeonlinepay (cdate,year,sch_id,stu_id,stu_uid,rm,fee,tid)values(now(),$year,$sid,$stu_id,'$uid',$val,'$prm',$feeid)";
				//echo $sql;
				mysql_query($sql)or die("query failed:".mysql_error());
			}
	}
	if($bank=="CC")
	 echo "<script language=\"javascript\">location.href='https://www.onlinepayment.com.my/NBepay/pay/test4957/?amount=$total&orderid=$feeid&bill_email=$mel&bill_mobile=$hp'</script>";
	else
		echo "<script language=\"javascript\">location.href='p.php?p=feetransview&id=$feeid'</script>";
}else{
	$sql="select * from stu where uid='$uid'";
	$res=mysql_query($sql) or die(mysql_error());
	if($row=mysql_fetch_assoc($res)){
		$name=$row['name'];
		$stu_id=$row['id'];

		$sesid=$row['ses_cls_id'];
		$stu_ic=$row['ic'];
		$sex=$row['sex'];
		$ishostel=$row['ishostel'];
		$isislah=$row['isislah'];
		$p1ic=$row['p1ic'];
		$iskawasan=$row['iskawasan'];
		$isstaff=$row['isstaff'];
		$isyatim=$row['isyatim'];
		$feehutang=$row['feehutang'];
		
		$isfeenew=$row['isfeenew'];
		$isspecial=$row['isspecial'];
		$isinter=$row['isinter'];
		$isfeebulanfree=$row['isfeefree'];
		$isfeeonanak=$row['isfeeonanak'];
		
		if($si_fee_on_childno)//force on anak
			$isfeeonanak=1;
		
		$sql="select * from ses_stu where stu_uid='$uid' and sch_id=$sid and year='$year'";
		$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2)){
			$clsname=$row2['cls_name'];
			$clslevel=$row2['cls_level'];
			$clscode=$row2['cls_code'];
		}
	
		$sql="select * from sch where id='$sid'";
		$res=mysql_query($sql)or die("sql failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$schname=$row['name'];
		$schlevel=$row['level'];
		mysql_free_result($res);
	}
	
	$jumanak=1;
	$noanak=1;
	if($p1ic!=""){
		$jumanak=0;
		$noanak=0;
		$sql="select * from stu where p1ic='$p1ic' order by bday";
		$res=mysql_query($sql) or die(mysql_error());
		$jumanak=mysql_num_rows($res);
		while($row=mysql_fetch_assoc($res)){
			$noanak++;
			$t=$row['ic'];
			if($t==$stu_ic)
				break;			
		}
	}
		
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php include("$MYOBJ/calender/calender.htm")?>
<SCRIPT LANGUAGE="JavaScript">
function check_button(){
	var sum=0;
	var checked=false;
	for (var i=0;i<document.myform.elements.length;i++){
		var e=document.myform.elements[i];
        if ((e.type=='checkbox')){
			if(e.checked==true){
				var str=e.value
				var arr=str.split("|")
				sum=sum+parseFloat(arr[1]);
				checked=true;
			}
			
        }
	}
	document.myform.total.value=sum.toFixed(2);
	if(checked){
		document.myform.btnpayment.disabled=false;
	}
	else{
		document.myform.btnpayment.disabled=true;
	}
}

function process_form(operation){
		if(document.myform.bank.value==""){
			alert("Sila pilih jenis pembayaran");
			document.myform.bank.focus();
			return;
		}
		if(document.myform.bankno.value==""){
			alert("Sila masukkan maklumat rujukan");
			document.myform.bankno.focus();
			return;
		}
		ret = confirm("Confirm bayaran?");
		if (ret == true){
			document.myform.submit();
			return true;
		}
		return false;
}
function process_online(operation){
		if(document.myform.bank.value==""){
			alert("Sila pilih jenis pembayaran");
			document.myform.bank.focus();
			return;
		}

		
		ret = confirm("Confirm bayaran?");
		if (ret == true){
			document.myform.target="newwin";
			newwin = window.open("","newwindow","HEIGHT=600,WIDTH=1000,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar");
			var a = window.setTimeout("document.myform.submit();",500);
			newwin.focus();
		}
		return false;
}

</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Borang Pembayaran Yuran Sekolah</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<!-- SETTING GRAYBOX -->
<script type="text/javascript"> var GB_ROOT_DIR = "<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/"; </script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_scripts.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />
</head>

<body>

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<input type="hidden" name="p" value="fee">
<div id="panelleft">
	<?php include('inc/lmenu.php');?>
</div> 
<div id="content2"> 
<div align="center" style="font-size:14px; font-weight:bold; padding:2px; font-family:Arial; color:#FFFFFF; background-image:url(../img/bg_title_lite.jpg);border: 1px solid #99BBFF;">
		<?php echo strtoupper($name);?>
</div>

<div id="story">
<div id="story_title" ><?php echo strtoupper($lg_fee);?> <?php echo $year;?> : <?php echo strtoupper($name);?></div>
<?php
if($ONLINEFEE){
?>
<font size="1" color="#0000FF">
Perhatian!
Pembayaran hendaklah dibuat terlebih dahulu ke <br>
<strong><?php echo "$ui_fee_footer_bank";?> </strong> 
<br>Pembayaran boleh dibuat secara Tunai(cash-deposit),Cek(cheque-deposit), Online(maybank2u dll).
<br>Selepas pembayaran dijelaskan, barulah maklumat di bawah dikemaskini untuk tindakan lanjut pihak sekolah. Terima Kasih
</font>
<?php } ?>


<?php
	$sql="select * from type where grp='feetype' order by idx";
	$resfeetype=mysql_query($sql)or die("query failed:".mysql_error());
	while($rowfeetype=mysql_fetch_assoc($resfeetype)){
		$feetype=$rowfeetype['val'];
		$feetypename=$rowfeetype['prm'];
?>
	
	<table width="100%" id="mytable">
       <tr id="mytabletitle">
	   	<td width="10%"><strong><?php echo strtoupper($lg_status);?></strong></td>
		<td width="10%"><strong><?php echo strtoupper($lg_date);?></strong></td>
		<td width="10%"><strong><?php echo strtoupper($lg_receipt);?></strong></td>
         <td width="30%"><strong><?php echo strtoupper($feetypename);?></strong></td>
         <td width="40%"><strong><?php echo strtoupper($lg_total);?>(RM)</strong></td>
       </tr>
<?php 
	$sql="select feeset.* from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sid and feeset.year=$xyear and type.grp='yuran' and type.val=$feetype";
	$resfee=mysql_query($sql)or die("query failed:".mysql_error());
	while($rowfee=mysql_fetch_assoc($resfee)){
		$feeformula="../efee/".$CONFIG['FILE_FORMULA']['etc'];
		include($feeformula);

		if($feeval==-1)
			continue;
?>
       <tr bgcolor="#FAFAFA">
         <td  width="10%">
		 <?php
			$sql="select * from feepay where stu_uid='$uid' and year='$year' and fee='$feename' and isdelete=0";
			$res2=mysql_query($sql) or die(mysql_error());
			$found=mysql_num_rows($res2);
			$rm="";
			$dt="";
			$tid="";$resitno="";
			if($found>0){
				$row2=mysql_fetch_assoc($res2);
				$rm=$row2['rm'];
				$tid=$row2['tid'];
				$dt=$row2['cdate'];
				$resitno=$row2['resitno'];
				if($resitno=="")
					$resitno=sprintf("%06d",$tid);
				$dt=strtok($dt," ");
				echo "-PAID-";
			}
			else{
				if($feeval==0){
					echo "-FOC-";
				}else{
           			echo "<input name=\"checkbox[]\" type=\"checkbox\" id=\"checkbox[]\" value=\"$feename|$feeval\" onClick=\"check_button()\">";
				}
			}
		   ?>
		   
         </td>
		 <td width="10%"><?php echo "$dt";?></td>
		 <td width="10%">
		 <?php 
				if ($USE_SCHOOL_RESIT_NUM)
					echo $resitno;
				else 
					printf("%06s",$tid);
		?>
		</td>
         <td width="30%"><?php echo strtoupper("$feename");?></td>
        <td width="40%"><?php if($found) printf("%.02f",$rm); else printf("%.02f",$feeval);?></td>
       </tr>
	 
<?php } ?>
</table>
 <?php } // while feetype ?> 
 
<?php
if($ONLINEFEE){
?>
	<table width="100%" id="mytitle" style="font-size:12px"> 
		<tr>
		<td width="20%" align="right">JUMLAH&nbsp;RM</td>
		<td><input name="total" type="text" id="total" value="0.00" size="4" readonly>(tandakan yuran yang telah dibayar)</td>
       </tr>   
       <tr>
            <td align="right">Email (Penjaga)</td>
            <td><input name="mel" type="text" id="mel" size="48" value="<?php echo $mel;?>"></td>
		</tr>
        <tr>
            <td align="right">Mobile Phone (Penjaga)</td>
            <td><input name="hp" type="text" id="hp" size="48" value="<?php echo $hp;?>"></td>
		</tr>
		<tr>
         <td align="right">Cara&nbsp;Pembayaran</td>
		<td>
			<select name="bank">
				<option value="">- Pilih -</option>
                <option value="CC">Kredit Kad</option>
				<option value="Online">Online-Banking</option>
				<option value="Tunai">Tunai (cash-deposit)</option>
				<option value="Cek">Cek (cheque-deposit)</option>
			</select>   
		</td>
		</tr>
		<tr>
            <td align="right">Rujukan&nbsp;Pembayaran</td>
            <td><input name="bankno" type="text" id="bankno" size="48">  (Nama bank DAN no rujukan/resit/no cek)</td>
		</tr>
		<tr>
			<td align="right">Tarikh&nbsp;Pembayaran</td>
		<td>
		   <input name="paydate" type="text" id="paydate" size="8" readonly value="<?php echo "$paydate";?>">
		   <input name=" cal" type="button" id=" cal" value="-" onClick="c2.popup('paydate')">
		</td>
		</tr>
        <!--
		<tr>
            <td align="right">Confirmkan&nbsp;Bayaran</td>
            <td><input type="button" name="btnpayment" value="Bayar" disabled onClick="return process_form('')"></td>
       </tr>
       -->
       <tr>
            <td align="right" valign="top">ONLINE PAYMENT</td>
            <td >
            	<input type="button" name="btnpayment" value="TERUSKAN BAYARAN" disabled onClick="return process_online('')">
                <a href="fee_online.php" onClick="return GB_showCenter('News Info',this.href,520,1000)" target="_blank">PROSES PEMBAYARAN</a>
                <br>
				Service pembayaran ini adalah selamat/secured dan diuruskan oleh <br>
				Payment Gateway <a href="http://molpay.com" target="_blank">MOLPAY</a> dengan lebih 20 bank seluruh Malaysia.)
            </td>
       </tr>  
</table>
		    <input name="feetype" type="hidden" id="feetype" value="<?php echo "$feetype";?>">
		   <input name="year" type="hidden" id="year" value="<?php echo "$year";?>">
		   <input type="hidden" name="p" value="fee">
<?php } ?>

</div></div>
</form>

</body>
</html>
