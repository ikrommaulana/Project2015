<?php
//17/05/2010 - add discount
$vmod="v5.1.0";
$vdate="110108";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");

verify('ADMIN|AKADEMIK|KEUANGAN');
$username = $_SESSION['username'];

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

	$feeval=$_POST['feeval'];
	$feename=$_POST['feename'];
	$feetype=$_POST['feetype'];
	$feesta=$_POST['feesta'];
	$feedesc=$_POST['feedesc'];
	$feedis=$_POST['feedis'];
	$jumdis=$_POST['jumdis'];
	$feerm=$_POST['feerm'];
	$feepdt=$_POST['feepdt'];
	$feerno=$_POST['feerno'];
	$op=$_POST['op'];

if($op=='save'){
	if (count($feename)>0) {
		//$sql="delete from feestu where uid='$uid' and ses='$year' and sid=$sid";
		//$res=mysql_query($sql) or die("$sql - query failed:".mysql_error());
		for ($i=0; $i<count($feename); $i++) {
			$fn=$feename[$i];
			$ft=$feetype[$i]; if($ft=="") $ft=0;
			$fv=$feeval[$i];
			$fs=$feesta[$i];
			$fd=$feedesc[$i];
			$fdis=$feedis[$i];
			$fdisval=$jumdis[$i];
			$frm=$feerm[$i];
			$paydate=$feepdt[$i];
			$resit=$feerno[$i];

				$sql="select * from type where grp='yuran' and prm='$fn'";
				$resmon=mysql_query($sql)or die("$sql - query failed:".mysql_error());
				$rowmon=mysql_fetch_assoc($resmon);
				$feemonth=$rowmon['code'];
				$feecode=$rowmon['etc'];
				if($feemonth=="")
					$feemonth=0;
					
			$sql="select * from feestu where uid='$uid' and ses='$year' and sid=$sid and fee='$fn'";
			$res=mysql_query($sql) or die("$sql - query failed:".mysql_error());
			$found=mysql_num_rows($res);
			if($found>0)
					$sql="update feestu set val=$fv,dis=$fdis,disval=$fdisval,rm=$frm,des='$fd' where uid='$uid' and ses='$year' and sid=$sid and fee='$fn'";
			else
					$sql="insert into feestu(dtm,sid,uid,ses,fee,typ,val,sta,des,rno,pdt,adm,dis,disval,rm,mon,cod)values
				(now(),$sid,'$uid','$year','$fn',$ft,$fv,$fs,'$fd','$resit','$paydate','$username',$fdis,$fdisval,$frm,'$feemonth','$feecode')";
			$res=mysql_query($sql) or die("$sql - query failed:".mysql_error());
		}
		$f=1;
		sys_log($link,"Fee","Update Student Fee","Matric:$uid","",$sid);
		echo "<script language=\"javascript\">location.href='$FN_FEEPAY.php?uid=$uid&sid=$sid&year=$year'</script>";
	}
}
	$sql="select * from stu where uid='$uid'";
	$res=mysql_query($sql) or die("$sql - query failed:".mysql_error());
	if($row=mysql_fetch_assoc($res)){
		$name=$row['name'];
		$stu_id=$row['id'];
		$stu_uid=$row['uid'];
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
		$isfeenew=$row['isfeenew'];
		$isspecial=$row['isspecial'];
		
		$isfeebulanfree=$row['isfeefree'];
		
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
	
}//else

	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php include("$MYOBJ/calender/calender.htm")?>
