<?php
//$vdate="110109";
$vdate="110109";// download excell and select by date
$vmod="v6.0.0";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
$username = $_SESSION['username'];
$isexcell = $_REQUEST['isexcell'];

if($isexcell){
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=report.xls");
	header ("Pragma: no-cache");
	header("Expires: 0");
}
	verify('ADMIN|AKADEMIK|KEUANGAN');
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];

	$sdate=$_REQUEST['sdate'];
	if($sdate=="")
		$sdate=date('Y-m-d');

	$edate=$_REQUEST['edate'];
	if($edate==""){
		$tomorrow = mktime(0, 0, 0, date("m"), date("d")+1, date("y"));
		//$edate=date('Y-m-d',$tomorrow);
		$edate=$sdate;
	}
	//$sqldate=" and (feetrans.cdate>='$sdate' and feetrans.cdate<'$edate 23:59:59')";
	$sqldate=" and (feetrans.paydate>='$sdate' and feetrans.paydate<='$edate')";

	//$sqldate=" and feetrans.paydate='$sdate'";
	if($sid!=0){
		$sql="select * from sch where id='$sid'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=stripslashes($row['name']);
		$ssname=stripslashes($row['sname']);
		$simg=$row['img'];
		$sqlsid="and sch_id='$sid'";
		$sqlfeetranssid="and feetrans.sch_id='$sid'";
		}else{
			if($FEE_REPORT_ALL_SCHOOL)
				$sname= $lg_all." ". $lg_school;
			else{
				$sqlsid=" and sch_id=$sid";
				$sqlfeetranssid="and feetrans.sch_id='$sid'";
			}
		}
?>


<html>
<head>
<?php include("$MYOBJ/datepicker/dp.php")?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<?php if($isexcell){?>
<style type="text/css">
#myborder{
	border: 1px solid #F1F1F1;/*F1F1F1*/
}
#mytabletitle{
	border: 1px solid #DDDDDD;
	background-color:#EEEEEE;
	font-weight:bold;
}
</style>
<?php }else{ ?>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<style type="text/css" media="print">
	.print_font{
		font-size:70%;
	}
</style>
<?php } ?>
</head>

<body >
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="p" value="../efee/feesum">

<div id="content">
<?php if(!$isexcell){?>
<div id="mypanel">
<div id="mymenu" align="center" >
     <a href="../efee/feesum.php?<?php echo "sid=$sid&sdate=$sdate&edate=$edate&isexcell=1";?>" id="mymenuitem"><img src="../img/excel.png"><br>Excel</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>    
	<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>    
</div> <!-- end mymenu -->
	<div align="right"  ><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br></div>
	</div> <!-- end mypanel -->
	<div id="mytabletitle" class="printhidden" align="right" >
		<a href="#" title="<?php echo $vdate;?>"></a><br>
				<select name="sid" id="sid" onChange="document.myform.submit()">
                <?php	
      		if(($sid=="0")&&($FEE_REPORT_ALL_SCHOOL))
            	echo "<option value=\"0\">- $lg_all $lg_school -</option>";
			elseif($sid=="0")
				echo "<option value=\"0\">- $lg_select $lg_school -</option>";
			else
                echo "<option value=$sid>$ssname</option>";
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=stripslashes($row['sname']);
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
			}
			if(($FEE_REPORT_ALL_SCHOOL)	&& ($sid>0))
					 echo "<option value=\"0\">- $lg_all -</option>";						  
			
?>
              </select>
                <input name="sdate" type="text" id="sdate" value="<?php echo "$sdate";?>" size="20" readonly onClick="displayDatePicker('sdate');" onKeyDown="displayDatePicker('sdate');">	
                <input name="edate" type="text" id="edate" value="<?php echo "$edate";?>" size="20" readonly onClick="displayDatePicker('edate');" onKeyDown="displayDatePicker('edate');">	
              <input type="submit" name="Submit" value="View"  >
	</div>
<?php } ?>
<div id="story" style="border:none;">

