<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
header("Content-Type: application/octet-stream");

# replace excelfile.xls with whatever you want the filename to default to
header("Content-Disposition: attachment; filename=report.xls");
header ("Pragma: no-cache");
header("Expires: 0");
		
$adm = $_SESSION['username'];
		$xfee=$_REQUEST['xfee'];
		$xuid=$_REQUEST['xuid'];
		
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
		
		$fee=$_REQUEST['fee'];
		if($fee!=""){
			$sqlfee="and fee='$fee'";
			$sql="select * from type where grp='yuran' and prm='$fee'";
			$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
           	$row=mysql_fetch_assoc($res);
            $feetype=$row['val'];
		}
		
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=stripslashes($row['name']);
			$ssname=stripslashes($row['sname']);
			$simg=$row['img'];
			$namatahap=$row['clevel'];
            $issemester=$row['issemester'];	
			$startsemester=$row['startsemester'];				  
		}

		 $year=$_REQUEST['year'];
		if($year==""){
			$year=date('Y');
			if(($issemester)&&(date('n')<$startsemester)){
				$year=$year-1;
			}
		}
		$xx=$year+1;
		if($issemester)
			$sesyear="$year/$xx";	  
		else
			$sesyear="$year";
			
		$sqlyear="and year='$year'";
		
		$clslevel=$_POST['clslevel'];
		$clscode=$_REQUEST['clscode'];
		if($clscode!=""){
			$sqlclscode="and ses_stu.cls_code='$clscode'";
			$sql="select * from cls where sch_id=$sid and code='$clscode'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=stripslashes($row['name']);
			$clslevel=$row['level'];
		}
		if($clslevel!="")
			$sqlclslevel="and ses_stu.cls_level='$clslevel'";
			
		$curryear=date('Y');
	if($year<$curryear)
		$sqlstatus="and (stu.status=6 or stu.status=3)";
	else
		$sqlstatus="and stu.status=6";
		
	if(($clslevel=="")&&($year==$curryear)){
    	$sql88="select * from stu where sch_id=$sid $sqlstatus and intake<='$year'
		 $sqlisyatim $sqlisstaff $sqliskawasan
			 $sqlishostel $sqlisfakir $sqlsearch";
	}else{
		$sql88="select * from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid
		 $sqlstatus and intake<='$year' and year='$year' 
		 $sqlclscode $sqlclslevel  $sqlisyatim $sqlisstaff $sqliskawasan $sqlishostel $sqlisfakir $sqlsearch";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>


<table width="100%" class="print_font" cellspacing="0" cellpadding="0">
	<tr style="font-size:9px">
		<td style="border: #F1F1F1 solid 1px;border-right:none; " bgcolor="#F1F1F1" width="1%" align="center">NO</td>
		<td style="border: #F1F1F1 solid 1px;border-right:none; " bgcolor="#F1F1F1" width="4%" align="center">MATRIC</td>
        <td style="border: #F1F1F1 solid 1px;border-right:none; " bgcolor="#F1F1F1" width="4%" align="center">INTAKE</td>
		<td style="border: #F1F1F1 solid 1px;border-right:none; " bgcolor="#F1F1F1" width="12%">&nbsp;NAME</a></td>
		<td style="border: #F1F1F1 solid 1px;border-right:none; " bgcolor="#F1F1F1" width="2%" align="center">CLASS</td>
<!--        
		<td style="border: #F1F1F1 solid 1px;border-right:none; " width="2%" align="center">INVOICE&nbsp;(RP)</td>
-->
<?php 
		$BILFEE=0;
		$sql="select * from type where grp='feetype' order by idx";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$xf=$row['prm'];
			$xx=$row['val'];
			$sql="select feeset.name,type.etc from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sid and year='$year' and type.grp='yuran' and type.val='$xx' order by type.idx";
			$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
			while($row2=mysql_fetch_assoc($res2)){
				$xf=$row2['etc'];
				$nm=$row2['name'];
?>
		<td style="border: #F1F1F1 solid 1px;border-right:none; " bgcolor="#F1F1F1" width="2%" align="center"><a href="#" title="<?php echo strtoupper("$nm");?>"><?php echo strtoupper("$xf");?></a></td>
<?php }}?>

		<td style="border: #F1F1F1 solid 1px;border-right:none; " bgcolor="#F1F1F1" width="2%" align="center">TOTAL</td>
		<td style="border: #F1F1F1 solid 1px;border-right:none; " bgcolor="#F1F1F1" width="2%" align="center">ADVANCE</td>
		<td style="border: #F1F1F1 solid 1px;"  bgcolor="#F1F1F1" width="2%" align="center">BALANCE</td>
		
	</tr>
	
	
