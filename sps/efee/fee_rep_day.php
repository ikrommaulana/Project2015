<?php
/** 
110112-update lain-lain enable negative value
110120-update claim
**/
//$vdate="110109";
$vdate="110109";// download excell and select by date
$vmod="v6.0.0";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEUANGAN');
$username = $_SESSION['username'];
$isexcell = $_REQUEST['isexcell'];
if($isexcell){
	header("Content-Type: application/octet-stream");
	header("Content-Disposition: attachment; filename=report.xls");
	header ("Pragma: no-cache");
	header("Expires: 0");
}
	$showheader=$_REQUEST['showheader'];
	$showdetail=$_REQUEST['showdetail'];
	
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];

	$sdate=$_REQUEST['sdate'];
	if($sdate=="")
		$sdate=date('Y-m-d');

	$sqldate=" and feetrans.paydate='$sdate'";
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
<input type="hidden" name="p" value="../efee/fee_rep_day">

<div id="content">
<?php if(!$isexcell){?>
<div id="mypanel">
<div id="mymenu" align="center" >
     <a href="../efee/fee_rep_day.php?<?php echo "sid=$sid&sdate=$sdate&isexcell=1";?>" id="mymenuitem"><img src="../img/excel.png"><br>Excel</a>
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

                <input name="sdate" type="text" id="sdate" value="<?php echo "$sdate";?>" size="20" readonly onClick="displayDatePicker('sdate');" onChange="document.myform.submit();">
              <input type="submit" name="Submit" value="<?php echo $lg_view; ?>"  >
			  <br>
		
		<input type="checkbox" name="showdetail" value="1"  onClick="showhide('showhide1');" <?php if($showdetail) echo "checked";?> > Show Detail 
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>	
</div>
<?php } ?>
<div id="story">
<div id="mytitlebg">
<?php echo strtoupper($lg_daily_collection_report);?> <?php echo "$sdate";?> : <?php echo strtoupper($sname);?>
</div>

<table width="100%" cellspacing="0">
	<tr>
		<td id="mytabletitle" width="1%" align="center" rowspan="2"><?php echo strtoupper($lg_no);?></td>
		<td id="mytabletitle" width="7%" align="center" rowspan="2"><?php echo strtoupper($lg_date);?></td>
		<td id="mytabletitle" width="2%" align="center" rowspan="2"><?php if($FEE_REPORT_USE_ACC)echo strtoupper($lg_account); else echo strtoupper($lg_matric);?></td>
		<td id="mytabletitle" width="10%"  rowspan="2"><?php echo strtoupper($lg_name);?></td>
		<td id="mytabletitle" width="2%" align="center" rowspan="2"><?php echo strtoupper("Resi");?></td>
		<td id="mytabletitle" width="2%" align="center" rowspan="2"><?php echo strtoupper($lg_total);?><br>Rp</td>
		<td id="mytabletitle" width="30%" align="center" colspan="5"><?php echo strtoupper($lg_payment);?></td>
		<td id="mytabletitle" width="7%" align="center" rowspan="2"><?php echo strtoupper($lg_reference);?></td>
<?php
		$sql="select * from type where grp='feetype' order by idx";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$xf=strtoupper($row['prm']);
			//$xf=$row['code'];
			echo "<td id=\"mytabletitle\" align=center width=\"3%\" rowspan=2>$xf</td>";
		}
		$sql="select * from type where grp='saletype' order by val";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$xf=strtoupper($row['prm']);
			//$xf=$row['code'];
			echo "<td id=\"mytabletitle\" align=center width=\"3%\" rowspan=2>$xf</td>";
		}
?>
		<td id="mytabletitle" align=center width="3%" rowspan=2>ADVANCE</td>
		<td id="mytabletitle" align=center width="3%" rowspan=2>RECLAIM</td>
		<td id="mytabletitle" align=center width="10%" colspan=2><?php echo strtoupper($lg_other);?></td>
	</tr>
	<tr>
		<td id="mytabletitle" align="center" width="3%"><?php echo $lg_cash;?></td>
		<td id="mytabletitle" align="center" width="3%"><?php echo $lg_cheque;?></td>
		<td id="mytabletitle" align="center" width="3%"><?php echo $lg_credit_card;?></td>
		<td id="mytabletitle" align="center" width="3%"><?php echo $lg_transfer;?></td>
		<td id="mytabletitle" align="center" width="3%"><?php echo $lg_other;?></td>
		<td id="mytabletitle" align="center" width="3%"><?php echo $lg_etc;?></td>
		<td id="mytabletitle" align="center" width="8%"><?php echo $lg_description;?></td>
	</tr>
