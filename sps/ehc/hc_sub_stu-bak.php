<?php
//29/03/10 4.1.0 - multi print
//25/05/10 4.2.0 - change dir, view cls ses,sub type 0
$vmod="v5.0.0";
$vdate="100909";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU');
$adm=$_SESSION['username'];
$isprint=$_REQUEST['isprint'];

	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
	if($sid!=0){
		$sql="select * from sch where id=$sid";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=$row['name'];
		$ssname=$row['sname'];
		$simg=$row['img'];
		$namatahap=$row['clevel'];
     	mysql_free_result($res);					  
	}else
		$namatahap="Level";

	$clslevel=$_REQUEST['clslevel'];
		if($clslevel=="")
			$clslevel="0";
		else
			$sqlclslevel="and ses_stu.cls_level=$clslevel";
			
	$clscode=$_REQUEST['clscode'];
	if($clscode!=""){
			$sqlclscode="and ses_stu.cls_code='$clscode'";
			$sql="select * from cls where sch_id=$sid and code='$clscode'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=$row['name'];
			$clslevel=$row['level'];
	}
	$year=$_REQUEST['year'];
	if($year=="")
		$year=date('Y');
		
	$sql="select * from ses_cls where year='$year' and cls_code='$clscode' and sch_id=$sid ";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$row2=mysql_fetch_assoc($res2);
	$gurukelas=$row2['usr_name'];
		
	$subcode=$_REQUEST['subcode'];
	if($subcode!=""){
		$sql="select * from sub where code='$subcode' and sch_id=$sid";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $subname=$row['name'];
		$grading=$row['grading'];
		$i=1;
		$sql="select * from grading where name='$grading' order by val desc";
		$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row3=mysql_fetch_assoc($res3)){
			$xg=$row3['grade'];
			$ginfo[$xg]['total']=0;
			$ginfo[$xg]['isfail']=$row3['sta'];
			$xgp=$row3['gp'];
			$ginfo[$xg]['gpoint']=$xgp;//$i++
		}
	
		$sql="select * from ses_sub where sub_code='$subcode' and cls_code='$clscode' and year=$year and sch_id=$sid";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $subguru=stripslashes(strtoupper($row['usr_name']));
		$gid=$row['usr_uid'];
	}

	$exam=$_REQUEST['exam'];
	$lastyear=$_REQUEST['lastyear'];
	if($lastyear=="")
		$lastyear=date('Y');
		
	$lastexam=$_REQUEST['lastexam'];
	if($lastexam!=""){
			$sql="select * from type where grp='exam' and code='$lastexam'";
            $res=mysql_query($sql)or die("$sql query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $lastexamname=$row['prm'];
	}
	$operation=$_REQUEST['operation'];
if($operation=="save"){
		$autootr=$_POST['autootr'];
		$markah=$_POST['markah'];
		$totalth=0;
		$totalfail=0;
		$totalpoint=0;
		for ($i=0; $i<count($markah); $i++) {
				$data=$markah[$i];
				list($point,$gred,$xuid)=split('[/.|]',$data);
				$val=0;
				if(is_numeric($point)){
						if(($point=="TT")||($point=="-1"))
							$val=-1;
						elseif(($point=="TH")||($point=="-2")){
							$val=-2;
							$totalth++;
						}else{
							$val=$point;
							$total_gp_sub++;
							$total_gp=$total_gp+$ginfo[$gred]['gpoint'];
							//echo "DDDDDDDDDD:$total_gp:$gred:".$ginfo[$gred]['gpoint']."<br>";
							
						}
				}else{
						if(($point=="TT")||($point=="-1"))
							$val=-1;
						if(($point=="TH")||($point=="-2")){
							$val=-2;
							$totalth++;
						}
				}
				
				if($val>=0)
					$totalpoint=$totalpoint+$val;
				if($val!=-1)
					$totalstu++;
				$ginfo[$gred]['total']++;
				if($ginfo[$gred]['isfail'])
					$totalfail++;
				
				
				$pp=$exam."_m";
				$gg=$exam."_g";
				$sql="select count(*) from hc_sub where uid='$xuid' and subcode='$subcode' and year=$year";
				$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
				$row=mysql_fetch_row($res);
				$found=$row[0];
				if($found){
					$sql="update hc_sub set $pp=$val, $gg='$gred', gid='$gid',nt=etr_m-tov_m where uid='$xuid' and subcode='$subcode' and year=$year";
					$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
				}else{
					$sql="insert into hc_sub (sid,uid,gid,year,$pp,$gg,nt,subcode,clscode,clslevel,adm,ts)values($sid,'$xuid','$gid',$year,$val,'$gred',etr_m-tov_m,'$subcode','$clscode',$clslevel,'$adm',now())";
					$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
				}
				if($autootr){
					$sql="select nt,tov_m from hc_sub where uid='$xuid' and subcode='$subcode' and year=$year";
					$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
					$row=mysql_fetch_row($res);
					$nt=$row[0];
					$tov=$row[1];
					if($nt>=0){
						$nt=$nt/4;
						$t1=$tov+$nt;$t2=$tov+$nt*2;$t3=$tov+$nt*3;
						$sql="select * from grading where name='$grading' and point<=$t1 order by val desc limit 1";
						$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
						$row3=mysql_fetch_assoc($res3);
						$g1=$row3['grade'];
						
						$sql="select * from grading where name='$grading' and point<=$t2 order by val desc limit 1";
						$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
						$row3=mysql_fetch_assoc($res3);
						$g2=$row3['grade'];
						
						$sql="select * from grading where name='$grading' and point<=$t3 order by val desc limit 1";
						$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
						$row3=mysql_fetch_assoc($res3);
						$g3=$row3['grade'];
							
						$sql="update hc_sub set otr1_m=$t1, otr1_g='$g1', otr2_m=$t2, otr2_g='$g2', otr3_m=$t3, otr3_g='$g3' where uid='$xuid' and subcode='$subcode' and year=$year";
						$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
					}
				}
						
				/*********************update student*******************/
				$j=0;
				$sql="select * from grading where name='$grading' and val>-1 order by val desc";
				$res3=mysql_query($sql)or die("$sql failed:".mysql_error()); $j=1;
				while($row3=mysql_fetch_assoc($res3)){
							$xg=$row3['grade'];
							$xgp=$row3['gp'];
							$arr_grade[$xg]=0;
							$arr_gpoint[$xg]=$xgp;
							//echo "DDDD:$xgp<br>";
				}
				$totalsub=0;$totalallsub=0;
				$gred_purata="";$jum_gp=0;$jum_gpsubjek=0;$gpk=0;$min=100;$max=0;$totalgp=0;$totalcredit=0;
				$sql="delete from hc_rep_stu where uid='$xuid' and sid=$sid and year='$year' and exam='$exam'";
				$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					
				$sql4="select $pp,$gg from hc_sub where uid='$xuid' and year='$year'";
				$res4=mysql_query($sql4)or die("query failed:".mysql_error());
				while($row4=mysql_fetch_row($res4)){
					$point=$row4[0];
					$gred=$row4[1];
					//$totalallsub++;
					
							if(($gred=="TT")||($gred=="TH"))
								$point=0;
				
							if($si_exam_use_th){
									if($gred!="TT"){
										$totalsub++;
									}
							}else{
									if(($gred!="TT")&&($gred!="TH")){
										$totalsub++;
									}
							}
							
							if(($gred!="")&&($gred!="TH")&&($gred!="TT")){
									$arr_grade[$gred]++;
									$jum_gpsubjek++;
									$jum_gp=$jum_gp+$arr_gpoint[$gred];
									//$ffff=$ffff+$arr_gpoint[$gred];
									//echo "SSS:$jum_gp=$jum_gp+$arr_gpoint[$gred]:$ffff<br>";
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
				if($jum_gpsubjek>0)
					$gpk=$jum_gp/$jum_gpsubjek;

				//echo "XXXXX: $exam:$gpk<br>";
				$sql="select * from grading where name='$grading' and val>-1 order by val desc";
				$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
				$xtotalgred="";
				while($row3=mysql_fetch_assoc($res3)){
						$xg=$row3['grade'];
						if(	$arr_grade[$xg]>0)
							$xtotalgred=$xtotalgred.$arr_grade[$xg]."$xg";
				}
				$sql="insert into hc_rep_stu (sid,year,uid,clscode,clslevel,exam,totalsub,totalpoint,totalfail,totalth,totalgred,avg,gp,adm,ts)
				values($sid,'$year','$xuid','$clscode','$clslevel','$exam',$totalsub,$totalpoint,$totalfail,$totalth,'$xtotalgred','$avg',$gpk,'$username',now())";
				$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				/****************** end update student ************/
		}

		$sql="delete from hc_sub_rep where sid=$sid and year=$year and clscode='$clscode' and subcode='$subcode' and exam='$exam'";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$avg=round($totalpoint/$totalstu,2);
		$gp=round($total_gp/$total_gp_sub,2);
		//echo "XXXX:$gp=round($total_gp/$total_gp_sub,2)<br>";
		$sql="insert into hc_sub_rep (sid,year,exam,clscode,clslevel,subcode,total_stu,total_point,total_fail,total_th,avg,gp,gid,adm,ts)
				values ($sid,$year,'$exam','$clscode','$clslevel','$subcode',$totalstu,$totalpoint,$totalfail,$totalth,$avg,$gp,'$gid','$adm',now())";
		$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
		
		$i=1;
		$sql="select * from grading where name='$grading' and val>-1 order by val desc";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$g=$row['grade'];
			$tg=$ginfo[$g]['total'];
			$sql="update hc_sub_rep set total_g$i=$tg where sid=$sid and year=$year and clscode='$clscode' and subcode='$subcode' and exam='$exam'";
			mysql_query($sql)or die("$sql failed:".mysql_error());
			$i++;
			//echo "$sql<br>";
		}	

		
		
		
		
		
		
		
		if($autootr){
		$totalpointsub=0;
		for($otrnumber=1;$otrnumber<4;$otrnumber++){
				$totalth=0;$total_gp_sub=0;$total_gp=0;$totalpoint=0;$totalstu=0;$totalfail=0;$jum_gpsubjek=0;
				$totalallsub=0;$jum_gp=0;$totalpointsub=0;

				$j=0;
				$sql="select * from grading where name='$grading' and val>-1 order by val desc";
				$res3=mysql_query($sql)or die("$sql failed:".mysql_error()); $j=1;
				while($row3=mysql_fetch_assoc($res3)){
									$xg=$row3['grade'];
									$arr_grade[$xg]=0;
									$xgp=$row3['gp'];
									$arr_gpoint[$xg]=$xgp;
									//$arr_gpoint[$xg]=$j++;
									//echo "SSS:$xgp<br>";
									$ginfo[$xg]['total']=0;
				}

						
				$sql="select uid,otr1_m,otr1_g from hc_sub where subcode='$subcode' and year=$year";
				$res33=mysql_query($sql)or die("$sql failed:".mysql_error());
				while($row33=mysql_fetch_assoc($res33)){
						$xuid=$row33['uid'];
						$point=$row33['otr1_m'];
						$gred=$row33['otr1_g'];
						$val=0;
						if(is_numeric($point)){
							if(($point=="TT")||($point=="-1"))
								$val=-1;
							if(($point=="TH")||($point=="-2")){
								$val=-2;
								$totalth++;
							}else{
								$val=$point;
								$total_gp_sub++;
								//$total_gp=$total_gp+$ginfo[$gred]['gpoint'];
								$total_gp=$total_gp+$arr_gpoint[$gred];
							}
						}else{
							if(($point=="TT")||($point=="-1"))
								$val=-1;
							if(($point=="TH")||($point=="-2")){
								$val=-2;
								$totalth++;
							}
						}
						
						//if($val>=0)
						//	$totalpoint=$totalpoint+$val;
						if($val!=-1)
							$totalstu++;
						$ginfo[$gred]['total']++;
						if($ginfo[$gred]['isfail'])
							$totalfail++;
						
						$exam="OTR".$otrnumber;
						$pp=$exam."_m";
						$gg=$exam."_g";
						/*
						$sql="select count(*) from hc_sub where uid='$xuid' and subcode='$subcode' and year=$year";
						$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
						$row=mysql_fetch_row($res);
						$found=$row[0];
						if($found){
							$sql="update hc_sub set $pp=$val, $gg='$gred', gid='$gid' where uid='$xuid' and subcode='$subcode' and year=$year";
							$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
						}else{
							$sql="insert into hc_sub (sid,uid,gid,year,$pp,$gg,subcode,clscode,clslevel,adm,ts)values($sid,'$xuid','$gid',$year,$val,'$gred','$subcode','$clscode',$clslevel,'$adm',now())";
							$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
						}
						*/
						
						/*********************update student*******************/
						
						$totalsub=0;
						$gred_purata="";$jum_gp=0;$jum_gpsubjek=0;$gpk=0;$min=100;$max=0;
						$sql="delete from hc_rep_stu where uid='$xuid' and sid=$sid and year='$year' and exam='$exam'";
						$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
							
						$sql4="select $pp,$gg from hc_sub where uid='$xuid' and year='$year'";
						$res4=mysql_query($sql4)or die("query failed:".mysql_error());
						while($row4=mysql_fetch_row($res4)){
								$point=$row4[0];
								$gred=$row4[1];
								$totalallsub++;
								if(($gred=="TT")||($gred=="TH"))
									$point=0;
					
								if($si_exam_use_th){
										if($gred!="TT"){
											//$totalpoint=$totalpoint+$point;
											$totalsub++;
										}
								}else{
										if(($gred!="TT")&&($gred!="TH")){
											//$totalpoint=$totalpoint+$point;
											$totalsub++;
										}
								}
								
								if(($gred!="")&&($gred!="TH")&&($gred!="TT")){
									$arr_grade[$gred]++;
									$jum_gpsubjek++;
									$jum_gp=$jum_gp+$arr_gpoint[$gred];
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
						if($jum_gpsubjek>0)
							$gpk=$jum_gp/$jum_gpsubjek;
		
						
						$sql="select * from grading where name='$grading' and val>-1 order by val desc";
						$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
						$xtotalgred="";
						while($row3=mysql_fetch_assoc($res3)){
								$xg=$row3['grade'];
								if(	$arr_grade[$xg]>0)
									$xtotalgred=$xtotalgred.$arr_grade[$xg]."$xg";
						}
						echo "$totalpoint:";
						$sql="insert into hc_rep_stu (sid,year,uid,clscode,clslevel,exam,totalsub,totalpoint,totalfail,totalth,totalgred,avg,gp,adm,ts)
						values($sid,'$year','$xuid','$clscode','$clslevel','$exam',$totalsub,$totalpoint,$totalfail,$totalth,'$xtotalgred','$avg',$gpk,'$username',now())";
						$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						/****************** end update student ************/
						//$totalpointsub=$totalpointsub+$totalpoint;
						//$totalpoint=0;
				}
				
				$sql="delete from hc_sub_rep where sid=$sid and year=$year and clscode='$clscode' and subcode='$subcode' and exam='$exam'";
				$res=mysql_query($sql)or die("$sql failed:".mysql_error());
				$avg=round($totalpoint/$totalstu,2);
				$gp=round($total_gp/$total_gp_sub,2);
				//echo "JJJJJ:$gp=round($total_gp/$total_gp_sub,2);<br>";
				$sql="insert into hc_sub_rep (sid,year,exam,clscode,clslevel,subcode,total_stu,total_point,total_fail,total_th,avg,gp,gid,adm,ts)
						values ($sid,$year,'$exam','$clscode','$clslevel','$subcode',$totalstu,$totalpoint,$totalfail,$totalth,$avg,$gp,'$gid','$adm',now())";
				$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
				//echo "$sql<br>";
				$i=1;
				$sql="select * from grading where name='$grading' and val>-1 order by val desc";
				$res=mysql_query($sql)or die("$sql failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
					$g=$row['grade'];
					$tg=$ginfo[$g]['total'];
					$sql="update hc_sub_rep set total_g$i=$tg where sid=$sid and year=$year and clscode='$clscode' and subcode='$subcode' and exam='$exam'";
					mysql_query($sql)or die("$sql failed:".mysql_error());
					$i++;
					//echo "$sql<br>";
				}	
		}
	}
	
		$updated="<font color=blue>&lt;SUCCESSFULLY UPDATED&gt</font>";
		$exam="";
}
	
	//echo "Total:$totalpoint sub:$totalsub fail:$totalfail";
	
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
		$sqlsort="order by $sort $order, name";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<!-- SETTING GRAY BOX -->
<script type="text/javascript"> var GB_ROOT_DIR = "<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/"; </script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_scripts.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />
<!-- apai remark
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/static_files/help.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/static_files/help.css" rel="stylesheet" type="text/css" media="all" />
-->
<script language="JavaScript">
function processform(operation){
		if(document.myform.sid.value==""){
			alert("Please select school..");
			document.myform.sid.focus();
			return;
		}
		document.myform.submit();
}

function check_grade(e,idx){
	var str=e.value
	var arr=str.split("|")
	p=parseInt(arr[0]);
	c=arr[1];		
	ele="g"+idx;
	document.myform.elements[ele].value=c;
}
function process_myform(exam){
		if(document.myform.sid.value==""){
			alert("Please select school..");
			document.myform.sid.focus();
			return;
		}
		if(document.myform.clscode.value==""){
			alert("Please update by class. Please select class..");
			document.myform.clscode.focus();
			return;
		}
	if(exam==""){
		alert('Please select exam..');
		return;
	}
	ret = confirm("Save the records??");
    if (ret == true){
    	document.myform.operation.value='save';
        document.myform.submit();
    }
}
function clear_newwin(){
	document.myform.action="";
	document.myform.target="";
}
var newwin = "";
function newwindowww(page) 
{ 
	var cflag=false;
	if(document.myform.sid.value=="0"){
			alert("Please select school");
			document.myform.sid.focus();
			return;
	}
	if(document.myform.clscode.value==""){
			alert("Please select class");
			document.myform.clscode.focus();
			return;
	}
	if(document.myform.subcode.value==""){
			alert("Please select subject");
			document.myform.subcode.focus();
			return;
	}
	for (var i=0;i<document.myform.elements.length;i++){
                var e=document.myform.elements[i];
                if ((e.id=='stuid')){
                        if(e.checked==true)
                               cflag=true;
    
                }
    }
	if(!cflag){
			alert('Please checked the item to show');
			return;
	}
		
	document.myform.action=page;
	document.myform.target="newwindow";
    newwin = window.open("","newwindow","HEIGHT=600,WIDTH=1000,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar");
	var a = window.setTimeout("document.myform.submit();",500);
    newwin.focus();
}
</script>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>
<body>

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="../ehc/hc_sub_stu">
	<input type="hidden" name="curr">
	<input type="hidden" name="operation">
	<input type="hidden" name="sort" value="<?php echo $sort;?>">
	<input type="hidden" name="order" value="<?php echo $order;?>">
	<input type="hidden" name="isprint" value="<?php echo $isprint;?>">
	<input type="hidden" name="exam" value="<?php echo $exam;?>">
	
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="process_myform('<?php echo $exam;?>');" id="mymenuitem"><img src="../img/save.png"><br>Save</a>
<a href="#" onClick="window.print()"id="mymenuitem"> <img src="../img/printer.png"><br>Print</a>
<a href="#" onClick="clear_newwin();document.myform.exam.value='';document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="newwindowww('../ehc/hc_stuslip.php')" id="mymenuitem"><img src="../img/letters.png"><br>Print&nbsp;Slip</a>
</div> <!-- end mymenu -->

<div align="right">
	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br>

      <select name="year" id="year" onChange="clear_newwin();document.myform.exam.value='';document.myform.submit();">
        <?php
            echo "<option value=$year>$lg_session $year</option>";
			$sql="select * from type where grp='session' and prm!='$year' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        echo "<option value=\"$s\">$lg_session $s</option>";
            }
            mysql_free_result($res);					  
?>
      </select>
	  <select name="sid" id="sid" onchange="clear_newwin();document.myform.exam.value='';document.myform.clscode[0].value='';document.myform.subcode[0].value='';document.myform.submit();">
        <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select $lg_school-</option>";
			else
                echo "<option value=$sid>$ssname</option>";
				
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['sname'];
							$t=$row['id'];
							
							echo "<option value=$t>$s</option>";
				}
				mysql_free_result($res);
			}							  
			
?>
	</select>
    	<select name="clslevel" onchange="document.myform.clscode[0].value=''; document.myform.submit();">
<?php    
		if($clslevel=="0")
            	echo "<option value=\"0\">- $lg_select $lg_level -</option>";
		else
			echo "<option value=$clslevel>$namatahap $clslevel</option>";
			$sql="select * from type where grp='classlevel' and sid='$sid' and prm!='$clslevel' order by prm";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
                        echo "<option value=$s>$namatahap $s</option>";
            }

