<?php
$vmod="v5.0.0";
$vdate="100909";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEUANGAN');
$username = $_SESSION['username'];
	$feeid=$_GET['id'];
	$sql="select * from feeonlinetrans where id=$feeid";
	$res=mysql_query($sql) or die(mysql_error());
	if($row=mysql_fetch_assoc($res)){
			$year=$row['year'];
			$sch_id=$row['sch_id'];
			$stu_id=$row['stu_id'];
			$total=$row['rm'];
			$bank=$row['bank'];
			$status=$row['sta'];
			$bankno=$row['bankno'];
			$paydate=$row['paydate'];
			$clsname=$row['clsname'];
			
			$sql="select * from type where grp='feesta' and val=$status order by val";
	        $res=mysql_query($sql)or die("query failed:".mysql_error());
    	    $row=mysql_fetch_assoc($res);
            $statusfee=$row['prm'];
	}
	mysql_free_result($res);
	
	$sql="select * from stu where id='$stu_id'";
	$res=mysql_query($sql) or die(mysql_error());
	if($row=mysql_fetch_assoc($res)){
			$stu_name=$row['name'];
			$stu_ic=$row['ic'];
			$stu_uid=$row['uid'];
			$stu_clscode=$row['clscode'];
	}
	mysql_free_result($res);

	$cek=mysql_query("select * from ses_stu where stu_uid='$stu_uid' and year='$year' and sch_id='$sch_id'");
	$cekcode=mysql_fetch_array($cek);
	$clscode=$cekcode['cls_code'];
	$namecls=$cekcode['cls_name'];
	
	if($clsname==""){
	$sqlclsname="select * from cls where sch_id='$sch_id' and code='$stu_clscode'";
	$resclsname=mysql_query($sqlclsname)or die("$sqlclsname query failed:".mysql_error());
	$rowclsname=mysql_fetch_assoc($resclsname);
	$clsname=$rowclsname['name'];
	}
	
	$sql="select * from sch where id='$sch_id'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
    $sch_name=$row['name'];
	mysql_free_result($res);
	