<?php
		$idx=0;$totalrm=0;$q=0;
		
		
		//if($ISPARENT_ACC)
			//$sql="select * from feetrans where id>0 $sqldate";
		//else
			//$sql="select feetrans.*,stu.name from feetrans INNER JOIN stu ON feetrans.stu_uid=stu.uid and feetrans.sch_id='$sid' $sqldate";
		$sql="select * from feetrans where id>0 and isdelete=0 $sqlfeetranssid $sqldate";
    	$res=mysql_query($sql)or die("query failed:".mysql_error());
        while($row=mysql_fetch_assoc($res)){
			$fid=$row['id'];
			$xuid=$row['stu_uid'];
			$acc=$row['acc'];
			$resitno=$row['resitno'];
			$xdt=$row['cdate'];
			$xrm=$row['rm'];
			$totalrm=$totalrm+$xrm;
			$xtyp=$row['paytype'];
			$ref=$row['nocek'];
			$name=stripslashes(strtoupper($row['name']));
			if(strlen($name)>20)
				$name=substr($name,0,20)."..";
			if(($q++%2)==0)
				$bg="";
			else
				$bg="";
?>
	<tr bgcolor="<?php echo $bg;?>" style="cursor:pointer" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';" onClick="window.open('../efee/<?php echo $FN_FEERESIT;?>.php?id=<?php echo $fid;?>')">
		<td id="myborder" align="center"><?php echo $q;?></td>
		<td id="myborder" align="center"><?php echo strtok($xdt," ");?></td>
		<td id="myborder" align="center"><?php if($FEE_REPORT_USE_ACC) echo $acc; else echo $xuid?></td>
		<td id="myborder" style="font-size:80%"><?php echo $name;?></td>
		<td id="myborder" align="center"><?php echo $resitno;?></td>
		<td id="myborder" align="right"><?php echo number_format($xrm, 2, '.', ',');?></td>
		<td id="myborder" align="right"><?php if(strcasecmp($xtyp,"Tunai")==0){ echo number_format($xrm, 2, '.', ','); $totaltunai=$totaltunai+$xrm;}?></td>
		<td id="myborder" align="right"><?php if(strcasecmp($xtyp,"Cek")==0){ echo number_format($xrm, 2, '.', ','); $totalcek=$totalcek+$xrm;}?></td>
		<td id="myborder" align="right"><?php if(strcasecmp($xtyp,"K.Kredit")==0){ echo number_format($xrm, 2, '.', ','); $totalkk=$totalkk+$xrm;}?></td>
		<td id="myborder" align="right"><?php if(strcasecmp($xtyp,"Transfer")==0){ echo number_format($xrm, 2, '.', ','); $totalonline=$totalonline+$xrm;}?></td>
		<td id="myborder" align="right"><?php if(strcasecmp($xtyp,"DLL")==0){ echo number_format($xrm, 2, '.', ','); $totaldll=$totaldll+$xrm;}?></td>
		<td id="myborder" style="font-size:80%"><?php echo $ref;?></td>

<?php
		//CHECK FEE
		$idx=0;
		$sql="select * from type where grp='feetype' order by idx";
		$resx=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($rowx=mysql_fetch_assoc($resx)){
			$feecode=$rowx['val'];
			$feerm=0;
			//$sql="select feepay.rm from feepay,type where feepay.fee=type.prm and feepay.tid=$fid and feepay.type='fee' and type.val=$feecode and type.grp='yuran'";
			$sql="select feepay.rm from feepay where feepay.item_type=$feecode and feepay.tid=$fid and feepay.type='fee'";
			$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
			while($rowxx=mysql_fetch_assoc($resxx))
				$feerm=$feerm+$rowxx['rm'];
			$itemsum[$idx]=$itemsum[$idx]+$feerm;$idx++;
			$xval=number_format($feerm, 2, '.', ',');
			//if($xval==0.00) $xval="-";
			echo "<td id=\"myborder\" align=right>$xval</td>";
		}
		//CHECK SALE
		$sql="select * from type where grp='saletype' order by val";
		$resx=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($rowx=mysql_fetch_assoc($resx)){
			$itemtype=$rowx['prm'];
			$itemcode=$rowx['code'];
			$feerm=0;
			//$sql="select feepay.rm from feepay,type where feepay.fee=type.prm and feepay.tid=$fid and item_type='$itemtype' and type='sale'";
			$sql="select feepay.rm from feepay,type where feepay.item_code=type.code and feepay.tid=$fid and item_type='$itemtype' and type='sale'";
			$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
			while($rowxx=mysql_fetch_assoc($resxx))
				$feerm=$feerm+$rowxx['rm'];
			$itemsum[$idx]=$itemsum[$idx]+$feerm;$idx++;
			$xval=number_format($feerm, 2, '.', ',');
			//if($xval==0.00) $xval="-";
			echo "<td align=right id=\"myborder\" align=right>$xval</td>";
		}
		//check lain-lain
		$feelain=0;
		$feelaindes="";
		$sql="select * from feepay where tid=$fid and (fee='$lg_other' or fee='Lain-Lain') ";
		$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
		if($rowxx=mysql_fetch_assoc($resxx)){
				$feelain=$rowxx['rm'];
				$feelaindes=$rowxx['etc'];
		}
		$totalfeelain=$totalfeelain+$feelain;
		
		$feeadvance=0;
		$sql="select * from feepay where tid=$fid and fee='ADVANCE'";
		$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
		if($rowxx=mysql_fetch_assoc($resxx)){
				$feeadvance=$rowxx['rm'];
				$feelaindes=$rowxx['etc'];
		}
		$totalfeeadvance=$totalfeeadvance+$feeadvance;
		
		$feetebus=0;
		$sql="select * from feepay where tid=$fid and fee='RECLAIM'";
		$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
		if($rowxx=mysql_fetch_assoc($resxx)){
				$feetebus=$rowxx['rm'];
		}
		$totalfeetebus=$totalfeetebus+$feetebus;
		//if($feetebus!=0){
		//	$feelaindes=$feelaindes." (reclaim:" .$feetebus.")";
		//}
?>
		<td id="myborder" align="right" ><?php echo number_format($feeadvance, 2, '.', ',');?></td>
		<td id="myborder" align="right" ><?php echo number_format($feetebus, 2, '.', ',');?></td>
		<td id="myborder" align="right" ><?php echo number_format($feelain, 2, '.', ',');?></td>
		<td id="myborder" style="font-size:80% "><?php echo $feelaindes;?></td>
	</tr>
<?php } ?>
	<tr>
		<td id="mytabletitle" width="1%" align="left" colspan="5"><?php echo strtoupper($lg_total);?></td>
		<td id="mytabletitle" width="1%" align="right" ><?php echo number_format($totalrm, 2, '.', ',');?></td>
		<td id="mytabletitle" width="1%" align="right" ><?php echo number_format($totaltunai, 2, '.', ',');?></td>
		<td id="mytabletitle" width="1%" align="right" ><?php echo number_format($totalcek, 2, '.', ',');?></td>
		<td id="mytabletitle" width="1%" align="right" ><?php echo number_format($totalkk, 2, '.', ',');?></td>
		<td id="mytabletitle" width="1%" align="right" ><?php echo number_format($totalonline, 2, '.', ',');?></td>
		<td id="mytabletitle" width="1%" align="right" ><?php echo number_format($totaldll, 2, '.', ',');?></td>
		<td id="mytabletitle" ></td>
