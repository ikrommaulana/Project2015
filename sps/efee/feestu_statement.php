<?php
$vdate="120321";// take rekod from feestu only
$vdate="120605";// second chek unpaid
$vmod="v6.0.1";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");

verify('ADMIN|AKADEMIK|KEUANGAN');
$adm = $_SESSION['username'];
	
	
	$uid=$_REQUEST['uid'];
	$sid=$_REQUEST['sid'];
	$year=$_REQUEST['year'];
	
	
	$showdetail=$_REQUEST['showdetail'];
	if($showdetail)
		$checked="checked";
	else
		$checked="";
	
		$sql="select * from sch where id=$sid";
        $res=mysql_query($sql)or die("$sql query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=stripslashes($row['name']);
		$slevel=$row['level'];
		$addr=$row['addr'];
		$state=$row['state'];
		$tel=$row['tel'];
		$fax=$row['fax'];
		$web=$row['url'];
		$school_img=$row['img'];
		$issemester=$row['issemester'];
		$startsemester=$row['startsemester'];
		$xx=$year+1;
		if($issemester)
			$sesyear="$year/$xx";	  
		else
			$sesyear="$year";
        mysql_free_result($res);
		
		$sql="select * from ses_stu where stu_uid='$uid' and year='$year'";
		$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
		if($row=mysql_fetch_assoc($res))
			$clsname=stripslashes($row['cls_name']);
	
		$sql="select * from stu where uid='$uid' and sch_id=$sid";
		$res=mysql_query($sql) or die("$sql - query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$stuname=stripslashes($row['name']);
		$p1name=stripslashes($row['p1name']);
		$addr=stripslashes($row['addr']);
		$poscode=stripslashes($row['poskod']);
		$city=stripslashes($row['bandar']);
		$state=stripslashes($row['state']);
		$matric=stripslashes($row['uid']);
		$rdate=stripslashes($row['rdate']);
		$sponser=stripslashes($row['sponser']);
		
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
<title>Statement - <?php echo $stuname;?></title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>
<body>
<form name="myform" method="post">
	<input type="hidden" name="op" value="<?php echo $op;?>">
	<input type="hidden" name="id" value="<?php echo $feeid;?>">

<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="#" onClick="javascript:href='<?php echo "$FN_FEEPAY.php?uid=$uid&sid=$sid&year=$year";?>'" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/goback.png"><br>Back</a>
			<div id="mymenu_space">&nbsp;&nbsp;</div>
			<div id="mymenu_seperator"></div>
			<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br>Print</a>
			<div id="mymenu_space">&nbsp;&nbsp;</div>
			<div id="mymenu_seperator"></div>
			<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="javascript: href='feestu_statement.php?uid=<?php echo "$uid&sid=$sid&year=$year";?>'" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br>Refresh</a>
			<div id="mymenu_space">&nbsp;&nbsp;</div>
			<div id="mymenu_seperator"></div>
			<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/close.png"><br>Close</a>
	</div>
	<div align="right">
	 	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		<!-- 
			<input type="checkbox" name="showdetail" value="1" <?php echo $checked;?> onClick="document.myform.submit();"> SHOW DETAIL
		-->
	</div>
</div>

<div id="story" style="border-style:none; <?php echo $CONFIG['RESIT_FONTSIZE']['etc'];?>">

<?php if($CONFIG['RESIT_SHOWHEADER']['val']){ 
	echo "<div id=\"myborder\" style=\"padding:0px 1px 0px 1px\">";
	include ($CONFIG['RESIT_SHOWHEADER']['etc']);
?> 
<?php } else {
	echo $CONFIG['RESIT_SHOWHEADER']['etc'];
?>
	<div id="myborder" style="padding:0px 2px 0px 2px ">
<?php } ?>


	<div id="myborder" style="padding:0px 1px 0px 1px">
	<div id="mytitle2" align="center">RINCIAN BIAYA</div>


 <table width="100%">
   <tr>
     <td><table width="100%">
       <tr>
         <td width="65%" valign="top"><table width="100%" >
             <tr>
				   <td width="26%" valign="top"><?php echo $lg_parent;?></td>
				   <td width="1%" valign="top">:</td>
				   <td valign="top"><?php echo "$p1name"; ?> </td>
             </tr>
			 <tr>
				   <td width="26%" valign="top"><?php echo $lg_student_name;?></td>
				   <td width="1%" valign="top">:</td>
				   <td valign="top"><?php echo "$stuname"; ?> </td>
             </tr>
			 <tr>
				   <td valign="top"><?php echo $lg_address;?></td>
				   <td valign="top">:</td>
				   <td valign="top">
				   	<?php 
						echo strtoupper(str_replace(",",",<br>",$addr)); 
						if(($poscode!="")||($city!=""))
							echo strtoupper("<br>$poscode $city");
						echo strtoupper("<br>$state");
					?>
				</td>
             </tr>
             
         </table></td>
         <td width="35%" valign="top"><table width="100%" >
             <tr>
				   <td width="45%" valign="top"><?php echo $lg_register_date;?></td>
				   <td width="1%" valign="top">:</td>
				   <td width="54%" valign="top"><?php echo $rdate;?></td>
             </tr>
			 <tr>
				   <td valign="top"><?php echo $lg_class;?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php echo "$clsname";?></td>
             </tr>
             <tr>
				   <td valign="top"><?php echo $lg_matric;?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php echo $matric;?></td>
             </tr >
             <tr>
				   <td valign="top"><?php echo "Tahun Ajaran";?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php echo "$sesyear";?></td>
             </tr>
             <!--
             <tr>
				   <td valign="top"><?php echo $lg_xprimary;?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php echo $sponser;?></td>
             </tr>
			-->
         </table></td>
       </tr>
     </table></td>
   </tr>
</table>
<table width="100%" cellspacing="0" cellpadding="2px">
            <tr id="mytitlebg">
              <td id="myborder" width="10%" align="center"><?php echo $lg_no;?></td>
              <td id="myborder" width="30%"><?php echo "Detail Pembayaran";?></td>
			  <td id="myborder" width="30%"><?php echo $lg_description;?></td>
			  <td id="myborder" width="30%"><?php echo $lg_date_pay;?></td>
			  <td id="myborder" width="10%" style="padding-right:10px;" align="right"><?php echo $lg_amount;?> (<?php echo "Rp";?>)</td>
			  <td id="myborder" width="10%" style="padding-right:10px;" align="right"><?php echo $lg_discount;?> (<?php echo "Rp";?>)</td>
			  <td id="myborder" width="10%" style="padding-right:10px;" align="right"><?php echo $lg_total;?> (<?php echo "Rp";?>)</td>
            </tr>
<?php	
		$sql="select sum(rm) from feepay where stu_uid='$uid' and fee='ADVANCE' and isdelete=0";
		$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		if($row2=mysql_fetch_row($res2))
			$advance=$row2[0];
		else
			$advance=0;
		
		$sql="select sum(rm) from feepay where stu_uid='$uid' and fee='RECLAIM' and isdelete=0";
		$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		if($row2=mysql_fetch_row($res2))
			$reclaim=$row2[0];
		else
			$reclaim=0;

		$advance=$advance+$reclaim; //statebus negative so kena +
		
		
	
	$sql="select * from feestu where uid='$uid' and ses='$year' and val>=0 and sta>=0 order by typ,mon";
	$res8=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row8=mysql_fetch_assoc($res8)){
			$fee=$row8['fee'];
			$val=$row8['val'];
			$disval=$row8['disval'];
			$rm=$row8['rm'];//after discount
			if(($val+$disval)!=$rm)// to cover old system not culculate dicount
				$rm=$val+$disval;
			$sta=$row8['sta'];
			$resitno=$row8['resitno'];
			$dtm=explode(" ",$row8['dtm']);
			$tgl=$dtm[0];
			
			$bg="";
			if($sta==1){
				$paid=$paid+$val;
				$bg='#FAFAFA';
			}
			else{
					//second check
						$sql="select * from feepay where stu_uid='$uid' and year='$year' and fee='$fee' and isdelete=0";
						$res9=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$foundpaid=mysql_num_rows($res9);
						if($foundpaid==0){
							$unpaid=$unpaid+$val;
						}else{
							$paid=$paid+$val;
							$bg='#FAFAFA';
							$sta=1;
						}
						
				
			}
			$q++;
			
		
?>
		<tr bgcolor="<?php echo $bg;?>">
			<td id="myborder" align="center"><?php echo $q;?></td>
			<td id="myborder"><?php echo $fee;?></td>
			<td id="myborder"><?php if($sta==1) echo "LUNAS ";echo $resitno;?></td>
			<?php if($sta==1)
			{
				$sqltgl="select * from feepay where stu_uid='$uid' and resitno='$resitno' and isdelete=0 and year='$year'";
				$file=mysql_query($sqltgl)or die("$sqltgl - query failed:".mysql_error());
				$open=mysql_fetch_assoc($file);
				$dtm=explode(" ",$open['cdate']);
				$tgl=$dtm[0];
				echo "<td id='myborder'>$tgl</td>";
			}
			else {
				echo "<td id='myborder'>&nbsp;</td>";
			}
			?>
			<td id="myborder" style="padding-right:10px;" align=right><?php echo number_format($rm,2,'.',',');?></td>
			<td id="myborder" style="padding-right:10px;" align=right><?php echo number_format($disval,2,'.',',');?></td>
			<td id="myborder" style="padding-right:10px;" align=right><?php echo number_format($val,2,'.',',');?></td>
  		</tr>
<?php } ?>

		<tr id="mytitlebg">
              <td id="myborder" colspan="6" align="center">RINGKASAN PENYATAAN</td>
        </tr>
		<tr id="mytitlebg">
              <td>&nbsp;</td>
			  <td>&nbsp;</td>
              <td>&nbsp;</td>
	      <td>&nbsp;</td>
              <td id="myborder" style="padding-right:10px;" align=right colspan="2">TOTAL BIAYA</td>
			  <td id="myborder" style="padding-right:10px;" align=right><?php echo number_format($unpaid+$paid,2,'.',',');?></td>
        </tr>
		<tr id="mytitlebg">
              <td>&nbsp;</td>
			  <td>&nbsp;</td>
              <td>&nbsp;</td>
	      <td>&nbsp;</td>
              <td id="myborder" style="padding-right:10px; font-weight:normal" align=right colspan="2">BIAYA YANG SUDAH DIBAYAR</td>
			  <td id="myborder" style="padding-right:10px; font-weight:normal" align=right><?php echo number_format($paid,2,'.',',');?></td>
        </tr>
        <tr id="mytitlebg">
              <td>&nbsp;</td>
			  <td>&nbsp;</td>
              <td>&nbsp;</td>
	      <td>&nbsp;</td>
              <td id="myborder" style="padding-right:10px; font-weight:normal" align=right colspan="2">BIAYA YANG BELUM DIBAYAR</td>
			  <td id="myborder" style="padding-right:10px; font-weight:normal" align=right><?php echo number_format($unpaid,2,'.',',');?></td>
        </tr>
		<tr id="mytitlebg">
              <td>&nbsp;</td>
			  <td>&nbsp;</td>
              <td>&nbsp;</td>
	      <td>&nbsp;</td>
              <td id="myborder" style="padding-right:10px; font-weight:normal" align=right colspan="2">ADVANCE (BIAYA DI MUKA)</td>
			  <td id="myborder" style="padding-right:10px; font-weight:normal" align=right><?php echo number_format($advance,2,'.',',');?></td>
        </tr>
        <tr id="mytitlebg">
              <td>&nbsp;</td>
			  <td>&nbsp;</td>
              <td>&nbsp;</td>
	      <td>&nbsp;</td>
              <td id="myborder" style="padding-right:10px;" align=right colspan="2">BIAYA YANG BELUM DIBAYAR</td>
			  <td id="myborder" style="padding-right:10px;" align=right><?php echo number_format($unpaid-$advance,2,'.',',');?></td>
        </tr>
</table> 
</div><!-- end myborderfull -->
<div align="center" style="font-size:9px ">
	<?php echo $CONFIG['RESIT_FOOTER']['des'];?>
</div>

</div></div>
</div><!-- border full -->

</form>
</body>
</html>