<?php
	
	$res=mysql_query($sql88)or die("$sql:query failed:".mysql_error());
	$q=$curr;
  	while($row=mysql_fetch_assoc($res)){
		if($clslevel=="")
				$uid=$row['uid'];
		else
				$uid=$row['stu_uid'];
		
		$sql="select * from stu where uid='$uid' and sch_id=$sid";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		$row2=mysql_fetch_assoc($res2);
		$sex=$row2['sex'];
		$xxid=$row2['id'];
		$intake=$row2['intake'];
		$sx=$lg_sexmf[$sex];
		$isblock=$row2['isblock'];
		$isstaff=$row2['isstaff'];
		$isislah=$row2['isislah'];//sponser
		$isfakir=$row2['isfakir'];//installment
		$star="0";
		
		if($isstaff)
			$star="1";
		if($isislah)
			$star="2";
		if($isfakir)
			$star="3";
		
			
		$name=ucwords(strtolower(stripslashes($row2['name'])));
		
		$sql="select * from ses_stu where stu_uid='$uid' and sch_id=$sid and year='$year'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2)){
			$cls=$row2['cls_code'];
			$ccode=$row2['cls_code'];
		}else
			$cls=$lg_none;
		
		if(($q++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="";
			

		if($isblock)
			$bgb="$bglred";
		else
			$bgb="";
			
		$totalpaid=0;
		$totalunpaid=0;
		$totalamount=0;
		$totalinvoice="";
?>

	<tr bgcolor="<?php echo $bg;?>">
              	<td id="myborder" style="border: #F1F1F1 solid 1px" align="center"><?php echo "$q";?></td>
              	<td id="myborder" style="border: #F1F1F1 solid 1px;border-right:none; border-top:none;" align="center" bgcolor="<?php echo "$bgb";?>"><?php echo "$uid";?></td>
                <td id="myborder" style="border: #F1F1F1 solid 1px;border-right:none; border-top:none;" align="center"><?php echo "$intake";?></td>
              	<td id="myborder" style="border: #F1F1F1 solid 1px;border-right:none;border-left:none; border-top:none;"><?php echo $name;?></td>
				<td id="myborder" style="border: #F1F1F1 solid 1px;border-right:none; border-top:none;" align="center"><?php echo "$cls";?></td>
<?php
/**
		$sql="select * from invoice2 where year='$year' and sch_id=$sid and stu_uid='$uid' and isdelete=0";
		$res5=mysql_query($sql)or die("$sql failed:".mysql_error());
		if($row5=mysql_fetch_assoc($res5)){
			$totalinvoice=$row5['rm'];
			$feeid=$row5['id'];
		}
		if($totalinvoice!="")
			$TOTALFEE['x0']=$TOTALFEE['x0']+$totalinvoice;
**/
?>
<!--
		<td id="myborder" style="border: #F1F1F1 solid 1px;border-right:none; border-top:none;" align="right"><?php if($totalinvoice!="")echo number_format($totalinvoice,2,'.',',');?></td>
-->
<?php
		$sql="select sum(rm) from feepay where stu_uid='$uid' and fee='ADVANCE' and isdelete=0";
		$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		if($row2=mysql_fetch_row($res2))
			$advance=$row2[0];
		else
			$advance=0;
		
		$sql="select sum(rm) from feepay where stu_uid='$uid' and fee='RECLAIM' and isdelete=0";
		$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		if($row2=mysql_fetch_row($res2))
			$stutebus=$row2[0];
		else
			$stutebus=0;
		$advance=$advance+$stutebus; //statebus negative so kena +
?>
<?php
		$sql="select * from type where grp='feetype' order by idx";
		$res5=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row5=mysql_fetch_assoc($res5)){
			$xf=$row5['prm'];
			$xx=$row5['val'];
			$sql="select feeset.name,type.etc from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sid and year='$year' and type.grp='yuran' and type.val='$xx' order by type.idx";
			$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
			while($row2=mysql_fetch_assoc($res2)){
				$fee=$row2['name'];
				$feesta="";
				$feeid="";
				$sql="select * from feestu where uid='$uid' and fee='$fee' and ses='$year'";
				$res8=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				if($row8=mysql_fetch_assoc($res8)){
					$feeid=$row8['id'];
					$feeval=$row8['val'];
					$feesta=$row8['sta'];
					if(($feesta<=0)&&($feeval>0)){
						$sql="select * from feepay where stu_uid='$uid' and year='$year' and fee='$fee' and isdelete=0";
						$res9=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$foundpaid=mysql_num_rows($res9);
						if($foundpaid==0){
							$bgl=$bglred;//red
							$totalunpaid=$totalunpaid+$feeval;
						}else{
							$totalpaid=$totalpaid+$feeval;
							$feesta=1;
						}
					}
					elseif($feesta==1)
						$totalpaid=$totalpaid+$feeval;
				}else{
					$sql="select * from feepay where stu_uid='$uid' and year='$year' and fee='$fee' and isdelete=0";
						$res9=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$foundpaid=mysql_num_rows($res9);
						if($foundpaid==0){
							$feeval="?";
						}else{
							$totalpaid=$totalpaid+$feeval;
							$feesta=1;
						}  
				}
				if($feeval==-1)
					$feeval=""; //? 
				if($feesta!=1){
						echo "<td style=\"border: #F1F1F1 solid 1px;border-right:none; border-top:none;\" align=\"right\" bgcolor=\"$bgl\">$feeval</td>";
				}else{
						echo "<td style=\"border: #F1F1F1 solid 1px;border-right:none; border-top:none;\" align=\"right\" bgcolor=\"$bgl\">$feeval</td>";
				}
				if((is_numeric($feeval))&&($feeval>0))
					$TOTALFEE[$fee]=$TOTALFEE[$fee]+$feeval;
				$bgl="";
				$xxx="";

	}  }
		$totalamount=$totalpaid+$totalunpaid;
		if((is_numeric($totalamount))&&($totalamount>0))
			$TOTALFEE['x1']=$TOTALFEE['x1']+round($totalamount,2);
		if(is_numeric($advance))
			$TOTALFEE['x2']=$TOTALFEE['x2']+round($advance,2);
		
		$balance=$totalunpaid-$advance;
		if((is_numeric($balance)))
			$TOTALFEE['x3']=$TOTALFEE['x3']+round($balance,2);
		
?>

		<td id="myborder" style="border: #F1F1F1 solid 1px;border-right:none; border-top:none;" align="right"><?php echo number_format($totalamount,2,'.',',');?></td>
		<td id="myborder" style="border: #F1F1F1 solid 1px;border-right:none; border-top:none;" align="right"><?php echo number_format($advance,2,'.',',');?></td>
		<td id="myborder" style="border: #F1F1F1 solid 1px;border-top:none;" align="right"><?php echo number_format($balance,2,'.',',');?></td>
		
</tr>
 
<?php  

} //if(($tahap!="")&&(clscode!=""))
?>
        
		
<tr>
				<td style="border: #F1F1F1 solid 1px;border-right:none; font-weight:normal" class="printhidden">&nbsp;</td>
			  	<td style="border: #F1F1F1 solid 1px;border-right:none; font-weight:normal" align="center">&nbsp;</td>
				<td style="border: #F1F1F1 solid 1px;border-right:none; font-weight:normal" align="center">&nbsp;</td>
                              	<td style="border: #F1F1F1 solid 1px;border-right:none; font-weight:normal" align="center">TOTAL AMOUNT</td>
				<td style="border: #F1F1F1 solid 1px;border-right:none; font-weight:normal" align="center">&nbsp;</td>
