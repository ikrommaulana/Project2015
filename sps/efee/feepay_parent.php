<?php
/** 
27/03/2010 - repair paydate kasi betul kat table feestu
01/03/2010 - upgrade fee_formula file
21/02/2010 - upgrade tebus and advance
31/08/2010 - commit trans,interface
**/
$vmod="v5.1.0";
$vdate="110503";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");

verify('ADMIN|AKADEMIK|KEUANGAN');
$username = $_SESSION['username'];
$ISCOMMIT=1;

	$sid=$_REQUEST['sid'];
	if($sid=="")
			$sid=$_SESSION['sid'];
	$year=$_REQUEST['year'];
	if($year=="")
		$year=date("Y");
			
	$uid=$_REQUEST['uid'];
	if($uid!=""){
		$sql="select * from stu where uid='$uid' and sch_id=$sid";
		$res=mysql_query($sql) or die("$sql - query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$acc=$row['acc'];
	}else
		$acc=$_REQUEST['acc'];
	$op=$_REQUEST['op'];
	
		
	
		if($sid==0){
			if(!$PARENT_CHILD_ALL_SCHOOL)
				$sqlsid=" and stu.sch_id=$sid";
		}else{
				$sqlsid=" and stu.sch_id=$sid";
		}
		
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
	
	$saleunit=$_POST['saleunit'];
	$salecode=$_POST['salecode'];
	$saleitem=$_POST['saleitem'];
	$saleprice=$_POST['saleprice'];
	$saletotal=$_POST['saletotal'];
	$saletype=$_POST['saletype'];
	$saledis=$_POST['saledis'];
	
	
	$rujukan=$_POST['rujukan'];
	if($onadvance=="")
		$onadvance=0;
	$ontebus=$_POST['ontebus'];
	$jumtebus=$_POST['jumtebus'];
	
	$sql="select * from stu where acc='$acc' order by id desc";
	$res=mysql_query($sql) or die("$sql - query failed:".mysql_error());
	if($row=mysql_fetch_assoc($res)){
		$name=stripslashes($row['name']);
		$p1name=stripslashes($row['p1name']);
		$p1ic=stripslashes($row['p1ic']);
		$acc=stripslashes($row['acc']);
		$stuuid=stripslashes($row['uid']);
		
		$sql="select sum(rm) from feepay where stu_uid='$acc' and fee='advance' and isdelete=0";
		$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		if($row2=mysql_fetch_row($res2))
			$advance=$row2[0];
		else
			$advance=0;
		
		$sql="select sum(rm) from feepay where stu_uid='$acc' and fee='TEBUS' and isdelete=0";
		$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		if($row2=mysql_fetch_row($res2))
			$stutebus=$row2[0];
		else
			$stutebus=0;

		$advance=$advance+$stutebus; //statebus negative so kena +
		//echo "SSS: $advance=$advance+$stutebus;";

	
		$sql="select * from sch where id=$sid";
		$res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$schname=$row['name'];
		$schlevel=$row['level'];
		$sidresitno=$row['resitno']+1;
		$scode=$row['scode'];
		mysql_free_result($res);
	
	}
		
	$jumanak=0;
	$noanak=0;
	if($PARENT_CHILD_ALL_SCHOOL)
			$sql="select * from stu where p1ic='$p1ic' and status=6 order by bday";
	else
			$sql="select * from stu where p1ic='$p1ic' and sch_id=$sid and status=6 order by bday";
	$res=mysql_query($sql) or die("$sql - query failed:".mysql_error());
	$jumanak=mysql_num_rows($res);
	while($row=mysql_fetch_assoc($res)){
		$noanak++;
		$t=$row['ic'];
		if($t==$ic)
			break;			
	}
	
if (($op==save)&&((count($cbox)>0)||($ontebus>0)||($jumbayar>0))) {
	//mysql_query("BEGIN");
	if ($USE_SCHOOL_RESIT_NUM){
		$newresitno=sprintf("$scode/%06d",$sidresitno);
		$sql="update sch set resitno=$sidresitno where id=$sid";
		mysql_query($sql)or die("$sql - failed:".mysql_error());
	}else{
		$newresitno=sprintf("%06d",$sidresitno);
		$sql="update sch set resitno=$sidresitno";
		mysql_query($sql)or die("$sql - failed:".mysql_error());
	}
	$namex=mysql_real_escape_string($p1name);
	$sql="insert into feetrans (cdate,year,sch_id,stu_uid,rm,admin,paytype,nocek,paydate,rujukan,resitno,name,ic,acc)
	values(now(),$year,$sid,'$stuuid',$jumbayar,'$username','$paytype','$nocek','$paydate','$rujukan','$newresitno','$namex','$p1ic','$acc')";
   	mysql_query($sql)or die("$sql - query failed:".mysql_error());
	$feeid=mysql_insert_id($link);
				
	if (count($cbox)>0) {
			for ($i=0; $i<count($cbox); $i++) {
				$data=$cbox[$i];
				list($prm,$val,$xuid,$xxfeetype)=explode("|",$data);
				$sql="select * from stu where uid='$xuid'";
				$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				$row=mysql_fetch_assoc($res);
				$xname=ucwords(strtolower(stripslashes($row['name'])));
				
				$sql="select * from ses_stu where stu_uid='$xuid' and sch_id=$sid and year='$year'";
				$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				$row=mysql_fetch_assoc($res);
				$xcls=ucwords(strtolower(stripslashes($row['cls_name'])));
				$etc=addslashes("$xuid:$xname - $xcls");
				
				$sql="insert into feepay (cdate,year,sch_id,stu_uid,rm,fee,resitno,type,tid,admin,acc,etc,item_type)
					values (now(),$year,$sid,'$xuid',$val,'$prm','$newresitno','fee',$feeid,'$username','$acc','$etc','$xxfeetype')";
				mysql_query($sql)or die("$sql - failed:".mysql_error());

				$sql="update feestu set sta=1,val=$val,rno=$feeid,pdt='$paydate',adm='$username',resitno='$newresitno' where uid='$xuid' and ses='$year' and sid='$sid' and fee='$prm'";
				mysql_query($sql)or die("$sql - failed:".mysql_error());

			}
	}

	for ($i=0; $i<count($saleunit); $i++) {
			if($saleunit[$i]>0){
				$cod=$salecode[$i];
				$nam=$saleitem[$i];
				$tot=$saletotal[$i];
				$qtt=$saleunit[$i];
				$grp=$saletype[$i];
				$val=$saleprice[$i];
				$dis=$saledis[$i];
				$disc="";
				if($dis>0)
					$disc=" Less $dis"."%";
				$etc="$qtt x RP$val". $disc;
				$sql="insert into feepay (cdate,year,sch_id,stu_uid,rm,fee,resitno,
						item_code,item_name,item_price,item_type,unit,type,etc,tid,admin)
						values(now(),$year,$sid,'$acc',$tot,'$nam','$newresitno',
						'$cod','$nam','$val','$grp','$qtt','sale','$etc',$feeid,'$username')";
				if(!mysql_query($sql)){
					 echo "$sql - query failed:".mysql_error();
					// mysql_query("ROLLBACK");
					 //$ISCOMMIT=0;
					 mysql_query($sql)or die("$sql - failed:".mysql_error());
				}
			}
	}

	if($onadvance){
		$sql="insert into feepay (cdate,year,sch_id,stu_uid,rm,fee,tid,resitno,type,admin,acc)values(now(),$year,$sid,'$acc',$payadvance,'ADVANCE',$feeid,'$newresitno','fee','$username','$acc')";
		if(!mysql_query($sql)){
					 echo "$sql - query failed:".mysql_error();
					 mysql_query($sql)or die("$sql - failed:".mysql_error());
					 //mysql_query("ROLLBACK");
					 //$ISCOMMIT=0;
		}
		//$sql="update stu set fee_advance=fee_advance+$payadvance where uid='$stu_uid'";
		//mysql_query($sql)or die("$sql - query failed:".mysql_error());
	}
	if($lainlainval>0){
		$sql="insert into feepay (cdate,year,sch_id,stu_uid,rm,fee,etc,tid,resitno,type,admin,acc)values(now(),$year,$sid,'$acc',$lainlainval,'Lain-Lain','$lainlaindes',$feeid,'$newresitno','fee','$username','$acc')";
		if(!mysql_query($sql)){
					echo "$sql - query failed:".mysql_error();
					mysql_query($sql)or die("$sql - failed:".mysql_error());
					// mysql_query("ROLLBACK");
					 //$ISCOMMIT=0;
		}
	}
	if($jumtebus>0){
		$jumteb="-$jumtebus";
		$sql="insert into feepay (cdate,year,sch_id,stu_uid,rm,fee,tid,resitno,admin,acc)values(now(),$year,$sid,'$acc',$jumteb,'TEBUS',$feeid,'$newresitno','$username','$acc')";
		if(!mysql_query($sql)){
					 echo "$sql - query failed:".mysql_error();
					mysql_query($sql)or die("$sql - failed:".mysql_error());
					// mysql_query("ROLLBACK");
					 //$ISCOMMIT=0;
		}
		//$sql="update stu set fee_advance=fee_advance-$jumtebus where uid='$stu_uid'";
		//mysql_query($sql)or die("$sql - query failed:".mysql_error());
	}
	//if($ISCOMMIT){
		//mysql_query("COMMIT");
		echo "<script language=\"javascript\">location.href='$FN_FEERESIT.php?id=$feeid'</script>";
	//}

}
/**
for ($i=0; $i<count($saleunit); $i++) {
		echo $saleunit[$i].":".$salecode[$i].":".$saleprice[$i].":".$saletotal[$i]."<br>";
}
**/
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php include("$MYOBJ/datepicker/dp.php")?>
<SCRIPT LANGUAGE="JavaScript">
function checkx(){
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
	jualan=parseFloat(document.form1.jualan.value);
	totalall=sum+lain-tebus+jualan;
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
	var advance=0;
	var adv=0;
	total_fee=parseFloat(document.form1.total.value);
	total_pay=parseFloat(document.form1.jumbayar.value);
	total_lain=parseFloat(document.form1.lainlainval.value);
	total_tebus=parseFloat(document.form1.jumtebus.value);
	total_jualan=parseFloat(document.form1.jualan.value);
	
	total_allpay=total_jualan+total_fee+total_lain-total_tebus;
	advance=total_pay-total_jualan-total_fee-total_lain+total_tebus;
	advance=advance.toFixed(2);
	total_fee=total_fee.toFixed(2);
	total_pay=total_pay.toFixed(2);
	total_lain=total_lain.toFixed(2);
	total_jualan=total_jualan.toFixed(2);
	total_tebus=total_tebus.toFixed(2);
	
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
		/**
		if(total_pay==0){
			alert('Sila tandakan pembayaran.');
			return;
		}
		**/
		
		ret = confirm("Yuran      RP "+total_fee+"\r\nJualan     RP "+total_jualan+"\r\nLain-Lain RP "+total_lain+ "\r\nAdvance RP "+advance+"\r\nTebus     RP "+total_tebus+ "\r\nJUMLAH BAYARAN RP "+total_pay+ ". Confirm??");
		if (ret == true){
				document.form1.op.value='save';
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
	jualan=parseFloat(document.form1.jualan.value);
	totalall=total+lain-tebus+jualan;
	document.form1.jumbayar.value=totalall.toFixed(2);
}
function kiratebus(ele,no){
	total=parseFloat(document.form1.total.value);
	lain=parseFloat(document.form1.lainlainval.value);
	tebus=parseFloat(document.form1.jumtebus.value);
	jualan=parseFloat(document.form1.jualan.value);
	totalall=total+lain-tebus+jualan;
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
		jualan=parseFloat(document.form1.jualan.value);
		total_allpay=total_fee+total_lain+jualan;
		document.form1.jumbayar.readOnly=true;
		document.form1.jumbayar.value=total_allpay.toFixed(2);
	}
}

