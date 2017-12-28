<?php
/** 
110112-update lain-lain enable negative value
**/
$vmod="v5.1.0";
$vdate="110112";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEUANGAN');
	$username = $_SESSION['username'];
		
	$showheader=$_REQUEST['showheader'];
	$showdetail=$_REQUEST['showdetail'];
	
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];

	$year=$_REQUEST['year'];
	if($year=="")
		$year=date("Y");

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
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<style type="text/css" media="print">
	.print_font{
		font-size:70%;
	}
</style>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body >
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="p" value="../efee/feetarget">

<div id="content" style="font-size:60%" >
<div id="mypanel">
<div id="mymenu" align="center" >
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
              <select name="year" id="year" onChange="document.myform.submit();">
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
               <input type="submit" name="Submit" value="View"  >
	</div>
<div id="story" style="border:none; overflow:auto">


<div id="mytitlebg">PROJECTION FEE <?php echo $year;?></div>
<!--
<table width="100%" cellspacing="0">
	<tr>
		<td id="mytabletitle" width="1%" align="center" ><?php echo strtoupper($lg_no);?></td>
		<td id="mytabletitle" width="20%" align="center" ><?php echo strtoupper($lg_item);?></td>
		<td id="mytabletitle" width="7%" align="center" >TAHUNAN</td>
<?php for($i=1;$i<=12;$i++){?>
		<td id="mytabletitle" width="5%" align="center"><?php echo date('F',mktime(0,0,0,$i,1,$year));;?></td>
<?php }?>
	<tr>
	
	<?php 
		$sql="select * from type where grp='feetype' order by idx";
		$res9=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row9=mysql_fetch_assoc($res9)){
			$feetype=$row9['prm'];
			$typeval=$row9['val'];
			$feetypeetc=$row9['etc'];

			$q++;
?>
		<tr>
			<td id="mytabletitle" width="7%" align="center"><?php echo $q;?></td>
			<td id="mytabletitle" width="5%"><?php echo strtoupper($feetype);?></td>
			<?php 
					$sql="select sum(feestu.val) from feestu,type where feestu.fee=type.prm and type.grp='yuran' 
					and type.val=$typeval and type.code='' and ses='$year' and feestu.sid=$sid and feestu.val>0";
					$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
					$row2=mysql_fetch_row($res2);
					$jum=$row2[0];
			?>
			<td id="myborder" align="center" ><?php echo number_format($jum,2,'.',',');?></td>
			<?php for($i=1;$i<=12;$i++){
					$sql="select sum(feestu.val) from feestu,type where feestu.fee=type.prm and type.grp='yuran' 
					and type.val=$typeval and type.code='$i' and ses='$year' and feestu.sid=$sid and feestu.val>0";
					$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
					$row2=mysql_fetch_row($res2);
					$jum=$row2[0];
			?>
					<td id="myborder" align="right" ><?php echo number_format($jum,2,'.',',');?></td>
			<?php }?>
		</tr>
<?php } ?>
		<tr>
			<td id="mytabletitle" width="7%" align="center">-</td>
			<td id="mytabletitle" width="5%">TOTAL</td>
			<?php 
					$sql="select sum(feestu.val) from feestu,type where feestu.fee=type.prm and type.grp='yuran' 
					and type.val=0 and type.code='' and ses='$year' and feestu.sid=$sid and feestu.val>0";
					$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
					$row2=mysql_fetch_row($res2);
					$jum=$row2[0];
			?>
			<td id="mytabletitle" align="center" ><?php echo number_format($jum,2,'.',',');?></td>
			<?php for($i=1;$i<=12;$i++){
					$sql="select sum(feestu.val) from feestu,type where feestu.fee=type.prm and type.grp='yuran' 
					and type.code='$i' and ses='$year' and feestu.sid=$sid and feestu.val>0";
					$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
					$row2=mysql_fetch_row($res2);
					$jum=$row2[0];
			?>
					<td id="mytabletitle" align="right" ><?php echo number_format($jum,2,'.',',');?></td>
			<?php }?>
		</tr>