?>		
  		</select>
	  <select name="clscode" id="clscode" onchange="clear_newwin();document.myform.exam.value='';document.myform.submit();">
        <?php	
      				if($clscode!="")
						echo "<option value=\"$clscode\">$clsname</option>";
					echo "<option value=\"\">- $lg_all $lg_class -</option>";
					$sql="select * from ses_cls where sch_id=$sid and cls_code!='$clscode' and year=$year order by cls_level";
            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=stripslashes($row['cls_name']);
						$a=$row['cls_code'];
                        echo "<option value=\"$a\">$b</option>";
            		}
            		
			?>
      </select>
      <select name="subcode" onchange="clear_newwin();document.myform.exam.value='';document.myform.submit();">
        <?php	
      				if($subcode=="")
						echo "<option value=\"\">- $lg_select $lg_subject -</option>";
					else
						echo "<option value=\"$subcode\">$subname</option>";
					
					$sql="select * from sub where sch_id=$sid and level=$clslevel and code!='$subcode' and grptype=0 order by name";
            		$res=mysql_query($sql)or die("$sql - failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $a=$row['name'];
						$b=$row['code'];
                        echo "<option value=\"$b\">$a</option>";
            		}
            		
			?>
      </select>

      <input type="button" name="Submit" value="View" onClick="clear_newwin();processform()" >
	