function process_tebus(){
	document.form1.jumtebus.readOnly=false;
	document.form1.btnpayment.disabled=false;
}

function chcolor(el,color,totaltab){
	for(i=1;i<=totaltab;i++)
		document.getElementById('tab'+i).style.background='#FAFAFA';

	el.style.background=color;
}

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>FEE - <?php echo $name;?></title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body>
<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="op">
	<input type="hidden" name="acc" value="<?php echo "$acc";?>">
	<input type="hidden" name="sid" value="<?php echo "$sid";?>">
	<input type="hidden" name="year" value="<?php echo "$year";?>">
	<!-- 
	<input type="hidden" name="p" value="../efee/feepay">
	 -->
		
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<?php if(is_verify("ADMIN|AKADEMIK|KEUANGAN")){?>
<a href="#" onClick="javascript:href='<?php echo "feepay_parent";?>.php?acc=<?php echo "$acc";?>&sid=<?php echo "$sid";?>&year=<?php echo "$year";?>'" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
<?php } ?>
<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
</div><!-- mymenu -->
	<div align="right">
	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
	</div> 
</div><!-- mypanel -->
<div id="story">
<div id="mytitlebg"><?php echo strtoupper($lg_payment);?></div>

 <table width="100%">
   <tr>
     <td width="59%" valign=top>
	 <table width="100%"  >
       <tr id="mytabletitle">
         <td width="29%"><?php echo strtoupper($lg_name);?></td>
         <td width="71%">&nbsp;<?php echo "$p1name";?></td>
       </tr>
       <tr>
         <td width="29%" bgcolor="#FAFAFA"><?php echo strtoupper($lg_account_no);?></td>
         <td width="71%">&nbsp;<?php echo "$acc";?> </td>
       </tr>
       <tr>
         <td width="29%" bgcolor="#FAFAFA"><?php echo strtoupper($lg_ic_number);?></td>
         <td width="71%">&nbsp;<?php echo "$p1ic";?> </td>
       </tr>
	   <tr style="font-weight:bold; color:#0033FF; font-size:12px">
         <td width="29%"  bgcolor="#FAFAFA">ADVANCE</td>
         <td width="71%">&nbsp;RP<?php printf("%.02f",$advance);?></td>
       </tr>
	   
     </table></td>
     <td width="41%" valign="top">
	 	
	 	<table width="100%" >
		 <tr id="mytabletitle">
			 <td colspan="3" ><?php echo strtoupper($lg_child_name);?></td>
		   </tr>