</table>   

-->
<table width="100%" cellspacing="0" cellpadding="4">
	<tr>
		<td id="mytabletitle" width="1%" align="center" ><?php echo strtoupper($lg_no);?></td>
		<td id="mytabletitle" width="7%" align="center" ><?php echo strtoupper($lg_month);?></td>
<?php 
		$q=0;
		$col=0;
		$sql="select * from type where grp='feetype' order by idx";
		$res9=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row9=mysql_fetch_assoc($res9)){
			$feetype=$row9['prm'];
			$feetypeval=$row9['val'];
			$feetypeetc=$row9['etc'];
			$col++;
		
?>
		<td id="mytabletitle" width="5%" align="center" ><?php echo strtoupper($feetype);?></td>
<?php } ?>
		<td id="mytabletitle" width="7%" align="center" ><?php echo strtoupper($lg_total);?></td>
	</tr>
<?php for($i=1;$i<=12;$i++){ 
	$q++;
?>
	<tr>
		<td id="mytabletitle" align="center"><?php echo $q;?></td>
		<td id="mytabletitle" align="center"><?php $dt=date('F',mktime(0,0,0,$i,1,$year));
		if ($dt=='January'){echo 'Januari';}
		elseif ($dt=='January'){echo 'Januari';}
		elseif ($dt=='February'){echo 'Februari';}
		elseif ($dt=='March'){echo 'Maret';}
		elseif ($dt=='April'){echo 'April';}
		elseif ($dt=='May'){echo 'Mei';}
		elseif ($dt=='June'){echo 'Juni';}
		elseif ($dt=='July'){echo 'Juli';}
		elseif ($dt=='August'){echo 'Agustus';}
		elseif ($dt=='September'){echo 'September';}
		elseif ($dt=='October'){echo 'Oktober';}
		elseif ($dt=='November'){echo 'November';}
		elseif ($dt=='December'){echo 'Desember';}

		else{echo $dt;}
		?></td>
<?php 
		$totalall=0;
		$jj=0;
		$sql="select * from type where grp='feetype' order by idx";
		$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row2=mysql_fetch_assoc($res2)){
			$feetype=$row2['prm'];
			$typeval=$row2['val'];
			$feetypeetc=$row2['etc'];
			if(($typeval==0) and ($i==1))
				$sql="select sum(feestu.val) from feestu,type where feestu.fee=type.prm and type.grp='yuran' 
				and type.val=$typeval and (type.code='$i' or type.code='' or type.code is null)and ses='$year' and feestu.sid=$sid and feestu.val>0";
			else
				$sql="select sum(feestu.val) from feestu,type where feestu.fee=type.prm and type.grp='yuran' 
				and type.val=$typeval and type.code='$i' and ses='$year' and feestu.sid=$sid and feestu.val>0";
			$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
			$row3=mysql_fetch_row($res3);
			$jum=$row3[0];
			$totalall=$totalall+$jum;
			$xtotal[$jj]=$xtotal[$jj]+$jum;$jj++;
			$totalallmonth=$totalallmonth+$jum;
		
?>
		<td id="myborder" align="center" ><?php echo number_format($jum,2,'.',',');?></td>
<?php } ?>
		<td id="mytabletitle" width="7%" align="center" ><?php echo number_format($totalall,2,'.',',');?></td>
	</tr>
<?php }?>
	<tr>
    	<td id="mytabletitle">&nbsp;</td>
        <td id="mytabletitle" align="center">TOTAL</td>
<?php 
		$totalall=0;
		$jj=0;
		$sql="select * from type where grp='feetype' order by idx";
		$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row2=mysql_fetch_assoc($res2)){

		
?>
		<td id="mytabletitle" align="center" ><?php echo number_format($xtotal[$jj++],2,'.',',');?></td>
<?php } ?>
		<td id="mytabletitle" width="7%" align="center" ><?php echo number_format($totalallmonth,2,'.',',');?></td>
	</tr>
</table>
</div></div>
</form>
</body>
</html>