<?php
		$idx=0;
		$sql="select * from type where grp='feetype'";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$xval=number_format($itemsum[$idx++], 2, '.', ',');
			echo "<td align=right id=\"mytabletitle\" align=right>$xval</td>";
		}
		$sql="select * from type where grp='saletype'";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$xval=number_format($itemsum[$idx++], 2, '.', ',');;
			echo "<td id=\"mytabletitle\" align=right>$xval</td>";
		}

?>
	<td id="mytabletitle" width="1%" align="right"><?php echo number_format($totalfeeadvance, 2, '.', ',');?></td>
	<td id="mytabletitle" width="1%" align="right"><?php echo number_format($totalfeetebus, 2, '.', ',');?></td>
	<td id="mytabletitle" width="1%" align="right"><?php echo number_format($totalfeelain, 2, '.', ',');?></td>
	<td id="mytabletitle" width="1%" align="right">&nbsp;</td>
	<tr>
</table> 



<div id="showhide1" <?php if(!$showdetail) echo "style=\"display:none\"";?>>
<div id="mytitlebg"><?php echo strtoupper($lg_payment_detail);?></div>
<table width="100%" class="print_font" cellspacing="0">
	<tr >
		<td id="mytabletitle" width="1%" align="center" rowspan="2"><?php echo strtoupper($lg_no);?></td>
		<td id="mytabletitle" width="7%" align="center" rowspan="2"><?php echo strtoupper($lg_date);?></td>
		<td id="mytabletitle" width="5%" align="center" rowspan="2"><?php if($FEE_REPORT_USE_ACC)echo strtoupper($lg_account); else echo strtoupper($lg_matric);?></td>
		<td id="mytabletitle" width="5%" align="center" rowspan="2"><?php echo strtoupper("RESI");?></td>
		<td id="mytabletitle" width="5%" align="center" rowspan="2"><?php echo strtoupper($lg_total);?><br>Rp</td>
		