<div id="mytitlebg">
<?php echo strtoupper($lg_daily_collection_report);?>  : <?php echo strtoupper($sname);?> (<?php echo "$sdate";?> - <?php echo "$edate";?>)
</div>

<table width="100%" cellspacing="0">
	<tr>
		<td id="mytabletitle" width="1%" align="center" rowspan="2"><?php echo strtoupper($lg_no);?></td>
		<td id="mytabletitle" width="7%" align="center" rowspan="2"><?php echo strtoupper($lg_date);?></td>
<?php 
		$sql="select * from type where grp='feetype' order by idx";
		$res9=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row9=mysql_fetch_assoc($res9)){
			$feetype=$row9['prm'];
			$feetypeval=$row9['val'];
			$feetypeetc=$row9['etc'];
				if($feetypeetc){ //just show summarized
					$colspan=1;
					$rowspan=2;
				}else{
					$sql="select * from type where grp='yuran' and val=$feetypeval";
					$res=mysql_query($sql)or die("$sql failed:".mysql_error());
					$colspan=mysql_num_rows($res);
					if($colspan==0)
						$colspan=1;
					$rowspan=1;
			}
		
?>
		<td id="mytabletitle" width="5%" align="center" colspan="<?php echo $colspan;?>" rowspan="<?php echo $rowspan;?>"><?php echo strtoupper($feetype);?></td>
<?php } ?>
<?php 
		$sql="select * from type where grp='saletype'";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$colspan=mysql_num_rows($res);
		if($colspan==0)
				$colspan=1;
?>
		<td id="mytabletitle" width="5%" align="center" colspan="<?php echo $colspan;?>"><?php echo strtoupper($lg_sales);?></td>
		<td id="mytabletitle" align=center width="5%" rowspan=2><?php echo strtoupper($lg_etc);?></td>
		<td id="mytabletitle" align=center width="5%" rowspan=2>ADV</td>
		<td id="mytabletitle" align=center width="5%" rowspan=2>CLM</td>
		<td id="mytabletitle" width="7%" align="center" rowspan="2"><?php echo strtoupper($lg_total);?><br>(<?php echo strtoupper($lg_rm);?>)</td>
	</tr>
	<tr>
		
<?php 
	 
		$sql="select * from type where grp='feetype' order by idx";
		$res9=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row9=mysql_fetch_assoc($res9)){
			$feetype=$row9['prm'];
			$feetypeval=$row9['val'];
			$feetypeetc=$row9['etc'];
				if($feetypeetc){ //just show summarized
						continue;//echo "<td id=\"mytabletitle\" align=center width=\"3%\">$feetype</td>";
				}else{
					$sql="select * from type where grp='yuran' and val=$feetypeval";
					$res=mysql_query($sql)or die("$sql failed:".mysql_error());
					if(mysql_num_rows($res)>0){
						while($row=mysql_fetch_assoc($res)){
							$xf=$row['prm'];
							$etc=$row['etc'];
							echo "<td id=\"mytabletitle\" align=center width=\"3%\"><a href=\"#\" title=\"$xf\">$etc</a></td>";
						}
					}else{
						echo "<td id=\"mytabletitle\" align=center width=\"3%\"><a href=\"#\" title=\"none\">-</a></td>";
					}
				}
		}


		$sql="select * from type where grp='saletype' order by val";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$xf=$row['prm'];
			$xc=$row['code'];
			echo "<td id=\"mytabletitle\" align=center width=\"3%\"><a href=\"#\" title=\"$xf\">$xc</a></td>";
		}
?>
		
	</tr>
<?php
		
