<?php
	//GET THE FEESET PARAM
	$feename=$rowfee['name'];
	//$feetype=$rowfee['type'];
	$feeval=$rowfee['val'];
	$feeyatim=$rowfee['yatim'];
	$feestaff=$rowfee['staff'];
	$feekawasan=$rowfee['kawasan'];
	$feexpelajar=$rowfee['xpelajar'];
	$feeanak2=$rowfee['a2'];
	$feeanak3=$rowfee['a3'];
	$feeanaknext=$rowfee['ax'];
	$feenew=$rowfee['new'];
	$feeasrama=$rowfee['asrama'];
	$special=$rowfee['special'];
	$inter=$rowfee['inter'];
	$feeetc=$rowfee['etc'];		
	$issex=$rowfee['issex'];
	$islvl=$rowfee['islvl'];
	$iscls=$rowfee['iscls'];
	$isfeefree=$rowfee['isfeefree'];
			
	$jumanak=0;
	$noanak=0;
	if($PARENT_CHILD_ALL_SCHOOL)
			$sql="select * from stu where p1ic='$p1ic' and status=6 order by bday";
	else
			$sql="select * from stu where p1ic='$p1ic' and sch_id=$sid and status=6 order by bday";
	$res=mysql_query($sql) or die("$sql - query failed:".mysql_error());
	$jumanak=mysql_num_rows($res);
	while($row=mysql_fetch_assoc($res)){
		$noanak++;
		$t=$row['uid'];
		if($t==$uid)
			break;
	}
	
	$feeclscode=$clscode;
	$feeclslevel=$clslevel;
	if($clslevel==""){//check last year punya cls
			$tmp=$year-1;
			$sql="select * from ses_stu where stu_uid='$uid' and year='$tmp'";
			$res_feestu=mysql_query($sql) or die("$sql - query failed:".mysql_error());
			if($row_feestu=mysql_fetch_assoc($res_feestu)){
					$feeclslevel=$row_feestu['cls_level'];
					$feeclslevel=$feeclslevel+1;
			}
	}	

	$feedis=0;
	$feedisval=0;
	$feerm=0;

	$sql="select * from feestu where uid='$uid' and ses='$year' and fee='$feename' and sid='$sid'";
	$res_feestu=mysql_query($sql) or die("$sql - query failed:".mysql_error());
	if($row_feestu=mysql_fetch_assoc($res_feestu)){
			$feeval=$row_feestu['val'];
			$feedes=$row_feestu['des'];
			$feerm=$row_feestu['rm'];
			$feedis=$row_feestu['dis'];
			$feedisval=$row_feestu['disval'];
	}else{
			if($feeanak2>-1){
					if($noanak==2)
						$feeval=$feeanak2;
			}
			if($feeanak3>-1){
					if($noanak==3)
						$feeval=$feeanak3;
			}
			if($feeanaknext>-1){
					if($noanak>3)
						$feeval=$feeanaknext;
			}
			
			if(($ishostel)&&($feeasrama>-1)){
					$feeval=$feeasrama;
			}
			if(($isxpelajar)&&($feexpelajar>-1)){
				if($feeval>$feexpelajar)
					$feeval=$feexpelajar;
			}
			if(($iskawasan)&&($feekawasan>-1)){
				if($feeval>$feekawasan)
					$feeval=$feekawasan;
			}
			
			if(($isstaff)&&($feestaff>-1)){
				if($feeval>$feestaff)
					$feeval=$feestaff;
			}
			if(($isyatim)&&($feeyatim>-1)){
				if($feeval>$feeyatim)
					$feeval=$feeyatim;
			}
			if(($isinter)&&($inter>-1)){
					$feeval=$inter;
			}
		
			
			if($feenew>-1){
					$sql="select * from feestu where uid='$uid' and fee='$feename' and sid='$sid' and sta!=0";
					$res_feenew=mysql_query($sql) or die("$sql - query failed:".mysql_error());
					$alreadypaid=mysql_num_rows($res_feenew);
					if(($alreadypaid==0)&&($isnew))
							$feeval=$feenew;
			}
			
			if(strlen($issex)>0){
						$tmp=strtok($issex,",");
						while($tmp!=false){
							list($xsex,$xval)=explode(":",$tmp);
							if($xsex==$sex)
								$feeval=$xval;
							$tmp=strtok(",");
						}
			}
			if(strlen($islvl)>0){
						$tmp=strtok($islvl,",");
						while($tmp!=false){
							list($xlvl,$xval)=explode(":",$tmp);
							if($xlvl==$feeclslevel)
								$feeval=$xval;
							$tmp=strtok(",");
						}
			}
			if(strlen($iscls)>0){
						$tmp=strtok($iscls,",");
						while($tmp!=false){
							list($xcls,$xval)=explode(":",$tmp);
							if($xcls==$feeclscode)
								$feeval=$xval;
							$tmp=strtok(",");
						}
			}
			
			$feerm=$feeval;
	}
?>