<?php 
$sql="select * from type where grp='feetype' order by val,idx";
$res=mysql_query($sql)or die("$sql failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){
			$prm=$row['prm'];
			$xval=$row['val'];
			$sql="select * from type where grp='yuran' and val=$xval";
			$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
			$bil_fee_year=mysql_num_rows($res2);
?>
		<td id="mytabletitle" width="20%" align="center" colspan="<?php echo $bil_fee_year;?>"><?php echo $prm;?></td>

<?php 
}
		$sql="select * from type where grp='saletype'";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$bil_sale=mysql_num_rows($res);
?>
		<td id="mytabletitle" width="20%" align="center" colspan="<?php echo $bil_sale;?>"><?php echo strtoupper($lg_sales);?></td>
		<td id="mytabletitle" width="20%" align="center" colspan="3"><?php echo strtoupper($lg_other);?></td>
	</tr>
	<tr>
		
<?php 
$sql="select * from type where grp='feetype' order by val,idx";
$res=mysql_query($sql)or die("$sql failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){
			$prm=$row['prm'];
			$xval=$row['val'];
			$sql="select * from type where grp='yuran' and val=$xval";
			$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
			while($row2=mysql_fetch_assoc($res2)){
				$xf=$row2['prm'];
				$xx=$row2['etc'];
				echo "<td id=\"mytabletitle\" align=center width=\"3%\"><a href=\"#\" title=\"$xf\">$xx</a></td>";
			}
}

		$sql="select * from type where grp='saletype' order by val";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$xf=strtoupper($row['prm']);
			$xc=$row['code'];
			echo "<td id=\"mytabletitle\" align=center width=\"3%\"><a href=\"#\" title=\"$xf\">$xc</a></td>";
		}
		
?>
		<td id="mytabletitle" align=center width="3%"><a href="#" title="Others Fee"><?php echo strtoupper($lg_etc);?></a></td>
		<td id="mytabletitle" align=center width="3%"><a href="#" title="Advance Fee">ADV</a></td>
		<td id="mytabletitle" align=center width="3%"><a href="#" title="Reclaim Fee Payment">CLM</a></td>
	</tr>