<!--                
				<td style="border: #F1F1F1 solid 1px;border-right:none; font-weight:normal" align="right"><?php echo number_format($TOTALFEE['x0'],2,'.',',');?>&nbsp;</td>
-->
<?php 
		$sql="select * from type where grp='feetype' order by idx";
		$res5=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row5=mysql_fetch_assoc($res5)){
			$xf=$row5['prm'];
			$xx=$row5['val'];
			$sql="select feeset.name,type.etc from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sid and year='$year' and type.grp='yuran' and type.val='$xx' order by type.idx";
			$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
			while($row2=mysql_fetch_assoc($res2)){
				$fee=$row2['name'];
?>
				<td style="border: #F1F1F1 solid 1px;border-right:none; font-weight:normal" align="center"><?php echo number_format($TOTALFEE[$fee],2,'.',',');?></td>
<?php 
			}  
		}
?>
		<td style="border: #F1F1F1 solid 1px;border-right:none; font-weight:normal" align="right"><?php echo number_format($TOTALFEE['x1'],2,'.',',');?></td>
		<td style="border: #F1F1F1 solid 1px;border-right:none; font-weight:normal" align="right"><?php echo number_format($TOTALFEE['x2'],2,'.',',');?></td>
		<td style="border: #F1F1F1 solid 1px;font-weight:normal" align="right"><?php echo number_format($TOTALFEE['x3'],2,'.',',');?></td>
		
</table>
     
</body>
</html>