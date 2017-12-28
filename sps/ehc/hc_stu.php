<?php
//29/03/10 4.1.0 - gui
$vmod="v5.0.0";
$vdate="100909";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU');
$adm=$_SESSION['username'];

	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
	
	$uid=$_REQUEST['uid'];
	$year=$_REQUEST['year'];
	if($year=="")
		$year=date('Y');
	
	$sql="select * from stu where uid='$uid'";
	$res=mysql_query($sql)or die("$sql failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$sid=$row['sch_id'];
	$stuic=$row['ic'];
	$stuname=stripslashes($row['name']);
	$mentor=$row['mentor'];
	
	$sql="select * from usr where uid='$mentor'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$mentorname=stripslashes($row['name']);

	$sql="select * from sch where id=$sid";
	$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
	$row2=mysql_fetch_assoc($res2);
	$sname=$row2['name'];
	$simg=$row2['img'];
	
	$clslevel=0;
	$clscode=$_REQUEST['clscode'];
	$sql="select * from ses_stu where stu_uid='$uid' and year=$year";
	$res=mysql_query($sql)or die("$sql failed:".mysql_error());
    if($row=mysql_fetch_assoc($res)){
            $clsname=$row['cls_name'];
			$clslevel=$row['cls_level'];
			$clscode=$row['cls_code'];
	}
		
	$sql="select * from type where grp='classlevel' and sid=$sid and prm=$clslevel ";
	$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
	$row2=mysql_fetch_assoc($res2);
	$grading=$row2['code'];
		
	
		$sql="select * from grading where name='$grading' order by val desc";
		$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row3=mysql_fetch_assoc($res3)){
			$xg=$row3['grade'];
			$ginfo[$xg]['total']=0;
			$ginfo[$xg]['isfail']=$row3['sta'];
			$ginfo[$xg]['gpoint']=$i++;;
		}
	
		
	
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
		$sqlsort="order by hc_sub.id $order";
	else
		$sqlsort="order by $sort $order, hc_sub.id";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript">
function processform(operation){
		if(document.myform.sid.value==""){
			alert("Please select school");
			document.myform.sid.focus();
			return;
		}
		document.myform.submit();
}
function process_myform(){
	ret = confirm("Kemaskini maklumat??");
    if (ret == true){
    	document.myform.operation.value='save';
        document.myform.submit();
    }
}
function check_grade(e,idx){
	var str=e.value
	var arr=str.split("|")
	p=parseInt(arr[0]);
	c=arr[1];		
	ele="g"+idx;
	document.myform.elements[ele].value=c;
}
function process_myform(){
	ret = confirm("Kemaskini maklumat??");
    if (ret == true){
    	document.myform.operation.value='save';
        document.myform.submit();
    }
}

</script>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>
<body>

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="curr">
	<input type="hidden" name="operation">
	<input type="hidden" name="sort" value="<?php echo $sort;?>">
	<input type="hidden" name="p" value="../ehc/hc_stu">
	<input type="hidden" name="order" value="<?php echo $order;?>">
	<input type="hidden" name="uid" value="<?php echo $uid;?>">
	<input type="hidden" name="sid" value="<?php echo $sid;?>">
	<input type="hidden" name="year" value="<?php echo $year;?>">
	
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="window.print();" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
<a href="../ehc/hc_stu_graf.php?uid=<?php echo "$uid&year=$year";?>" id="mymenuitem"><img src="../img/graphbar.png"><br>Analisys</a>
<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
</div> <!-- end mymenu -->
<div align="right">
	  <a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
</div>
</div><!-- end mypanel -->

<div id="story">

<div id="mytitlebg" align="center">
	<?php echo strtoupper($lg_headcount_student_report);?>
