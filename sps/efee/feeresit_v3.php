<?php
//110109 - fixed online
//120615 - add remark delete
//120095 - remove sid from select stu
$vmod="v6.1.1";
$vdate="120095";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEUANGAN');
$adm = $_SESSION['username'];
	
	
	$op=$_REQUEST['op'];
	$feeid=$_REQUEST['id'];
	$delrem=$_REQUEST['delrem'];
	
	if($op=="delete"){
		$feetrans="feetrans";
		$feepay="feepay";
		$sql="update feetrans set isdelete=1,deleteby='$adm',deletets=now(),deleterem='$delrem' where id=$feeid";
		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$sql="update feepay set isdelete=1,deleteby='$adm' where tid=$feeid";
		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		
		$sql="select * from feetrans where id=$feeid";
		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$onlineidx=$row['onlineid'];
		$sid=$row['sch_id'];
		if($onlineidx>0){
			$sql="update feeonlinetrans set sta=0  where id=$onlineidx";
			$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		}
		$sql="update feestu set sta=0, rno=0  where rno=$feeid";
		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		
		sys_log($link,"Fee","Payment Delete","Receipt:$feeid","",$sid);
		
	}elseif($op=="viewdel"){
			$feetrans="feetrans_del";
			$feepay="feepay_del";
	}else{
		$feetrans="feetrans";
		$feepay="feepay";
	}
	$sql="select * from $feetrans where id=$feeid";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$stu_uid=$row['stu_uid'];
	$year=$row['year'];
	$diterimaoleh=$row['admin'];
	$total=$row['rm'];
	$isdelete=$row['isdelete'];
	$deleterem=$row['deleterem'];
	$paytype=$row['paytype'];
	$resitno=$row['resitno'];
	$paydate=$row['paydate'];
	$nocek=$row['nocek'];
	$rujukan=$row['rujukan'];
	$sch_id=$row['sch_id'];
	$sid=$sch_id;
	$dt=$row['cdate'];
	
	$open=mysql_query("SELECT *FROM usr WHERE uid='$diterimaoleh'") or die (mysql_error());
	$read=mysql_fetch_assoc($open);
	if($read>0) {
		$diterima=$read['name'];
	}
	else {
		$diterima=$diterimaoleh;
	}
	if(($paydate=="")||($paydate=="0000-00-00"))
		$paydate=strtok($dt, " ");
	
	$sql="select * from ses_stu where stu_uid='$stu_uid' and year='$year'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	if($row=mysql_fetch_assoc($res))
		$clsname=stripslashes($row['cls_name']);
	
	//$sql="select * from stu where uid='$stu_uid' and sch_id=$sch_id";
	$sql="select * from stu where uid='$stu_uid'";
	$res=mysql_query($sql) or die("$sql - query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$name=stripslashes($row['name']);
		
	$sql="select * from sch where id='$sch_id'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=stripslashes($row['name']);
		$slevel=$row['level'];
		$addr=$row['addr'];
		$state=$row['state'];
		$tel=$row['tel'];
		$fax=$row['fax'];
		$web=$row['url'];
		$school_img=$row['img'];
        mysql_free_result($res);
		
	$sql="select * from type where grp='fee_interface' and (sid=0 or sid='$sid')";
	$res=mysql_query($sql) or die("$sql failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
			$prm=$row['prm'];
			$CONFIG[$prm]['val']=$row['val'];
			$CONFIG[$prm]['code']=$row['code'];
			$CONFIG[$prm]['etc']=$row['etc'];
			$CONFIG[$prm]['des']=$row['des'];
	}

?> 

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<script language="JavaScript">
function process_form(op){
	if(document.myform.delrem.value==""){
				alert("Please enter the information");
				document.myform.delrem.focus();
				return;
	}
	ret = confirm("Are you sure want to delete??");
	if (ret == true){
		document.myform.op.value=op;
		document.myform.submit();
	}
	return;
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Resit - <?php echo $name;?></title>

</head>
<body>
<form name="myform" method="post">
	<input type="hidden" name="op" value="<?php echo $op;?>">

<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<?php 
			if(($op!="delete")&&($op!="viewdel")){
		?>
			<a href="#" onClick="showhide('divdel')"id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
                         <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
		<?php }  ?>
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>        
		<a href="#" onClick="javascript: href='<?php echo $FN_FEERESIT;?>.php?id=<?php echo $feeid;?>'" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
        				<div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>        
	</div>
	<div align="right">
	 	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
	</div>
</div>

<div id="story" style="border-style:none; <?php echo $CONFIG['RESIT_FONTSIZE']['etc'];?>">
<div id="divdel" style="background-color:#FAFAFA; font-size:12px; font-weight:bold; display:none">
<br>
	Alasan Di Hapus :<br>
    <input type="text" name="delrem" size="48" maxlength="48"><input type="button" value="Hapus"  onClick="process_form('delete')">
<br>
<br>
</div>

<?php if($CONFIG['RESIT_SHOWHEADER']['val']){ 

	echo "<div id=\"myborder\" style=\"padding:0px 1px 0px 1px\">";
	include ($CONFIG['RESIT_SHOWHEADER']['etc']);
?> 
	<div id="mytitle" align="center"><?php echo strtoupper($lg_payment_receipt);?></div>
<?php } else {
	echo $CONFIG['RESIT_SHOWHEADER']['etc'];
?>
	<div id="myborder" style="padding:0px 2px 0px 2px ">
<?php } ?>

<?php if(($op=="delete")||($op=="viewdel")||($isdelete)){?>
<div align="center" style="font-size:12px; color:#FF0000">
<font size="4" color="#FF0000"><strong>DELETED!</strong></font><br>
Reason&nbsp;:&nbsp;
<?php echo $deleterem;?>
</div>
<?php }?>

 <?php
	$sql="select * from stu where uid='$stu_uid'";
	$res=mysql_query($sql) or die(mysql_error());
	$row=mysql_fetch_assoc($res);
	$stu_name=stripslashes($row['name']);
	$stu_ic=$row['ic'];
	mysql_free_result($res);
	
	$sql="select * from sch where id='$sch_id'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
    $sch_name=$row['name'];
	mysql_free_result($res);
?>

 <table width="100%">
   <tr>
     <td><table width="100%">
       <tr>
         <td width="65%" valign="top"><table width="100%" >
             <tr>
				   <td width="26%" valign="top"><?php echo $lg_school;?></td>
				   <td width="1%" valign="top">:</td>
				   <td valign="top"><?php echo "$sch_name"; ?> </td>
             </tr>
             <tr>
				   <td valign="top"><?php echo "$lg_class / $lg_year_session";?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php echo "$clsname / $year";?></td>
             </tr>
             <tr>
				   <td valign="top"><?php echo $lg_matric;?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php echo $stu_uid;?></td>
             </tr >
             <tr>
				   <td valign="top"><?php echo $lg_name;?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php echo "$stu_name";?> </td>
             </tr valign="top">
             <tr>
				   <td valign="top"><?php echo $lg_icnumber;?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php echo "$stu_ic";?> </td>
             </tr>
         </table></td>
         <td width="35%" valign="top"><table width="100%" >
             <tr>
				   <td width="45%" valign="top"><?php echo "No Resi";?></td>
				   <td width="1%" valign="top">:</td>
				   <td width="54%" valign="top"><?php echo $resitno;?></td>
             </tr>
             <tr>
				   <td valign="top"><?php echo $lg_date;?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php $dd=strtok($dt," "); list($y,$m,$d)=explode('-',$dd); echo "$d-$m-$y";?></td>
             </tr>
			 <tr>
				   <td valign="top"><?php echo "Jenis Pembayaran";?></td>
				   <td valign="top">:</td>
				   <td valign="top">
				   <?php 
						if($paytype=="TUNAI") echo $lg_cash;
						elseif($paytype=="K.Kredit") echo $lg_credit_card;
						elseif($paytype=="CEK") echo $lg_cheque;
						elseif($paytype=="DLL") echo $lg_other;
						elseif($paytype=="Online") echo $lg_online;
						else echo $paytype;
				   ?>
				   </td>
			 </tr>
			 <tr>
				   <td valign="top"><?php echo "Tanggal Pembayaran";?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php $dd=strtok($paydate," "); list($y,$m,$d)=explode('-',$dd); echo "$d-$m-$y";?></td>
             </tr>
			 <tr>
				   <td valign="top"><?php echo "Surat Tanda Bukti";?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php echo "$nocek";?></td>
			 <tr>
				   <td valign="top"><?php echo $lg_receive_by;?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php echo "$diterima";?></td>
             </tr>
         </table></td>
       </tr>
     </table></td>
   </tr>
</table>

<table width="100%" cellspacing="0">
            <tr id="mytitlebg">
              <td id="myborder" width="10%" align="center"><?php echo $lg_no;?></td>
              <td id="myborder" style="border-right:none;" width="30%"><?php echo $lg_item;?></td>
			  <td id="myborder" style="border-left:none;" width="40%"><?php echo $lg_description;?></td>
              <td id="myborder" width="20%"colspan="2" align="right"><?php echo $lg_amount;?> (<?php echo "Rp";?>)&nbsp;</td>
            </tr>
<?php	
	$sql="select * from $feepay where tid=$feeid order by id";
	$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
		$fee=$row['fee'];
		$etc=$row['etc'];
		$rm=$row['rm'];
		$q++;
?>
		<tr>
			<td id="myborder" align=center><?php echo $q;?></td>
			<td id="myborder" style="border-right:none;"><?php echo $fee;?></td>
			<td id="myborder" style="border-left:none;"><?php echo $etc;?></td>
			<td id="myborder" style="border-right:none; margin:20px 20px 20px 20px;" align=right><?php echo number_format($rm,2,'.',',');?></td>
			<td id="myborder" style="border-right:none;border-left:none;">&nbsp;</td>
  		</tr>
<?php } ?>
  		<tr id="mytitlebg">
              <td id="myborder">&nbsp;</td>
			  <td id="myborder" style="border-right:none;">&nbsp;</td>
              <td id="myborder" style="border-left:none;"><?php echo $lg_total_payment;?> (<?php echo "Rp";?>)&nbsp;&nbsp;</td>
              <td id="myborder" style="border-right:none;" align="right" ><?php echo number_format($total,2,'.',',');?></td>
			  <td id="myborder" style="border-right:none;border-left:none" align="right">&nbsp;</td>
        </tr>
</table> 
</div><!-- end myborderfull -->
<div align="center" style="font-size:9px ">
	
</div>

</div></div>
</div><!-- border full -->
</form>
</body>
</html>