<?php
		$sql="select * from stu where acc='$acc' and status=6 $sqlsid";
		$res8=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		while($row=mysql_fetch_assoc($res8)){
				$xname=ucwords(strtolower(stripslashes($row['name'])));
				$xuid=$row['uid'];
				$uid=$row['uid'];
				$xsid=$row['sch_id'];
				$sid=$row['sch_id'];
				$noanak++;
				
				$ishostel=$row['ishostel'];
				$isxpelajar=$row['isislah'];
				$p1ic=$row['p1ic'];
				$sex=$row['sex'];
				$rdate=$row['rdate'];
				$iskawasan=$row['iskawasan'];
				$isstaff=$row['isstaff'];
				$isyatim=$row['isyatim'];
				$isfeenew=$row['isfeenew'];
				$isspecial=$row['isspecial'];
				$isinter=$row['isinter'];
				
				$sql="select * from ses_stu where stu_uid='$xuid' and sch_id=$xsid and year='$year'";
				$res11=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				$row11=mysql_fetch_assoc($res11);
				$xcls=ucwords(strtolower(stripslashes($row11['cls_name'])));
				if($xcls=="")
					$xcls="-";

				$jumanak=0;
				$noanak=0;
				$sql="select * from stu where p1ic='$p1ic' and status=6 $sqlsid order by bday";
				$res=mysql_query($sql) or die("$sql - query failed:".mysql_error());
				$jumanak=mysql_num_rows($res);
				while($row=mysql_fetch_assoc($res)){
					$noanak++;
					$t=$row['uid'];
					if($t==$xuid)
						break;			
				}
				
				
				$sql="select * from type where grp='feetype' order by idx";
				$resfeetype=mysql_query($sql)or die("query failed:".mysql_error());
				while($rowfeetype=mysql_fetch_assoc($resfeetype)){
					$feetype=$rowfeetype['val'];
					$feegroup=$rowfeetype['prm'];
					
					$sql="select feeset.* from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$xsid and year='$year' and type.grp='yuran' and type.val=$feetype";
					$resfee=mysql_query($sql)or die("$sql - query failed:".mysql_error());
					while($rowfee=mysql_fetch_assoc($resfee)){
									
						include($CONFIG['FILE_FORMULA']['etc']);
						//echo "V:$$feeval";
						if($feeval==-1){
							$feesta=-1;
						}else{
							$resit="";
							$paydt="";
							$xresitno="";
							//$sql="select * from feepay where stu_uid='$uid' and year='$year' and fee='$feename'";
							$sql="select feepay.*,feetrans.paydate from feepay INNER JOIN feetrans ON feepay.tid=feetrans.id where feepay.stu_uid='$xuid' and feepay.year='$year' and feepay.fee='$feename' and feepay.isdelete=0";
							$res3=mysql_query($sql) or die("$sql - query failed:".mysql_error());
							if($row2=mysql_fetch_assoc($res3)){
								$feeval=$row2['rm'];
								$resit=$row2['tid'];
								$xresitno=$row2['resitno'];
								$paydt=$row2['paydate'];
								$feesta=1;
							}elseif($feeval==0){
									$feesta=2;
							}else{
									$feesta=0;
							}
						}
						//echo "$feename = $feeval<br>";
						$sql="select * from feestu where uid='$xuid' and ses='$year' and fee='$feename'";
						$res_feestux=mysql_query($sql) or die("$sql - query failed:".mysql_error());
						if(!$row_feestux=mysql_fetch_assoc($res_feestux)){
							$sql="insert into feestu(dtm,sid,uid,ses,fee,typ,val,sta,des,rno,resitno,pdt,adm)values(now(),$sid,'$xuid','$year',
							'$feename',$feetype,$feeval,$feesta,'$fdes','$resit','$xresitno','$paydt','$username')";
							echo $sql;//$res=mysql_query($sql) or die("$sql - query failed:".mysql_error());
						}
					}
				}
