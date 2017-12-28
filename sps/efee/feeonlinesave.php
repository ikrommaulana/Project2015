<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK|KEUANGAN');
$username = $_SESSION['username'];


	$feeid=$_POST['feeid'];
	$status=$_POST['status'];
	
	$sql="select * from feeonlinetrans where id=$feeid";
			
	$res=mysql_query($sql) or die(mysql_error());
	if($row=mysql_fetch_assoc($res)){
			$year=$row['year'];
			$sid=$row['sch_id'];
			$stu_id=$row['stu_id'];
			$uid=$row['stu_uid'];
			$total=$row['rm'];
			$bank=$row['bank'];
			$bankno=$row['bankno'];
			$paydate=$row['paydate'];
			if($paydate=="")
				$paydate=date("Y-m-d");
	}

	$sql="select * from stu where uid='$uid' and sch_id=$sid";
	$res=mysql_query($sql) or die("$sql - query failed:".mysql_error());
	if($row=mysql_fetch_assoc($res)){
		$name=stripslashes($row['name']);
		$stuname=stripslashes($row['name']);
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
		$tabung=$row['fee_tabung'];
		
		$isfeenew=$row['isfeenew'];
		$isspecial=$row['isspecial'];
		$isinter=$row['isinter'];
		
		$isfeebulanfree=$row['isfeefree'];
		$isfeeonanak=$row['isfeeonanak'];
		
		if($si_fee_on_childno)//force on anak
			$isfeeonanak=1;
		
		$sql="select * from ses_stu where stu_uid='$uid' and sch_id=$sid and year='$year'";
		$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2)){
			$clsname=$row2['cls_name'];
			$clscode=$row2['cls_code'];
			$clslevel=$row2['cls_level'];
		}
	
		$sql="select * from sch where id=$sid";
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
	
		$sql="select * from sch where id='$sid'";
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
		$sidresitno=$row['resitno']+1;
		$scode=$row['scode'];
		
	$sql="select * from ses_stu where stu_uid='$uid' and year='$year'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	if($row=mysql_fetch_assoc($res))
		$clsname=$row['cls_name'];
		
	$sql="select * from type where grp='fee_interface' and (sid=0 or sid='$sid')";
	$res=mysql_query($sql) or die("$sql failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
			$prm=$row['prm'];
			$CONFIG[$prm]['val']=$row['val'];
			$CONFIG[$prm]['code']=$row['code'];
			$CONFIG[$prm]['etc']=$row['etc'];
	}
	
	if($status==2){
		$sql="update feeonlinetrans set sta=$status,admin='$username' where id=$feeid";
		$res=mysql_query($sql) or die(mysql_error());
		$statusfee="DITOLAK";
	}
	else{
	
	
		//check feestu
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

	
		$sql="update feeonlinetrans set sta=$status,admin='$username' where id=$feeid";
		$res=mysql_query($sql) or die(mysql_error());
		$statusfee="DITERIMA";
		
		if ($USE_SCHOOL_RESIT_NUM){
			$newresitno=sprintf("$scode/%06d",$sidresitno);
			$sql="update sch set resitno=$sidresitno where id=$sch_id";
			mysql_query($sql)or die("$sql - failed:".mysql_error());
		}else{
			$newresitno=sprintf("%06d",$sidresitno);
			$sql="update sch set resitno=$sidresitno";
			mysql_query($sql)or die("$sql - failed:".mysql_error());
		}
		
		$stuname=mysql_real_escape_string($stuname);
		$sql="insert into feetrans (cdate,year,sch_id,stu_id,stu_uid,rm,onlineid,admin,paytype,nocek,paydate,resitno,name,ic)
			values(now(),'$year',$sid,$stu_id,'$uid',$total,$feeid,'$username','Online','$bankno','$paydate','$newresitno','$stuname','$ic')";
	   	mysql_query($sql)or die("query failed:".mysql_error());
		$fid=mysql_insert_id($link);
		
		$sql="select * from feeonlinepay where tid=$feeid order by id";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
	  	while($row=mysql_fetch_assoc($res)){
			$fee=$row['fee'];
			$rm=$row['rm'];
			$sql="select * from type where grp='yuran' and prm='$fee'";

			$resfeetype=mysql_query($sql)or die("query failed:".mysql_error());
			$roww=mysql_fetch_assoc($resfeetype);
			$ftype=$roww['val'];
			$sql="insert into feepay (cdate,year,sch_id,stu_id,stu_uid,rm,fee,tid,admin,resitno,type,item_type)values
			(now(),'$year',$sid,$stu_id,'$uid',$rm,'$fee',$fid,'$username','$newresitno','fee','$ftype')";

			mysql_query($sql)or die("query failed:".mysql_error());
			$sql="update feestu set sta=1,val=$rm,rno=$fid,pdt=now(),adm='$username',resitno='$newresitno' where uid='$uid' and ses='$year' and sid='$sid' and fee='$fee'";
			mysql_query($sql)or die("$sql - query failed:".mysql_error());
		}
		echo "<script language=\"javascript\">location.href='$FN_FEERESIT.php?id=$fid'</script>";
	}	
	

