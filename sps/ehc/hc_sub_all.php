<?php
//29/03/10 4.1.0 - gui
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
		
	$tahap="Tahap";
	if($sid!=0){
		$sql="select * from sch where id=$sid";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=$row['name'];
		$ssname=$row['sname'];
		$tahap=$row['clevel'];
		$simg=$row['img'];
     	mysql_free_result($res);					  
	}

	$clslevel=$_REQUEST['clslevel'];
	if($clslevel=="")
		$clslevel=0;
	
	$sqlclslevel="and hc_sub.cls_level='$clslevel'";
	
	$clscode=$_REQUEST['clscode'];
	if($clscode!=""){
			$sqlclscode="and hc_sub.cls_code='$clscode'";
			$sql="select * from cls where sch_id=$sid and code='$clscode'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=$row['name'];
			$clslevel=$row['level'];
	}
	$year=$_REQUEST['year'];
	if($year=="")
		$year=date('Y');
		
	$subgroup=$_REQUEST['subgroup'];
	if($subgroup!=""){
		$sqlsubgroup=" and sub_grp='$subgroup'";
		$sqlsubgroup2=" and grp='$subgroup'";
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
	/**if($sort=="")
		$sqlsort="order by sub_name $order";
	else
		$sqlsort="order by $sort $order, sub_name";**/
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
function process_myform(exam){
	if(exam==""){
		alert('Sila pilih peperiksaan');
		return;
	}
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
	<input type="hidden" name="p" value="../ehc/hc_sub_all">
	<input type="hidden" name="curr">
	<input type="hidden" name="operation">
	<input type="hidden" name="sort" value="<?php echo $sort;?>">
	<input type="hidden" name="order" value="<?php echo $order;?>">
	<input type="hidden" name="isprint" value="<?php echo $isprint;?>">
	<input type="hidden" name="exam" value="<?php echo $exam;?>">
	
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
<a href="#" onClick="document.myform.exam.value='';document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
</div> <!-- end mymenu -->

<div id="viewpanel"  align="right">
<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br>

      <select name="year" id="year" onChange="document.myform.exam.value='';document.myform.submit();">
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
	  <select name="sid" id="sid" onchange="document.myform.clscode[0].value='';document.myform.subgroup[0].value='';document.myform.submit();">
        <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select $lg_school -</option>";
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
	<select name="clslevel" onchange="document.myform.clscode[0].value='';document.myform.submit();">
                <?php    
					if($clslevel=="0")
							echo "<option value=\"0\">- $lg_level -</option>";
					else
						echo "<option value=$clslevel>$tahap $clslevel</option>";
						$sql="select * from type where grp='classlevel' and sid='$sid' and prm!='$clslevel' order by prm";
						$res=mysql_query($sql)or die("query failed:".mysql_error());
						while($row=mysql_fetch_assoc($res)){
									$s=$row['prm'];
									echo "<option value=$s>$tahap $s</option>";
						}
				?>
              </select>
	  <select name="clscode" id="clscode" onchange="document.myform.submit();">
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
      <select name="subgroup" onchange="document.myform.exam.value='';document.myform.submit();">
        <?php	
      				if($subgroup!="")
						echo "<option value=\"$subgroup\">$subgroup</option>";
						
					echo "<option value=\"\">- $lg_all $lg_subject -</option>";
					
					$sql="select * from type where grp='subtype' and prm!='$subgroup' and val=0 order by idx";
            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=$row['prm'];
                        echo "<option value=\"$b\">$b</option>";
            		}
			?>
      </select>

      <input type="button" name="Submit" value="View" onClick="processform()" >
</div><!-- end viewpanel -->
</div><!-- end mypanel -->

<div id="story">

<div id="mytitle" align="center">
	<?php if(($simg!="")) echo "<img src=$simg><br>";?>
	<?php echo strtoupper($lg_headcount_subject_report);?> <?php echo $updated;?>
</div>
<table width="100%" id="mytitle">
  <tr>
    <td width="100%">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="45%" align="right"><?php echo "$lg_school : $sname";?></td>
			<td width="10%" align="center">&nbsp;</td>
			<td width="45%" align="left"> <?php echo $lg_class;?> : <?php if($clscode=="") echo "$tahap $clslevel / $year"; else echo "$clsname / $year";?></td>
		  </tr>
		</table>
	</td>
  </tr>
</table>


<table width="100%" cellspacing="0">
  <tr>
    <td id="mytabletitle" width="1%" align="center" rowspan="2"><?php echo strtoupper($lg_no);?></td>
    <td id="mytabletitle" width="2%" rowspan="2">&nbsp;<a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_code);?></a></td>
    <td id="mytabletitle" width="15%" rowspan="2">&nbsp;<a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_subjek);?></a></td>
	<td id="mytabletitle" width="4%" rowspan="2" align="center"><?php echo strtoupper($lg_total_student);?></td>
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
 <?php for($i=0;$i<9;$i++){?>
	<td id="mytabletitle" align="center"><?php echo $lg_pass;?></td>
	<td id="mytabletitle" align="center"><?php echo $lg_gp_subject;?></td>
<?php } ?>
  </tr>