</div>
<table width="100%" id="mytabletitle">
  <tr>
    <td width="50%">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="20%"><?php echo strtoupper($lg_name);?></td>
			<td width="1%">:</td>
			<td><?php echo $stuname;?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_matric);?></td>
			<td>:</td>
			<td><?php echo "$uid";?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_ic_number);?></td>
			<td>:</td>
			<td><?php echo "$stuic";?></td>
		  </tr>
		</table>
	</td>
    <td width="50%" valign="top">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="20%"><?php echo strtoupper($lg_school);?></td>
			<td width="1%">:</td>
			<td><?php echo $sname;?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_class);?></td>
			<td>:</td>
			<td><?php echo "$clsname / $year";?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_mentor);?></td>
			<td>:</td>
			<td><?php echo "$mentorname";?> </td>
		  </tr>
		</table>
	</td>
  </tr>
</table>

    		
<table width="100%" cellspacing="0">
  <tr>
    <td id="mytabletitle" width="1%" align="center" rowspan="2"><?php echo strtoupper($lg_no);?></td>
    <td id="mytabletitle" width="2%" align="center" rowspan="2"><a href="#" onClick="formsort('subcode','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_code);?></a></td>
    <td id="mytabletitle" width="15%" align="left"  rowspan="2">&nbsp;<a href="#" onClick="formsort('sub.name','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_subject);?></a></td>
	<td id="mytabletitle" width="4%" align="center" rowspan="2"><?php echo strtoupper($lg_value_added);?></td>
	<td id="mytabletitle" width="4%" align="center" colspan="2">TOV</td>
	<td id="mytabletitle" width="4%" align="center" colspan="2">OTR1</td>
	<td id="mytabletitle" width="4%" align="center" colspan="2">AR1</td>
	<td id="mytabletitle" width="4%" align="center" colspan="2">OTR2</td>
	<td id="mytabletitle" width="4%" align="center" colspan="2">AR2</td>
	<td id="mytabletitle" width="4%" align="center" colspan="2">OTR3</td>
	<td id="mytabletitle" width="4%" align="center" colspan="2">AR3</td>
	<td id="mytabletitle" width="4%" align="center" colspan="2">ETR</td>
	<td id="mytabletitle" width="4%" align="center" colspan="2">AR4</td>
  </tr>
  <tr>
  	<td id="mytabletitle" align="center">M</td>
	<td id="mytabletitle" align="center">G</td>
	<td id="mytabletitle" align="center">M</td>
	<td id="mytabletitle" align="center">G</td>
	<td id="mytabletitle" align="center">M</td>
	<td id="mytabletitle" align="center">G</td>
	<td id="mytabletitle" align="center">M</td>
	<td id="mytabletitle" align="center">G</td>
	<td id="mytabletitle" align="center">M</td>
	<td id="mytabletitle" align="center">G</td>
	<td id="mytabletitle" align="center">M</td>
	<td id="mytabletitle" align="center">G</td>
	<td id="mytabletitle" align="center">M</td>
	<td id="mytabletitle" align="center">G</td>
	<td id="mytabletitle" align="center">M</td>
	<td id="mytabletitle" align="center">G</td>
	<td id="mytabletitle" align="center">M</td>
	<td id="mytabletitle" align="center">G</td>
  </tr>
<?php 

	$sql="select *,sub.name from hc_sub INNER JOIN sub ON hc_sub.subcode=sub.code where uid='$uid' and year=$year and sub.level=$clslevel and sub.sch_id=$sid $sqlsort";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
		$sc=$row['subcode'];
		$sn=$row['name'];
				
		if($q++%2==0)
			$bg="#FAFAFA";
		else
			$bg="#FAFAFA";
		$bg="";
