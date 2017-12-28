<?php
/** 
110112-update lain-lain enable negative value
110120-update claim
**/
$vmod="v5.1.0";
$vdate="110120";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");

verify('ADMIN|AKADEMIK|KEUANGAN');
$username = $_SESSION['username'];
$ISCOMMIT=1;
	$uid=$_REQUEST['uid'];
	$sid=$_REQUEST['sid'];
	$op=$_REQUEST['op'];
	
	$sql="select * from type where grp='fee_interface' and (sid=0 or sid='$sid')";
	$res=mysql_query($sql) or die("$sql failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
			$prm=$row['prm'];
			$CONFIG[$prm]['val']=$row['val'];
			$CONFIG[$prm]['code']=$row['code'];
			$CONFIG[$prm]['etc']=$row['etc'];
	}
	
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
	$advdes=$_POST['advdes'];
	
	$saleunit=$_POST['saleunit'];
	$salecode=$_POST['salecode'];
	$saleitem=$_POST['saleitem'];
	$saleprice=$_POST['saleprice'];
	$saletotal=$_POST['saletotal'];
	$saletype=$_POST['saletype'];
	$saledis=$_POST['saledis'];
	$saledisrm=$_POST['saledisrm'];
	
	
	$rujukan=$_POST['rujukan'];
	if($onadvance=="")
		$onadvance=0;
	$ontebus=$_POST['ontebus'];
	$jumtebus=$_POST['jumtebus'];
	
	//echo "DDD:$paydate <br>";
	$sql="select * from stu where uid='$uid' and sch_id=$sid";
	$res=mysql_query($sql) or die("$sql - query failed:".mysql_error());
	if($row=mysql_fetch_assoc($res)){
		$name=stripslashes($row['name']);
		$stu_id=$row['id'];
		$stu_uid=$row['uid'];
		$sch_id=$row['sch_id'];
		$ic=$row['ic'];
		$ishostel=$row['ishostel'];
		$isxpelajar=$row['isislah'];
		$p1ic=$row['p1ic'];
		$sex=$row['sex'];
		$rdate=$row['rdate'];
		$iskawasan=$row['iskawasan'];
		$isstaff=$row['isstaff'];
		$isyatim=$row['isyatim'];
		$feehutang=$row['feehutang'];
		$isnew=$row['isnew'];
		//$advance=$row['fee_advance'];
		
		$isfeenew=$row['isfeenew'];
		$isspecial=$row['isspecial'];
		$isinter=$row['isinter'];
		
		$isfeebulanfree=$row['isfeefree'];
		
		$sql="select sum(rm) from feepay where stu_uid='$uid' and fee='advance' and isdelete=0";
		$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		if($row2=mysql_fetch_row($res2))
			$advance=$row2[0];
		else
			$advance=0;
		
		$sql="select sum(rm) from feepay where stu_uid='$uid' and fee='RECLAIM' and isdelete=0";
		$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		if($row2=mysql_fetch_row($res2))
			$stutebus=$row2[0];
		else
			$stutebus=0;

		$advance=$advance+$stutebus; //statebus negative so kena +
		//echo "SSS: $advance=$advance+$stutebus;";
		$sql="select * from ses_stu where stu_uid='$uid' and sch_id=$sch_id and year='$year'";
		$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2)){
			$clsname=$row2['cls_name'];
			$clscode=$row2['cls_code'];
			$clslevel=$row2['cls_level'];
		}
	
		$sql="select * from sch where id=$sch_id";
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
	$sql="select * from stu where p1ic='$p1ic' and status=6 order by bday";
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
	/** check to update feestu **/
	$sql="select * from type where grp='feetype' order by idx";
	$resfeetype=mysql_query($sql)or die("query failed:".mysql_error());
	while($rowfeetype=mysql_fetch_assoc($resfeetype)){
		$feetype=$rowfeetype['val'];
		$feegroup=$rowfeetype['prm'];
		
		$sql="select feeset.* from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sid and year='$year' and type.grp='yuran' and type.val=$feetype";
		$resfee=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		while($rowfee=mysql_fetch_assoc($resfee)){
						
			include($CONFIG['FILE_FORMULA']['etc']);
			if($feeval==-1){
					$feesta=-1;
			}else{
					$resit="";
					$paydt="";
					$xresitno="";
					//$sql="select * from feepay where stu_uid='$uid' and year='$year' and fee='$feename'";
					$sql="select feepay.*,feetrans.paydate from feepay INNER JOIN feetrans ON feepay.tid=feetrans.id where feepay.stu_uid='$uid' and feepay.year='$year' and feepay.fee='$feename' and feepay.isdelete=0";
					$res2=mysql_query($sql) or die("$sql - query failed:".mysql_error());
					if($row2=mysql_fetch_assoc($res2)){
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
			$sql="select * from feestu where uid='$uid' and ses='$year' and fee='$feename'";
			$res_feestux=mysql_query($sql) or die("$sql - query failed:".mysql_error());
			if(!$row_feestux=mysql_fetch_assoc($res_feestux)){
				$sql="insert into feestu(dtm,sid,uid,ses,fee,typ,val,sta,des,rno,resitno,pdt,adm,rm,dis,disval)values(now(),$sid,'$uid','$year',
				'$feename',$feetype,$feeval,$feesta,'$fdes','$resit','$xresitno','$paydt','$username',$feerm,$feedis,$feedisval)";
				mysql_query($sql) or die("$sql - query failed:".mysql_error());
			}
		}
	}

	if ($USE_SCHOOL_RESIT_NUM){
		$newresitno=sprintf("$scode/%06d",$sidresitno);
		$sql="update sch set resitno=$sidresitno where id=$sch_id";
		mysql_query($sql)or die("$sql - failed:".mysql_error());
	}else{
		$newresitno=sprintf("%06d",$sidresitno);
		$sql="update sch set resitno=$sidresitno";
		mysql_query($sql)or die("$sql - failed:".mysql_error());
	}
	$namex=mysql_real_escape_string($name);
	$sql="insert into feetrans (cdate,year,sch_id,stu_id,stu_uid,rm,admin,paytype,nocek,paydate,rujukan,resitno,name,ic)
	values(now(),'$year',$sch_id,$stu_id,'$stu_uid',$jumbayar,'$username','$paytype','$nocek','$paydate','$rujukan','$newresitno','$namex','$ic')";
   	mysql_query($sql)or die("$sql - query failed:".mysql_error());
	$feeid=mysql_insert_id($link);
				
	if (count($cbox)>0) {
			for ($i=0; $i<count($cbox); $i++) {
				$data=$cbox[$i];
				//$prm=strtok($data,"|");
				//$val=strtok("|");
				list($prm,$val,$xrm,$dis,$disval,$xxfeetype)=explode("|",$data);
				if($xrm=="")$xrm=$val;
				if($dis=="")$dis=0;
				if($disval=="")$disval=0;
				
				$etc="";
				if($disval>0)
					$etc=$lg_rm."$xrm"." ".$lg_discount." ".$lg_rm."$disval";
				//$etc="$qtt x RP$val". $disc;
				$sql="insert into feepay (cdate,year,sch_id,stu_id,stu_uid,rm,fee,resitno,type,tid,admin,etc,xrm,dis,disval,item_type)values
				(now(),'$year',$sch_id,$stu_id,'$stu_uid',$val,'$prm','$newresitno','fee',$feeid,'$username','$etc',$xrm,$dis,$disval,'$xxfeetype')";
				mysql_query($sql)or die("$sql - failed:".mysql_error());
				//if(!mysql_query($sql))
					// mysql_query("ROLLBACK");
				//echo "($prm,$val,$xrm,$dis,$disval)$etc";
				//exit;
				$sql="update feestu set sta=1,val=$val,rno=$feeid,pdt='$paydate',adm='$username',resitno='$newresitno' where uid='$uid' and ses='$year' and sid='$sid' and fee='$prm'";
				mysql_query($sql)or die("$sql - failed:".mysql_error());
				//if(!mysql_query($sql))
					// mysql_query("ROLLBACK");
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
				$disval=$saledisrm[$i];
				$disc="";
				if($dis>0){
					//$disc=" Less $dis"."%";
					$disc=" ".$lg_discount." $dis"."%";
					//$disc=" ".$lg_discount." ".$lg_rm."$disval";
				}
				$etc="$qtt x RP$val". $disc;
				$sql="insert into feepay (cdate,year,sch_id,stu_id,stu_uid,rm,fee,resitno,
						item_code,item_name,item_price,item_type,unit,type,etc,tid,admin)
						values(now(),'$year',$sch_id,$stu_id,'$stu_uid',$tot,'$nam','$newresitno',
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
		$sql="insert into feepay (cdate,year,sch_id,stu_id,stu_uid,rm,fee,tid,resitno,type,etc,admin)values(now(),'$year',$sch_id,$stu_id,'$stu_uid',$payadvance,'ADVANCE',$feeid,'$newresitno','fee','$advdes','$username')";
		if(!mysql_query($sql)){
					 echo "$sql - query failed:".mysql_error();
					 mysql_query($sql)or die("$sql - failed:".mysql_error());
					 //mysql_query("ROLLBACK");
					 //$ISCOMMIT=0;
		}
		//$sql="update stu set fee_advance=fee_advance+$payadvance where uid='$stu_uid'";
		//mysql_query($sql)or die("$sql - query failed:".mysql_error());
	}
	//if($lainlainval>0){
	if($lainlainval!=0){
		$sql="insert into feepay (cdate,year,sch_id,stu_id,stu_uid,rm,fee,etc,tid,resitno,type,admin)values(now(),'$year',$sch_id,$stu_id,'$stu_uid',$lainlainval,'$lg_other','$lainlaindes',$feeid,'$newresitno','fee','$username')";
		if(!mysql_query($sql)){
					echo "$sql - query failed:".mysql_error();
					mysql_query($sql)or die("$sql - failed:".mysql_error());
					// mysql_query("ROLLBACK");
					 //$ISCOMMIT=0;
		}
	}
	if($jumtebus>0){
		$jumteb="-$jumtebus";
		$sql="insert into feepay (cdate,year,sch_id,stu_id,stu_uid,rm,fee,etc,tid,resitno,type,admin)values(now(),'$year',$sch_id,$stu_id,'$stu_uid',$jumteb,'RECLAIM','$advdes',$feeid,'$newresitno','fee','$username')";
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
	//document.form1.btnpayment.disabled=false;
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
	<input type="hidden" name="sid" value="<?php echo "$sid";?>">
	<input type="hidden" name="op">
	<input type="hidden" name="uid" value="<?php echo "$uid";?>">
	<input type="hidden" name="year" value="<?php echo "$year";?>">
	<input name="stu_id" type="hidden" id="stu_id" value="<?php echo "$stu_id";?>">
	<input name="stu_uid" type="hidden" id="stu_uid" value="<?php echo "$stu_uid";?>">
	<input name="sch_id" type="hidden" id="sch_id" value="<?php echo "$sch_id";?>">
	<input type="hidden" name="p" value="../efee/feepay">
		
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<?php if(is_verify("ADMIN|AKADEMIK|KEUANGAN")){?>
<a href="#" onClick="javascript:href='<?php echo $FN_FEECFG;?>.php?uid=<?php echo "$uid";?>&sid=<?php echo "$sid";?>&year=<?php echo "$year";?>'" id="mymenuitem"><img src="../img/tool.png"><br>Config</a>
<a href="#" onClick="javascript:href='<?php echo $FN_FEEPAY;?>.php?uid=<?php echo "$uid";?>&sid=<?php echo "$sid";?>&year=<?php echo "$year";?>'" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
<?php } ?>
<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
</div><!-- mymenu -->
	<div align="right">
	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
	</div> 
</div><!-- mypanel -->
<div id="story">
<div id="mytitlebg"><?php echo strtoupper($lg_payment);?></div>

 <table width="100%" bgcolor="#FFFFCC">
   <tr>
     <td width="59%" valign=top>
	 <table width="100%"  >
       <tr>
         <td width="29%"><?php echo strtoupper($lg_name);?></td>
		 <td width="2%">:</td>
         <td width="71%">&nbsp;<?php echo "$name";?></td>
       </tr>
       <tr>
         <td width="29%" ><?php echo strtoupper($lg_matric);?></td>
		 <td width="2%" >:</td>
         <td width="71%">&nbsp;<?php echo "$stu_uid";?> </td>
       </tr>
       <tr>
         <td width="29%"><?php echo strtoupper($lg_ic_number);?></td>
		 <td width="2%" >:</td>
         <td width="71%">&nbsp;<?php echo "$ic";?> </td>
       </tr>
	   <tr>
         <td width="29%" ><?php echo strtoupper($lg_register);?></td>
		 <td width="2%" >:</td>
         <td width="71%">&nbsp;<?php echo "$rdate";?></td>
       </tr>
       <tr>
         <td width="29%" ><?php echo strtoupper($lg_class);?></td>
		 <td width="2%" >:</td>
         <td width="71%">&nbsp;<?php echo "$clsname";?> </td>
       </tr>
	   <tr style="font-weight:bold; color:#0033FF; font-size:12px">
         <td width="29%" >ADVANCE</td>
		 <td width="2%" >:</td>
         <td width="71%">&nbsp;RP<?php printf("%.02f",$advance);?></td>
       </tr>
	   
     </table></td>
     <td width="41%"><table width="100%" >
       <tr>
         <td width="53%" align="right"><?php echo strtoupper($lg_orphan);?>:</td>
         <td width="47%">&nbsp;<?php if($isyatim) echo $lg_yes; else echo "-";?></td>
       </tr>
       <tr>
         <td width="53%" align="right"><?php echo strtoupper($lg_staff_child);?>:</td>
         <td width="47%">&nbsp;<?php if($isstaff) echo $lg_yes; else echo "-";?></td>
       </tr>
	   <tr>
         <td width="53%" align="right"><?php echo strtoupper($lg_international);?>:</td>
         <td width="47%">&nbsp;<?php if($isinter) echo $lg_yes; else echo "-";?></td>
       </tr>
       <tr>
         <td width="53%" align="right"><?php echo strtoupper($lg_xprimary);?>:</td>
         <td width="47%">&nbsp;<?php if($isxpelajar) echo $lg_yes; else echo "-";?></td>
       </tr>
       <tr>
         <td width="53%" align="right"><?php echo strtoupper($lg_kariah);?>:</td>
         <td width="47%">&nbsp;<?php if($iskawasan) echo $lg_yes; else echo "-";?></td>
       </tr>
	   <tr>
         <td width="53%" align="right"><?php echo strtoupper($lg_hostel);?>:</td>
         <td width="47%">&nbsp;<?php if($ishostel) echo $lg_yes; else echo "-";?></td>
       </tr>
       <tr>
         <td width="53%" align="right"><?php echo strtoupper($lg_sibling_no_in_school);?>:</td>
         <td width="47%">&nbsp;<?php echo "$noanak/$jumanak";?> </td>
       </tr>
<?php if($CONFIG['SHOW_HUTANG']['val']){?>
	    <tr>
         <td width="53%" align="right"><?php echo strtoupper($lg_old_outstanding);?>:</td>
         <td width="47%">&nbsp;<?php  echo "$feehutang";?></td>
       </tr>
<?php } ?>
     </table>
	 
	 </td>
   </tr>
 </table>

<?php if(is_verify("ADMIN|KEUANGAN")){ ?>
<table width="100%" cellspacing="0" bgcolor="#fafafa">
<tr>
<td width="50%" valign="top"  id="myborder">
<table width="100%" cellspacing="0" id="mytitlebg"> 
	  	<tr>
		 <td id="myborder" width="20%"><?php echo strtoupper($lg_payment);?></td>
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
	  	<tr>
		<td id="myborder"><?php echo strtoupper($lg_total_fee);?></td>
		<td id="myborder"><input name="total" type="text" id="total" value="0.00" size="9" readonly style="font-weight:bold; text-align:right; padding:0px 0px 0px 0px"></td>
		</tr>
		<tr>
		<td id="myborder"><?php echo strtoupper($lg_total_sale);?></td>
		<td id="myborder"><input name="jualan" type="text" id="jualan" value="0.00" size="9" readonly style="font-weight:bold; text-align:right; padding:0px 0px 0px 0px"> 
		</td>
		</tr>
		<tr>
			<td id="myborder"><?php echo strtoupper($lg_other_payment);?></td>
			<td id="myborder">
				<input name="lainlainval" type="text" value="0.00" size="9" style="font-weight:bold; text-align:right " onKeyUp="kira(this)">
				<input name="lainlaindes" type="text" size="32" style="color:#999999 " value="<?php echo $lg_description;?>.." onMouseDown="document.form1.lainlaindes.value='';document.form1.lainlaindes.focus();" >
				<input type="hidden" name="lainlain" value="0">
			 </td>
       </tr>  
	   <tr id="mytabletitle">
		 <td id="myborder"><?php echo strtoupper($lg_total_payment);?></td>
		 <td id="myborder">
		 	<input name="jumbayar" type="text" size="9" value="0.00" readonly style="font-weight:bold; text-align:right ">
		 	<input type="hidden" name="payadvance">
		 	<input name="onadvance" type="checkbox" value="1" readonly onClick="process_advance()">
		  	ADVANCE
		   	<input name="ontebus" type="checkbox" value="<?php echo $advance; ?>" onClick="process_tebus()" <?php if($advance<=0)echo "disabled";?>>
		  	CLAIM 
		  	<input name="jumtebus" type="text" size="4" value="0.00" readonly style="font-weight:bold; text-align:right " onKeyUp="kiratebus(this)">
		  <?php echo strtoupper($lg_note);?>:
		  <input type="text" name="advdes">
		 
		 </td>
       </tr>   
	</table>
</td>



<td width="50%" valign="top"  id="myborder">
<table width="100%" cellspacing="0" id="mytitlebg"> 
	  	<tr>
		 <td id="myborder" width="30%"><?php echo strtoupper($lg_payment_date);?></td>
		 <td id="myborder"> <input name="paydate" type="text" id="paydate" size=11 readonly value="<?php echo "$paydate";?>" style="font-weight:bold;text-align:right" onClick="displayDatePicker('paydate');" onKeyDown="displayDatePicker('paydate');">		 	
		 </td>
       </tr>   
	  <tr>
		 <td id="myborder" colspan="2">
		 	<input name="paytype" type="radio" value="TUNAI" checked > <?php echo strtoupper($lg_cash);?>
		   	&nbsp;&nbsp;&nbsp;<input name="paytype" type="radio" value="CEK" > <?php echo strtoupper($lg_cheque);?>
		   	&nbsp;&nbsp;&nbsp;<input name="paytype" type="radio" value="K.Kredit" > <?php echo strtoupper($lg_credit_card);?>
		   	&nbsp;&nbsp;&nbsp;<input name="paytype" type="radio" value="DLL"> <?php echo strtoupper($lg_other);?> : 
		   	<?php echo strtoupper($lg_description);?> <input name="nocek" type="text" id="nocek" size="20">
		 </td>
       </tr>  
	   <tr>
		 <td id="myborder"><?php echo strtoupper($lg_office_use);?></td>
		 <td id="myborder"><input name="rujukan" type="text" id="rujukan" size="16"><font color="#0000FF" style="font-weight:normal">(<?php echo $lg_if_needed;?>)</font></td>
       </tr>  
	   <tr >
		 <td id="myborder"><?php echo strtoupper($lg_confirm_payment);?></td>
		 <td id="myborder"><input type="button" name="btnpayment" value="<?php echo $lg_confirm_payment;?>" onClick="process_form()"></td>
       </tr>  
	</table>
</td>
</tr>

</table>
	
<?php } ?>

<div id="mytabdiv">
	<div id="tab1" class="mytab" style="font-family:Arial; border:1px solid #003399; border-bottom:none;background-color:#DDEEEE;" onClick="chcolor(this,'#DDEEEE',2);show('divtab1');hide('divtab2');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#"><?php echo strtoupper($lg_fees_item);?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
	<div id="tab2" class="mytab" style="font-family:Arial; border:1px solid #003399; border-bottom:none;" onClick="chcolor(this,'#DDEEEE',2);show('divtab2');hide('divtab1');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#"><?php echo strtoupper($lg_sales_item);?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
	<br><br>
</div>

<div id="divtab2" style="background-color:#F1F1F1;display:none;" >
	<?php include("tabjualan.php");?>
</div>



<div id="divtab1">
<?php
	$xxx=0;
	$sql="select * from type where grp='feetype' order by idx";
	$resfeetype=mysql_query($sql)or die("query failed:".mysql_error());
	$numrow=mysql_num_rows($resfeetype);
	while($rowfeetype=mysql_fetch_assoc($resfeetype)){
		$feetype=$rowfeetype['val'];$feegroup=$rowfeetype['prm'];$xxx++;
?>

<table width="100%" cellspacing="0">
       <tr>
	   	<td id="mytabletitle" width="3%"><?php echo strtoupper($lg_status);?></td>
		<td id="mytabletitle" width="7%"><?php echo strtoupper($lg_date);?></td>
		<td id="mytabletitle" width="7%"><?php echo strtoupper($lg_receipt);?></td>
		<td id="mytabletitle" width="20%">&nbsp;<?php echo strtoupper($feegroup);?></td>  
		<td id="mytabletitle" width="7%" align="right"><?php echo strtoupper($lg_amount);?>(<?php echo strtoupper($lg_rm);?>)&nbsp;&nbsp;</td>
		<td id="mytabletitle" width="7%" align="right"><?php echo strtoupper($lg_discount);?> % &nbsp;&nbsp;</td>
		<td id="mytabletitle" width="7%" align="right"><?php echo strtoupper($lg_discount);?>(<?php echo strtoupper($lg_rm);?>)&nbsp;&nbsp;</td>
		<td id="mytabletitle" width="7%" align="right"><?php echo strtoupper($lg_total);?>(<?php echo strtoupper($lg_rm);?>)&nbsp;&nbsp;</td>
		<td id="mytabletitle" width="40%">&nbsp;</td>  
       </tr>

<?php 
	
	$sql="select feeset.* from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sch_id and year='$year' and type.grp='yuran' and type.val=$feetype";
	$xxfeetype=$feetype;
	$resfee=mysql_query($sql)or die("$sql - query failed:".mysql_error());
	while($rowfee=mysql_fetch_assoc($resfee)){
		include($CONFIG['FILE_FORMULA']['etc']);
		if($feeval==-1)
			continue;
		
		if(($q++%2)==0)
			$bg="$bglyellow";
		else
			$bg="$bglyellow";
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
           			echo "<input name=\"cbox[]\" type=\"checkbox\" id=\"cb\" value=\"$feename|$feeval|$feerm|$feedis|$feedisval|$xxfeetype\" onClick=\"checkx()\">";
				}
			}
		   ?>
         </td>
		<td id="myborder"><?php echo "$dt";?></td>
		<td id="myborder"><?php echo $rno;?></td>
        <td id="myborder">&nbsp;<?php echo "$feename";?></td>
		<td id="myborder"align="right"><?php printf("%.02f",$feerm);?>&nbsp;&nbsp;</td>
		<td id="myborder"align="right"><?php printf("%.02f",$feedis);?> %&nbsp;&nbsp;</td>
		<td id="myborder"align="right"><?php printf("%.02f",$feedisval);?>&nbsp;&nbsp;</td>
		<td id="myborder"align="right"><?php if($found) printf("%.02f",$rm); else printf("%.02f",$feeval);?>&nbsp;&nbsp;</td>
		<td id="myborder">&nbsp;</td>
       </tr>
	
<?php } ?> <!-- yuran -->

 </table>

 <?php } ?> <!-- jenis yuran -->
 </div>
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