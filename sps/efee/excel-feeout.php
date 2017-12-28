<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
header("Content-Type: application/octet-stream");

# replace excelfile.xls with whatever you want the filename to default to
header("Content-Disposition: attachment; filename=report.xls");
header ("Pragma: no-cache");
header("Expires: 0");
		
		$cutoff=$_REQUEST['cutoff'];
		$view=$_REQUEST['view'];
		if($view=="")
			$view="show_summary";
			
		$allstudent=$_REQUEST['allstudent'];
		if($allstudent==1)
			$sqlstudent="";
		else
			$sqlstudent=" and stu.status=6";
			
		$unsponser=$_REQUEST['unsponser'];
		if($unsponser==1)
			$sqlunsponser=" and stu.isislah=0";
			
		$sponseronly=$_REQUEST['sponseronly'];
		if($sponseronly==1)
			$sqlsponseronly=" and stu.isislah=1";
			
		$isinstallment=$_REQUEST['isinstallment'];
		if($isinstallment==1)
			$sqlinstallment=" and stu.isfakir=1";
			
		$uninstall=$_REQUEST['uninstall'];
		if($uninstall==1)
			$sqluninstall=" and stu.isfakir=0";
			
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
		
		$year=$_REQUEST['year'];
		if($year=="")
			$year=date('Y');
		$sqlyear="and year='$year'";
		
		$xx=$year+1;
		if(($sid==2)||($sid==3))
			$sesyear="$year/$xx";
		else
			$sesyear=$year;
		
		
		$clslevel=$_REQUEST['clslevel'];
		$clscode=$_REQUEST['clscode'];
		if($clscode!=""){
			$sqlclscode="and ses_stu.cls_code='$clscode'";
			$sql="select * from cls where sch_id=$sid and code='$clscode'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=$row['name'];
			$clslevel=$row['level'];
		}
		if($clslevel!=""){
			$sqlclslevel="and cls_level='$clslevel'";
			$sqllevel="and level='$clslevel'";
		}
			
		$search=$_REQUEST['search'];
		if(strcasecmp($search,"- $lg_name_matrik_ic -")==0)
			$search="";
		if($search!=""){
			$search=addslashes($search);
			$sqlsearch = "and (uid='$search' or ic='$search' or name like '%$search%')";
			$search=stripslashes($search);
			$sqlclscode="";
			$clsname="";
			$clslevel="";
			$sqlclslevel="";
		}

	
		
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=stripslashes($row['name']);
			$ssname=stripslashes($row['sname']);
			$namatahap=$row['clevel'];
            mysql_free_result($res);					  
		}

		$month=$_REQUEST['month'];
		if($month=="")
			$month=1;
			//$month=date('m');
			
		$xmonth=$month+1;
		$tillmonth=sprintf("$year-%02d-00",$xmonth);
		$maxout=$_REQUEST['maxout'];
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>


<table width="100%" cellspacing="0" cellpadding="0"  style="font-size:9px; font-family:Tahoma" >
<tr>
	
	<td style="border: #F1F1F1 solid 1px;border-right:none;" rowspan="2" width="1%" align="center">NO</td>
    <td style="border: #F1F1F1 solid 1px;border-right:none;" rowspan="2" width="3%" align="center">MATRIC</td>
	<td style="border: #F1F1F1 solid 1px;border-right:none;" rowspan="2" width="1%" align="center">CLASS</td>
    <td style="border: #F1F1F1 solid 1px;border-right:none; padding-left:2px;" rowspan="2" width="10%">NAME</td>
<?php
		$sql="select * from type where grp='feetype' and (sid=0 or sid=$sid) order by idx";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$xf=$row['prm'];
			$xt=$row['val'];
					
			$sql="select feeset.name,type.* from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sid and year='$year' and type.grp='yuran' and type.val=$xt and type.code<=$month";
			$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
			$colspan=mysql_num_rows($res2);
			
			if($colspan==0) 
				continue;
			
			$feesetname[$idxfee]=$xz;
			$feesetcode[$idxfee]=$xx;
			$feesetstuval[$idxfee]=0;
			$feesettotal[$idxfee]=0;
			$idxfee++;
			$FEEGROUP[]=$xf;
			$FEEITEMGROUPCOUNT[$xf]=$colspan;
			//$colspan=$colspan+1;//add jumlah
?>
			<td style="border: #F1F1F1 solid 1px;border-right:none;" align=center width="1%" colspan="<?php echo $colspan;?>"><?php echo $xf;?></td>
<?php }  ?>
		<td style="border: #F1F1F1 solid 1px;border-right:none;" rowspan="2" width="3%" align="center">TOTAL</td>
		<td style="border: #F1F1F1 solid 1px;border-right:none;" width="2%" align="center" rowspan="2">ADVANCE</td>
		<td style="border: #F1F1F1 solid 1px;" width="2%" align="center" rowspan="2">BALANCE</td>
  </tr>
    
  <tr>
