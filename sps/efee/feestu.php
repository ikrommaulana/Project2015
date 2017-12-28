<?php

$username = $_SESSION['username'];
$vmod="v5.0.0";
$vdate="110218";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
	$p=$_REQUEST['p'];
	$uid=$_REQUEST['uid'];
	$sid=$_REQUEST['sid'];
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
	$rujukan=$_POST['rujukan'];
	
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
		$reg_year=strtok($rdate,"-");
		$iskawasan=$row['iskawasan'];
		$isstaff=$row['isstaff'];
		$isyatim=$row['isyatim'];
		$feehutang=$row['feehutang'];
		$advance=$row['fee_advance'];
		
		$isfeenew=$row['isfeenew'];
		$isnew=$row['isnew'];
		$isspecial=$row['isspecial'];
		$isinter=$row['isinter'];
		$isfeebulanfree=$row['isfeefree'];
		
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
	
if (count($cbox)>0) {
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
				$sql="select * from feepay where stu_uid='$uid' and year='$year' and fee='$feename'";
				$res2=mysql_query($sql) or die("$sql - query failed:".mysql_error());
				if($row2=mysql_fetch_assoc($res2)){
					$feeval=$row2['rm'];
					$resit=$row2['tid'];
					$dt=$row2['cdate'];
					$paydt=strtok($dt," ");
					$feesta=1;
				}elseif($feeval==0){
						$feesta=2;
				}else{
						$feesta=0;
				}
			}
			//echo "$feename = $feeval<br>";
			$sql="select * from feestu where uid='$uid' and ses='$year' and fee='$feename'";
			$res_feestux=mysql_query($sql) or die("$sql - query failed:".mysql_error());
			if(!$row_feestux=mysql_fetch_assoc($res_feestux)){
				$sql="insert into feestu(dtm,sid,uid,ses,fee,typ,val,sta,des,rno,pdt,adm)values(now(),$sid,'$uid','$year',
				'$feename',$feetype,$feeval,$feesta,'$fdes','$resit','$paydt','$username')";
				$res=mysql_query($sql) or die("$sql - query failed:".mysql_error());
			}
		}
	}

	
		
	$sql="insert into feetrans (cdate,year,sch_id,stu_id,stu_uid,rm,admin,paytype,nocek,paydate,rujukan)values(now(),$year,$sch_id,$stu_id,'$stu_uid',$total,'$username','$paytype','$nocek','$paydate','$rujukan')";
	//echo $sql;
   	mysql_query($sql)or die("$sql - query failed:".mysql_error());
	$feeid=mysql_insert_id($link);
	if (count($cbox)>0) {
			for ($i=0; $i<count($cbox); $i++) {
				$data=$cbox[$i];
				$prm=strtok($data,"|");
				$val=strtok("|");
				$sql="insert into feepay (cdate,year,sch_id,stu_id,stu_uid,rm,fee,tid,admin)values(now(),$year,$sch_id,$stu_id,'$stu_uid',$val,'$prm',$feeid,'$username')";
				mysql_query($sql)or die("$sql - query failed:".mysql_error());
				$sql="update feestu set sta=1,val=$val,rno=$feeid,pdt=now(),adm='$username' where uid='$uid' and ses='$year' and sid='$sid' and fee='$prm'";
				mysql_query($sql)or die("$sql - query failed:".mysql_error());
			}
	}
	
	echo "<script language=\"javascript\">location.href='$FN_FEERESIT.php?id=$feeid'</script>";
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php include("$MYOBJ/calender/calender.htm")?>
<SCRIPT LANGUAGE="JavaScript">
function check(){
	var sum=0;
	var checked=false;
	for (var i=0;i<document.form1.elements.length;i++){
		var e=document.form1.elements[i];
        if ((e.type=='checkbox')){
			if(e.checked==true){
				var str=e.value
				var arr=str.split("|")
				sum=sum+parseFloat(arr[1]);
				checked=true;
			}
			
        }
	}
	document.form1.total.value=sum.toFixed(2);
	if(checked)
		document.form1.btnpayment.disabled=false;
	else
		document.form1.btnpayment.disabled=true;
}

</script>
<script language="JavaScript">
function process_form(operation){
		ret = confirm("Confirm bayaran?");
		if (ret == true){
			document.form1.submit();
			return true;
		}
		return false;
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Borang Pembayaran Yuran Sekolah</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<input type="hidden" name="sid" value="<?php echo "$sid";?>">
		<input type="hidden" name="uid" value="<?php echo "$uid";?>">
		<input type="hidden" name="p" value="<?php echo "$p";?>">

<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
		<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
</div><!-- mymenu -->
<div align="right"><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a></div> 
</div><!-- mypanel -->
<div id="story">
<div id="mytitlebg"><?php echo strtoupper($lg_school_fee);?></div>

 <table width="100%" >
   <tr>
     <td width="59%" valign=top>
	 <table width="100%"  >
	 	<tr>
         <td width="29%" ><?php echo strtoupper("Tahun Ajaran");?></td>
		 <td width="2%" >:</td>
         <td width="71%">
<select name="year" id="year" onchange="document.myform.submit();">
<?php
            echo "<option value=$year>$year</option>";
			$sql="select * from type where grp='session' and prm!='$year' and prm>='$reg_year' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        echo "<option value=\"$s\">$s</option>";
            }
            mysql_free_result($res);					  
