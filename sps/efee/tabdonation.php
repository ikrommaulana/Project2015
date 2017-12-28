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
	dontotal = document.getElementById('dontotal'+id);
	saleunit = document.getElementById('saleunit'+id);
	qtt = parseInt(saleunit.value);

	dis = parseFloat(document.getElementById('saledis'+id).value);
	
	if((val==-1)&&(qtt==0))
		return;

	saleunit.value=qtt+val;
		
	sum=saleunit.value*saleprice.value;
	totaldis=sum*dis/100;
	finalprice=sum-totaldis;
	dontotal.value=finalprice.toFixed(2);

	for(i=1;i<totalitem;i++){
		totalall=totalall+parseFloat(document.getElementById('dontotal'+i).value);
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
		document.getElementById("dontotal"+idx).value=lastprice.toFixed(2);
	}else{
		document.getElementById("saledisrm"+idx).value=0;
		document.getElementById("dontotal"+idx).value=jum;
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
		document.getElementById("dontotal"+idx).value=lastprice.toFixed(2);
	}else{
		document.getElementById("saledis"+idx).value=0;
		document.getElementById("dontotal"+idx).value=jum;
	}
}
</script>
</head>

<body>
<div id="mytabletitle"><br>&raquo;&nbsp;SUMBANGAN</div>

<table width="100%" cellspacing="0">
       <tr>
	   	<td id="mytabletitle" width="5%">NO</td>
        <td id="mytabletitle" width="15%">&nbsp;<?php echo strtoupper($salegroup);?></td> 
		<td id="mytabletitle" width="5%" align="center">TOTAL(RP)</td>
		<td id="mytabletitle" width="35%">&nbsp;</td> 
       </tr>
<?php
	$xxx=0;
	$sql="select * from type where grp='donation' order by idx";
	$ressale=mysql_query($sql)or die("query failed:".mysql_error());
	$numrowx=mysql_num_rows($ressale);
	while($rowsale=mysql_fetch_assoc($ressale)){
		$sale=$rowsale['prm'];
		$code=$rowsale['code'];
		$price=$rowsale['des'];
		if(($jj++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="#FAFAFA";
		$bg="#FFFFFF";
?>
<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
	   	<td id="myborder"><?php echo $jj;?></td>
        <td id="myborder">&nbsp;<?php echo strtoupper($sale);?></td> 
		<td id="myborder" align="center">
				<input name="dontotal[]" id="dontotal<?php echo $q;?>" type="text" size="10" style="text-align:right; font-weight:bold; color:#0000FF" value="0.00">
				<input name="doncode[]" type="hidden" value="<?php echo $code?>" >
				<input name="donitem[]" type="hidden" value="<?php echo $sale?>" >
				<input name="dontype[]" type="hidden" value="<?php echo $salegroup?>" >
		 </td>
		 <td>&nbsp;</td>
       </tr>

<?php }?>
 </table>

</body>
</html>