?> 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript">
function process_form(operation){
	
		if(document.form1.status.value==0){
			alert("Please select status");
			document.form1.status.focus();
			return;
		}
		if(document.form1.status.value==2)
			ret = confirm("Confirm Reject??");
		else
			ret = confirm("Confirm Accept??");
		if (ret == true){
			document.form1.submit();
		}
	
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>

<body>
<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="#" onClick="<?php if($f!=""){?>top.document.myform.submit();<?php }?>window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
	</div>
	<div align="right">
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
	</div>
</div>

<div id="story">
<div id="mytitlebg"><?php echo strtoupper($lg_online_payment);?></div>
<form name="form1" method="post" action="feeonlinesave.php">
<input type="hidden" name="p" value="../efee/feeonlinesave">

<table width="100%" >
   <tr >
     <td>     
	 <table width="100%">
       <tr>
         <td width="67%"><table width="100%"  border="0" cellpadding="0" cellspacing="3">
             <tr>
               <td width="38%"><?php echo strtoupper($lg_school);?></td>
               <td width="3%">:</td>
               <td width="59%"><?php echo "$sch_name"; ?> </td>
             </tr>
             <tr>
               <td width="38%" ><?php echo strtoupper($lg_class);?></td>
               <td width="3%">:</td>
               <td width="59%"><?php echo "$namecls";?></td>
             </tr>
	     <tr>
               <td width="38%" ><?php echo strtoupper($lg_year_session);?></td>
               <td width="3%">:</td>
               <td width="59%"><?php echo "$year";?></td>
             </tr>
             <tr>
               <td width="38%" ><?php echo strtoupper($lg_matric);?></td>
               <td width="3%">:</td>
               <td width="59%"><?php echo $stu_uid;?></td>
             </tr>
             <tr>
               <td width="38%" ><?php echo strtoupper($lg_name);?></td>
               <td width="3%">:</td>
               <td width="59%"><?php echo "$stu_name";?> </td>
             </tr>
             <tr>
               <td width="38%" ><?php echo strtoupper($lg_ic_number);?></td>
               <td width="3%">:</td>
               <td width="59%"><?php echo "$stu_ic";?> </td>
             </tr>
         </table></td>
         <td width="33%"><table width="100%"  border="0" cellpadding="0" cellspacing="3">
             <tr>
               <td width="34%"><?php echo strtoupper("Nomor Resi Online");?></td>
               <td width="3%">:</td>
               <td width="63%"><?php $ff=sprintf("%06s",$feeid); echo "$ff";?>
               </td>
             </tr>
             <tr>
               <td width="34%" ><?php echo strtoupper($lg_date);?></td>
               <td width="3%">:</td>
               <td width="63%"><?php $dt=date("Y-m-d");echo "$dt";?>
               </td>
             </tr>
			  <tr>
               <td width="34%" >TANGGAL PEMBAYARAN</td>
               <td width="3%">:</td>
               <td width="63%"><?php echo "$paydate";?>
               </td>
             </tr>
             <tr>
               <td width="34%"> <?php echo strtoupper($lg_status);?></td>
               <td width="3%">:</td>
               <td width="63%"><?php echo "$statusfee";?> </td>
             </tr>
             <tr>
               <td width="34%"><?php echo strtoupper("cara Pembayaran");?></td>
               <td width="3%">:</td>
               <td width="63%"><?php echo "$bank";?> </td>
             </tr>
             <tr>
               <td width="34%"><?php echo strtoupper("tanda bukti pembayaran");?></td>
               <td width="3%">:</td>
               <td width="63%"><?php echo "$bankno";?> </td>
             </tr>
         </table></td>
       </tr>
     </table></td>
   </tr>
</table>


          <table width="100%" cellspacing="0">
            <tr >
              <td id="mytabletitle" width="5%" align="center">ITEM</td>
              <td id="mytabletitle" width="40%">KETERANGAN IURAN</td>
              <td id="mytabletitle" width="10%">JUMLAH (Rp)</td>
			  <td id="mytabletitle" width="45%">&nbsp;</td>
            </tr>

<?php	
	$sql="select * from feeonlinepay where tid=$feeid order by id";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
		$fee=$row['fee'];
		$rm=$row['rm'];
		if(($q++%2)==0)
				$bgc="bgcolor=#FAFAFA";
		else
				$bgc="";
?>
            <tr >
              <td id="myborder" align="center"><?php echo "$q";?></td>
              <td id="myborder"><?php echo "$fee";?></td>
              <td id="myborder"><?php echo "$rm.00";?></td>
			  <td id="myborder">&nbsp;</td>
            </tr>

<?php } ?>
            <tr id="mytitle" style=" font-size:14px">
              <td id="myborder">&nbsp;</td>
              <td id="myborder">Jumlah Pembayaran:</td>
              <td id="myborder"><?php echo "$total.00";?></td>
			  <td id="myborder">
			  <?php 
  		  	    $sql="select * from type where grp='feesta' and val=$status";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
            	$row=mysql_fetch_assoc($res);
                $a=$row['prm'];
				$b=$row['val'];
				if($b!=0)
					$disabled="DISABLED";
				echo "<select name=\"status\" id=\"status\" $disabled>";
                echo "<option value=\"$b\">$a</option>";
				$sql="select * from type where grp='feesta' and val!=$status order by val";
	            $res=mysql_query($sql)or die("query failed:".mysql_error());
    	        while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        echo "<option value=\"$v\">$v $s</option>";
        	    }	
?>

                </select>
      <input type="button" name="Button" value="Submit" onClick="return process_form('')" <?php echo $disabled;?>>
	  </td>
            </tr>
		</table>

<input name="feeid" type="hidden" id="feeid" value="<?php echo "$feeid";?>" >                
 </form>
 </div></div>
</body>
</html>