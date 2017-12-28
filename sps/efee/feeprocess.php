<?php
/** version 3 **/
		$sql="select * from type where grp='fee_interface' and (sid=0 or sid='$FFSID')";
		$res=mysql_query($sql) or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
				$prm=$row['prm'];
				$CONFIG[$prm]['val']=$row['val'];
				$CONFIG[$prm]['code']=$row['code'];
				$CONFIG[$prm]['etc']=$row['etc'];
		}
		$sql="select * from stu where uid='$FFUID' and sch_id=$FFSID";
		$resstu=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$rowstu=mysql_fetch_assoc($resstu);
		$ic=$rowstu['ic'];
		$p1ic=$rowstu['p1ic'];	
		$status=$rowstu['status'];
		$ishostel=$rowstu['ishostel'];
		$isxpelajar=$rowstu['isislah'];
		$sex=$rowstu['sex'];
		$iskawasan=$rowstu['iskawasan'];
		$isstaff=$rowstu['isstaff'];
		$isyatim=$rowstu['isyatim'];
		$feehutang=$rowstu['feehutang'];
		$isfeenew=$rowstu['isfeenew'];
		$isnew=$rowstu['isnew'];
		$isfeebulanfree=$rowstu['isfeefree'];
		$isblock=$rowstu['isblock'];
			
				
		$sql="select * from ses_stu where stu_uid='$FFUID' and sch_id=$FFSID and year='$FFYEAR'";
		$resstu2=mysql_query($sql)or die("query failed:".mysql_error());
		if($rowstu2=mysql_fetch_assoc($resstu2)){
				$clslevel=$rowstu2['cls_level'];
				$clscode=$rowstu2['cls_code'];
		}
		
		//CLEAR ALL UNPAID FEE
		$sql="delete from feestu where uid='$FFUID' and sid=$FFSID and ses='$FFYEAR'";
		mysql_query($sql)or die("$sql:query failed:".mysql_error());

		//CHECK THE PAYMENT
		$sql="select feepay.*,feetrans.paydate from feepay INNER JOIN feetrans ON feepay.tid=feetrans.id where feepay.stu_uid='$FFUID' and feepay.sch_id='$FFSID' and feepay.year='$FFYEAR' and feepay.isdelete=0";
		$res2=mysql_query($sql) or die("$sql - query failed:".mysql_error());
		while($row2=mysql_fetch_assoc($res2)){
				$feeval=$row2['rm'];
				$feename=$row2['fee'];
				$rno=$row2['tid'];
				$resitno=$row2['resitno'];
				$pdt=$row2['paydate'];
				$xrm=$row2['xrm'];
				$dis=$row2['dis'];
				$disval=$row2['disval'];
				$feesta=1;
				$feetype=$row2['item_type'];

				$sql="select * from type where grp='yuran' and prm='$feename'";
				$resmon=mysql_query($sql)or die("$sql - query failed:".mysql_error());
				$rowmon=mysql_fetch_assoc($resmon);
				$feemonth=$rowmon['code'];
				$feecode=$rowmon['etc'];
				if($feemonth=="")
					$feemonth=0;
					
			//$sql="select * from feestu where uid='$uid' and ses='$year' and sid=$sid and fee='$fn'";
			//$res=mysql_query($sql) or die("$sql - query failed:".mysql_error());
			//$found=mysql_num_rows($res);
			//if($found>0)
			//		$sql="update feestu set val=$feeval,dis=$dis,disval=$disval,rm=$frm where 
			//				uid='$uid' and ses='$year' and sid=$sid and fee='$fn'";
			//else
				$sql="insert into feestu(dtm,sid,uid,ses,fee,typ,val,sta,des,rno,resitno,pdt,adm,rm,dis,disval,mon,cod)
				values(now(),'$FFSID','$FFUID','$FFYEAR','$feename','$feetype','$feeval','$feesta','$fdes','$rno','$resitno',
				'$pdt','$username','$xrm','$dis','$disval','$feemonth','$feecode')";
				mysql_query($sql) or die("$sql - query failed:".mysql_error());

		}
		
		
		//NOW REPROCESS THE UPAID
		$sql="select feeset.* from feeset INNER JOIN type ON feeset.name=type.prm where feeset.sch_id=$FFSID and year='$FFYEAR' and type.grp='yuran'";
		$resfee=mysql_query($sql)or die("$sql - query failed:".mysql_error());
		while($rowfee=mysql_fetch_assoc($resfee)){
				$feename=$rowfee['name'];
				$feetype=$rowfee['type'];
				
				$sid=$FFSID;$year=$FFYEAR;$uid=$FFUID;
				include($CONFIG['FILE_FORMULA']['etc']);
				

				if($feeval==-1)
						$feesta=-1;
				elseif($feeval==0)
						$feesta=-1;
				else
						$feesta=0;
					
				$resit="";$paydt="";$xresitno="";

				$sql="select * from type where grp='yuran' and prm='$feename'";
				$resmon=mysql_query($sql)or die("$sql - query failed:".mysql_error());
				$rowmon=mysql_fetch_assoc($resmon);
				$feemonth=$rowmon['code'];
				$feecode=$rowmon['etc'];
				if($feemonth=="")
					$feemonth=0;
		
				$sql="select * from feestu where uid='$FFUID' and ses='$FFYEAR' and fee='$feename'";
				$res_feestux=mysql_query($sql) or die("$sql - query failed:".mysql_error());
				if(!$row_feestux=mysql_fetch_assoc($res_feestux)){
						$sql="insert into feestu(dtm,sid,uid,ses,fee,typ,val,sta,des,rno,resitno,pdt,adm,rm,dis,disval,mon,cod)
						values(now(),'$FFSID','$FFUID','$FFYEAR','$feename','$feetype','$feeval','$feesta','$fdes','$resit','$xresitno',
						'$paydt','$username','$feerm','$feedis','$feedisval','$feemonth','$feecode')";
						mysql_query($sql) or die("$sql - query failed:".mysql_error());
						
				}
		}

?>