</div><!-- end viewpanel -->
</div><!-- end mypanel -->

<div id="story">
<div id="mytitlebg"><?php echo strtoupper($lg_headcount_class_report);?> <?php echo $updated;?></div>
<table width="100%" id="mytitle">
  <tr>
    <td width="50%">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="20%"><?php echo strtoupper($lg_school);?></td>
			<td width="1%">:</td>
			<td><?php echo strtoupper($sname);?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_class);?></td>
			<td>:</td>
			<td><?php echo strtoupper("$clsname / $year");?></td>
		  </tr>
		</table>
	</td>
    <td width="50%">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="20%"><?php echo strtoupper($lg_subject);?></td>
			<td width="1%">:</td>
			<td><?php echo strtoupper($subname);?></td>
		  </tr>
		 <tr>
			<td><?php echo strtoupper($lg_teacher);?></td>
			<td>:</td>
			<td><?php echo strtoupper($subguru);?></td>
		  </tr>
		</table>
	
	</td>
  </tr>
</table>

<div <?php if($exam=="") echo "style=\"display:none\"";?> >

	<div id="mytitle">
		<div id="myclick" onClick="document.myform.exam.value='';document.myform.submit();" onMouseOver="showhide('img2','img1');" onMouseOut="showhide('img1','img2');">
			<img src="../img/icon_remove.gif" id="img1" style="float:left;display:block;padding:0px 2px 0px 2px;">
			<img src="../img/icon_remove_hover.gif" id="img2" style="float:left;display:none;padding:0px 2px 0px 2px;">
		AUTO SETTING - <?php echo $exam;?>
		</div>&nbsp;
	</div>

				<select name="lastyear" onchange="clear_newwin();document.myform.submit();" >

				<?php
					echo "<option value=$lastyear>$lastyear</option>";
					$sql="select * from type where grp='session' and prm!='$lastyear' and prm>=$year-1 order by val desc";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
						$s=$row['prm'];
						$v=$row['val'];
						echo "<option value=\"$s\">$s</option>";
					}
					mysql_free_result($res);					  
				?>
          		</select>
				<select name="lastexam" onchange="clear_newwin();document.myform.submit();" >
				<?php	
      				if($lastexam=="")
						echo "<option value=\"\">- $lg_select $lg_exam -</option>";
					else
						echo "<option value=\"$lastexam\">$lastexamname</option>";
					//$sql="select * from type where grp='exam' and code!='$lastexam' and (sid=0 or sid=$sid) order by idx";
					$sql="select distinct(prm),code from type where grp='exam' and code!='$lastexam' and (sid=0 or sid=$sid) order by idx";
            		$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $a=$row['prm'];
						$b=$row['code'];
                        echo "<option value=\"$b\">$a</option>";
            		}
            		mysql_free_result($res);	
					
			?>
    </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	SET DEFAULT OTR <input type="checkbox" name="autootr" value="1">