<?php
		$idx=0;$q=0;$totalrm=0;
		
		
		//if($ISPARENT_ACC)
			//$sql="select * from feetrans where id>0 $sqldate";
		//else
			//$sql="select feetrans.*,stu.name from feetrans INNER JOIN stu ON feetrans.stu_uid=stu.uid and feetrans.sch_id='$sid' $sqldate";
		$sql="select * from feetrans where  id>0 and isdelete=0 $sqlfeetranssid $sqldate";
    	$res=mysql_query($sql)or die("query failed:".mysql_error());
        while($row=mysql_fetch_assoc($res)){
			$fid=$row['id'];
			$xuid=$row['stu_uid'];
			$acc=$row['acc'];
			$xrm=$row['rm'];
			$xdt=$row['cdate'];
			$resitno=$row['resitno'];
			$totalrm=$totalrm+$xrm;
			$xtyp=$row['paytype'];
			$name=stripslashes(strtoupper($row['name']));
			if(($q++%2)==0)
				$bg="#FAFAFA";
			else
				$bg="";
?>
	<tr bgcolor="<?php echo $bg;?>" style="cursor:pointer" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';" onClick="window.open('../efee/<?php echo $FN_FEERESIT;?>.php?id=<?php echo $fid;?>')">
		<td id="myborder" width="1%" align="center"><?php echo $q;?></td>
		<td id="myborder" width="5%" align="center"><?php echo strtok($xdt," ");?></td>
		<td id="myborder" width="5%" align="center"><?php if($FEE_REPORT_USE_ACC) echo $acc; else echo $xuid?></td>
		<td id="myborder" width="5%" align="center"><?php echo $resitno;?></td>
		<td id="myborder" width="5%" align="right"><?php echo number_format($xrm,2,'.',',');?></td>
		
<?php 
$idx=0;
$sql="select * from type where grp='feetype' order by val,idx";
$resq=mysql_query($sql)or die("$sql failed:".mysql_error());
while($rowq=mysql_fetch_assoc($resq)){
		$prm=$rowq['prm'];
		$xv=$rowq['val'];

		$sql="select * from type where grp='yuran' and val=$xv";
		$resx=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($rowx=mysql_fetch_assoc($resx)){
			$fee=$rowx['prm'];
			$feerm="";
			$sql="select feepay.rm,feetrans.paytype from feepay INNER JOIN feetrans ON feepay.tid=feetrans.id where tid=$fid and fee='$fee' and feepay.type='fee' and feepay.isdelete=0";
			$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
			if($rowxx=mysql_fetch_assoc($resxx)){
				$feerm=$rowxx['rm'];
				$ptype=$rowxx['paytype'];
				$sumpaytype[$fee][$ptype]=$sumpaytype[$fee][$ptype]+$feerm;
			}
			$itemsum2[$idx]=$itemsum2[$idx]+$feerm;
			$idx++;
?>
			<td id="myborder" align=right><?php if($feerm=="") echo "-"; else echo $feerm;?></td>
<?php
		}}
		
		//CHECK SALE
		$sql="select * from type where grp='saletype' order by val";
		$resx=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($rowx=mysql_fetch_assoc($resx)){
			$itemtype=$rowx['prm'];
			$feerm=0;
			//$sql="select feepay.rm from feepay,type where feepay.fee=type.prm and feepay.tid=$fid and item_type='$itemtype' and type='sale'";
			$sql="select feepay.rm from feepay,type where feepay.item_code=type.code and feepay.tid=$fid and item_type='$itemtype' and type='sale'";
			$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
			//echo "<br>$sql";
			while($rowxx=mysql_fetch_assoc($resxx)){
				$feerm=$feerm+$rowxx['rm'];
				//echo "$feerm:";
			}
			$itemsum2[$idx]=$itemsum2[$idx]+$feerm;$idx++;
			$xval=$feerm;
			if($xval==0) $xval="-";
			echo "<td id=\"myborder\" align=right>$xval</td>";
			
			$sql="select paytype from feetrans where id=$fid";
			$resxxx=mysql_query($sql)or die("$sql failed:".mysql_error());
			$rowxxx=mysql_fetch_assoc($resxxx);
			$ptype=$rowxxx['paytype'];
			$sumpaytype[$feecode][$ptype]=$sumpaytype[$feecode][$ptype]+$feerm2;//got problem here
		}
		
		$feelain="";
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
				$sumpaytype['$lg_other'][$ptype]=$sumpaytype['$lg_other'][$ptype]+$feelain;
		}
		$feeadvance="";
		$sql="select * from feepay where tid=$fid and fee='ADVANCE'";
		$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
		if($rowxx=mysql_fetch_assoc($resxx)){
				$feeadvance=$rowxx['rm'];
				$sql="select paytype from feetrans where id=$fid";
				$resxxx=mysql_query($sql)or die("$sql failed:".mysql_error());
				$rowxxx=mysql_fetch_assoc($resxxx);
				$ptype=$rowxxx['paytype'];
				$sumpaytype['ADVANCE'][$ptype]=$sumpaytype['ADVANCE'][$ptype]+$feeadvance;
		}
		$feeclaim="";
		$sql="select * from feepay where tid=$fid and fee='RECLAIM'";
		$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
		if($rowxx=mysql_fetch_assoc($resxx)){
				$feeclaim=$rowxx['rm'];
				$sql="select paytype from feetrans where id=$fid";
				$resxxx=mysql_query($sql)or die("$sql failed:".mysql_error());
				$rowxxx=mysql_fetch_assoc($resxxx);
				$ptype=$rowxxx['paytype'];
				$sumpaytype['RECLAIM'][$ptype]=$sumpaytype['RECLAIM'][$ptype]+$feeclaim;
		}
?>
			<td id="myborder" align=right><?php if($feelain=="") echo "-"; else echo $feelain;?></td>
			<td id="myborder" align=right><?php if($feeadvance=="") echo "-"; else echo $feeadvance;?></td>
			<td id="myborder" align=right><?php if($feeclaim=="") echo "-"; else echo $feeclaim;?></td>
	</tr>
<?php } ?>
	<tr>
		<td id="mytabletitle" width="1%" align="left" colspan="4"><?php echo strtoupper($lg_total);?></td>
		<td id="mytabletitle" width="1%" align="right" ><?php echo number_format($totalrm,2,'.',',');?></td>
<?php 
		$idx=0;
$sql="select * from type where grp='feetype' order by val,idx";
$res=mysql_query($sql)or die("$sql failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){
		$prm=$row['prm'];
		$xval=$row['val'];

		$sql="select * from type where grp='yuran' and val=$xval";
		$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row2=mysql_fetch_assoc($res2)){
			$fee=$row2['prm'];
			$sum=$itemsum2[$idx++];
?>
			<td id="mytabletitle" align=right><?php if($sum=="") echo "-"; else echo $sum;?></td>
<?php
		}}
		$sql="select * from type where grp='saletype'";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$xval=$itemsum2[$idx++];
			if($xval==0.00) $xval="-";
			echo "<td id=\"mytabletitle\" align=right>$xval</td>";
		}
?>
			<td id="mytabletitle" align=right><?php echo $totalfeelain;?></td>
			<td id="mytabletitle" align=right><?php echo $totalfeeadvance;?></td>
			<td id="mytabletitle" align=right><?php echo $totalfeetebus;?></td>
	<tr>
</table>   
</div><!-- end showhide -->