$sql="select distinct(paydate) from feetrans where id>0 and isdelete=0 $sqlsid $sqldate order by paydate";
$ress=mysql_query($sql)or die("$sql failed:".mysql_error());
while($rows=mysql_fetch_assoc($ress)){
		$xdt=$rows['paydate'];
		$idx=0;
		$dayrm=0;
		$daylain=0;
		$dayadv=0;
		
		if(($q++%2)==0)
				$bg="#FAFAFA";
			else
				$bg="";
		$sql="select * from feetrans where id>0 and isdelete=0 $sqlsid and paydate='$xdt'";
    	$res=mysql_query($sql)or die("query failed:".mysql_error());
        while($row=mysql_fetch_assoc($res)){
			$fid=$row['id'];
			$xrm=$row['rm'];
			$dayrm=$dayrm+$xrm;
			$xtyp=$row['paytype'];
 
			$idx=0;
			$sql="select * from type where grp='feetype' order by idx";
			$res9=mysql_query($sql)or die("$sql failed:".mysql_error());
			while($row9=mysql_fetch_assoc($res9)){
						$feetype=$row9['prm'];
						$feetypeval=$row9['val'];
						$feetypeetc=$row9['etc'];
						if($feetypeetc){ //just show summarized
								$feerm="";
								//$sql="select feepay.rm from feepay,type where feepay.fee=type.prm and feepay.tid=$fid and feepay.type='fee' and type.val=$feetypeval";
								$sql="select rm from feepay where item_type=$feetypeval and tid=$fid and type='fee' and isdelete=0";
								$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
								while($rowxx=mysql_fetch_assoc($resxx)){
									$feerm=$feerm+$rowxx['rm'];
									$feerm2=$rowxx['rm'];
									
									$sql="select paytype from feetrans where id=$fid";
									$resxxx=mysql_query($sql)or die("$sql failed:".mysql_error());
									$rowxxx=mysql_fetch_assoc($resxxx);
									$ptype=$rowxxx['paytype'];
									$sumpaytype[$idx][$ptype]=$sumpaytype[$idx][$ptype]+$feerm2;
								}
								$dayfeerm[$idx]=$dayfeerm[$idx]+$feerm;
								$idx++;
						}else{
								$sql="select * from type where grp='yuran' and val=$feetypeval";
								$resx=mysql_query($sql)or die("$sql failed:".mysql_error());
								if(mysql_num_rows($resx)>0){
										while($rowx=mysql_fetch_assoc($resx)){
											$fee=$rowx['prm'];
											$feerm="";
											$sql="select feepay.rm,feetrans.paytype from feepay INNER JOIN feetrans ON feepay.tid=feetrans.id where tid=$fid and feepay.type='fee' and fee='$fee' and feepay.isdelete=0";
											$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
											if($rowxx=mysql_fetch_assoc($resxx)){
												$feerm=$rowxx['rm'];
												$ptype=$rowxx['paytype'];
												$sumpaytype[$idx][$ptype]=$sumpaytype[$idx][$ptype]+$feerm;
											}
											$dayfeerm[$idx]=$dayfeerm[$idx]+$feerm;
											$idx++;
										}
								}else{
									//$dayfeerm[$idx]=$dayfeerm[$idx]+$feerm;
									$idx++;
								}
						}
			}

			
			$sql="select * from type where grp='saletype' and val>0";
			$resx=mysql_query($sql)or die("$sql failed:".mysql_error());
			while($rowx=mysql_fetch_assoc($resx)){
				$saletype=$rowx['prm'];
				$feerm="";
				$sql="select feepay.rm from feepay,type where feepay.fee=type.prm and and feepay.tid=$fid and feepay.type='fee' and type.val=$feecode";
				$sql="select rm from feepay where item_type='$saletype' and feepay.type='sale' and  feepay.tid=$fid and isdelete=0";
				$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
				while($rowxx=mysql_fetch_assoc($resxx)){
					$feerm=$feerm+$rowxx['rm'];
					$feerm2=$rowxx['rm'];
					
					$sql="select paytype from feetrans where id=$fid";
					$resxxx=mysql_query($sql)or die("$sql failed:".mysql_error());
					$rowxxx=mysql_fetch_assoc($resxxx);
					$ptype=$rowxxx['paytype'];
					$sumpaytype[$idx][$ptype]=$sumpaytype[$idx][$ptype]+$feerm2;
				}
				$dayfeerm[$idx]=$dayfeerm[$idx]+$feerm;$idx++;
			}
			//check lain-lain
			$feelain=0;
			$feelaindes="";
			$sql="select * from feepay where tid=$fid and (fee='$lg_other' or fee='Lain-Lain')";
			$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
			if($rowxx=mysql_fetch_assoc($resxx)){
					$feelain=$rowxx['rm'];
					$feelaindes=$rowxx['etc'];
					$sql="select paytype from feetrans where id=$fid";
					$resxxx=mysql_query($sql)or die("$sql failed:".mysql_error());
					$rowxxx=mysql_fetch_assoc($resxxx);
					$ptype=$rowxxx['paytype'];
					$sumpaytype[$idx][$ptype]=$sumpaytype[$idx][$ptype]+$feelain;
			}
			$daylain=$daylain+$feelain;
			$dayfeerm[$idx]=$dayfeerm[$idx]+$feelain;
			$idx++;
			$feeadvance=0;
			$sql="select * from feepay where tid=$fid and fee='ADVANCE'";
			$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
			if($rowxx=mysql_fetch_assoc($resxx)){
					$feeadvance=$rowxx['rm'];
					$feelaindes=$rowxx['etc'];
					$sql="select paytype from feetrans where id=$fid";
					$resxxx=mysql_query($sql)or die("$sql failed:".mysql_error());
					$rowxxx=mysql_fetch_assoc($resxxx);
					$ptype=$rowxxx['paytype'];
					$sumpaytype[$idx][$ptype]=$sumpaytype[$idx][$ptype]+$feeadvance;
			}
			$dayadv=$dayadv+$feeadvance;
			$dayfeerm[$idx]=$dayfeerm[$idx]+$feeadvance;
			$idx++;
			$feeclaim=0;
			$sql="select * from feepay where tid=$fid and fee='RECLAIM'";
			$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
			if($rowxx=mysql_fetch_assoc($resxx)){
					$feeclaim=$rowxx['rm'];
					$feelaindes=$rowxx['etc'];
					$sql="select paytype from feetrans where id=$fid";
					$resxxx=mysql_query($sql)or die("$sql failed:".mysql_error());
					$rowxxx=mysql_fetch_assoc($resxxx);
					$ptype=$rowxxx['paytype'];
					$sumpaytype[$idx][$ptype]=$sumpaytype[$idx][$ptype]+$feeclaim;
			}
			$dayclm=$dayclm+$feeclaim;
			$dayfeerm[$idx]=$dayfeerm[$idx]+$feeclaim;
			$idx++;
	} 
		$bg="";
?>
	<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
		<td id="myborder" align="center"><?php echo $q;?></td>
		<td id="myborder" align="center"><?php echo $xdt;?></td>
<?php 
	for($jj=0;$jj<$idx;$jj++){
?>
			<td id="myborder" align=right><?php if($dayfeerm[$jj]==0) echo "-"; else echo $dayfeerm[$jj];?></td>
<?php } ?>
		<td id="myborder" align="right" ><?php echo number_format($dayrm,2,'.',',');?></td>
	</tr>
<?php
	for($jj=0;$jj<$idx;$jj++){
		$totalfeerm[$jj]=$totalfeerm[$jj]+$dayfeerm[$jj];
		$totalrm=$totalrm+$dayfeerm[$jj];
		$dayfeerm[$jj]=0;
	}

 } //for 