</div>        		
<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td id="mytabletitle" width="1%" align="center" rowspan="2" class="printhidden"><input type=checkbox name=checkall value="0" onClick="check(1)"></td>
    <td id="mytabletitle" width="1%" align="center" rowspan="2"><?php echo strtoupper($lg_no);?></td>
    <td id="mytabletitle" width="2%" align="center" rowspan="2"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_matric);?></a></td>
	<td id="mytabletitle" width="1%" align="center" rowspan="2"><a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_mf);?></a></td>	
    <td id="mytabletitle" width="15%" rowspan="2">&nbsp;<a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_name);?></a></td>
	<td id="mytabletitle" width="4%" align="center" rowspan="2"><?php echo strtoupper($lg_value_added);?></td>
	<td id="mytabletitle" width="4%" align="center" colspan="2"><a href="p.php?p=../ehc/hc_sub_stu<?php echo "&sid=$sid&year=$year&clslevel=$clslevel&clscode=$clscode&subcode=$subcode&gid=$gid&isprint=$isprint&exam=TOV";?>"><img src="../img/edit12.png" class="printhidden">TOV</a></td>
	<td id="mytabletitle" width="4%" align="center" colspan="2"><a href="p.php?p=../ehc/hc_sub_stu<?php echo "&sid=$sid&year=$year&clslevel=$clslevel&clscode=$clscode&subcode=$subcode&gid=$gid&isprint=$isprint&exam=OTR1";?>"><img src="../img/edit12.png" class="printhidden">OTR1</a></td>
	<td id="mytabletitle" width="4%" align="center" colspan="2"><a href="p.php?p=../ehc/hc_sub_stu<?php echo "&sid=$sid&year=$year&clslevel=$clslevel&clscode=$clscode&subcode=$subcode&gid=$gid&isprint=$isprint&exam=AR1";?>"><img src="../img/edit12.png" class="printhidden">AR1</a></td>
	<td id="mytabletitle" width="4%" align="center" colspan="2"><a href="p.php?p=../ehc/hc_sub_stu<?php echo "&sid=$sid&year=$year&clslevel=$clslevel&clscode=$clscode&subcode=$subcode&gid=$gid&isprint=$isprint&exam=OTR2";?>"><img src="../img/edit12.png" class="printhidden">OTR2</a></td>
	<td id="mytabletitle" width="4%" align="center" colspan="2"><a href="p.php?p=../ehc/hc_sub_stu<?php echo "&sid=$sid&year=$year&clslevel=$clslevel&clscode=$clscode&subcode=$subcode&gid=$gid&isprint=$isprint&exam=AR2";?>"><img src="../img/edit12.png" class="printhidden">AR2</a></td>
	<td id="mytabletitle" width="4%" align="center" colspan="2"><a href="p.php?p=../ehc/hc_sub_stu<?php echo "&sid=$sid&year=$year&clslevel=$clslevel&clscode=$clscode&subcode=$subcode&gid=$gid&isprint=$isprint&exam=OTR3";?>"><img src="../img/edit12.png" class="printhidden">OTR3</a></td>
	<td id="mytabletitle" width="4%" align="center" colspan="2"><a href="p.php?p=../ehc/hc_sub_stu<?php echo "&sid=$sid&year=$year&clslevel=$clslevel&clscode=$clscode&subcode=$subcode&gid=$gid&isprint=$isprint&exam=AR3";?>"><img src="../img/edit12.png" class="printhidden">AR3</a></td>
	<td id="mytabletitle" width="4%" align="center" colspan="2"><a href="p.php?p=../ehc/hc_sub_stu<?php echo "&sid=$sid&year=$year&clslevel=$clslevel&clscode=$clscode&subcode=$subcode&gid=$gid&isprint=$isprint&exam=ETR";?>"><img src="../img/edit12.png" class="printhidden">ETR</a></td>
	<td id="mytabletitle" width="4%" align="center" colspan="2"><a href="p.php?p=../ehc/hc_sub_stu<?php echo "&sid=$sid&year=$year&clslevel=$clslevel&clscode=$clscode&subcode=$subcode&gid=$gid&isprint=$isprint&exam=AR4";?>"><img src="../img/edit12.png" class="printhidden">AR4</a></td>
  </tr>
  <tr>
  	<td id="mytabletitle" align="center" width="2%">M</td>
	<td id="mytabletitle" align="center" width="2%">G</td>
	<td id="mytabletitle" align="center" width="2%">M</td>
	<td id="mytabletitle" align="center" width="2%">G</td>
	<td id="mytabletitle" align="center" width="2%">M</td>
	<td id="mytabletitle" align="center" width="2%">G</td>
	<td id="mytabletitle" align="center" width="2%">M</td>
	<td id="mytabletitle" align="center" width="2%">G</td>
	<td id="mytabletitle" align="center" width="2%">M</td>
	<td id="mytabletitle" align="center" width="2%">G</td>
	<td id="mytabletitle" align="center" width="2%">M</td>
	<td id="mytabletitle" align="center" width="2%">G</td>
	<td id="mytabletitle" align="center" width="2%">M</td>
	<td id="mytabletitle" align="center" width="2%">G</td>
	<td id="mytabletitle" align="center" width="2%">M</td>
	<td id="mytabletitle" align="center" width="2%">G</td>
	<td id="mytabletitle" align="center" width="2%">M</td>
	<td id="mytabletitle" align="center" width="2%">G</td>
  </tr>