?>
		 
			<tr>
			 <td width="50%"><?php echo "$xuid - $xname";?> </td>
			 <td width="1%">:</td>
			 <td width="40%"><?php echo $xcls;?> </td>
		   </tr>
		 <?php }?>
     	</table>
	 </td>
   </tr>
 </table>

<?php if(is_verify("ADMIN|KEUANGAN")){ ?>
<table width="100%" cellspacing="0">
<tr>
<td width="50%" valign="top">
<table width="100%" cellspacing="0" id="mytitlebg"> 
	  	<tr style="font-size:14px">
		 <td id="myborder" width="30%"><?php echo $lg_payment;?></td>
		 <td id="myborder">
		 	<select name="year" id="year" onchange="document.form1.submit();">
<?php
            echo "<option value=$year>$lg_session $year</option>";
			$sql="select * from type where grp='session' and prm!='$year' and prm>='$reg_year' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        echo "<option value=\"$s\">$lg_session $s</option>";
            }
            mysql_free_result($res);					  
?>
      </select>
	  </td>
       </tr>   
	  	<tr >
		<td id="myborder" style="font-size:14px"><?php echo $lg_total_fee;?></td>
		<td id="myborder" style="font-size:14px">
		 	<input name="total" type="text" id="total" value="0.00" size="9" readonly style="font-weight:bold; text-align:right; padding:0px 0px 0px 0px">
		</td>
		</tr>
		<tr>
		<td id="myborder" style="font-size:14px"><?php echo $lg_total_sale;?></td>
		<td id="myborder" style="font-size:14px">
			<input name="jualan" type="text" id="jualan" value="0.00" size="9" readonly style="font-weight:bold; text-align:right; padding:0px 0px 0px 0px"> 
		</td>
		</tr>
		<!-- 
		<tr>
		<td id="myborder" style="font-size:14px">Jumlah Sumbangan</td>
		<td id="myborder" style="font-size:14px">
			<input name="sumbangan" type="text" id="sumbangan" value="0.00" size="9" readonly style="font-weight:bold; text-align:right; padding:0px 0px 0px 0px"> 
		</td>
		 -->
		</tr>
		<tr>
		<td id="myborder" style="font-size:14px"><?php echo $lg_other_payment;?></td>
		<td id="myborder" style="font-size:14px">
			<input name="lainlainval" type="text" value="0.00" size="9" style="font-weight:bold; text-align:right " onKeyUp="kira(this)">
		 	<input name="lainlaindes" type="text" size="32" style="color:#999999 " value="<?php echo $lg_description;?>.." onMouseDown="document.form1.lainlaindes.value='';document.form1.lainlaindes.focus();" >
		 	<input type="hidden" name="lainlain" value="0">
		 </td>
       </tr>  
	   	   <tr id="mytabletitle" style="font-size:14px" >
		 <td id="myborder"><?php echo $lg_total_payment;?></td>
		 <td id="myborder">
		 	<input name="jumbayar" type="text" size="9" value="0.00" readonly style="font-weight:bold; text-align:right ">
		 	<input type="hidden" name="payadvance">
		 	<input name="onadvance" type="checkbox" value="1" readonly onClick="process_advance()">
		  	ADVANCE
		   	<input name="ontebus" type="checkbox" value="<?php echo $advance; ?>" onClick="process_tebus()" <?php if($advance<=0)echo "disabled";?>>
		  	CLAIM 
		  	<input name="jumtebus" type="text" size="5" value="0.00" readonly style="font-weight:bold; text-align:right " onKeyUp="kiratebus(this)">
		  
		 
		 </td>
       </tr>   
	</table>