?>
	<tr>
		<td id="mytabletitle" align="center">&nbsp;</td>
		<td id="mytabletitle" align="center"><?php echo strtoupper($lg_total);?></td>
<?php 
	for($jj=0;$jj<$idx;$jj++){
?>
			<td id="mytabletitle" align=right><?php if($totalfeerm[$jj]==0) echo "-"; else echo $totalfeerm[$jj];?></td>
<?php } ?>
		<td id="mytabletitle" align="right" ><?php echo number_format($totalrm,2,'.',',');?></td>
	<tr>
</table>   
<!-- ------------------------------------------------------------------------------------------------------------- -->

<div id="mytitlebg"><?php echo strtoupper($lg_summary);?></div>
<table width="100%" class="print_font" cellspacing="0">
	<tr>
		<td id="mytabletitle" width="1%" align="center" rowspan="2"><?php echo strtoupper($lg_no);?></td>
		<td id="mytabletitle" width="7%" align="center" rowspan="2"><?php echo strtoupper($lg_item);?></td>
<?php 
		$sql="select * from type where grp='feetype' order by idx";
		$res9=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row9=mysql_fetch_assoc($res9)){
			$feetype=$row9['prm'];
			$feetypeval=$row9['val'];
			$feetypeetc=$row9['etc'];
			if($feetypeetc){ //just show summarized
				$colspan=1;
				$rowspan=2;
			}else{
				$sql="select * from type where grp='yuran' and val=$feetypeval";
				$res=mysql_query($sql)or die("$sql failed:".mysql_error());
				$colspan=mysql_num_rows($res);
				if($colspan==0)
					$colspan=1;
				$rowspan=1;
			}
?>
		<td id="mytabletitle" width="5%" align="center" colspan="<?php echo $colspan;?>" rowspan="<?php echo $rowspan;?>"><?php echo strtoupper($feetype);?></td>
<?php } ?>
<?php 
		$sql="select * from type where grp='saletype'";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$colspan=mysql_num_rows($res);
		if($colspan==0)
				$colspan=1;