<?php 
//if($clscode!=""){
	$sql="select stu.name,stu.uid,stu.sex,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid and year='$year' $sqlclscode $sqlclslevel $sqlstatus $sqlsort";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$name=strtoupper(stripslashes($row['name']));
		$sex=$row['sex'];
		$sex=$lg_sexmf[$sex];
		if(($q++%2)==0)
			$bg=$bglyellow;
		else
			$bg="";
		$bg="";
?>
   	<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';" >
  	<td id="myborder" align="center" class="printhidden"><input type=checkbox name=stu[] id="stuid" value="<?php echo "$uid";?>" onClick="check(0)"></td>
	<td id="myborder" align="center"><?php echo $q?></td>
    <td id="myborder" align="center"><?php echo $uid?></td>
	<td id="myborder" align="center"><?php echo $sex;?></td>
	<td id="myborder"><a href="../ehc/hc_stu.php?uid=<?php echo "$uid&year=$year";?>" target="_blank" onClick="return GB_showPage('<?php echo addslashes($name);?>',this.href)"><?php echo "$name";?></a></td>
	<?php
		$sql="select * from hc_sub where uid='$uid' and subcode='$subcode' and year='$year'";
		$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
  		$row2=mysql_fetch_assoc($res2);
			$rep['tov_m']['total_point']=$rep['tov_m']['total_point']+$row2['tov_m'];
			$rep['otr1_m']['total_point']=$rep['otr1_m']['total_point']+$row2['otr1_m'];
			$rep['otr2_m']['total_point']=$rep['otr2_m']['total_point']+$row2['otr2_m'];
			$rep['otr3_m']['total_point']=$rep['otr3_m']['total_point']+$row2['otr3_m'];
			$rep['ar1_m']['total_point']=$rep['ar1_m']['total_point']+$row2['ar1_m'];
			$rep['ar2_m']['total_point']=$rep['ar2_m']['total_point']+$row2['ar2_m'];
			$rep['ar3_m']['total_point']=$rep['ar3_m']['total_point']+$row2['ar3_m'];
			$rep['etr_m']['total_point']=$rep['etr_m']['total_point']+$row2['etr_m'];
			$rep['ar4_m']['total_point']=$rep['ar4_m']['total_point']+$row2['ar4_m'];
	?>
	<td id="myborder" align="center" bgcolor="#FAFAFA"><strong><?php echo $row2['nt'];?></strong></td>
	<?php if($exam=="TOV") include ('../ehc/hc_reg.php');else{?>
	<td id="myborder" align="center" bgcolor="#FAFAFA" style="border-right:none;"><?php if($row2['tov_m']>=0) echo $row2['tov_m']; else echo "0";?></td>
	<td id="myborder" align="center" bgcolor="#FAFAFA" style="border-left:none;"><?php echo $row2['tov_g'];?></td>
	<?php } if($exam=="OTR1") include ('../ehc/hc_reg.php');else{?>
	<td id="myborder" align="center" style="border-right:none;"><?php if($row2['otr1_m']>=0) echo $row2['otr1_m']; else echo "0";?></td>
	<td id="myborder" align="center" style="border-left:none;"><?php echo $row2['otr1_g'];?></td>
	<?php } if($exam=="AR1") include ('../ehc/hc_reg.php');else{?>
	<td id="myborder" align="center" style="border-right:none;"><strong><?php if($row2['ar1_m']>=0) echo $row2['ar1_m']; else echo "0";?></strong></td>
	<td id="myborder" align="center" style="border-left:none;"><strong><?php echo $row2['ar1_g'];?></strong></td>
	<?php } if($exam=="OTR2") include ('../ehc/hc_reg.php');else{?>
	<td id="myborder" align="center" style="border-right:none;" bgcolor="#FAFAFA"><?php if($row2['otr2_m']>=0) echo $row2['otr2_m']; else echo "0";?></td>
	<td id="myborder" align="center" style="border-left:none;" bgcolor="#FAFAFA"><?php echo $row2['otr2_g'];?></td>
	<?php } if($exam=="AR2") include ('../ehc/hc_reg.php');else{?>
	<td id="myborder" align="center" style="border-right:none;" bgcolor="#FAFAFA"><strong><?php if($row2['ar2_m']>=0) echo $row2['ar2_m']; else echo "0";?></strong></td>
	<td id="myborder" align="center" style="border-left:none;" bgcolor="#FAFAFA"><strong><?php echo $row2['ar2_g'];?></strong></td>
	<?php } if($exam=="OTR3") include ('../ehc/hc_reg.php');else{?>
	<td id="myborder" align="center" style="border-right:none;"><?php if($row2['otr3_m']>=0) echo $row2['otr3_m']; else echo "0";?></td>
	<td id="myborder" align="center" style="border-left:none;"><?php echo $row2['otr3_g'];?></td>
	<?php } if($exam=="AR3") include ('../ehc/hc_reg.php');else{?>
	<td id="myborder" align="center" style="border-right:none;"><strong><?php if($row2['ar3_m']>=0) echo $row2['ar3_m']; else echo "0";?></strong></td>
	<td id="myborder" align="center" style="border-left:none;"><strong><?php echo $row2['ar3_g'];?></strong></td>
	<?php } if($exam=="ETR") include ('../ehc/hc_reg.php');else{?>
	<td id="myborder" align="center" bgcolor="#FAFAFA" style="border-right:none;"><strong><?php if($row2['etr_m']>=0) echo $row2['etr_m']; else echo "0";?></strong></td>
	<td id="myborder" align="center" bgcolor="#FAFAFA" style="border-left:none;"><strong><?php echo $row2['etr_g'];?></strong></td>
	<?php } if($exam=="AR4") include ('../ehc/hc_reg.php');else{?>
	<td id="myborder" align="center" bgcolor="#FAFAFA" style="border-right:none;"><strong><?php if($row2['ar4_m']>=0) echo $row2['ar4_m']; else echo "0";?></strong></td>
	<td id="myborder" align="center" bgcolor="#FAFAFA" style="border-left:none;"><strong><?php echo $row2['ar4_g'];?></strong></td>
	<?php } ?>
	
  </tr>