<div id="mytitlebg"><?php echo strtoupper($lg_payment_summary);?></div>
<table width="100%" cellspacing="0"  >
	<tr>
		<td id="mytabletitle" width=10% rowspan="2"><?php echo strtoupper($lg_payment);?></td>
		<td id="mytabletitle" width=10% rowspan="2" align="center"><?php echo strtoupper($lg_total);?> Rp</td>
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
		<td id="mytabletitle" width="5%" align="center" colspan="3"><?php echo strtoupper($lg_other);?></td>
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
					$sql="select * from type where grp='yuran' and val=$feetypeval order by idx";
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
		<td id="mytabletitle" align=center width="3%"><a href="#" title="Others Fee"><?php echo strtoupper($lg_etc);?></a></td>
		<td id="mytabletitle" align=center width="3%"><a href="#" title="Advance Fee">ADV</a></td>
		<td id="mytabletitle" align=center width="3%"><a href="#" title="Reclaim Fee Payment">CLM</a></td>
	</tr>
<?php 
		$PAYMENTTYPE[0]='TUNAI';
		$PAYMENTTYPE[1]='CEK';
		$PAYMENTTYPE[2]='K.KREDIT';
		$PAYMENTTYPE[3]='TRANSFER';
		$PAYMENTTYPE[4]='DLL';
		
		$PAYMENTNAME[0]=$lg_cash;
		$PAYMENTNAME[1]=$lg_cheque;
		$PAYMENTNAME[2]=$lg_credit_card;
		$PAYMENTNAME[3]=$lg_transfer;
		$PAYMENTNAME[4]=$lg_other;
		
		$PAYMENTTOTAL[0]=$totaltunai;
		$PAYMENTTOTAL[1]=$totalcek;
		$PAYMENTTOTAL[2]=$totalkk;
		$PAYMENTTOTAL[3]=$totalonline;
		$PAYMENTTOTAL[4]=$totaldll;
		
for($jjj=0;$jjj<5;$jjj++){
	$bg="";
?>

	<tr bgcolor="<?php echo $bg;?>" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';" >
		<td id="myborder" style="font-weight:bold"><?php echo strtoupper($PAYMENTNAME[$jjj]);?></td>
		<td id="myborder" align="right" style="font-weight:bold"><?php echo number_format($PAYMENTTOTAL[$jjj],2,'.',',');?></td>
<?php 
	$sql="select * from type where grp='feetype' order by idx";
	$res9=mysql_query($sql)or die("$sql failed:".mysql_error());
	while($row9=mysql_fetch_assoc($res9)){
		$feetype=$row9['prm'];
		$feetypeval=$row9['val'];
		$feetypeetc=$row9['etc'];
		$feerm=0;
		if($feetypeetc){ //just show summarized
						$feerm="";
						$sql="select feepay.rm from feepay,feetrans where feepay.item_type=$feetypeval and feepay.tid=feetrans.id and type='fee' and feepay.isdelete=0 $sqlfeetranssid $sqldate and paytype='$PAYMENTTYPE[$jjj]'";
						$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
						while($rowxx=mysql_fetch_assoc($resxx)){
								$feerm=$feerm+$rowxx['rm'];
						}
		?>				
						<td id="myborder" align=right><?php if($feerm=="") echo "-"; else echo $feerm; ?></td>
		<?php

		}else{
				$sql="select * from type where grp='yuran' and val=$feetypeval";
				$res=mysql_query($sql)or die("$sql failed:".mysql_error());
				if(mysql_num_rows($res)>0){
						while($row=mysql_fetch_assoc($res)){
							$feename=$row['prm'];
							$feerm="";
							$sql="select feepay.rm from feepay INNER JOIN feetrans ON feepay.tid=feetrans.id where fee='$feename' and feepay.type='fee' and feepay.isdelete=0 $sqlfeetranssid $sqldate and paytype='$PAYMENTTYPE[$jjj]'";
							$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
							while($rowxx=mysql_fetch_assoc($resxx)){
								$feerm=$feerm+$rowxx['rm'];
							}
?>
							<td id="myborder" align=right><?php if($feerm=="") echo "-"; else echo $feerm;?></td>
<?php					} //while
				}else{
						echo "<td id=myborder align=right>-</td>";
				}
		}
}

		//SALES
		$sql="select * from type where grp='saletype'";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$saletype=$row['prm'];
			$feerm="";
			$sql="select feepay.rm from feepay INNER JOIN feetrans ON feepay.tid=feetrans.id where item_type='$saletype' and feepay.type='sale' and feepay.isdelete=0 $sqlfeetranssid $sqldate and paytype='$PAYMENTTYPE[$jjj]'";
			$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
			while($rowxx=mysql_fetch_assoc($resxx)){
				$feerm=$feerm+$rowxx['rm'];
			}
?>
			<td id="myborder" align=right><?php if($feerm=="") echo "-"; else echo $feerm;?></td>
<?php	} ?>
<?php 
			$dllrm="";
			$sql="select feepay.rm from feepay INNER JOIN feetrans ON feepay.tid=feetrans.id where (fee='$lg_other' or fee='Lain-Lain') and feepay.type='fee'  and feepay.isdelete=0 $sqlfeetranssid $sqldate and paytype='$PAYMENTTYPE[$jjj]'";
			$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
			while($rowxx=mysql_fetch_assoc($resxx)){
				$dllrm=$dllrm+$rowxx['rm'];
			}
			$advrm="";
			$sql="select feepay.rm from feepay INNER JOIN feetrans ON feepay.tid=feetrans.id where fee='ADVANCE' and feepay.type='fee' and feepay.isdelete=0 $sqlfeetranssid $sqldate and paytype='$PAYMENTTYPE[$jjj]'";
			$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
			while($rowxx=mysql_fetch_assoc($resxx)){
				$advrm=$advrm+$rowxx['rm'];
			}
			$clmrm="";
			$sql="select feepay.rm from feepay INNER JOIN feetrans ON feepay.tid=feetrans.id where fee='RECLAIM' and feepay.type='fee' and feepay.isdelete=0 $sqlfeetranssid $sqldate and paytype='$PAYMENTTYPE[$jjj]'";
			$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
			while($rowxx=mysql_fetch_assoc($resxx)){
				$clmrm=$clmrm+$rowxx['rm'];
			}
