<?php
//10/05/2010 - repair header
//13/04/2010 - new loaded
$vmod="v4.1.0";
$vdate="10/05/2010";
include_once('../etc/db.php');
include_once('../etc/session.php');
include("$MYOBJ/fckeditor/fckeditor.php") ;
verify('ADMIN|AKADEMIK|KEUANGAN|HEP|HR|HEP-OPERATOR');
	$username = $_SESSION['username'];
	$stu=$_REQUEST['checker'];
	$sid=$_REQUEST['sid'];
	$month=$_REQUEST['month'];
	$year=$_REQUEST['year'];
	$letter=$_REQUEST['id'];
	$letter='feewarn';
	if($sid=="")
		$sid=$_SESSION['sid'];
		
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<script language="JavaScript">
<!--
function process_save(op){
		ret = confirm("Are you sure want to save??");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
		return;
}
//-->
</script>
</head>
<body>
<form name="myform" action="" method="post">
	<input type="hidden" name="id" value="<?php echo $id;?>" >
	<input type="hidden" name="op" value="">

<div id="content">
	<div id="mypanel">
		<div id="mymenu" align="center">
			<a href="letter_config.php?id=feewarn&sid=<?php echo $sid;?>" id="mymenuitem"><img src="../img/option.png"><br>Configure</a>
			<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
			<a href="#" onClick="window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
		</div>
		<div align="right">
		<br>
		&nbsp;&nbsp;
	 	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
		</div>
	</div>
	<!-- 
	<table width="50%">
		<tr>
			<td id="mytabletitle" width="10%" align="center">BIL</td>
			<td id="mytabletitle" width="60%" align="center">YURAN</td>
			<td id="mytabletitle" width="30%" align="center">JUMLAH (RP</td>
		</tr>
		<tr><td id=myborder align=center>Bil</td><td id=myborder >YURAN</td><td id=myborder align=center>JUMLAH (RP)</td></tr>
	</table>
	 -->
<?php
		$totalpage=count($stu);
		for($numberofstudent=0;$numberofstudent<count($stu);$numberofstudent++){
			$pageno++;
			$uid=$stu[$numberofstudent];

			$sql="select * from stu where uid='$uid'";
			$res=mysql_query($sql,$link)or die("$sql query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$sid=$row['sch_id'];
			$NAMA_PELAJAR=stripslashes($row['name']);
			$KP_PELAJAR=$row['ic'];
			$NAMA_BAPA=stripslashes($row['p1name']);
			$KP_BAPA=stripslashes($row['p1ic']);
			
			$addr=$row['addr'];
			$ALAMAT=str_replace(",",",<br>",$addr);
			$BANDAR=$row['bandar'];
			$POSKOD=$row['poskod'];
			$NEGERI=$row['state'];
			
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=stripslashes($row['name']);
			$stype=$row['level'];
			$simg=$row['img'];
			
			$sql="select * from letter where type='$letter' and sid=$sid";
			$res=mysql_query($sql,$link)or die("$sql query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$content=$row['content'];
			$isheader=$row['isheader'];
			$content=stripslashes($content);
			

					$j=0;$rm=0;$totalrm=0;
					
					$TABLE_TUNGGAKAN="<table width=\"50%\" style=\"font-size:80%\">
						<tr>
							<td id=mytabletitle width=\"10%\" align=center>BIL</td>
							<td id=mytabletitle width=\"60%\">MAKLUMAT TUNGGAKAN YURAN</td>
							<td id=mytabletitle width=\"30%\" align=center>JUMLAH (RP</td>
						</tr>";
			
						for($yy=2010;$yy>=2008;$yy--){
							$totalyear=0;
							//$sql="select feestu.*,type.code from feestu INNER JOIN type ON feestu.fee=type.prm where feestu.sta=0 and feestu.val>0 and feestu.ses=$yy and feestu.uid='$uid' and feestu.sid='$sid' and type.grp='yuran' and type.val=0 and type.grp='yuran' order by type.code";
							$sql="select feestu.*,type.code from feestu INNER JOIN type ON feestu.fee=type.prm where feestu.sta=0 and feestu.val>0 and feestu.ses=$yy and feestu.uid='$uid' and type.grp='yuran' and type.val=0 and type.grp='yuran' order by type.code";
							$res=mysql_query($sql,$link)or die("query failed:".mysql_error());
							while($row=mysql_fetch_assoc($res)){
								$feename=strtoupper($row['fee']);
								$rm=$row['val'];
								$totalrm=$totalrm+$rm;
								$totalyear=$totalyear+$rm;
								//$j++;
								//$TABLE_TUNGGAKAN=$TABLE_TUNGGAKAN."$j.$feename: $rm<br>"; 
								//$TABLE_TUNGGAKAN=$TABLE_TUNGGAKAN."<tr><td id=myborder align=center>$j</td><td id=myborder >$feename</td><td id=myborder align=center>$rm.00</td></tr>";
							}
							
							if($totalyear>0){
								$j++;
								$TABLE_TUNGGAKAN=$TABLE_TUNGGAKAN."<tr><td id=myborder align=center>$j</td><td id=myborder >TAHUNAN $yy</td><td id=myborder align=center>$totalyear.00</td></tr>";
							}
							//$sql="select feestu.*,type.code from feestu INNER JOIN type ON feestu.fee=type.prm where feestu.sta=0 and feestu.val>0 and feestu.ses=$yy and feestu.uid='$uid' and feestu.sid='$sid' and type.grp='yuran' and type.val>0 and type.grp='yuran' order by type.code";
							$sql="select feestu.*,type.code from feestu INNER JOIN type ON feestu.fee=type.prm where feestu.sta=0 and feestu.val>0 and feestu.ses=$yy and feestu.uid='$uid' and type.grp='yuran' and type.val>0 and type.grp='yuran' order by type.val,idx";
							$res=mysql_query($sql,$link)or die("$sql query failed:".mysql_error());
							while($row=mysql_fetch_assoc($res)){
								$feename=strtoupper($row['fee']);
								$rm=$row['val'];
								$totalrm=$totalrm+$rm;
								$j++;
								//$TABLE_TUNGGAKAN=$TABLE_TUNGGAKAN."$j.$feename: $rm<br>"; 
								$TABLE_TUNGGAKAN=$TABLE_TUNGGAKAN."<tr><td id=myborder align=center>$j</td><td id=myborder >$feename $yy</td><td id=myborder align=center>$rm.00</td></tr>";
							}
						}
							$totalrm=number_format($totalrm,2,'.',',');
							$TABLE_TUNGGAKAN=$TABLE_TUNGGAKAN."<tr><td id=mytabletitle align=center>-</td><td id=mytabletitle >JUMLAH</td><td id=mytabletitle align=center>$totalrm</td></tr>";
						
						$TABLE_TUNGGAKAN=$TABLE_TUNGGAKAN."</table>";
			
?>
<div id="story" style="font-size:120%; border:none">
<div id="mytitlebg" class="printhidden" style="color:#CCCCCC" align="right">PAGE <?php echo "$pageno/$totalpage";?></div>
<div id="letter" style="display:block; border:none; padding:10px 10px 10px 10px">
	<?php if($isheader){ include("../inc/header_global2.php"); ?>
			<div id="myborder"></div>
	<?php } ?>
<?php 
	eval("\$content = \"$content\";"); //evaluate the input string as PHP;
	print $content; 
?>
</div>
</div><!-- story -->

<?php if($pageno!=$totalpage){?>
<div style="page-break-after:always"></div>
<?php } ?>
<?php } ?>
</div>
</form>
</body>
</html>