<?php }//} ?>
<!--  
<tr>
	<td id="mytabletitle" align="center" class="printhidden"></td>
    <td id="mytabletitle" colspan="23" align="center">RUMUSAN PENCAPAIAN SUBJEK</td>
	
</tr>
-->
<?php
	$i=0;
	$sql="select * from type where grp='headcount' order by idx";
	$res2=mysql_query($sql)or die("$sql query failed:".mysql_error());
    while($row2=mysql_fetch_assoc($res2)){
		$ex=$row2['code'];
		$sql="select * from hc_sub_rep where sid=$sid and year=$year and clscode='$clscode' and subcode='$subcode' and exam='$ex'";
		$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
		if($row=mysql_fetch_assoc($res)){
			$total[$i]=$row['total_stu'];
			$xtotalpoint[$i]=$row['total_point'];
			$fail[$i]=$row['total_fail'];
			$th[$i]=$row['total_th'];
			$xavg[$i]=$row['avg'];
			$xxgp[$i]=$row['gp'];
			$totalgred[$i][1]=$row["total_g1"];
			$totalgred[$i][2]=$row["total_g2"];
			$totalgred[$i][3]=$row["total_g3"];
			$totalgred[$i][4]=$row["total_g4"];
			$totalgred[$i][5]=$row["total_g5"];
			$totalgred[$i][6]=$row["total_g6"];
			$totalgred[$i][7]=$row["total_g7"];
			$totalgred[$i][8]=$row["total_g8"];
			$totalgred[$i][9]=$row["total_g9"];
		}
		$i++;
	}