?>
			<td id="myborder" align=right><?php if($dllrm=="") echo "-"; else echo $dllrm;?></td>
			<td id="myborder" align=right><?php if($advrm=="") echo "-"; else echo $advrm;?></td>
			<td id="myborder" align=right><?php if($clmrm=="") echo "-"; else echo $clmrm;?></td>
	</tr>
<?php } //loop paytype?>
	<tr>
		<td id="mytabletitle" ><?php echo strtoupper($lg_total);?></td>
		<td align="right" id="mytabletitle"><?php echo number_format($totalrm,2,'.',',');?></td>
<?php 
	$idx=0;
	$sql="select * from type where grp='feetype' order by idx";
	$res9=mysql_query($sql)or die("$sql failed:".mysql_error());
	while($row9=mysql_fetch_assoc($res9)){
		$feetype=$row9['prm'];
		$feetypeval=$row9['val'];
		$feetypeetc=$row9['etc'];
		if($feetypeetc){ //just show summarized
						$feerm="";
						$sql="select feepay.rm from feepay,feetrans where feepay.item_type=$feetypeval and feepay.tid=feetrans.id and type='fee' and feepay.isdelete=0 $sqlfeetranssid $sqldate ";
						$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
						while($rowxx=mysql_fetch_assoc($resxx)){
							$feerm=$feerm+$rowxx['rm'];
						}
		?>				
						<td id="mytabletitle" align=right><?php if($feerm=="") echo "-"; else $feerm; ?></td>
		<?php
		}else{
				$sql="select * from type where grp='yuran' and val=$feetypeval";
				$res=mysql_query($sql)or die("$sql failed:".mysql_error());
				if(mysql_num_rows($res)>0){
				while($row=mysql_fetch_assoc($res)){
					$fee=$row['prm'];
					$feerm="";
					$sql="select feepay.rm from feepay INNER JOIN feetrans ON feepay.tid=feetrans.id where fee='$fee' and feepay.type='fee' and feepay.isdelete=0 $sqlfeetranssid $sqldate";
					$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
					while($rowxx=mysql_fetch_assoc($resxx))
						$feerm=$feerm+$rowxx['rm'];
?>
			<td id="mytabletitle" align=right><?php if($feerm=="") echo "-"; else echo $feerm;?></td>
<?php
				}
		
			}else{
					echo "<td id=mytabletitle align=center>-</td>";
			}
		}
		
	}

?>
			
