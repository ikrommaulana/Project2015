
<?php
//110525 - USE CREDIT HOUR
//110619 - patch TT
//110626 - TAK KIRA PERATUS IF CREDIT>0 but NO MARKPOINT
//120621 - update fail
// standard edition

include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN|HEP|HEP-OPERATOR');
$username = $_SESSION['username'];

		$sid=$_POST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
			
		$year=$_POST['year'];
		$usr_uid=$_POST['usr_uid'];
		$clscode=$_POST['clscode'];
		$subcode=$_POST['subcode'];
		$arrmarkah=$_POST['markah'];
		$stuuid=$_POST['stuuid'];
		$exam=$_POST['exam'];
		$sql="select * from type where grp='exam' and code='$exam'";
        $res=mysql_query($sql)or die("$sql failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $examname=$row['prm'];

			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sch_name=$row['name'];
			$sch_id=$row['id'];
            mysql_free_result($res);					  

			$sql="select * from usr where uid='$usr_uid'";
            $res=mysql_query($sql)or die("$sql failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $usrname=$row['name'];
			$usruid=$row['uid'];
			$usrid=$row['id'];
			$usric=$row['ic'];
			$usr_id=$row['id'];
            mysql_free_result($res);					  

			$sql="select * from cls where code='$clscode' and sch_id=$sid";
            $res=mysql_query($sql)or die("$sql failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
			$clslevel=$row['level'];
			$clsname=$row['name'];
            mysql_free_result($res);
			
			$sql="select * from sub where code='$subcode' and level='$clslevel' and sch_id=$sid";
    	    $res=mysql_query($sql)or die("$sql failed:".mysql_error());
	        $row=mysql_fetch_assoc($res);
    	    $grptype=$row['grptype'];
			$grp=$row['grp'];
			$subname=$row['name'];
			$credit=$row['credit'];
			$grading=$row['grading'];
			$kkm=$row['sname'];
			$idx=$row['idx'];
			
			$sql="select type from grading where name='$grading'";
            $res=mysql_query($sql)or die("$sql failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $gradingtype=$row['type']; //0=skala, 1=gred
			
    	   	if($exam=="DDQ")
		   		$grading="GP_DQ";  

/** sorting control **/
	$order=$_POST['order'];
	if($order=="")
		$order="asc";
		
	if($order=="desc")
		$nextdirection="asc";
	else
		$nextdirection="desc";
		
	$sort=$_POST['sort'];
	if($sort=="")
		$sqlsort="order by name $order";
	else
		$sqlsort="order by $sort $order, id desc";


	$q=0;
for ($i=0; $i<count($arrmarkah); $i++) {
					$data=$arrmarkah[$i];
					list($markah,$gred,$uid)=explode("|",$data);
					if($uid==""){
					$uid=$stuuid[$i];
					}
					$sql="select * from stu where uid='$uid'";
            		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
            		$row=mysql_fetch_assoc($res);
					$stu_ic=$row['ic'];
					$stu_uid=$row['uid'];
					$stu_name=$row['name'];
					mysql_free_result($res);

					/*$sqlkkm="select * from exam where stu_uid='$stu_uid' and sub_code='$subcode' and cls_level='$clslevel' and examtype='$exam' and sch_id=$sch_id and year='$year'";
					$reskkm=mysql_query($sqlkkm)or die("$sqlkkm query failed:".mysql_error());
					$rowkkm=mysql_fetch_assoc($reskkm);
					$kkm_asal=$rowkkm['kkm'];
					$idx_asal=$rowkkm['idx'];
					
					if($kkm_asal!=$kkm && $kkm_asal!=""){
					$kkm=$kkm_asal;
					}else{
					$kkm=$kkm;
					}
					
					if($idx_asal!=$idx && $idx_asal!=""){
					$idx=$idx_asal;
					}else{
					$idx=$idx;
					}
					*/
					
					$sql="delete from exam where stu_uid='$stu_uid' and sub_code='$subcode' and cls_level='$clslevel' and examtype='$exam' and sch_id=$sch_id and year='$year'";
					$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
					
					$astu_name=addslashes($stu_name);
					$asubname=addslashes($subname);
					$aclsname=addslashes($clsname);
					$ausrname=addslashes($usrname);
					$val=0;
					if(is_numeric($markah))
						$val=$markah;

							
					$gp=0;
					$isfail=0;
					if($gradingtype==1)
						$sql="select * from grading where name='$grading' and grade='$markah' order by val desc limit 1";
					elseif(!is_numeric($markah))
						$sql="select * from grading where name='$grading' and grade='$markah' order by val desc limit 1";
					else
						$sql="select * from grading where name='$grading' and point<=$val order by val desc limit 1";
					$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
					if($row3=mysql_fetch_assoc($res3)){
						$gp=$row3['gp'];
						$gred=$row3['grade'];
						$isfail=$row3['sta'];
					}
					
					$sql="insert into exam(dt,sch_id,year,cls_name,cls_level,cls_code,
								stu_uid,stu_name,usr_uid,sub_code,sub_name,sub_grp,sub_type,grading,gradingtype,
								point,grade,examtype,adm,ts,val,gp,credit,isfail,kkm,idx) values 
								(now(),'$sch_id','$year','$aclsname','$clslevel','$clscode',
								'$stu_uid','$astu_name','$usruid','$subcode','$asubname','$grp','$grptype','$grading','$gradingtype',
								'$markah','$gred','$exam','$username',now(),'$val','$gp','$credit','$isfail','$kkm','$idx')";
					$res=mysql_query($sql)or die("$sql failed:".mysql_error());

			//update ranking
			if($credit>0){
					$sql="select * from grading where name='$grading' order by val desc";
					$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
					while($row3=mysql_fetch_assoc($res3)){
								$xg=$row3['grade'];
								$arr_grade[$xg]=0;
					}
					
					$totalsub=0;
					$totalallsub=0;
					$totalpoint=0;
					$gred_purata="";
					
					$totalcredit=0;
					$totalgp=0;
					$gpa=0;
					
					$min=100;
					$max=0;
					$sql="delete from examrank where stu_uid='$stu_uid' and sch_id=$sid and year='$year' and exam='$exam' and cls_level='$clslevel';";
					$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						
					$sql4="select * from exam where stu_uid='$stu_uid' and sub_type=0 and cls_level='$clslevel' and year='$year' and examtype='$exam'";
					$res4=mysql_query($sql4)or die("query failed:".mysql_error());
					while($row4=mysql_fetch_assoc($res4)){
								$xpoint=$row4['point'];
								$xgred=$row4['grade'];
								$xgp=$row4['gp'];
								$xcredit=$row4['credit'];
			
								if(!is_numeric($xpoint))
									$xpoint=0;
								
								if($xgred!="TT"){
										if($xcredit>0){
											$arr_grade[$xgred]++;
											if(is_numeric($xpoint)){
													$totalallsub++;
													$totalpoint=$totalpoint+$xpoint;
													$totalsub++;
													if($min>$xpoint)
														$min=$xpoint;
													if($max<$xpoint)
														$max=$xpoint;
											}
											
											if($xgp>=0){
													$totalcredit=$totalcredit+$xcredit;
													$totalgp=$xgp*$xcredit+$totalgp;
											}
										}
								}
							
					}//end while
					if($totalsub>0){
								$avg=$totalpoint/$totalsub;
								$sql="select * from grading where name='$grading' and point<=$avg order by val desc limit 1";
								$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
								$row3=mysql_fetch_assoc($res3);
								$gred_purata=$row3['grade'];
					}else{
								$avg=0;
					}
					if($totalcredit>0)
						$gpa=$totalgp/$totalcredit;

					$sumgred="";
					$sql="select * from grading where name='$grading' and val>=0 order by val desc";
					$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
					while($row3=mysql_fetch_assoc($res3)){
								$xg=$row3['grade'];
								if(	$arr_grade[$xg]>0)
									$sumgred=$sumgred.$arr_grade[$xg]."$xg";
					}
					$xclsname=addslashes($clsname);
					$sql="insert into examrank (dt,sch_id,year,stu_uid,cls_code,cls_name,cls_level,avg,exam,total_sub,avg_gred,gpk,total_credit,total_point,min,max,total_all_sub,total_gred)
					values(now(),$sid,'$year','$stu_uid','$clscode','$xclsname','$clslevel',$avg,'$exam',$totalsub,'$gred_purata',$gpa,$totalcredit,$totalpoint,$min,$max,$totalallsub,'$sumgred')";
					$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
								
					
					$sql="select * from grading where name='$grading' order by val desc";
					$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					$k=0;$j=1;
					while($row2=mysql_fetch_assoc($res2)){
										$p=$row2['grade'];						
										$sql="select count(*) from exam where sch_id=$sid and year='$year' and cls_code='$clscode' and stu_uid='$stu_uid' and grade='$p' and examtype='$exam' and credit=1";
										$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
										$row3=mysql_fetch_row($res3);
										$num=$row3[0];
										$k++;
										$g="g".$k;
										$sql="update examrank set $g=$num where sch_id=$sid and year='$year' and cls_code='$clscode' and stu_uid='$stu_uid' and exam='$exam'";
										$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					}
					
			
			}//grptype=0;
}//for

echo "<script language=\"javascript\">location.href='../examrep/rep_exam_subject_one.php?exam=$exam&year=$year&clscode=$clscode&subcode=$subcode&sid=$sid'</script>";
?>