<?php
		$sql="select * from type where grp='feetype' and (sid=0 or sid=$sid) order by idx";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$idxfee=0;$gidx=0;
		while($row=mysql_fetch_assoc($res)){
			$xf=$row['prm'];
			$xt=$row['val'];
			if($xt>0)
				$sql="select feeset.name,type.* from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sid and year='$year' and type.grp='yuran' and type.val=$xt and type.code<=$month order by feeset.id";
			else
				$sql="select feeset.name,type.* from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sid and year='$year' and type.grp='yuran' and type.val=$xt order by feeset.id";
				//echo "$sql<br>";
			$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
			$num=mysql_num_rows($res2);
			if($num==0) continue;
			while($row2=mysql_fetch_assoc($res2)){
				$xx=$row2['etc'];
				$xz=$row2['prm'];
				$feesetname[$idxfee]=$xz;
				$feesetcode[$idxfee]=$xx;
				$idxfee++;
?>

				<td style="border: #F1F1F1 solid 1px;border-right:none; border-top:none;font-weight:normal;" align="center" width="2%"><a href="#" title="<?php echo "$xz";?>"><?php echo "$xx";?></a></td>
<?php } $grpidx[$gidx++]=$idxfee-1;?>

<?php }?>
  </tr>
  
    
  
 <?php
	if($clslevel=="")
			$sql="select * from stu where sch_id=$sid and stu.sch_id=$sid $sqlstudent $sqlunsponser $sqluninstall $sqlsponseronly $sqlinstallment and stu.rdate<'$tillmonth' $sqlsearch $sqlsort";
	else
			$sql="select distinct(stu_uid) from ses_stu INNER JOIN stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid $sqlstudent $sqlunsponser $sqluninstall $sqlsponseronly $sqlinstallment and stu.rdate<'$tillmonth' $sqlclslevel $sqlclscode $sqlsearch $sqlyear $sqlsort";
			

$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){
		if($clslevel=="")
				$uid=$row['uid'];
		else
				$uid=$row['stu_uid'];
		$sql="select * from stu where uid='$uid' and sch_id=$sid";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		$row2=mysql_fetch_assoc($res2);
		$name=ucwords(strtolower(stripslashes($row2['name'])));
		$status=$row2['status'];

		$sql="select * from ses_stu where stu_uid='$uid' and sch_id=$sid and year='$year'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		if($row2=mysql_fetch_assoc($res2)){
			$cname=$row2['cls_name'];
			$ccode=$row2['cls_code'];
			$clevel=$row2['cls_level'];
		}else
			$cls=$lg_none;
			
		$ALLPAID=1;
		$jumhutang=0;
		$sql="select * from type where grp='feetype' and (sid=0 or sid=$sid) order by val";
		$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row2=mysql_fetch_assoc($res2)){
			$xf=$row2['prm'];
			$xt=$row2['val'];
			
			
			if($xt>0)
				$sql="select feeset.*,type.code from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sid and year='$year' and type.grp='yuran' and type.val=$xt and type.code<=$month order by feeset.id";
			else
				$sql="select feeset.*,type.code from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sid and year='$year' and type.grp='yuran' and type.val=$xt order by feeset.id";
			//echo $sql;
			$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
			$num3=mysql_num_rows($res3);
			while($rowfee=mysql_fetch_assoc($res3)){
				$feename=$rowfee['name'];
				$stuoutfee[$feename]="";
				$stupaysta[$feename]=1;//byr
				$feemonth=$rowfee['code'];
				$strmonth=sprintf("%02d",$feemonth);
				$sql="select * from feestu where uid='$uid' and fee='$feename' and ses='$year' and fee='$feename'";
				//echo "$sql:";
				$res9=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				$row9=mysql_fetch_assoc($res9);
				$feeval=$row9['val'];
				//echo ":$feeval<br>";
				$feesta=$row9['sta'];
				$paydate=$row9['pdt'];
				//baru tambah 4.1.0
				if($edate>"0000-00-00"){
					//echo "$uid:$edate > 2010-$strmonth-00<br>";
					if($edate < "$year-$strmonth-00"){
						//echo "$uid:$edate > 2010-$strmonth-00<br>";
						$feeval=-1;
						$stupaysta[$feename]=-1;//tak termasuk
						$stuoutfee[$feename]="-1";
					}
				}
				//end baru tambah 4.1.0
				if(($feeval>0)&&($feesta!=1)){
						$ALLPAID=0;
						$stuoutfee[$feename]=$feeval;
						//echo "stuoutfee[$feename]=$feeval<br>";
						$jumhutang=$jumhutang+$feeval;
						$stupaysta[$feename]=2;//hutang
				}elseif(($feeval==-1)||($feeval==0)){
				    //echo "X stuoutfee[$feename]=$feeval<br>";
					$stupaysta[$feename]=-1;//tak termasuk
					$stuoutfee[$feename]="";//"-"
					if(($edate>"0000-00-00")&&($edate < "$year-$strmonth-00"))
						$stuoutfee[$feename]="X";
				}
				if($cutoff!=""){
					//echo "if(($feeval>0)&&($feesta==1)&&($paydate>$cutoff)){";
					if(($feeval>0)&&($feesta==1)&&($paydate>$cutoff)){
							$ALLPAID=0;
							$stuoutfee[$feename]=$feeval;
							$jumhutang=$jumhutang+$feeval;
							$stupaysta[$feename]=2;//hutang
					}
				}
				$feestatustype[$feename]=$xt;//hutang
				
			}
			
		}

		if($ALLPAID)
			continue;
		
		
		if($maxout!=""){
			if($jumhutang<$maxout)
				continue;
		}
		for($j=0;$j<$idxfee;$j++){
				$feename=$feesetname[$j];
				$outfee[$feename]=$outfee[$feename]+$stuoutfee[$feename];
		}
		
		$totaljumlahtertunggak=$totaljumlahtertunggak+$jumhutang;
	
		if(($q++%2)==0)
			$bg=$bglyellow;
		else
			$bg=$bglyellow;
			
		if($isblock)
			$bg="$bglred";
			