?>
		<td id="mytabletitle" width="5%" align="center" colspan="<?php echo $colspan;?>"><?php echo strtoupper($lg_sales);?></td>
		<td id="mytabletitle" align=center width="5%" rowspan=2><?php echo strtoupper($lg_etc);?></td>
		<td id="mytabletitle" align=center width="5%" rowspan=2>ADV</td>
		<td id="mytabletitle" align=center width="5%" rowspan=2>CLM</td>
		<td id="mytabletitle" width="7%" align="center" rowspan="2"><?php echo strtoupper($lg_total);?><br>(<?php echo strtoupper($lg_rm);?>)</td>
	</tr>
	<tr>
		
<?php 
		$sql="select * from type where grp='feetype' order by idx";
		$res9=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row9=mysql_fetch_assoc($res9)){
				$feetype=$row9['prm'];
				$feetypeval=$row9['val'];
				$feetypeetc=$row9['etc'];
				if($feetypeetc){ //just show summarized
					continue;//echo "<td id=\"mytabletitle\" align=center width=\"3%\">$feetype</td>";
				}else{
						$sql="select * from type where grp='yuran' and val=$feetypeval";
						$res=mysql_query($sql)or die("$sql failed:".mysql_error());
						if(mysql_num_rows($res)>0){
							while($row=mysql_fetch_assoc($res)){
								$xf=$row['prm'];
								$etc=$row['etc'];
								echo "<td id=\"mytabletitle\" align=center width=\"3%\"><a href=\"#\" title=\"$xf\">$etc</a></td>";
							}
						}else{
							echo "<td id=\"mytabletitle\" align=center width=\"3%\"><a href=\"#\" title=\"none\">-</a></td>";
						}
				}
		}

		$sql="select * from type where grp='saletype' order by val";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$xf=$row['prm'];
			$xc=$row['code'];
			echo "<td id=\"mytabletitle\" align=center width=\"3%\"><a href=\"#\" title=\"$xf\">$xc</a></td>";
		}
?>

		
	</tr>
	<tr>
		<td id="myborder" bgcolor="#FAFAFA" align="center">1</td>
		<td id="myborder" bgcolor="#FAFAFA"><?php echo strtoupper($lg_cash);?></td>
<?php 
	$total=0;
	for($jj=0;$jj<$idx;$jj++){ 
		$total=$total+$sumpaytype[$jj]['TUNAI'];
?>
			<td id="myborder" bgcolor="#FAFAFA" align=right><?php if($sumpaytype[$jj]['TUNAI']==0) echo "-"; else echo $sumpaytype[$jj]['TUNAI'];?></td>
<?php } ?>
		<td id="myborder" bgcolor="#FAFAFA" align="right" style="font-weight:bold"><?php echo number_format($total,2,'.',',');?></td>
	</tr>
	<tr>
		<td id="myborder" bgcolor="#FAFAFA" align="center">2</td>
		<td id="myborder" bgcolor="#FAFAFA"><?php echo strtoupper($lg_cheque);?></td>