?>
<tr bgcolor="#FAFAFA">
	<td id="myborder" align="center" class="printhidden"></td>
	<td id="myborder" colspan="5" align="right"><?php echo $lg_student;?></td>
<?php for($j=0;$j<$i;$j++){ ?>
    <td id="myborder" colspan="2" align="center"><?php echo $total[$j]?></td>
<?php }?>
</tr>
<tr bgcolor="#FAFAFA">
	<td id="myborder" align="center" class="printhidden"></td>
	<td id="myborder" colspan="5" align="right"><?php echo $lg_total_absence;?></td>
<?php 
	for($j=0;$j<$i;$j++){ 
?>
    <td id="myborder" colspan="2" align="center" <?php echo $bg;?>><?php echo $th[$j]?></td>
<?php }?>
</tr>
<tr bgcolor="#FAFAFA">
	<td id="myborder" align="center" class="printhidden"></td>
	<td id="myborder" colspan="5" align="right"><?php echo $lg_total_mark;?>&nbsp;</td>
    <?php for($j=0;$j<$i;$j++){ ?>
    <td id="myborder" colspan="2" align="center"><?php echo $xtotalpoint[$j]?></td>
<?php }?>
</tr>
<tr bgcolor="#FAFAFA">
	<td id="myborder" align="center" class="printhidden"></td>
	<td id="myborder" colspan="5" align="right"><?php echo $lg_average_mark;?>&nbsp;</td>