<?php 
if($clscode!="")
	$sql="select sub_name,sub_code from ses_sub where cls_code='$clscode' and sch_id=$sid and year=$year and sub_grptype=0 $sqlsubgroup $sqlsort";
else
	$sql="select name,code from sub where level='$clslevel' and sch_id=$sid  and grptype=0 $sqlsubgroup2 $sqlsort";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
  	while($row=mysql_fetch_row($res)){
		$xsubname=$row[0];
		$xsubcode=$row[1];
		$name=stripslashes(strtoupper($name));
		if(($q++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="";
?>
    <tr bgcolor="<?php echo $bg;?>" >
  	<td id="myborder" align="center"><?php echo $q?></td>
    <td id="myborder" >&nbsp;<?php echo "$xsubcode";?></td>
	<td id="myborder" >&nbsp;<?php echo "$xsubname";?></td>
<?php
		if($clscode=="")
			$sql="select sum(total_stu) from hc_sub_rep where exam='TOV' and year=$year and sid=$sid and subcode='$xsubcode' and clslevel='$clslevel'";
		else
			$sql="select total_stu from hc_sub_rep where exam='TOV' and year=$year and sid=$sid and subcode='$xsubcode' and clscode='$clscode'";
		$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$row3=mysql_fetch_row($res3);
		$xbil=$row3[0];
?>
	<td id="myborder" align="center"><?php echo $xbil;?></td>
<?php 
	
	$sql="select * from type where grp='headcount' and prm='EXAM' order by idx";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row2=mysql_fetch_assoc($res2)){
		$ex=$row2['code'];
		if($clscode=="")
			$sql="select * from hc_sub_rep where exam='$ex' and year=$year and sid=$sid and subcode='$xsubcode' and clslevel='$clslevel'";
		else
			$sql="select * from hc_sub_rep where exam='$ex' and year=$year and sid=$sid and subcode='$xsubcode' and clscode='$clscode'";
		$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$per=$xbil=$xfail=$xavg=$xgp="0";
		$g1=$g2=$g3=$g4=$g5=$g6=$g7=$g8=$g9=$gp=0;
		while($row3=mysql_fetch_assoc($res3)){
			$xbil=$xbil+$row3['total_stu'];
			$xfail=$xfail+$row3['total_fail'];
			$xavg=$xavg+$row3['avg'];
			$xgp=$xgp+$row3['gp'];
			
			$g1=$g1+$row3['total_g1'];
			$g2=$g2+$row3['total_g2'];
			$g3=$g3+$row3['total_g3'];
			$g4=$g4+$row3['total_g4'];
			$g5=$g5+$row3['total_g5'];
			$g6=$g6+$row3['total_g6'];
			$g7=$g7+$row3['total_g7'];
			$g8=$g8+$row3['total_g8'];
			$g9=$g9+$row3['total_g9'];
		}
		$totalg=($g1*1)+($g2*2)+($g3*3)+($g4*4)+($g5*5)+($g6*6)+($g7*7)+($g8*8)+($g9*9);
		if($xbil>0) $per=($xbil-$xfail)/$xbil*100;
		if($allclasses) $xgp=$xgp/2;
		if($xbil>0) $xgp=$totalg/$xbil;
?>
	
	<td id="myborder" align="center"><?php if($xbil>0) {echo round($per,0); echo "%";};?></td>
	<td id="myborder" align="center"><?php if($xbil>0) printf("%.02f",$xgp);?></td>
<?php }  ?>
  </tr>
<?php } ?>


</table>


</div></div>
</form>

</body>
</html>
