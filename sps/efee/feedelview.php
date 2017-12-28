<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK|KEUANGAN');
$username = $_SESSION['username'];
	
	
	$op=$_REQUEST['op'];
	$feeid=$_REQUEST['id'];
	
	
	$op="delete";
	$feetrans="feetrans_del";
	$feepay="feepay_del";
	
	$sql="select * from $feetrans where id=$feeid";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$stu_uid=$row['stu_uid'];
	$year=$row['year'];
	$diterimaoleh=$row['admin'];
	$total=$row['rm'];
	$paytype=$row['paytype'];
	$paydate=$row['paydate'];
	$nocek=$row['nocek'];
	$resitno=$row['resitno'];
	$rujukan=$row['rujukan'];
	$sch_id=$row['sch_id'];
	$sid=$sch_id;
	$dt=$row['cdate'];
	if(($paydate=="")||($paydate=="0000-00-00"))
		$paydate=strtok($dt, " ");
	
	$sql="select * from ses_stu where stu_uid='$stu_uid' and year='$year'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	if($row=mysql_fetch_assoc($res))
		$clsname=stripslashes($row['cls_name']);
	
	$sql="select * from stu where uid='$stu_uid' and sch_id=$sch_id";
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
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
		<a href="#" onClick="javascript: href='feedelview.php?id=<?php echo $feeid;?>'" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
		<a href="#" onClick="window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
	</div>
	<div id="left" align="right">v4.0.0</div>
</div>
<div id="story" style="border-style:none; <?php echo $CONFIG['RESIT_FONTSIZE']['etc'];?>">


<?php if($CONFIG['RESIT_SHOWHEADER']['val']){ 

	echo "<div id=\"myborder\" style=\"padding:0px 1px 0px 1px\">";
	include ($CONFIG['RESIT_SHOWHEADER']['etc']);
?> 
	<div id="mytitle" align="center">RESIT RASMI</div>
<?php } else {
	echo $CONFIG['RESIT_SHOWHEADER']['etc'];
?>
	<div id="myborder" style="padding:0px 2px 0px 2px ">
<?php } ?>

<?php if($op=="delete"){?>
<div align="center"><font size="4" color="#FF0000"><strong>DELETED!</strong></font></div>
<?php }?>

 <?php
	$sql="select * from stu where uid='$stu_uid' and sch_id=$sch_id";
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
   <tr >
     <td><table width="100%">
       <tr>
         <td width="65%"><table width="100%" >
             <tr>
               <td width="26%" valign="top"><?php echo "$lg_sekolah";?></td>
               <td width="1%" valign="top">:</td>
               <td valign="top"><?php echo "$sch_name"; ?> </td>
             </tr>
             <tr>
               <td valign="top">Sesi</td>
               <td valign="top">:</td>
               <td valign="top"><?php echo "$year / $clsname";?></td>
             </tr>
             <tr>
               <td valign="top">Matrik</td>
               <td valign="top">:</td>
               <td valign="top"><?php echo $stu_uid;?></td>
             </tr >
             <tr>
               <td valign="top">Nama</td>
               <td valign="top">:</td>
               <td valign="top"><?php echo "$stu_name";?> </td>
             </tr valign="top">
             <tr>
               <td valign="top">Kad Pengenalan</td>
               <td valign="top">:</td>
               <td valign="top"><?php echo "$stu_ic";?> </td>
             </tr>
         </table></td>
         <td width="35%" valign="top"><table width="100%" >
             <tr>
               <td width="45%" valign="top">No Resit</td>
               <td width="1%" valign="top">:</td>
               <td width="54%" valign="top"><?php echo $resitno;?>
               </td>
             </tr>
             <tr>
               <td valign="top">Tarikh</td>
               <td valign="top">:</td>
               <td valign="top"><?php $dd=strtok($dt," "); list($y,$m,$d)=split('[/.-]',$dd); echo "$d-$m-$y";?>
               </td>
             </tr>
			 <tr>
               <td valign="top">Kod Bayaran</td>
               <td valign="top">:</td>
               <td valign="top"><?php echo "$paytype";?>
               </td valign="top">
			   <tr>
               <td valign="top">Rujukan Bayaran </td>
               <td valign="top">:</td>
               <td valign="top"><?php echo "$nocek";?>
               </td>
             </tr>
			   <tr>
               <td valign="top">Tarikh Bayaran </td>
               <td valign="top">:</td>
               <td valign="top"><?php $dd=strtok($paydate," "); list($y,$m,$d)=split('[/.-]',$dd); echo "$d-$m-$y";?>
               </td>
             </tr>
			 <tr>
               <td valign="top">Diterima Oleh </td>
               <td valign="top">:</td>
               <td valign="top"><?php echo "$diterimaoleh";?>
               </td>
             </tr>
         </table></td>
       </tr>
     </table></td>
   </tr>
</table>
<table width="100%" cellspacing="0">
            <tr id="mytitle">
              <td id="myborder" width="10%" style="border-left:none" align="center">Bil</td>
              <td id="myborder" width="50%" style="border-left:none">Keterangan</td>
              <td id="myborder" width="40%" style="border-right:none" colspan="2" align="center">Jumlah (RP)</td>
            </tr>
	<?php	
	$sql="select * from $feepay where tid=$feeid order by id";
	$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
		$fee=$row['fee'];
		$etc=$row['etc'];
		if($etc!="")
			$fee="$fee : $etc";
		$rm=$row['rm'];
		$q++;
?>
		<tr>
			<td id="myborder" align=center  style="border-left:none"><?php echo $q;?></td>
			<td id="myborder"><?php echo $fee;?></td>
			<td id="myborder" style="border-right:none; margin:20px 20px 20px 20px;" align=right><?php echo number_format($rm,2,'.',',');?></td>
			<td id="myborder" style="border-right:none;border-left:none;">&nbsp;</td>
  		</tr>
<?php  }  ?>
	<tr id="mytitle">
              <td id="myborder" style="border-left:none;">&nbsp;</td>
              <td id="myborder"><strong>Jumlah Bayaran</strong></td>
              <td id="myborder" align="right" style="border-right:none;"><strong><?php echo number_format($total,2,'.',',');?></strong></td>
			  <td id="myborder" width="18%"   style="border-right:none;border-left:none" align="right">&nbsp;</td>
        </tr>
</table> 
</div><!-- end myborderfull -->
<table width="100%" style="font-size:70% ">
  <tr>
    <td align="center">
	<font face="Palatino Linotype">
	<?php echo "$ui_fee_footer";?></font></td>
  </tr>
</table> 

</div></div>
</div><!-- border full -->
</body>
</html>