<?php for($j=0;$j<$i;$j++){ ?>
    <td id="myborder"  colspan="2" align="center"><?php if($total[$j]>0)echo round($xavg[$j],0);?></td>
<?php }?>
</tr>
<tr bgcolor="#FAFAFA">
	<td id="myborder" align="center" class="printhidden"></td>
	<td id="myborder" colspan="5" align="right">%&nbsp;<?php echo $lg_pass;?>&nbsp;</td>
<?php for($j=0;$j<$i;$j++){ ?>
    <td id="myborder" colspan="2" align="center"><?php if($total[$j]>0) printf("%d",($total[$j]-$fail[$j])/$total[$j]*100);?></td>
<?php }?>
</tr>
<tr bgcolor="#FAFAFA">
	<td id="myborder" align="center" class="printhidden"></td>
	<td id="myborder" colspan="5" align="right">&nbsp;<?php echo $lg_gp_student;?></td>
<?php for($j=0;$j<$i;$j++){ ?>
    <td id="myborder"  colspan="2" align="center"><?php printf("%.02f", $xxgp[$j]);?></td>
<?php }?>
</tr>
<!-- 
<tr>
	<td id="myborder" colspan="5"></td>
    <td id="myborder" align="center"></td>
	<td id="myborder" align="center"></td>
    <td id="myborder" align="center"></td>
	<td id="myborder" align="center"></td>
    <td id="myborder" align="center"></td>
	<td id="myborder" align="center"></td>
    <td id="myborder" align="center"></td>
	<td id="myborder" align="center"></td>
    <td id="myborder" align="center"></td>
	<td id="myborder" align="center"></td>
    <td id="myborder" align="center"></td>
	<td id="myborder" align="center"></td>
    <td id="myborder" align="center"></td>
	<td id="myborder" align="center"></td>
    <td id="myborder" align="center"></td>
	<td id="myborder" align="center"></td>
    <td id="myborder" align="center"></td>
	<td id="myborder" align="center"></td>
</tr>
 -->
<?php
		$y=0;
		$sql="select * from grading where name='$grading' and val>=0 order by val desc";
		$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
		$total_gred=mysql_num_rows($res3);
		while($row3=mysql_fetch_assoc($res3)){
			$g=$row3['grade'];
			$y++;
?>
		<tr bgcolor="#FAFAFA">
		<td id="myborder" align="center" class="printhidden"></td>
		<td id="myborder" colspan="5" align="right"><?php echo $lg_total;?> <?php echo "$g";?>&nbsp;</td>
		<?php for($j=0;$j<$i;$j++){ ?>
		    <td id="myborder" align="center"><?php echo $totalgred[$j][$y];?></td>
			<td id="myborder" align="right"><?php if($total[$j]>0) printf("%d%%",$totalgred[$j][$y]/$total[$j]*100);?></td>
		<?php }?>

		</tr>
<?php } ?>

</table>


</div></div>
</form>

</body>
</html>