</td>



<td width="50%" valign="top">
<table width="100%" cellspacing="0" id="mytitlebg"> 
	  	<tr style="font-size:14px">
		 <td id="myborder" width="30%"><?php echo $lg_payment_date;?></td>
		 <td id="myborder"> <input name="paydate" type="text" id="paydate" size=11 readonly value="<?php echo "$paydate";?>" style="font-weight:bold;text-align:right" onClick="displayDatePicker('paydate');" onKeyDown="displayDatePicker('paydate');">		 	
		 </td>
       </tr>   
	  <tr  style="font-size:14px">
		 <td id="myborder" colspan="2">
		 <input name="paytype" type="radio" value="TUNAI" checked > <?php echo $lg_cash;?>
		   	<input name="paytype" type="radio" value="CEK" > <?php echo $lg_cheque;?>
		   	<input name="paytype" type="radio" value="K.Kredit" > <?php echo $lg_credit_card;?>
		   	<input name="paytype" type="radio" value="DLL"> <?php echo $lg_other;?>
		   	<?php echo $lg_description;?><input name="nocek" type="text" id="nocek" size="20">
		 </td>
       </tr>  
	   <tr style="font-size:14px">
		 <td id="myborder"><?php echo $lg_office_use;?></td>
		 <td id="myborder"><input name="rujukan" type="text" id="rujukan" size="16"><font color="#0000FF" style="font-weight:normal">(<?php echo $lg_if_needed;?>)</font></td>
       </tr>  
	   <tr style="font-size:14px">
		 <td id="myborder"><?php echo $lg_confirm_payment;?></td>
		 <td id="myborder"><input type="button" name="btnpayment" value="<?php echo $lg_confirm_payment;?>" onClick="process_form()"></td>
       </tr>  
	</table>
