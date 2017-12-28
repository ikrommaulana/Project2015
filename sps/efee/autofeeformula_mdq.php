<?php
/** version 3 **/
		$sql="select * from stu where uid='$FFUID' and sch_id=$FFSID";
		$resstu=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$rowstu=mysql_fetch_assoc($resstu);
		$ic=$rowstu['ic'];
		$p1ic=$rowstu['p1ic'];
			
		//clear the unpaid
		$sql="delete from feestu where uid='$FFUID' and sid=$FFSID and ses='$FFYEAR' and sta=0;";
		mysql_query($sql)or die("$sql:query failed:".mysql_error());
		
		
		$status=$rowstu['status'];
		$ishostel=$rowstu['ishostel'];
		$isxpelajar=$rowstu['isislah'];
		
		$sex=$rowstu['sex'];
		$iskawasan=$rowstu['iskawasan'];
		$isstaff=$rowstu['isstaff'];
		$isyatim=$rowstu['isyatim'];
		$feehutang=$rowstu['feehutang'];
		$isfeenew=$rowstu['isfeenew'];
		$isfeebulanfree=$rowstu['isfeefree'];
		$isblock=$rowstu['isblock'];
			
		$jumanak=0;
		$noanak=0;
		$sql="select * from stu where p1ic='$p1ic' and status=6 order by bday";
		$resstu2=mysql_query($sql) or die(mysql_error());
		$jumanak=mysql_num_rows($resstu2);
		while($rowstu2=mysql_fetch_assoc($resstu2)){
			$noanak++;
			$t=$rowstu2['ic'];
			if($t==$ic)
				break;			
		}
		
		$sql="select * from ses_stu where stu_uid='$FFUID' and sch_id=$FFSID and year='$FFYEAR'";
		$resstu2=mysql_query($sql)or die("query failed:".mysql_error());
		if($rowstu2=mysql_fetch_assoc($resstu2)){
			$ffclslevel=$rowstu2['cls_level'];
			$ffclscode=$rowstu2['cls_code'];
		}


		$sql="select * from type where grp='feetype' order by idx";
		$resfeetype=mysql_query($sql)or die("query failed:".mysql_error());
		while($rowfeetype=mysql_fetch_assoc($resfeetype)){ //while 0
				$feetype=$rowfeetype['val'];
				$feegroup=$rowfeetype['prm'];
				
				$sql="select feeset.* from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$FFSID and year='$FFYEAR' and type.grp='yuran' and type.val=$feetype";
				$resfee=mysql_query($sql)or die("$sql - query failed:".mysql_error());
				while($rowfee=mysql_fetch_assoc($resfee)){
								
					/************************************************************************/
					$feename=$rowfee['name'];
					$sql="select * from feestu where uid='$FFUID' and ses='$FFYEAR' and fee='$feename'";
					$res_feestu=mysql_query($sql) or die("$sql - query failed:".mysql_error());
					if($row_feestu=mysql_fetch_assoc($res_feestu)){
							$feeval=$row_feestu['val'];
							$feedes=$row_feestu['des'];
							$feerm=$row_feestu['rm'];
							$feedis=$row_feestu['dis'];
							$feedisval=$row_feestu['disval'];
					}else{
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
							
							if(($isspecial)&&($special>-1)){
									$feeval=$special;
							}
							
							if(($isinter)&&($inter>-1)){
									$feeval=$inter;
							}

							if($feenew>-1){
								$sql="select * from feestu where uid='$FFUID' and fee='$feename' and sid='$FFSID' and sta!=0";
								$res_feenew=mysql_query($sql) or die("$sql - query failed:".mysql_error());
								$alreadypaid=mysql_num_rows($res_feenew);
								if($alreadypaid!=0)
									$feeval=-1;
								else{
									$feeval=$feenew;
								}
							}
							if(strlen($issex)>0){
								if($issex==$sex)
									$feeval=$feeetc;
							}
							if(strlen($islvl)>0){
										$xlvl=strtok($islvl,",");
										while($xlvl!=false){
											if($xlvl==$ffclslevel)
												$feeval=$feeetc;
											$xlvl=strtok(",");
										}
							}
							if(strlen($iscls)>0){
										$xccode=strtok($iscls,",");
										while($xccode!=false){
											if($xccode==$ffclscode)
												$feeval=$feeetc;
											$xccode=strtok(",");
										}
							}
							

							

					}
					
					/**************************************************************************/
					if($feeval==-1){
						$feesta=-1;
					}else{
						$resit="";
						$paydt="";
						$xresitno="";
						//$sql="select * from feepay where stu_uid='$FFUID' and year='$FFYEAR' and fee='$feename'";
						$sql="select feepay.*,feetrans.paydate from feepay INNER JOIN feetrans ON feepay.tid=feetrans.id where feepay.stu_uid='$FFUID' and feepay.year='$FFYEAR' and feepay.fee='$feename'";
						$res2=mysql_query($sql) or die("$sql - query failed:".mysql_error());
						if($row2=mysql_fetch_assoc($res2)){
							$feeval=$row2['rm'];
							$resit=$row2['tid'];
							$xresitno=$row2['resitno'];
							$paydt=$row2['paydate'];
							$feesta=1;
						}elseif($feeval==0){
								$feesta=2;
						}else{
								$feesta=0;
						}
					}
					//echo "$feename = $feeval<br>";
					$sql="select * from feestu where uid='$FFUID' and ses='$FFYEAR' and fee='$feename'";
					$res_feestux=mysql_query($sql) or die("$sql - query failed:".mysql_error());
					if(!$row_feestux=mysql_fetch_assoc($res_feestux)){
						$rm=$feeval;
						$dis=0;
						$disval=0;
						$sql="insert into feestu(dtm,sid,uid,ses,fee,typ,val,sta,des,rno,resitno,pdt,adm,rm,dis,disval)values(now(),$FFSID,'$FFUID','$FFYEAR',
						'$feename',$feetype,$feeval,$feesta,'$fdes','$resit','$xresitno','$paydt','$username','$rm','$dis','$disval')";
						mysql_query($sql) or die("$sql - query failed:".mysql_error());
					}
				}
		}// while 0
























?>