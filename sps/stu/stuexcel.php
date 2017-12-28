<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
///*
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=report.xls");
header ("Pragma: no-cache");
header("Expires: 0");
//*/
include_once("$MYLIB/inc/language_$LG.php");
$sql=$_REQUEST['sql'];
$sql=stripslashes($sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Excell</title>
</head>

<body>

 <?php
 echo $sql;
echo "<table width='100%' border='1'>";
echo "<tr>";
echo "<td align=center bgcolor=#FFFF33>$lg_no</td>";
echo "<td align=center bgcolor=#FFFF33>$lg_status</td>";
echo "<td align=center bgcolor=#FFFF33>$lg_school</td>";
//echo "<td align=center bgcolor=#FFFF33>$lg_class_session</td>";
//echo "<td align=center bgcolor=#FFFF33>$lg_level</td>";
//echo "<td align=center bgcolor=#FFFF33>$lg_year</td>";
echo "<td align=center bgcolor=#FFFF33>$lg_name</td>";
echo "<td align=center bgcolor=#FFFF33>$lg_ic</td>";
echo "<td align=center bgcolor=#FFFF33>$lg_birth_no</td>";
//echo "<td align=center bgcolor=#FFFF33>NO.RUJ</td>";
//echo "<td align=center bgcolor=#FFFF33>PAHANG</td>";
//echo "<td align=center bgcolor=#FFFF33>UPSR</td>";
//echo "<td align=center bgcolor=#FFFF33>TEMUDUGA</td>";
//echo "<td align=center bgcolor=#FFFF33>SIMULASI</td>";
//echo "<td align=center bgcolor=#FFFF33>$lg_school</td>";
echo "<td align=center bgcolor=#FFFF33>$lg_address</td>";
echo "<td align=center bgcolor=#FFFF33>$lg_tel_home</td>";
echo "<td align=center bgcolor=#FFFF33>$lg_hp ($lg_contact)</td>";
echo "<td align=center bgcolor=#FFFF33>$lg_email ($lg_contact)</td>";
echo "<td align=center bgcolor=#FFFF33>$lg_father</td>";
echo "<td align=center bgcolor=#FFFF33>$lg_hp</td>";
echo "<td align=center bgcolor=#FFFF33>$lg_email</td>";
echo "<td align=center bgcolor=#FFFF33>$lg_job</td>";
echo "<td align=center bgcolor=#FFFF33>$lg_salary</td>";
echo "<td align=center bgcolor=#FFFF33>$lg_mother</td>";
echo "<td align=center bgcolor=#FFFF33>$lg_hp</td>";
echo "<td align=center bgcolor=#FFFF33>$lg_email</td>";
echo "<td align=center bgcolor=#FFFF33>$lg_job</td>";
echo "<td align=center bgcolor=#FFFF33>$lg_salary</td>";
//echo "<td align=center bgcolor=#FFFF33>PENDAPATAN KELUARGA</td>";
echo "</tr>";

		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$id=$row['id'];
			$sta=$row['status'];
			$rdate=$row['rdate'];
			$sid=$row['sch_id'];
			$xid=$row['id'];
			$pt=$row['pt'];
			$ptdate=$row['ptdate'];
			$uid=$row['uid'];
			$name=stripslashes($row['name']);
			$citizen=stripslashes($row['citizen']);
			$ic=$row['ic'];
			$nosb=$row['nosb'];
			$bstate=$row['bstate'];
			$sex=$lg_malefemale[$row['sex']];
			$race=$row['race'];
			$religion=$row['religion'];
			$bday=$row['bday'];
			list($y,$m,$d)=split('[/.-]',$bday);
			$tel=$row['tel'];
			$hp=$row['hp'];
			$mel=$row['mel'];
			$addr=$row['addr'];
			$bandar=$row['bandar'];
			$poskod=$row['poskod'];
			$state=$row['state'];
			$transport=$row['transport'];
			$ill=$row['ill'];
			$status=$row['status'];
			$clslevel=$row['cls_level'];
			$year=$row['sesyear'];
			
			$p1name=$row['p1name'];
			$p1ic=$row['p1ic'];
			$p1bstate=$row['p1bstate'];
			$p1job=$row['p1job'];
			$p1sal=$row['p1sal'];
			$p1com=$row['p1com'];
			$p1tel=$row['p1tel'];
			$p1tel2=$row['p1tel2'];
			$p1fax=$row['p1fax'];
			$p1state=$row['p1state'];
			$p1addr=$row['p1addr'];
			$p1mel=$row['p1mel'];
			$p1hp=$row['p1hp'];
			$clssession=stripslashes($row['clssession']);
			
			$p2name=$row['p2name'];
			$p2ic=$row['p2ic'];
			$p2bstate=$row['p2bstate'];
			$p2job=$row['p2job'];
			$p2sal=$row['p2sal'];
			$p2com=$row['p2com'];
			$p2tel=$row['p2tel'];
			$p2tel2=$row['p2tel2'];
			$p2fax=$row['p2fax'];
			$p2state=$row['p2state'];
			$p2addr=$row['p2addr'];
			$p2mel=$row['p2mel'];
			$p2hp=$row['p2hp'];
			
			$p3name=$row['p3name'];
			$p3ic=$row['p3ic'];
			$p3rel=$row['p3rel'];
			$p3tel=$row['p3tel'];
			$p3tel2=$row['p3tel2'];
			$p3fax=$row['p3fax'];
			$p3state=$row['p3state'];
			$p3addr=$row['p3addr'];
			$p3mel=$row['p3mel'];
			$p3hp=$row['p3hp'];
			
			$sendername=stripslashes($row['sendername']);
			$collectorname=stripslashes($row['collectorname']);
			$cartype=stripslashes($row['cartype']);
			$carno=stripslashes($row['carno']);
			$cartype2=stripslashes($row['cartype2']);
			$carno2=stripslashes($row['carno2']);
			$istransport=stripslashes($row['istransport']);
			$twoway=stripslashes($row['twoway']);
			$saddr=stripslashes($row['saddr']);
			$faddr=stripslashes($row['faddr']);
			
			$q0=stripslashes($row['q0']);
			list($q01,$q02,$q03)=split('[/.|]',$q0);
			$q1=stripslashes($row['q1']);
			list($q11,$q12,$q13)=split('[/.|]',$q1);
			$q2=stripslashes($row['q2']);
			list($q21,$q22,$q23)=split('[/.|]',$q2);
			$q3=stripslashes($row['q3']);
			list($q31,$q32,$q33)=split('[/.|]',$q3);
			$q4=stripslashes($row['q4']);
			list($q41,$q42,$q43)=split('[/.|]',$q4);
			$q5=stripslashes($row['q5']);
			list($q51,$q52,$q53)=split('[/.|]',$q5);
			$q6=stripslashes($row['q6']);
			list($q61,$q62,$q63)=split('[/.|]',$q6);
			$q7=stripslashes($row['q7']);
			list($q71,$q72,$q73)=split('[/.|]',$q7);
			$q8=stripslashes($row['q8']);
			list($q81,$q82,$q83)=split('[/.|]',$q8);
			$q9=stripslashes($row['q9']);
			list($q91,$q92,$q93)=split('[/.|]',$q9);
			
			$pschool=$row['pschool'];	
			$pschoolyear=$row['pschoolyear'];	
			
				$test1=$row['test1'];
				$test2=$row['test2'];
				$upsr=$row['upsr_result'];
				$totalsal=$p1sal+$p2sal;
				$q++;
				
				$sql2="select * from type where grp='stusta' and val=$sta";
				$res2=mysql_query($sql2)or die("query failed:".mysql_error());
        		$row2=mysql_fetch_assoc($res2);
        		$status=$row2['prm'];
				$sql2="select * from sch where id='$sid'";
				$res2=mysql_query($sql2)or die("query failed:".mysql_error());
        		$row2=mysql_fetch_assoc($res2);
        		$sname=$row2['sname'];

echo "<tr>";
echo "<td align=center valign=top>$q</td>";
echo "<td align=center valign=top>$status</td>";
echo "<td valign=top>$sname</td>";
//echo "<td valign=top>$clssession</td>";
//echo "<td valign=top>$clslevel</td>";
//echo "<td valign=top>$year</td>";
echo "<td valign=top>$name</td>";
echo "<td valign=top>\"$ic\"</td>";
echo "<td valign=top>\"$nosb\"</td>";
//echo "<td valign=top>$trans</td>";
//echo "<td valign=top>$anak</td>";
//echo "<td valign=top>$upsr</td>";
//echo "<td valign=top>$test1</td>";
//echo "<td valign=top>$test2</td>";
//echo "<td valign=top>$pschool</td>";
echo "<td valign=top>$addr</td>";
echo "<td valign=top>\"$tel\"</td>";
echo "<td valign=top>\"$hp\"</td>";
echo "<td valign=top>$mel</td>";
echo "<td valign=top>$p1name</td>";
echo "<td valign=top>\"$p1hp\"</td>";
echo "<td valign=top>$p1mel</td>";
echo "<td valign=top>$p1job</td>";
echo "<td valign=top>$p1sal</td>";
echo "<td valign=top>$p2name</td>";
echo "<td valign=top>\"$p2hp\"</td>";
echo "<td valign=top>$p2mel</td>";
echo "<td valign=top>$p2job</td>";
echo "<td valign=top>$p2sal</td>";			  
//echo "<td valign=top>$totalsal</td>";		

echo "</tr>";
		
		
 }

echo"</table>";





?> 

</body>
</html>