<?php
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
			$res2=mysql_query($sql) or die("$sql - query failed:".mysql_error());
			if($row2=mysql_fetch_assoc($res2)){
				continue;
			}
				
			include('feeformula.php');
			if($feeval==-1){
				$feesta=-1;
			}else{
				$sql="select * from feepay where stu_uid='$uid' and year='$year' and fee='$feename'";
				$res2=mysql_query($sql) or die("$sql - query failed:".mysql_error());
				if($row2=mysql_fetch_assoc($res2)){
					$rm=$row2['rm'];
					$resit=$row2['tid'];
					$dt=$row2['cdate'];
					$paydate=strtok($dt," ");
					$feesta=1;
				}elseif($feeval==0){
						$feesta=2;
				}else{
						$feesta=0;
				}
			}
		}
	}
?>
