<?php
/**
111017 - update fee code for feestu
**/
$vmod="v6.0.0";
$vdate="111017";
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK|KEUANGAN');
$username = $_SESSION['username'];

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

	$feeval=$_POST['feeval'];
	$feename=$_POST['feename'];
	$feetype=$_POST['feetype'];
	$feesta=$_POST['feesta'];
	$feedesc=$_POST['feedesc'];
	$feepdt=$_POST['feepdt'];
	$feerno=$_POST['feerno'];
	$op=$_POST['op'];

if($op=='save'){
	if (count($feename)>0) {
		//$sql="delete from feestu where uid='$uid' and ses='$year' and sid=$sid";
		$res=mysql_query($sql) or die("$sql - query failed:".mysql_error());
		for ($i=0; $i<count($feename); $i++) {
				$fn=$feename[$i];
				$ft=$feetype[$i]; if($ft=="") $ft=0;
				$fv=$feeval[$i];
				$fs=$feesta[$i];
				$fd=$feedesc[$i];
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
						$sql="update feestu set val=$fv where uid='$uid' and ses='$year' and sid=$sid and fee='$fn'";
				else
						$sql="insert into feestu(dtm,sid,uid,ses,fee,typ,val,sta,des,rno,pdt,adm,mon,cod)
						values(now(),$sid,'$uid','$year','$fn',$ft,$fv,$fs,'$fd','$resit','$paydate','$username','$feemonth','$feecode')";
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
function check(){
	var sum=0;
	var checked=false;
}

</script>
<script language="JavaScript">
function process_form(operation){
		ret = confirm("Kemaskini maklumat??");
		if (ret == true){
			document.myform.op.value='save';
			document.myform.submit();
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
		<input type="hidden" name="op">

<div id="content">
<div id="mypanel">
			<div id="mymenu" align="center">
			<a href="#" onClick="process_form();" id="mymenuitem"><img src="../img/save.png"><br>Save</a>
			<a href="#" onClick="javascript:href='<?php echo $FN_FEEPAY;?>.php?uid=<?php echo "$uid";?>&sid=<?php echo "$sid";?>&year=<?php echo "$year";?>'" id="mymenuitem"><img src="../img/goback.png"><br>Back</a>
			<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
			</div><!-- mymenu -->
			<div align="right">
					<br><br><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
			</div>
</div><!-- mypanel -->
<div id="story">
<div id="mytitle">
	<div style="float:left">Yuran Sekolah <?php echo "$schlevel";?> <?php if($f==1) echo "<font color=\"#0066FF\">&lt;successfully&nbsp;update&gt;</font>";?></div>
</div>
<div id="tipsdiv" class="printhidden">
	- Sila setkan maklumat yuran mengikut keperluan pelajar<br>
	- Setkan nilai sebenar yuran yang dikenakan<br>
	- Nilai -1 bermaksud pelajar tidak dikenakan yuran tersebut (tekan butang -1 untuk cara pantas)<br>
	
	
</div>
 <table width="100%" id="mytitle">
   <tr>
     <td width="59%" valign=top>
	 <table width="100%"  >
	 	<tr>
         <td width="29%" >Yuran Sesi</td>
		 <td width="2%" >:</td>
         <td width="71%">
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
         <td width="29%" >Nama</td>
		 <td width="2%" >:</td>
         <td width="71%">&nbsp;<?php echo "$name";?></td>
       </tr>
       <tr>
         <td width="29%" >ID Pelajar</td>
		 <td width="2%" >:</td>
         <td width="71%">&nbsp;<?php echo "$stu_uid";?> </td>
       </tr>
       <tr>
         <td width="29%">Kad Pengenalan</td>
		 <td width="2%" >:</td>
         <td width="71%">&nbsp;<?php echo "$ic";?> </td>
       </tr>
	   <tr>
         <td width="29%" >Daftar</td>
		 <td width="2%" >:</td>
         <td width="71%">&nbsp;<?php echo "$rdate";?></td>
       </tr>
       <tr>
         <td width="29%" >Kelas</td>
		 <td width="2%" >:</td>
         <td width="71%">&nbsp;<?php echo "$clsname";?> </td>
       </tr>
	   
     </table></td>
     <td width="41%"><table width="100%" >
       <tr>
         <td width="53%" align="right">Anak Yatim:</td>
         <td width="47%">&nbsp;<?php if($isyatim) echo "YA"; else echo "-";?></td>
       </tr>
       <tr>
         <td width="53%" align="right">Anak Staff:</td>
         <td width="47%">&nbsp;<?php if($isstaff) echo "YA"; else echo "-";?></td>
       </tr>
       <tr>
         <td width="53%" align="right">Bekas Pelajar:</td>
         <td width="47%">&nbsp;<?php if($isxpelajar) echo "YA"; else echo "-";?></td>
       </tr>
       <tr>
         <td width="53%" align="right">Pelajar Kawasan:</td>
         <td width="47%">&nbsp;<?php if($iskawasan) echo "YA"; else echo "-";?></td>
       </tr>
       <tr>
         <td width="53%" align="right">Anak Sekolah:</td>
         <td width="47%">&nbsp;<?php echo "$noanak/$jumanak";?> </td>
       </tr>
	   <tr>
         <td width="53%" align="right">Asrama:</td>
         <td width="47%">&nbsp;<?php if($ishostel) echo "YA"; else echo "-";?></td>
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

	<table width="100%" cellpadding="0" cellspacing="0">
<?php
	$sql="select * from type where grp='feetype' order by idx";
	$resfeetype=mysql_query($sql)or die("query failed:".mysql_error());
	while($rowfeetype=mysql_fetch_assoc($resfeetype)){
		$feetype=$rowfeetype['val'];
		$feegroup=$rowfeetype['prm'];
?>
       <tr>
	   	<td id="mytabletitle" width="10%">Status</td>
		<td id="mytabletitle" width="10%">Tarikh</td>
		<td id="mytabletitle" width="10%">ResitNo</td>
        <td id="mytabletitle" width="30%"><?php echo $feegroup;?></td>
        <td id="mytabletitle" width="10%" align="center">Jumlah(RP)</td>
		<td id="mytabletitle" width="30%" align="center">Keterangan</td>
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
		$strstatus="";
		$found=0;$resitno="";
		$bg="#FFFFFF";
		
		include($CONFIG['FILE_FORMULA']['etc']);
	
		//echo $sql;
		if($feeval==-1){
			$feesta=-1;
			$strstatus="-EXCLUDED-";
			$bg="#FFFFFF";
		}else{
			$sql="select * from feepay where stu_uid='$uid' and year='$year' and fee='$feename'  and isdelete=0";
			$res2=mysql_query($sql) or die("$sql - query failed:".mysql_error());
			if($row2=mysql_fetch_assoc($res2)){
				$rm=$row2['rm'];
				$resit=$row2['tid'];
				$resitno=$row2['resitno'];
				$dt=$row2['cdate'];
				$paydate=strtok($dt," ");
				$found=1;
				$readonly="readonly";
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
       <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';" >
         <td id="myborder">&nbsp;<?php echo "$strstatus";?></td>
		 <td id="myborder">&nbsp;<?php echo "$paydate";?></td>
		 <td id="myborder">&nbsp;<?php echo $resitno;?></td>
         <td id="myborder">&nbsp;<?php echo "$feename";?></td>
        <td id="myborder"align="right">&nbsp;
		<?php if ($readonly==""){?>
				<input type="button" value="-1" onClick="document.myform.feeval<?php echo $q;?>.value=-1">
		<?php } ?>
		 <input name="feeval[]" id="feeval<?php echo $q;?>" type="text" size="4" value="<?php if($found) printf("%.02f",$rm); else printf("%.02f",$feeval);?>" <?php echo $readonly?> >
		 <input name="feename[]" type="hidden" value="<?php echo $feename?>" >
		 <input name="feetype[]" type="hidden" value="<?php echo $feetype?>" >
		 <input name="feesta[]" type="hidden" value="<?php echo $feesta?>" >
		 <input name="feerno[]" type="hidden" value="<?php echo $resit?>" >
		 <input name="feepdt[]" type="hidden" value="<?php echo $paydate?>" >
		 </td>
		 <td id="myborder"><input name="feedesc[]" type="text" style="width:99% " value="<?php echo $feedes?>" <?php echo $readonly?>></td>
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