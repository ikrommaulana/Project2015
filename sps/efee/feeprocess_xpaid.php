<?php
//12/04/2010 - change processing engine
$vmod="v4.1.0";
$vdate="12/04/2010";
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK|KEUANGAN');
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];

		$sql="select * from type where grp='fee_interface' and (sid=0 or sid='$sid')";
		$res=mysql_query($sql) or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
				$prm=$row['prm'];
				$CONFIG[$prm]['val']=$row['val'];
				$CONFIG[$prm]['code']=$row['code'];
				$CONFIG[$prm]['etc']=$row['etc'];
		}
	
		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
			
		//$sql="select * from stu where uid NOT IN (select uid from feestu where ses='$year'and sid=$sid) and stu.sch_id=$sid and stu.status=6";
		$sql="select uid from stu where sch_id=$sid and sch_id=$sid and stu.status=6";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$uid=$row['uid'];
			$ishostel=$row['ishostel'];
			$isxpelajar=$row['isislah'];
			$p1ic=$row['p1ic'];
			$p1hp=$row['p1hp'];
			$p2hp=$row['p2hp'];
			$p1job=$row['p1job'];
			$p2job=$row['p2job'];
			$addr=$row['addr'];
			$sex=$row['sex'];
			$rdate=$row['rdate'];
			$iskawasan=$row['iskawasan'];
			$isstaff=$row['isstaff'];
			$isblock=$row['isblock'];
			$isyatim=$row['isyatim'];
			$feehutang=$row['feehutang'];
			$isfeenew=$row['isfeenew'];
			$isspecial=$row['isspecial'];
			$isinter=$row['isinter'];
			$isfeebulanfree=$row['isfeefree'];
			
			$jumanak=0;
			$noanak=0;
			$sql="select * from stu where p1ic='$p1ic' and status=6 order by bday";
			$res3=mysql_query($sql) or die("$sql - query failed:".mysql_error());
			$jumanak=mysql_num_rows($res3);
			while($row3=mysql_fetch_assoc($res3)){
				$noanak++;
				$t=$row3['ic'];
				if($t==$ic)
					break;			
			}
			$sql="select * from ses_stu where stu_uid='$uid';";
			$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
			$row2=mysql_fetch_assoc($res2);
			$clslevel=$row2['cls_level'];
			$clscode=$row2['cls_code'];
			$clsname=$row2['cls_name'];
			//echo "$uid-$clscode-$clsname<br>";
			$sql="select * from type where grp='feetype' order by idx";
			$resfeetype=mysql_query($sql)or die("query failed:".mysql_error());
			while($rowfeetype=mysql_fetch_assoc($resfeetype)){
				$feetype=$rowfeetype['val'];
				$feegroup=$rowfeetype['prm'];
				
				$sql="select feeset.* from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$sid and year='$year' and type.grp='yuran' and type.val=$feetype";
				$resfee=mysql_query($sql)or die("$sql - query failed:".mysql_error());
				while($rowfee=mysql_fetch_assoc($resfee)){
						$feename=$rowfee['name'];
						$sql="select * from feestu where uid='$uid' and ses='$year' and fee='$feename'";
						$res_feestu=mysql_query($sql) or die("$sql - query failed:".mysql_error());
						if($row_feestu=mysql_fetch_assoc($res_feestu)){
							$feevallllll=$row_feestu['val'];
							$feedeseeeee=$row_feestu['des'];
						}else{
							include($CONFIG['FILE_FORMULA']['etc']);
							if($feeval==-1)
								$feesta=-1;
							elseif($feeval==0)
								$feesta=2;
							else 
								$feesta=0;
							
							$sql="insert into feestu(dtm,sid,uid,ses,fee,typ,val,rm,sta,des,rno,pdt,adm)values(now(),$sid,'$uid','$year',
								'$feename',$feetype,$feeval,$feeval,$feesta,'$fdes','$resit','$paydt','$username')";
								$res5=mysql_query($sql) or die("$sql - query failed:".mysql_error());
							echo "$uid-$clscode-$clsname-$feename<br>";
						}
				}
			}
			//exit;
		}
		echo "DONE<br>";
?>
<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>