<?php
		
				$sql="select * from type where grp='saletype'";
				$res=mysql_query($sql)or die("$sql failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
					$saletype=$row['prm'];
					$feerm="";
					$sql="select feepay.rm from feepay INNER JOIN feetrans ON feepay.tid=feetrans.id where item_type='$saletype' and feepay.type='sale' and feepay.isdelete=0 $sqlfeetranssid $sqldate";
					$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
					while($rowxx=mysql_fetch_assoc($resxx))
						$feerm=$feerm+$rowxx['rm'];
					
		?>
			<td id="mytabletitle" align=right><?php if($feerm=="") echo "-"; else echo $feerm;?></td>
<?php
		}
		
			$dllrm="";
			$sql="select feepay.rm from feepay INNER JOIN feetrans ON feepay.tid=feetrans.id where (fee='$lg_other' or fee='Lain-Lain') and feepay.type='fee' and feepay.isdelete=0 $sqlfeetranssid $sqldate";
			$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
			while($rowxx=mysql_fetch_assoc($resxx)){
				$dllrm=$dllrm+$rowxx['rm'];
			}
			$advrm="";
			$sql="select feepay.rm from feepay INNER JOIN feetrans ON feepay.tid=feetrans.id where fee='ADVANCE' and feepay.type='fee' and feepay.isdelete=0 $sqlfeetranssid $sqldate";
			$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
			while($rowxx=mysql_fetch_assoc($resxx)){
				$advrm=$advrm+$rowxx['rm'];
			}
			$clmrm="";
			$sql="select feepay.rm from feepay INNER JOIN feetrans ON feepay.tid=feetrans.id where fee='RECLAIM' and feepay.type='fee' and feepay.isdelete=0 $sqlfeetranssid $sqldate";
			$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
			while($rowxx=mysql_fetch_assoc($resxx)){
				$clmrm=$clmrm+$rowxx['rm'];
			}
?>
			<td id="mytabletitle" align=right><?php if($dllrm=="") echo "-"; else echo number_format($dllrm,2,'.',',');?></td>
			<td id="mytabletitle" align=right><?php if($advrm=="") echo "-"; else echo number_format($advrm,2,'.',',');?></td>
			<td id="mytabletitle" align=right><?php if($clmrm=="") echo "-"; else echo number_format($clmrm,2,'.',',');?></td>
	</tr>
</table>

<br>
<br>
<br>
<?php if($LG=="BM"){?>
<div id="mytitle">SERAHAN KUTIPAN HARIAN <?php $dt=explode("-",$sdate); echo $dt[2]."/".$dt[1]."/".$dt[0]?> DARI BAHAGIAN YURAN KEPADA BAHAGIAN AKAUN</div>
<?php }else{?>
<div id="mytitle">DATA PER HARI PADA TANGGAL <?php $dt=explode("-",$sdate); echo $dt[2]."/".$dt[1]."/".$dt[0]?> DARI KASIR UNTUK BAGIAN KEUANGAN</div>
<?php } ?>
<br>
<br>
<?php if($LG=="BM"){?>
<strong>Serahan tunai/cek/lain-lain pada:.................,hari:.................., jam........................</strong>
<?php }else{?>
<strong>Tanggal:................., Hari:.................., Jam:........................</strong>
<?php } ?>
<br>
<br>


<table width="500px" style="font-size:120% ">
	<tr >
		<td id="mytabletitle" width=50%><?php echo strtoupper($lg_item);?></td>
		<td id="mytabletitle" align="right"><?php echo strtoupper($lg_total);?> Rp</td>
	</tr>
	<tr >
		<td bgcolor="#FAFAFA"><?php echo strtoupper($lg_cash);?></td>
		<td bgcolor="#FAFAFA" align="right"><?php echo number_format($totaltunai,2,'.',',');?></td>
	</tr>
	<tr>
		<td bgcolor="#FAFAFA"><?php echo strtoupper($lg_cheque);?></td>
		<td bgcolor="#FAFAFA" align="right"><?php echo number_format($totalcek,2,'.',',');?></td>
	</tr>
	<tr>
		<td bgcolor="#FAFAFA"><?php echo strtoupper($lg_credit_card);?></td>
		<td bgcolor="#FAFAFA" align="right"><?php echo number_format($totalkk,2,'.',',');?></td>
	</tr>
	<tr>
		<td bgcolor="#FAFAFA"><?php echo strtoupper($lg_transfer);?></td>
		<td bgcolor="#FAFAFA" align="right"><?php echo number_format($totalonline,2,'.',',');?></td>
	</tr>
	<tr>
		<td  bgcolor="#FAFAFA"><?php echo strtoupper($lg_other);?></td>
		<td bgcolor="#FAFAFA" align="right"><?php echo number_format($totaldll,2,'.',',');?></td>
	</tr>
	<tr>
		<td bgcolor="#F1F1F1"><?php echo strtoupper($lg_total_amount);?></td>
		<td bgcolor="#F1F1F1" align="right" id="mytitle"><?php echo number_format($totalrm,2,'.',',');?></td>
	</tr>
	<tr style="font-size:80% ">
		<td bgcolor="#FAFAFA">
			<u><?php echo strtoupper($lg_verification_deliver);?></u><br><br>
			<?php echo $lg_name;?>:<br><br><br>
			<?php echo $lg_signatory;?>:<br><br>
		</td>
		<td bgcolor="#FAFAFA">
			<u><?php echo strtoupper($lg_verification_receive);?></u><br><br>
			<?php echo $lg_name;?>:<br><br><br>
			<?php echo $lg_signatory;?>:<br><br>
		</td>
	</tr>
</table>


	<strong><?php echo $lg_legend;?></strong>
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