?> 

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>

<body>
<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
		<a href="#" onClick="window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
	</div>
</div>
<div id="story"  style="font-size:120%">

<?php if($CONFIG['RESIT_SHOWHEADER']['val']){ 

	echo "<div id=\"myborder\" style=\"padding:0px 1px 0px 1px\">";
	include ('../inc/school_header.php');
 } else {
	echo $CONFIG['RESIT_SHOWHEADER']['etc'];
 } 
 ?>
 <?php
	$sql="select * from stu where id='$stu_id'";
	$res=mysql_query($sql) or die(mysql_error());
	$row=mysql_fetch_assoc($res);
	$stu_name=$row['name'];
	$stu_ic=$row['ic'];
	$uid=$row['uid'];
	mysql_free_result($res);
	
	$sql="select * from sch where id='$sid'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
    $sch_name=$row['name'];
	mysql_free_result($res);
?>
<div align="center"><font size=3 color="#FF0000"><strong>IULAN ONLINE - <?php echo "$statusfee";?></strong></font></div>
 <br>
 <table width="100%" >
   <tr >
     <td>     
	 <table width="100%">
       <tr>
         <td width="67%"><table width="100%"  border="0" cellpadding="0" cellspacing="3">
             <tr>
               <td width="38%" ><?php echo $lg_sekolah;?></td>
               <td width="3%">:</td>
               <td width="59%"><?php echo "$sch_name"; ?> </td>
             </tr>
             <tr>
               <td width="38%" ><?php echo $lg_level;?></td>
               <td width="3%">:</td>
               <td width="59%"><?php echo "$clsname";?></td>
             </tr>
			 <tr>
               <td width="38%" ><?php echo $lg_session;?></td>
               <td width="3%">:</td>
               <td width="59%"><?php echo "$year / $clsname";?></td>
             </tr>
             <tr>
               <td width="38%" ><?php echo $lg_matric;?></td>
               <td width="3%">:</td>
               <td width="59%"><?php echo $uid;?></td>
             </tr>
             <tr>
               <td width="38%" ><?php echo $lg_name;?></td>
               <td width="3%">:</td>
               <td width="59%"><?php echo "$stu_name";?> </td>
             </tr>
             <tr>
               <td width="38%" >No KTP</td>
               <td width="3%">:</td>
               <td width="59%"><?php echo "$stu_ic";?> </td>
             </tr>
         </table></td>
         <td width="33%"><table width="100%"  border="0" cellpadding="0" cellspacing="3">
             <tr>
               <td width="34%" >No Resi</td>
               <td width="3%">:</td>
               <td width="63%"><?php $ff=sprintf("%06s/%s",$fid,$feeid); echo "$ff";?>
               </td>
             </tr>
             <tr>
               <td width="34%" >Tanggal</td>
               <td width="3%">:</td>
               <td width="63%"><?php $dt=date("Y-m-d");echo "$dt";?>
               </td>
             </tr>
             <tr>
               <td width="34%">Status</td>
               <td width="3%">:</td>
               <td width="63%"><?php echo "$statusfee";?> </td>
             </tr>
             <tr>
               <td width="34%">Bank</td>
               <td width="3%">:</td>
               <td width="63%"><?php echo "$bank";?> </td>
             </tr>
             <tr>
               <td width="34%">Bank Ref.</td>
               <td width="3%">:</td>
               <td width="63%"><?php echo "$bankno";?> </td>
             </tr>
         </table></td>
       </tr>
     </table></td>
   </tr>
</table>
          <table width="100%">
            <tr >
              <td id="mytabletitle" width="5%" align="center">ITEM</td>
              <td id="mytabletitle" width="65%">MAKLUMAT YURAN</td>
              <td id="mytabletitle" width="30%">JUMLAH (RP)</td>
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
            </tr>

<?php } ?>
            <tr >
              <td id="myborder">&nbsp;</td>
              <td id="myborder"><strong>Jumlah Bayaran:</strong></td>
              <td id="myborder"><strong><?php echo "$total.00";?></strong></td>
            </tr>
		</table>
 </div></div>
</body>
</html>