<SCRIPT LANGUAGE="JavaScript">
function process_form(operation){
		ret = confirm("Save the information??");
		if (ret == true){
			document.myform.op.value='save';
			document.myform.submit();
			return true;
		}
		return false;
}
function process_diskaun(idx)
{
	var dis = parseFloat(document.getElementById("feedis"+idx).value);
	var jum = parseFloat(document.getElementById("feerm"+idx).value);
	if(jum>0){
		jumdis=jum*dis/100;
		lastprice=jum-jumdis;
		document.getElementById("jumdis"+idx).value=jumdis.toFixed(2);
		document.getElementById("feeval"+idx).value=lastprice.toFixed(2);
	}else{
		document.getElementById("jumdis"+idx).value=0;
		document.getElementById("feeval"+idx).value=jum;
	}
}
function process_potongan(idx)
{
	//var dis = parseFloat(document.getElementById("feedis"+idx).value);
	var jumdis = parseFloat(document.getElementById("jumdis"+idx).value);
	var jum = parseFloat(document.getElementById("feerm"+idx).value);
	if(jum>0){
		//jumdis=jum*dis/100;
		dis=jumdis/jum*100;
		lastprice=jum-jumdis;
		//document.getElementById("jumdis"+idx).value=jumdis.toFixed(2);
		document.getElementById("feedis"+idx).value=dis.toFixed(2);
		document.getElementById("feeval"+idx).value=lastprice.toFixed(2);
	}else{
		document.getElementById("jumdis"+idx).value=0;
		document.getElementById("feeval"+idx).value=jum;
	}
}
function process_value(idx)
{
	var feeval = parseFloat(document.getElementById("feeval"+idx).value);
	var feerm  = parseFloat(document.getElementById("feerm"+idx).value);
	jumdis=feerm-feeval;
	if(jumdis>=0){
		dis=jumdis/feerm*100;
		document.getElementById("jumdis"+idx).value=jumdis.toFixed(2);
		document.getElementById("feedis"+idx).value=dis.toFixed(2);
	}else{
		alert('Operation not allowed. Value exceeded amount');
		document.myform.submit();
	}
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>IURAN - <?php echo strtoupper($name);?></title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<input type="hidden" name="sid" value="<?php echo "$sid";?>">
		<input type="hidden" name="uid" value="<?php echo "$uid";?>">
		<input type="hidden" name="op">

<div id="content">
	<div id="mypanel">
			<div id="mymenu" align="center">
					<a href="#" onClick="javascript:href='<?php echo "$FN_FEEPAY.php?uid=$uid&sid=$sid&year=$year";?>'" id="mymenuitem"><img src="../img/goback.png"><br>Back</a>
					<a href="#" onClick="process_form();" id="mymenuitem"><img src="../img/save.png"><br>Simpan</a>
				    <a href="#" onClick="javascript:href='<?php echo "$FN_FEECFG.php?uid=$uid&sid=$sid&year=$year&year$year";?>'" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
					<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
			</div><!-- mymenu -->
			<div align="right">
					<br><br><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
			</div>
	</div><!-- mypanel -->
<div id="story">
<div id="mytitlebg">
<?php echo strtoupper($lg_fee);?> <?php echo strtoupper("$schlevel");?> <?php if($f==1) echo "<font color=\"#0066FF\">&lt;successfully&nbsp;update&gt;</font>";?>
</div>

 <table width="100%" bgcolor="#FFFF99" id="myborder">
   <tr>
     <td width="59%" valign=top>
	 <table width="100%"  >
	 	<tr>
         <td width="15%" ><?php echo strtoupper($lg_year_session);?></td>
		 <td width="2%" >:</td>
         <td width="80%">
			<select name="year" id="year" onchange="document.myform.submit();">
<?php
            echo "<option value=$year>$year</option>";
			$sql="select * from type where grp='session' and prm!='$year' order by val desc";
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
         <td><?php echo strtoupper($lg_name);?></td>
		 <td>:</td>
         <td>&nbsp;<?php echo strtoupper("$name");?></td>
       </tr>
       <tr>
         <td><?php echo strtoupper($lg_matric);?></td>
		 <td>:</td>
         <td>&nbsp;<?php echo "$stu_uid";?> </td>
       </tr>
       <tr>
         <td><?php echo strtoupper($lg_ic_number);?></td>
		 <td>:</td>
         <td>&nbsp;<?php echo "$ic";?> </td>
       </tr>
	   <tr>
         <td><?php echo strtoupper($lg_register);?></td>
		 <td>:</td>
         <td>&nbsp;<?php echo "$rdate";?></td>
       </tr>
       <tr>
         <td><?php echo strtoupper($lg_class);?></td>
		 <td>:</td>
         <td>&nbsp;<?php echo "$clsname";?> </td>
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
         <td width="53%" align="right"><?php echo strtoupper($lg_from_primary);?>:</td>
         <td width="47%">&nbsp;<?php if($isxpelajar) echo $lg_yes; else echo "-";?></td>
       </tr>
       <!-- <tr>
         <td width="53%" align="right"><?php echo strtoupper($lg_kariah);?>:</td>
         <td width="47%">&nbsp;<?php if($iskawasan) echo $lg_yes; else echo "-";?></td>
       </tr> -->
       <tr>
         <td width="53%" align="right"><?php echo "KAKAK BERADIK ";?>:</td>
         <td width="47%">&nbsp;<?php echo "$noanak/$jumanak";?> </td>
       </tr>
	   <tr>
         <td width="53%" align="right"><?php echo strtoupper($lg_hostel);?>:</td>
         <td width="47%">&nbsp;<?php if($ishostel) echo $lg_yes; else echo "-";?></td>
       </tr>
<?php if($si_fee_show_old_fee){?>
	    <tr>
         <td width="53%" align="right">Tunggakan Yuran Lama:</td>
         <td width="47%">&nbsp;<?php  echo "$feehutang";?></td>
       </tr>
<?php } ?>

     </table>
	 
	 </td>
   </tr>
 </table>

<table width="100%" cellspacing="0" cellpadding="0">
<?php
	$jj=0;
	$sql="select * from type where grp='feetype' order by idx";
	$resfeetype=mysql_query($sql)or die("query failed:".mysql_error());
	while($rowfeetype=mysql_fetch_assoc($resfeetype)){
		$feetype=$rowfeetype['val'];
		$feegroup=$rowfeetype['prm'];
?>
       <tr>
	   	<td id="mytabletitle" width="7%"align="center"><?php echo strtoupper($lg_status);?></td>
		<td id="mytabletitle" width="7%"align="center"><?php echo strtoupper($lg_date);?></td>
		<td id="mytabletitle" width="5%"align="center"><?php echo strtoupper($lg_no_resi) ;?></td>
        <td id="mytabletitle" width="20%"><?php echo strtoupper($feegroup);?></td>
        <td id="mytabletitle" width="10%" align="center"><?php echo strtoupper($lg_amount);?> Rp</td>
		<td id="mytabletitle" width="10%" align="center"><?php echo strtoupper($lg_discount);?> %</td>
		<td id="mytabletitle" width="10%" align="center"><?php echo strtoupper($lg_discount);?> (<?php echo strtoupper($lg_rm);?>)</td>
		<td id="mytabletitle" width="10%" align="center"><?php echo strtoupper($lg_after_disc);?> Rp</td>
		<td id="mytabletitle" width="30%" align="center"><?php echo strtoupper($lg_description);?></td>
       </tr>

<?php 
	
	$sql="select feeset.* from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sid and year='$year' and type.grp='yuran' and type.val=$feetype";
	$resfee=mysql_query($sql)or die("$sql - query failed:".mysql_error());
	while($rowfee=mysql_fetch_assoc($resfee)){
		$rm="";
		$dt="";
		$tid="";
		$feeval=""; $resit="";$paydate="";
		$readonly="";
		$disabled="";
		$strstatus="";
		$resitno="";
		$found=0;
		$bg="#FFFFFF";
		
		include($CONFIG['FILE_FORMULA']['etc']);
	
		//echo $sql;
		if($feeval==-1){
			$feesta=-1;
			$strstatus="-EXCLUDED-";
			$bg="#FAFAFA";
		}else{
			$sql="select * from feepay where stu_uid='$uid' and year='$year' and fee='$feename' and isdelete=0";
			$res2=mysql_query($sql) or die("$sql - query failed:".mysql_error());
			if($row2=mysql_fetch_assoc($res2)){
				$rm=$row2['rm'];
				$resit=$row2['tid'];
				$resitno=$row2['resitno'];
				
				$dt=$row2['cdate'];
				$paydate=strtok($dt," ");
				$found=1;
				$readonly="readonly";
				$disabled="disabled";
				$bg="#FAFAFA";
				$strstatus="-PAID-";
				$feesta=1;
			}elseif($feeval==0){
					$strstatus="-FOC-";
					$feesta=2;
			}else{
					$strstatus="-UNPAID-";
					$feesta=0;
			}
		}
		$q++;
?>
	<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
         <td id="myborder" align="center"><?php echo "$strstatus";?></td>
		 <td id="myborder"><?php echo "$paydate";?></td>
		 <td id="myborder"><?php echo $resitno;?></td>
         <td id="myborder"><?php echo "$feename";?></td>
         <td id="myborder"align="center">
		 	
			 <input type="button" value="-1" onClick="
			 	document.myform.feerm<?php echo $q-1;?>.value=-1;
			 	document.myform.feeval<?php echo $q-1;?>.value=-1;
				document.myform.feedis<?php echo $q-1;?>.value=0.00;
				document.myform.jumdis<?php echo $q-1;?>.value=0.00;
			">
			 <input name="feerm[]"  id="feerm<?php echo $jj;?>"  onKeyUp="process_diskaun(<?php echo $jj;?>)"  type="text" size="7" style="text-align:right;font-weight:bold;color:#0000FF" value="<?php printf("%.02f",$feerm);?>" <?php echo $readonly?> ><!--here-->
		 </td>
		 <td id="myborder"align="center"><input name="feedis[]" id="feedis<?php echo $jj;?>" onKeyUp="process_diskaun(<?php echo $jj;?>)"  type="text" size="5" style="text-align:right;" value="<?php printf("%.02f",$feedis);?>" <?php echo $readonly?> ></td>
		 <td id="myborder"align="center"><input name="jumdis[]" id="jumdis<?php echo $jj;?>" onKeyUp="process_potongan(<?php echo $jj;?>)" type="text" size="7" style="text-align:right;" value="<?php printf("%.02f",$feedisval);?>" <?php echo $readonly?> ></td>
		 <td id="myborder"align="center">
				<input name="feeval[]" id="feeval<?php echo $jj;?>" type="text" size="7"   onKeyUp="process_value(<?php echo $jj;?>)" style="text-align:right; font-weight:bold; color:#009900" value="<?php if($found) printf("%.02f",$rm); else printf("%.02f",$feeval);?>" <?php echo $readonly?>>
				<input name="feename[]" type="hidden" value="<?php echo $feename?>" >
				<input name="feetype[]" type="hidden" value="<?php echo $feetype?>" >
				<input name="feesta[]" type="hidden" value="<?php echo $feesta?>" >
				<input name="feerno[]" type="hidden" value="<?php echo  $resit?>" >
				<input name="feepdt[]" type="hidden" value="<?php echo $paydate?>" >
		 </td>
		 <td id="myborder"><input name="feedesc[<?php echo $jj++;?>]" type="text" style="width:99% " value="<?php echo $feedes;?>" <?php echo $readonly?>></td>
	   </tr>
	
<?php } ?> <!-- jenis yuran -->
<?php } ?> <!-- group yuran -->
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