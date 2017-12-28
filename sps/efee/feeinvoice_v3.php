<?php
//110109 - fixed online
$vmod="v5.0.0";
$vdate="110109";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEUANGAN');
$adm = $_SESSION['username'];
	
	
	$op=$_REQUEST['op'];
	$feeid=$_REQUEST['id'];
	
	
	$feetrans="invoice2";
	$feepay="invoice2_item";
	
	if($op=="delete"){

		$sql="update invoice2 set isdelete=1,deleteby='$adm' where id=$feeid";
		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$sql="update invoice2_item set isdelete=1,deleteby='$adm' where tid=$feeid";
		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				
		sys_log($link,"Fee","Invoice Delete","Invoice:$feeid","",$sid);
		
	}
	
	$sql="select * from $feetrans where id=$feeid";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$stu_uid=$row['stu_uid'];
	$year=$row['year'];
	$diterimaoleh=$row['admin'];
	$total=$row['rm'];
	$isdelete=$row['isdelete'];
	$paytype=$row['paytype'];
	$resitno=$row['resitno'];
	$paydate=$row['paydate'];
	$nocek=$row['nocek'];
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
		<a href="#" onClick="javascript: href='feeinvoice_v3.php?id=<?php echo $feeid;?>'" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
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
	<div id="mytitle" align="center" style="font-size:14px ">INVOICE</div>
<?php } else {
	echo $CONFIG['RESIT_SHOWHEADER']['etc'];
?>
	<div id="myborder" style="padding:0px 2px 0px 2px ">
<?php } ?>

<?php if(($op=="delete")||($op=="viewdel")||($isdelete)){?>
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
				   <td valign="top"><?php echo $lg_class;?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php echo "$clsname / $year";?></td>
             </tr>
             <tr>
				   <td valign="top"><?php echo $lg_matric;?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php echo $stu_uid;?></td>
             </tr >
             <tr>
				   <td valign="top"><?php echo $lg_student_name;?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php echo "$stu_name";?> </td>
             </tr valign="top">
             <tr>
				   <td valign="top"><?php echo $lg_ic_number;?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php echo "$stu_ic";?> </td>
             </tr>
         </table></td>
         <td width="35%" valign="top"><table width="100%" >
             <tr>
				   <td width="45%" valign="top">Invoice No</td>
				   <td width="1%" valign="top">:</td>
				   <td width="54%" valign="top"><?php echo $resitno;?></td>
             </tr>
             <tr>
				   <td valign="top"><?php echo $lg_date;?></td>
				   <td valign="top">:</td>
				   <td valign="top"><?php $dd=strtok($dt," "); list($y,$m,$d)=split('[/.-]',$dd); echo "$d-$m-$y";?></td>
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
              <td id="myborder" width="20%"colspan="2" align="right"><?php echo $lg_amount;?> (<?php echo $lg_rm;?>)&nbsp;</td>
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
              <td id="myborder" style="border-left:none;"><?php echo $lg_total_amount;?> (<?php echo $lg_rm;?>)&nbsp;&nbsp;</td>
              <td id="myborder" style="border-right:none;" align="right" ><?php echo number_format($total,2,'.',',');?></td>
			  <td id="myborder" style="border-right:none;border-left:none" align="right">&nbsp;</td>
        </tr>
</table> 
<br>
<br>
Please make payment to ...
<br>
<br>
<br>

</div><!-- end myborderfull -->
<div align="center" style="font-size:9px ">
	<?php //echo $CONFIG['RESIT_FOOTER']['des'];?>
	
</div>

</div></div>
</div><!-- border full -->
</body>
</html>