?>
		<tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
			  <td style="border: #F1F1F1 solid 1px;border-right:none; border-top:none;" align="center"><?php echo "$q";?></td>
              <td style="border: #F1F1F1 solid 1px;border-right:none; border-top:none;" align="center"><?php echo "$uid";?></td>
			  <td style="border: #F1F1F1 solid 1px;border-right:none; border-top:none;" align="center"><?php echo $ccode;?></td>
			  <td style="border: #F1F1F1 solid 1px;border-right:none; border-top:none; padding-left:2px;"><?php echo $name;?></td>
			  
<?php
		$feeval=0;$xval="";
		$grpfeeout=0;
		for($j=0;$j<$idxfee;$j++){
				$feename=$feesetname[$j];
				
				if($stupaysta[$feename]==-1)
					$bgc="";//#FAFAFA $bglgray;
				if($stupaysta[$feename]==0)
					$bgc="";//#FAFAFA $bglgray;
				if($stupaysta[$feename]==1)
					$bgc=$bglgreen;
				if($stupaysta[$feename]==2)
					$bgc=$bglred;
				
			
?>
			<td style="border: #F1F1F1 solid 1px;border-right:none; border-top:none;" align="center" bgcolor="<?php echo $bgc;?>"> <?php echo $stuoutfee[$feename];?> </td>

<?php 
$bgc="";
} 
?>
<?php
if($cutoff!="")
		$sqlcutoff="and cdate<='$cutoff 60:00:00'";				
else
		$sqlcutoff="";
	
		$sql="select sum(rm) from feepay where stu_uid='$uid' and fee='ADVANCE' and isdelete=0 $sqlcutoff";
		$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		if($row2=mysql_fetch_row($res2))
			$advance=$row2[0];
		else
			$advance=0;
		
		$sql="select sum(rm) from feepay where stu_uid='$uid' and fee='RECLAIM' and isdelete=0 $sqlcutoff";
		$res2=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		if($row2=mysql_fetch_row($res2))
			$stutebus=$row2[0];
		else
			$stutebus=0;
		$advance=$advance+$stutebus; //statebus negative so kena +
		$totaladvance=$advance+$totaladvance;
		$finalhutang=$jumhutang-$advance;

?>

			<td style="border: #F1F1F1 solid 1px;border-right:none; border-top:none;" align="right"><?php echo number_format($jumhutang,2,'.',',');?></td>
			<td style="border: #F1F1F1 solid 1px;border-right:none; border-top:none;" align="right"><?php echo number_format($advance,2,'.',',');?></td>
			<td style="border: #F1F1F1 solid 1px;border-top:none;" align="right"><?php echo number_format($finalhutang,2,'.',',');?></td>
	</tr>
	
	
<?php 
}
?>

	<tr>
		<td style="border: #F1F1F1 solid 1px;border-right:none;" colspan="4" align="center"><?php echo $lg_total_amount;?></td>
		
<?php
		$q=0;$grpfeeout=0;
		for($j=0;$j<$idxfee;$j++){
				$prm=$feesetname[$j];
?>
    <td  style="border: #F1F1F1 solid 1px;border-right:none;font-weight:normal;"align="center"><?php echo $outfee[$prm];?></td>
<?php } ?>
    <td style="border: #F1F1F1 solid 1px;border-right:none;font-weight:normal;" align=right><?php echo number_format($totaljumlahtertunggak,2,'.',',');?></td>
	<td style="border: #F1F1F1 solid 1px;border-right:none;font-weight:normal;" align=right><?php echo number_format($totaladvance,2,'.',',');?></td>
	<td style="border: #F1F1F1 solid 1px;border-top:none;font-weight:normal;" align=right><?php echo number_format($totaljumlahtertunggak-$totaladvance,2,'.',',');?></td>
	</tr>
</table>





     
</body>
</html>