?>
  <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';" >
  	<td id="myborder" align="center"><?php echo $q?></td>
    <td id="myborder" align="left">&nbsp;<?php echo $sc?></td>
	<td id="myborder" align="left">&nbsp;<a href="../ehc/hc_stu_graf.php?uid=<?php echo "$uid&year=$year&sub=$sc";?>"><?php echo strtoupper("$sn");?></a></td>
	<td id="myborder" align="center" bgcolor="#FAFAFA"><?php echo $row['nt'];?></td>
	<td id="myborder" align="center" bgcolor="#FAFAFA"><?php if($row['tov_m']>=0)echo $row['tov_m'];?></td>
	<td id="myborder" align="center" bgcolor="#FAFAFA"><?php echo $row['tov_g'];?></td>
	<td id="myborder" align="center" ><?php if($row['otr1_m']>=0)echo $row['otr1_m'];?></td>
	<td id="myborder" align="center" ><?php echo $row['otr1_g'];?></td>
	<td id="myborder" align="center" ><?php if($row['ar1_m']>=0)echo $row['ar1_m'];?></td>
	<td id="myborder" align="center" ><?php echo $row['ar1_g'];?></td>
	<td id="myborder" align="center" bgcolor="#FAFAFA"><?php if($row['otr2_m']>=0)echo $row['otr2_m'];?></td>
	<td id="myborder" align="center" bgcolor="#FAFAFA"><?php echo $row['otr2_g'];?></td>
	<td id="myborder" align="center" bgcolor="#FAFAFA"><?php if($row['ar2_m']>=0)echo $row['ar2_m'];?></td>
	<td id="myborder" align="center" bgcolor="#FAFAFA"><?php echo $row['ar2_g'];?></td>
	<td id="myborder" align="center" ><?php if($row['otr3_m']>=0) echo $row['otr3_m'];?></td>
	<td id="myborder" align="center" ><?php echo $row['otr3_g'];?></td>
	<td id="myborder" align="center" ><?php if($row['ar3_m']>=0) echo $row['ar3_m'];?></td>
	<td id="myborder" align="center" ><?php echo $row['ar3_g'];?></td>
	<td id="myborder" align="center" bgcolor="#FAFAFA"><?php if($row['etr_m']>=0)echo $row['etr_m'];?></td>
	<td id="myborder" align="center" bgcolor="#FAFAFA"><?php echo $row['etr_g'];?></td>
	<td id="myborder" align="center" bgcolor="#FAFAFA"><?php if($row['ar4_m']>=0)echo $row['ar4_m'];?></td>
	<td id="myborder" align="center" bgcolor="#FAFAFA"><?php echo $row['ar4_g'];?></td>
  </tr>
<?php } ?>
<tr>
    <td id="mytabletitle" align="right" colspan="3">&nbsp;</td>
	<td id="mytabletitle" align="center"><?php echo $lg_report;?></td>
    <td id="mytabletitle" align="center"><?php echo $lg_no;?></td>
	<td id="mytabletitle" align="center">%</td>
    <td id="mytabletitle" align="center"><?php echo $lg_no;?></td>
	<td id="mytabletitle" align="center">%</td>
    <td id="mytabletitle" align="center"><?php echo $lg_no;?></td>
	<td id="mytabletitle" align="center">%</td>
    <td id="mytabletitle" align="center"><?php echo $lg_no;?></td>
	<td id="mytabletitle" align="center">%</td>
    <td id="mytabletitle" align="center"><?php echo $lg_no;?></td>
	<td id="mytabletitle" align="center">%</td>
    <td id="mytabletitle" align="center"><?php echo $lg_no;?></td>
	<td id="mytabletitle" align="center">%</td>
    <td id="mytabletitle" align="center"><?php echo $lg_no;?></td>
	<td id="mytabletitle" align="center">%</td>
    <td id="mytabletitle" align="center"><?php echo $lg_no;?></td>
	<td id="mytabletitle" align="center">%</td>
    <td id="mytabletitle" align="center"><?php echo $lg_no;?></td>
	<td id="mytabletitle" align="center">%</td>
</tr>


<tr bgcolor="#FAFAFA">

	<td align="right" colspan="4">
		<div id="myborder" align="right"><?php echo $lg_total_subject;?></div>
		<div id="myborder" align="right"><?php echo $lg_absence;?></div>
		<div id="myborder" align="right"><?php echo $lg_total_mark;?></div>
		<div id="myborder" align="right"><?php echo $lg_average_mark;?></div>
