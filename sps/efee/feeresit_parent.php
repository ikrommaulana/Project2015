<?php
$vmod="v5.0.0";
$vdate="100909";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEUANGAN');
$adm = $_SESSION['username'];
	
	
	$op=$_REQUEST['op'];
	$feeid=$_REQUEST['id'];
	
	
	if($op=="delete"){
		$feetrans="feetrans";
		$feepay="feepay";
		
		$sql="update feetrans set isdelete=1,deleteby='$adm' where id=$feeid";
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
	$acc=$row['acc'];
	$year=$row['year'];
	$diterimaoleh=$row['admin'];
	$total=$row['rm'];
	$isdelete=$row['isdelete'];
	$paytype=$row['paytype'];
	$resitno=$row['resitno'];
	$paydate=$row['paydate'];
	$nocek=$row['nocek'];
	$rujukan=$row['rujukan'];
	$name=stripslashes($row['name']);
	$ic=$row['ic'];
	$sch_id=$row['sch_id'];
	$sid=$sch_id;
	$dt=$row['cdate'];
	if(($paydate=="")||($paydate=="0000-00-00"))
		$paydate=strtok($dt, " ");
		
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
<script language="JavaScript">
function process_form(op){
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
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>
<body>
<form name="myform" method="post">
	<input type="hidden" name="op" value="<?php echo $op;?>">
</form>
<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<?php 
			if(($op!="delete")&&($op!="viewdel")){
		?>
			<a href="#" onClick="process_form('delete')"id="mymenuitem"><img src="../img/delete.png"><br>Delete</a>
		<?php }  ?>
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
		<a href="#" onClick="javascript: href='<?php echo $FN_FEERESIT;?>.php?id=<?php echo $feeid;?>'" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
		<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
	</div>
	<div align="right">
	 	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
	</div>
</div>
<div id="story" style="border-style:none; <?php echo $CONFIG['RESIT_FONTSIZE']['etc'];?>">


<?php if($CONFIG['RESIT_SHOWHEADER']['val']){ 

	echo "<div id=\"myborder\" style=\"padding:0px 1px 0px 1px\">";
	include ($CONFIG['RESIT_SHOWHEADER']['etc']);
?> 
	<div id="mytitle" align="center"><?php echo strtoupper($lg_official_receipt);?></div>
<?php } else {
	echo $CONFIG['RESIT_SHOWHEADER']['etc'];
?>
	<div id="myborder" style="padding:0px 2px 0px 2px ">
<?php } ?>

<?php if(($op=="delete")||($op=="viewdel")||($isdelete)){?>
<div align="center"><font size="4" color="#FF0000"><strong>DELETED!</strong></font></div>
<?php }?>


 <table width="100%">
       <tr>
         <td width="65%" valign="top"><table width="100%" >

             <tr>
				   <td width="26%" valign="top"><?php echo $lg_date;?></td>
				   <td width="1%" valign="top">:</td>
				   <td valign="top"><?php $dd=strtok($dt," "); list($y,$m,$d)=split('[/.-]',$dd); echo "$d-$m-$y";?></td>
             </tr>
             <tr>
				   <td valign="top"><?php echo $lg_account_no;?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php echo $acc;?></td>
             </tr >
             <tr>
				   <td valign="top"><?php echo $lg_name;?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php echo "$name";?> </td>
             </tr valign="top">
             <tr>
				   <td valign="top"><?php echo $lg_ic_number;?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php echo "$ic";?> </td>
             </tr>
			 <tr>
				   <td valign="top"><?php echo $lg_session;?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php echo "$year";?> </td>
             </tr>
         </table></td>
         <td width="35%" valign="top"><table width="100%" >
             <tr>
				   <td width="45%" valign="top"><?php echo $lg_receipt_no;?></td>
				   <td width="1%" valign="top">:</td>
				   <td width="54%" valign="top"><?php echo $resitno;?></td>
             </tr>
			 <tr>
				   <td valign="top"><?php echo $lg_payment_code;?></td>
				   <td valign="top">:</td>
				   <td valign="top">
				   <?php 
						if($paytype=="TUNAI") echo $lg_cash;
						if($paytype=="K.Kredit") echo $lg_credit_card;
						if($paytype=="CEK") echo $lg_cheque;
						if($paytype=="DLL") echo $lg_other;
						if($paytype=="Online") echo $lg_online;
				   ?>
				   </td>
			 </tr>
			 <tr>
				   <td valign="top"><?php echo $lg_payment_date;?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php $dd=strtok($paydate," "); list($y,$m,$d)=split('[/.-]',$dd); echo "$d-$m-$y";?></td>
             </tr>
			 <tr>
				   <td valign="top"><?php echo $lg_reference;?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php echo "$nocek";?></td>
			 <tr>
				   <td valign="top"><?php echo $lg_receive_by;?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php echo "$diterimaoleh";?></td>
             </tr>
         </table></td>
       </tr>
     </table>
<table width="100%" cellspacing="0">
            <tr id="mytitle">
              <td id="myborder" width="8%" align="center"><?php echo $lg_item;?></td>
              <td id="myborder" style="border-right:none;" width="27%"><?php echo $lg_description;?></td>
			  <td id="myborder" style="border-left:none;" width="50%">&nbsp;</td>
              <td id="myborder" width="15%"colspan="2" align="right"><?php echo $lg_amount;?> (<?php echo $lg_rm;?>)&nbsp;</td>
            </tr>
<?php	
	$sql="select * from $feepay where tid=$feeid order by id";
	$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
		$fee=$row['fee'];
		$etc=stripslashes($row['etc']);
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
  		<tr id="mytitle">
              <td id="myborder" style="border-right:none;" colspan="2">&nbsp;</td>
              <td id="myborder" style="border-left:none;"  align="right"><?php echo $lg_total_payment;?> (<?php echo $lg_rm;?>)&nbsp;&nbsp;</td>
              <td id="myborder" style="border-right:none;" align="right" ><?php echo number_format($total,2,'.',',');?></td>
			  <td id="myborder" style="border-right:none;border-left:none" align="right">&nbsp;</td>
        </tr>
</table> 
</div><!-- end myborderfull -->
<div align="center" style="font-size:9px ">
	<?php echo $CONFIG['RESIT_FOOTER']['des'];?>
</div>

</div></div>
</div><!-- border full -->
</body>
</html>