</td>
</tr>

</table>

      
	
	
	
<?php } ?>
<!--
<div id="mytabdiv">
		<div id="tab1" class="mytab" style="font-family:Arial; border:1px solid #003399; border-bottom:none; background-color:#FFFF00;" onClick="chcolor(this,'#FFFF00');show('divtabyuran'); hide('divtabjualan');hide('divtabsumbangan');"><a href="#">YURAN</a></div>
		<div id="tab2" class="mytab" style="font-family:Arial; border:1px solid #003399; border-bottom:none;" onClick="chcolor(this,'#FFFF00');show('divtabjualan');hide('divtabyuran');hide('divtabsumbangan');"><a href="#">JUALAN</a></div>
		<div id="tab3" class="mytab" style="font-family:Arial; border:1px solid #003399; border-bottom:none;" onClick="chcolor(this,'#FFFF00');hide('divtabjualan');hide('divtabyuran');show('divtabsumbangan');"><a href="#">SUMBANGAN</a></div>
</div>
-->
<div id="mytabdiv">
<?php
	$xxx=0;
	$sql="select * from type where grp='feetype' order by idx";
	$resfeetype=mysql_query($sql)or die("query failed:".mysql_error());
	$feenumrow=mysql_num_rows($resfeetype);
	while($rowfeetype=mysql_fetch_assoc($resfeetype)){
		$feetype=$rowfeetype['val'];
		$feegroup=$rowfeetype['prm'];
		$xxx++;
?>
	<div id="tab<?php echo $xxx;?>" class="mytab" style="font-family:Arial; border:1px solid #003399; border-bottom:none;<?php if($xxx==1) echo "background-color:#DDEEEE;";?>" onClick="chcolor(this,'#DDEEEE',<?php echo $feenumrow+1;?>);
		<?php 
		for($jjj=1;$jjj<=$feenumrow+1;$jjj++){
			if($xxx==$jjj)
				echo "show('divtab$jjj');";
			else
				echo "hide('divtab$jjj');";
		} 
		?>
		"><a href="#"><?php echo $xxx;?>.&nbsp;<?php echo $feegroup;?> </a></div>
<?php } ?>
		
		
		<div id="tab<?php $xxx++;echo $xxx;?>" class="mytab" style="font-family:Arial; border:1px solid #003399; border-bottom:none;<?php if($xxx==1) echo "background-color:#DDEEEE;";?>" onClick="chcolor(this,'#DDEEEE',<?php echo $feenumrow+1;?>);
		<?php 
		
		for($jjj=1;$jjj<=$feenumrow+1;$jjj++){
			if($xxx==$jjj)
				echo "show('divtab$jjj');";
			else
				echo "hide('divtab$jjj');";
		} 
		?>
		"><a href="#"><?php echo $xxx;?>.&nbsp;<?php echo $lg_sales;?></a></div>