<?php
		$sql="select * from grading where name='$grading' and val>=0 order by val desc";
		$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
		$total_gred=mysql_num_rows($res3); $i=1;
		while($row3=mysql_fetch_assoc($res3)){
			$g=$row3['grade'];
			$arr_grade[$g]=0;
			$arr_gpoint[$g]=$i++;
?>
		<div id="myborder" align="right">&nbsp;<?php echo $lg_total;?> <?php echo "$g";?>&nbsp;</div>
<?php }?>
		<div id="myborder" align="right"><?php echo $lg_pass;?>&nbsp;</div>
		<div id="myborder" align="right"><?php echo $lg_total_grade;?>&nbsp;</div>
		<div id="myborder" align="right"><?php echo $lg_grade_point;?>&nbsp;</div>
	</td>
<?php
$sql="select * from type where grp='headcount' order by idx";
$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){ 
	$ex=$row['code'];
	$exampoint=$ex."_m";
	$examgrade=$ex."_g";
	$totalsub=$totalallsub=$totalth=$totalpoint=$avgpoint=$totalgp=$agp=$totalfail=0;
	$sql="select * from grading where name='$grading' order by val desc";
	$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
	$i=1;
	while($row2=mysql_fetch_assoc($res2)){
		$g=$row2['grade'];
		$arr_grade[$g]=0;
		$arr_gsta[$g]=$row2['sta'];
		$arr_gpoint[$g]=$i++;
		$arr_gname[$g]=$g;
	}
	$sql="select $exampoint,$examgrade from hc_sub where uid='$uid' and year=$year";
	$res2=mysql_query($sql)or die("query failed:".mysql_error());
	while($row2=mysql_fetch_row($res2)){
		$point=$row2[0];
		$gred=$row2[1];
		
		if(!is_numeric($point))
			$point=0;
		
		if($gred=="TT")
			continue;
		if($point=="-1")
			continue;
		
		$totalallsub++;
		
		if($gred=="TH"){
			$point=0;
			$totalth++;
			if($si_exam_use_th)
				$totalsub++;
		}else{
			$totalsub++;
			$arr_grade[$gred]++;
			if($arr_gsta[$gred])
				$totalfail++;
			$totalgp=$totalgp+$arr_gpoint[$gred];
		}
		$totalpoint=$totalpoint+$point;

		
	}
	$sumgred="&nbsp;";
	$avgpoint=0;
	$agp=0;
	$totalpass=0;
	$perpass=0;
	if($totalsub>0){
		$avgpoint=round($totalpoint/$totalsub,0);
		$agp=round($totalgp/$totalsub,2);
		$totalpass=$totalsub-$totalfail;
		$perpass=round($totalpass/$totalsub*100,0);
		$sql="select * from grading where name='$grading' and val > -1 order by val desc";
		$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row2=mysql_fetch_assoc($res2)){
			$g=$row2['grade'];
			if($arr_grade[$g]>0)
					$sumgred=$sumgred.$arr_grade[$g].$g;
			
		}
	}
?> 
    <td id="myborder" align="center" colspan="2" valign="top">
		<div id="myborder" align="center"><?php echo "$totalallsub";?></div>
		<div id="myborder" align="center"><?php echo "$totalth";?></div>
		<div id="myborder" align="center"><?php echo "$totalpoint";?></div>
		<div id="myborder" align="center"><?php echo "$avgpoint";?></div>
<?php
$sql="select * from grading where name='$grading' and val>-1 order by val desc";
	$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
	$total_gred=mysql_num_rows($res2); $i=1;
	while($row2=mysql_fetch_assoc($res2)){
		$g=$row2['grade'];
?>
		<div id="myborder" align="center"><?php echo $arr_grade[$g];?></div>
<?php } ?>
		<div id="myborder" align="center"><?php echo "$perpass%";?></div>
		<div id="myborder" align="center"><?php echo $sumgred;?></div>
		<div id="myborder" align="center"><?php printf("%.02f",$agp);?></div>
	</td>

<?php }?>
</tr>


</table>
<table width="100%" id="mytitle">
<tr>
<td align="center" width="33%"><?php echo $lg_mentor_signatory;?></td>
<td align="center" width="33%"><?php echo $lg_student_signatory;?></td>
<td align="center" width="33%"><?php echo $lg_parent_signatory;?></td>
</tr></table>

</div></div>
</form>
</body>
</html>
