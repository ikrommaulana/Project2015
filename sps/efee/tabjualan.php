<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
<SCRIPT LANGUAGE="JavaScript">
function check_quantiti(id,val,totalitem){
	var sum=0;
	var lain=0;
	var totalall=0;
	var totaldis=0;
	var finaleprice=0;
	var jualan=0;

	saleprice = document.getElementById('saleprice'+id);
	saletotal = document.getElementById('saletotal'+id);
	saleunit = document.getElementById('saleunit'+id);
	qtt = parseInt(saleunit.value);
	
	dis = parseFloat(document.getElementById('saledis'+id).value);
	
	if((val==-1)&&(qtt==0))
		return;

	saleunit.value=qtt+val;
		
	sum=saleunit.value*saleprice.value;
	totaldis=sum*dis/100;
	finalprice=sum-totaldis;
	saletotal.value=finalprice.toFixed(2);
	for(i=1;i<=totalitem;i++){
		
		totalall=totalall+parseFloat(document.getElementById('saletotal'+i).value);
		
	}
	document.form1.jualan.value=totalall.toFixed(2);
}
function process_diskaun(idx)
{
	var dis = parseFloat(document.getElementById("saledis"+idx).value);
	var jum = parseFloat(document.getElementById("saleprice"+idx).value);
	var qtt = parseFloat(document.getElementById("saleunit"+idx).value);
	if(jum>0){
		jumdis=jum*dis/100;
		lastprice=(jum-jumdis)*qtt;
		document.getElementById("saledisrm"+idx).value=jumdis.toFixed(2);
		document.getElementById("saletotal"+idx).value=lastprice.toFixed(2);
	}else{
		document.getElementById("saledisrm"+idx).value=0;
		document.getElementById("saletotal"+idx).value=jum;
	}
}
function process_potongan(idx)
{
	var jumdis = parseFloat(document.getElementById("saledisrm"+idx).value);
	var jum = parseFloat(document.getElementById("saleprice"+idx).value);
	var qtt = parseFloat(document.getElementById("saleunit"+idx).value);
	if(jum>0){
		dis=jumdis/jum*100;
		lastprice=(jum-jumdis)*qtt;
		document.getElementById("saledis"+idx).value=dis.toFixed(2);
		document.getElementById("saletotal"+idx).value=lastprice.toFixed(2);
	}else{
		document.getElementById("saledis"+idx).value=0;
		document.getElementById("saletotal"+idx).value=jum;
	}
}
</script>
</head>

<body>

<?php
	$sql="select * from type where grp='saleitem' order by idx";
	$ressale=mysql_query($sql)or die("query failed:".mysql_error());
	$totalitem=mysql_num_rows($ressale);
	$xxx=0;
	$sql="select * from type where grp='saletype' order by idx";
	$resfeetype=mysql_query($sql)or die("query failed:".mysql_error());
	$numrow=mysql_num_rows($resfeetype);
	while($rowfeetype=mysql_fetch_assoc($resfeetype)){
		$salegroup=$rowfeetype['prm'];
		$xxx++;
		$sql="select * from type where grp='saleitem' and prm='$saletype' order by idx";
		
?>

<table width="100%" cellspacing="0">
       <tr>
	   	<td id="mytabletitle" width="5%"><?php echo strtoupper($lg_no);?></td>
        <td id="mytabletitle" width="15%">&nbsp;<?php echo strtoupper($salegroup);?></td> 
		<td id="mytabletitle" width="5%" align="center"><?php echo strtoupper($lg_unit_price);?></td>
		<td id="mytabletitle" width="5%" align="center"><?php echo strtoupper($lg_discount);?>(%)</td>
		<td id="mytabletitle" width="5%" align="center"><?php echo strtoupper($lg_discount);?>(<?php echo strtoupper($lg_rm);?>)</td>
		<td id="mytabletitle" width="10%" align="center"><?php echo strtoupper($lg_quantity);?></td> 
		<td id="mytabletitle" width="5%"  align="center"><?php echo strtoupper($lg_total);?>(<?php echo strtoupper($lg_rm);?>)</td>
		<td id="mytabletitle" width="35%">&nbsp;</td> 
       </tr>
<?php
	$xxx=0;
	$sql="select * from type where grp='saleitem' and etc='$salegroup' order by idx";
	$ressale=mysql_query($sql)or die("query failed:".mysql_error());
	$numrowx=mysql_num_rows($ressale);
	while($rowsale=mysql_fetch_assoc($ressale)){
		$sale=$rowsale['prm'];
		$code=$rowsale['code'];
		$price=$rowsale['des'];
		if(($q++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="#FAFAFA";
		$bg="$bglyellow";
?>
<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
	   	<td id="myborder"><?php echo $q;?></td>
        <td id="myborder">&nbsp;<?php echo strtoupper($sale);?></td> 
		<td id="myborder" align="center"><input name="saleprice[]" id="saleprice<?php echo $q;?>" type="text" size="5" style="text-align:right; border:none; font-weight:bold; color:#0000FF;" readonly value="<?php printf("%.02f",$price);?>"></td>
		<td id="myborder" align="center"><input name="saledis[]"   id="saledis<?php echo $q;?>"   onKeyUp="process_diskaun(<?php echo $q;?>);check_quantiti(<?php echo $q;?>,0,<?php echo $totalitem;?>);checkx();" type="text" size="5" style="text-align:right; font-weight:bold; color:#0000FF" value="<?php printf("%.02f",$feedis);?>"></td>
		<td id="myborder" align="center"><input name="saledisrm[]" id="saledisrm<?php echo $q;?>" onKeyUp="process_potongan(<?php echo $q;?>);check_quantiti(<?php echo $q;?>,0,<?php echo $totalitem;?>);checkx();" type="text" size="5" style="text-align:right; font-weight:bold; color:#0000FF" value="<?php printf("%.02f",$feedisval);?>"></td>
		<td id="myborder" align="center">
				<input type="button" value="-" onClick="check_quantiti(<?php echo $q;?>,-1,<?php echo $totalitem;?>);checkx();">
				<input type="text" name="saleunit[]" id="saleunit<?php echo $q;?>" style="text-align:center; border:none; font-weight:bold; color:#0000FF;" value="0" size="5" readonly>
				<input type="button" value="+" onClick="check_quantiti(<?php echo $q;?>,1,<?php echo $totalitem;?>);checkx();">
		</td>
		<td id="myborder" align="center">
				<input name="saletotal[]" id="saletotal<?php echo $q;?>" type="text" size="5" style="text-align:right; border:none; font-weight:bold; color:#0000FF" readonly value="0.00">
				<input name="salecode[]" type="hidden" value="<?php echo $code?>" >
				<input name="saleitem[]" type="hidden" value="<?php echo $sale?>" >
				<input name="saletype[]" type="hidden" value="<?php echo $salegroup?>" >
		 </td>
		 <td>&nbsp;</td>
       </tr>

<?php }?>
 </table>
<?php }?>
</body>
</html>