<?php 
	$total=0;
	for($jj=0;$jj<$idx;$jj++){ 
		$total=$total+$sumpaytype[$jj]['CEK'];
?>
			<td id="myborder" bgcolor="#FAFAFA" align=right><?php if($sumpaytype[$jj]['CEK']==0) echo "-"; else echo $sumpaytype[$jj]['CEK'];?></td>
<?php } ?>
		<td id="myborder" bgcolor="#FAFAFA" align="right" style="font-weight:bold"><?php echo number_format($total,2,'.',',');?></td>
	</tr>
	<tr>
		<td id="myborder" bgcolor="#FAFAFA" align="center">3</td>
		<td id="myborder" bgcolor="#FAFAFA"><?php echo strtoupper($lg_credit_card);?></td>
<?php 
	$total=0;
	for($jj=0;$jj<$idx;$jj++){ 
		$total=$total+$sumpaytype[$jj]['K.Kredit'];
?>
			<td id="myborder" bgcolor="#FAFAFA" align=right><?php if($sumpaytype[$jj]['K.Kredit']==0) echo "-"; else $sumpaytype[$jj]['K.Kredit'];?></td>
<?php } ?>
		<td id="myborder" bgcolor="#FAFAFA" align="right" style="font-weight:bold"><?php echo number_format($total,2,'.',',');?></td>
	</tr>
	<tr>
		<td id="myborder" bgcolor="#FAFAFA" align="center">4</td>
		<td id="myborder" bgcolor="#FAFAFA"><?php echo strtoupper($lg_online);?></td>
<?php 
	$total=0;
	for($jj=0;$jj<$idx;$jj++){ 
		$total=$total+$sumpaytype[$jj]['Online'];
?>
			<td id="myborder" bgcolor="#FAFAFA" align=right><?php if($sumpaytype[$jj]['Online']==0) echo "-"; else echo $sumpaytype[$jj]['Online'];?></td>
<?php } ?>
		<td id="myborder" bgcolor="#FAFAFA" align="right" style="font-weight:bold"><?php echo number_format($total,2,'.',',');?></td>
	</tr>
	<tr>
		<td id="myborder" bgcolor="#FAFAFA" align="center">5</td>
		<td id="myborder" bgcolor="#FAFAFA"><?php echo strtoupper($lg_other);?></td>
<?php 
	$total=0;
	for($jj=0;$jj<$idx;$jj++){ 
		$total=$total+$sumpaytype[$jj]['DLL'];
?>
			<td id="myborder" bgcolor="#FAFAFA" align=right><?php if($sumpaytype[$jj]['DLL']==0) echo "-"; else echo $sumpaytype[$jj]['DLL'];?></td>
<?php } ?>
		<td id="myborder" bgcolor="#FAFAFA" align="right" style="font-weight:bold"><?php echo number_format($total,2,'.',',');?></td>
	</tr>
	<tr>
		<td id="mytabletitle" align="center"></td>
		<td id="mytabletitle" align="center"><?php echo strtoupper($lg_total);?></td>
<?php 
	for($jj=0;$jj<$idx;$jj++){
?>
			<td id="mytabletitle" align=right><?php if($totalfeerm[$jj]==0) echo "-"; else echo $totalfeerm[$jj];?></td>
<?php } ?>
		<td id="mytabletitle" align="right" ><?php echo number_format($totalrm,2,'.',',');?></td>
	<tr>
</table>

<strong><?php echo strtoupper($lg_legend);?></strong>
<?php 
		$sql="select * from type where grp='yuran' and val=0";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$nam=$row['prm'];
			$kod=$row['etc'];
			echo "$nam($kod).&nbsp;";
	}
?>

</div></div>
</form>
</body>
</html>