?>
      </select>
		 </td>
       </tr>
       <tr>
         <td width="29%"><?php echo strtoupper($lg_name);?></td>
		 <td width="2%">:</td>
         <td width="71%">&nbsp;<?php echo "$name";?></td>
       </tr>
       <tr>
         <td width="29%"><?php echo strtoupper($lg_matric);?></td>
		 <td width="2%">:</td>
         <td width="71%">&nbsp;<?php echo "$stu_uid";?> </td>
       </tr>
       <tr>
         <td width="29%"><?php echo strtoupper($lg_ic_number);?></td>
		 <td width="2%" >:</td>
         <td width="71%">&nbsp;<?php echo "$ic";?> </td>
       </tr>
	   <tr>
         <td width="29%" ><?php echo strtoupper($lg_register_date);?></td>
		 <td width="2%" >:</td>
         <td width="71%">&nbsp;<?php echo "$rdate";?></td>
       </tr>
       <tr>
         <td width="29%" ><?php echo strtoupper($lg_class);?></td>
		 <td width="2%" >:</td>
         <td width="71%">&nbsp;<?php echo "$clsname";?> </td>
       </tr>
	   
     </table></td>
     <td width="41%">
	 
	 </td>
   </tr>
 </table>

<table width="100%" cellspacing="0">
<?php
	$sql="select * from type where grp='feetype' order by idx";
	$resfeetype=mysql_query($sql)or die("query failed:".mysql_error());
	while($rowfeetype=mysql_fetch_assoc($resfeetype)){
		$feetype=$rowfeetype['val'];
		$feegroup=$rowfeetype['prm'];
?>

       <tr>
	   	<td id="mytabletitle" width="10%"><?php echo strtoupper($lg_status);?></td>
		<td id="mytabletitle" width="10%"><?php echo strtoupper($lg_date);?></td>
		<td id="mytabletitle" width="10%"><?php echo strtoupper("Resi");?></td>
        <td id="mytabletitle" width="30%"><?php echo strtoupper($feegroup);?></td>
        <td id="mytabletitle" width="40%" align="center"><?php echo strtoupper($lg_total);?>(<?php echo ("Rp");?>)</td>
       </tr>

<?php 
	$sql="select feeset.* from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sch_id and year='$year' and type.grp='yuran' and type.val=$feetype";
	$resfee=mysql_query($sql)or die("$sql - query failed:".mysql_error());
	while($rowfee=mysql_fetch_assoc($resfee)){
		include($CONFIG['FILE_FORMULA']['etc']);
		if($feeval==-1)
			continue;
		
		if(($q++%2)==0)
			$bg="bgcolor=#FAFAFA";
		else
			$bg="bgcolor=#FAFAFA";
?>
       <tr <?php echo $bg;?>>
         <td  id="myborder">
		 <?php
			$sql="select * from feepay where stu_uid='$stu_uid' and year='$year' and fee='$feename'";
			$res2=mysql_query($sql) or die("$sql - query failed:".mysql_error());
			$found=mysql_num_rows($res2);
			$rm="";$dt="";	$tid="";
			$resit="";
			if($found>0){
				$row2=mysql_fetch_assoc($res2);
				$rm=$row2['rm'];
				$tid=$row2['tid'];
				$resit=$row2['resitno'];
				$dt=$row2['cdate'];
				$dt=strtok($dt," ");
				echo "-PAID-";
			}
			else{
				if($feeval==0)
					echo "-FOC-";
				else
					echo "-UNPAID-";
			}
		   ?>
         	</td>
		 	<td id="myborder"><?php echo "$dt";?></td>
		 	<td id="myborder"><?php echo "$resit";?></td>
         	<td id="myborder"><?php echo "$feename";?></td>
        	<td id="myborder"align="center"><?php if($found) printf("%.02f",$rm); else printf("%.02f",$feeval);?></td>
       </tr>
	
<?php } ?> <!-- yuran -->
<?php } ?> <!-- jenis yuran -->
 </table>
  


</form>
</div></div>
</body>
</html>
<!-- 
ver 2.6
11-11-2008 add paydate
11-11-2008 add dll paytype
 -->