<!-- change to $feenumrow+2 if this open
		<div id="tab<?php $xxx++; echo $xxx;?>" class="mytab" style="font-family:Arial; border:1px solid #003399; border-bottom:none;<?php if($xxx==1) echo "background-color:#DDEEEE;";?>" onClick="chcolor(this,'#DDEEEE',<?php echo $feenumrow+2;?>);
		<?php 
		
		for($jjj=1;$jjj<=$feenumrow+2;$jjj++){
			if($xxx==$jjj)
				echo "show('divtab$jjj');";
			else
				echo "hide('divtab$jjj');";
		} 
		?>
		"><a href="#"><?php echo $xxx;?>.&nbsp;Sumbangan</a></div>
 -->
	<br><br>
</div>

<div id="divtab<?php echo $feenumrow+1;?>" style="background-color:#F1F1F1;display:none;">
	<?php include("tabjualan.php");?>
</div>

<?php
	$xxx=0;
	$sql="select * from type where grp='feetype' order by idx";
	$resfeetype=mysql_query($sql)or die("query failed:".mysql_error());
	$numrow=mysql_num_rows($resfeetype);
	while($rowfeetype=mysql_fetch_assoc($resfeetype)){
		$feetype=$rowfeetype['val'];$feegroup=$rowfeetype['prm'];$xxx++;
		if($xxx==1)
			echo "<div id=\"divtab$xxx\">";
		else
			echo "<div id=\"divtab$xxx\" style=\"display:none;\">";
?>
<div id="mytabletitle" style="font-size:14px "><?php echo strtoupper($feegroup);?></div>
<table width="100%" cellspacing="0">
       <tr>
	   	<td id="mytabletitle" width="3%"><?php echo strtoupper($lg_status);?></td>
		<td id="mytabletitle" width="7%"><?php echo strtoupper($lg_date);?></td>
		<td id="mytabletitle" width="7%"><?php echo strtoupper($lg_receipt);?></td>
		<td id="mytabletitle" width="7%" align="right"><?php echo strtoupper($lg_total);?>(<?php echo strtoupper($lg_rm);?>)&nbsp;&nbsp;</td>
        <td id="mytabletitle" width="30%">&nbsp;<?php echo strtoupper($feegroup);?></td>  
		<td id="mytabletitle" width="40%">&nbsp;</td>  
       </tr>

<?php
	$qq=0;
	$sql="select * from stu where acc='$acc' and status=6 $sqlsid";
	$resxxx=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($rowxx=mysql_fetch_assoc($resxxx)){
		$name=stripslashes($rowxx['name']);
		$p1name=stripslashes($rowxx['p1name']);
		$p1ic=stripslashes($rowxx['p1ic']);
		//$acc=stripslashes($row['acc']);
		$stu_id=$rowxx['id'];
		$stu_uid=$rowxx['uid'];
		$uid=$rowxx['uid'];
		$sid=$rowxx['sch_id'];
		$ic=$rowxx['ic'];
		$ishostel=$rowxx['ishostel'];
		$isxpelajar=$rowxx['isislah'];
		$p1ic=$rowxx['p1ic'];
		$sex=$rowxx['sex'];
		$rdate=$rowxx['rdate'];
		$iskawasan=$rowxx['iskawasan'];
		$isstaff=$rowxx['isstaff'];
		$isyatim=$rowxx['isyatim'];
		$feehutang=$rowxx['feehutang'];
		//$advance=$rowxx['fee_advance'];
		
		$isfeenew=$rowxx['isfeenew'];
		$isspecial=$rowxx['isspecial'];
		$isinter=$rowxx['isinter'];
		
		$isfeebulanfree=$rowxx['isfeefree'];

		if(($qq++%2)==0)
			$bg="$bglyellow";
		else
			$bg="";
?>
	<tr bgcolor="#FFFF00" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='#FFFF00';">
	<td colspan="6" style="font-size:12px ">
		<strong>
		&nbsp;
			<a href="<?php echo $FN_FEECFG;?>.php?uid=<?php echo "$stu_uid&sid=$sid&year=$year";?>" target="_blank" title="Edit">
			<img src="../img/edit12.png">
			
			&nbsp;<?php echo "$qq. $stu_uid - $name";?>
			</a>
			</strong>
	</td>
	</tr>
<?php
	
	$sql="select feeset.* from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sid and year='$year' and type.grp='yuran' and type.val=$feetype";
	$xxfeetype=$feetype;
	$resfee=mysql_query($sql)or die("$sql - query failed:".mysql_error());
	while($rowfee=mysql_fetch_assoc($resfee)){
		include($CONFIG['FILE_FORMULA']['etc']);
		if($feeval==-1)
			continue;
		
		$q++;
?>

<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
         <td  id="myborder">
		 <?php
			$sql="select * from feepay where stu_uid='$stu_uid' and year='$year' and fee='$feename' and isdelete=0";
			$res2=mysql_query($sql) or die("$sql - query failed:".mysql_error());
			$found=mysql_num_rows($res2);
			$rm="";
			$dt="";
			$tid="";
			$rno="";
			if($found>0){
				$row2=mysql_fetch_assoc($res2);
				$rm=$row2['rm'];
				$tid=$row2['tid'];
				$rno=$row2['resitno'];
				$dt=$row2['cdate'];
				$dt=strtok($dt," ");
				echo "-PAID-";
			}
			else{
				if($feeval==0){
					echo "-FOC-";
				}else{
					if(is_verify("ADMIN|KEUANGAN"))
           				echo "<input name=\"cbox[]\" type=\"checkbox\" id=\"cb\" value=\"$feename|$feeval|$stu_uid|$xxfeetype\" onClick=\"checkx()\">";

				}
			}
		   ?>
         </td>
		<td id="myborder"><?php echo "$dt";?></td>
		<td id="myborder"><?php echo $rno;?></td>
        <td id="myborder"align="right"><?php if($found) printf("%.02f",$rm); else printf("%.02f",$feeval);?>&nbsp;&nbsp;</td>
        <td id="myborder">&nbsp;<?php echo "$feename";?></td>
		<td id="myborder">&nbsp;</td>
       </tr>
	
<?php } } ?> <!-- yuran -->

 </table>
 </div>
 <?php } ?> <!-- jenis yuran -->

<div id="mytitlebg" align="right">-- END OF PAGE --</div>
</div></div>
</form>
</body>
</html>
<!-- 
ver 2.6
11-11-2008 add paydate
11-11-2008 add dll paytype
 -->