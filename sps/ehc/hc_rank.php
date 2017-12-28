<?php
//29/03/10 4.1.0 - multi print
//25/05/10 4.2.0 - change dir, view cls ses
$vmod="v5.0.0";
$vdate="100909";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU');
	
		$isprint=$_REQUEST['isprint'];
		$update_result=$_REQUEST['update_result'];

		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
	
		$examcode=$_REQUEST['exam'];
		if($examcode!=""){
			$sql="select * from type where grp='exam' and code='$examcode'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $examname=$row['prm'];
			$sqlcls="";
		}
		
		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
		$sqlyear="and year='$year'";
		
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$ssname=$row['sname'];
			$namatahap=$row['clevel'];
			$simg=$row['img'];
            mysql_free_result($res);					  
		}
		else{
			$namatahap=$lg_level;
		}
		
		$clslevel=$_REQUEST['clslevel'];
		if($clslevel=="")
			$clslevel="0";
		else
			$sqlclslevel="and hc_rep_stu.clslevel=$clslevel";
	
		$clscode=$_REQUEST['clscode'];
		if($clscode!="")
			$sqlclscode="and hc_rep_stu.clscode='$clscode'";
			
		$sex=$_REQUEST['sex'];
		if($sex!="")
			$sqlsex="and sex='$sex'";
		

		$sql="select * from cls where sch_id='$sid' and code='$clscode'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$clsname=$row['name'];

		$sql="select * from type where sid='$sid' and prm='$clslevel' and grp='classlevel'";
    	$res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$grading=$row['code'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<script language="JavaScript">
function processform(operation){
		if(document.myform.exam.value==""){
			alert("Please select exam");
			document.myform.exam.focus();
			return;
		}
		if(document.myform.sid.value=="0"){
			alert("Please select school");
			document.myform.sid.focus();
			return;
		}
		if(document.myform.clslevel.value=="0"){
			alert("Please select level");
			document.myform.clslevel.focus();
			return;
		}
		document.myform.clscode.value="";
		document.myform.update_result.value="1";
		document.myform.submit();
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>

<body>

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="p" value="../ehc/hc_rank">
<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
<input name="order" type="hidden" id="order" value="<?php echo $order;?>">
<input name="isprint" type="hidden" id="isprint" value="<?php echo $isprint;?>">
<input name="update_result" type="hidden" value="0">



<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<a href="#" onClick="document.myform.exam.value='';document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
</div>


<div align="right">                
		  <select name="year" id="year" onchange="document.myform.submit();">
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
		   <select name="sid" id="sid"  onchange="document.myform.clslevel[0].value='0';document.myform.exam[0].value='';document.myform.clscode[0].value='';document.myform.submit();">
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
		       <select name="exam" id="exam" onchange="document.myform.submit();">
                  <?php	
      				if($examcode!="")
						echo "<option value=\"$examcode\">$examcode</option>";
					$sql="select * from type where grp='headcount' and code!='$examcode' order by idx";
            		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
						$b=$row['code'];
                        echo "<option value=\"$b\">$b</option>";
            		}
            		mysql_free_result($res);	
			?>
                </select>
		   <input type="button" onClick="document.myform.submit();" value="View">
		   <br>
			<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>

</div>
	
</div>


<div id="story">

<div id="mytitle" align="center">
<?php if($simg!="") echo "<img src=$simg><br>";?>
<?php echo strtoupper($lg_headcount_report);?>
</div>
<table width="100%" id="mytitle">
  <tr>
    <td width="100%">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="33%" align="right"><?php echo "$$lg_school : $sname";?></td>
			<td width="33%" align="center"><?php echo $lg_exam;?> : <?php echo "$examcode";?></td>
			<td width="33%" align="left"> <?php echo $lg_class;?> : <?php if($clscode=="") echo "$namatahap $clslevel / $year"; else echo "$clsname / $year";?></td>
		  </tr>
		</table>
	</td>
  </tr>
</table>

<table width="100%" cellspacing="0">
	<tr>
			<td id="mytabletitle" width="3%" align="center"><?php echo strtoupper($lg_no);?></td>
            <td id="mytabletitle" width="7%" align="center"><?php echo strtoupper($lg_matric);?></td>
			<td id="mytabletitle" width="3%" align="center"><?php echo strtoupper($lg_mf);?></td>
            <td id="mytabletitle" width="25%"><?php echo strtoupper($lg_name);?></td>
			<td id="mytabletitle" width="15%"><?php echo strtoupper($lg_class);?></td>
			<td id="mytabletitle" width="8%" align="center"><?php echo strtoupper($lg_total_subject);?></td>
			<td id="mytabletitle" width="8%" align="center"><?php echo strtoupper($lg_total_mark);?></td>
			<td id="mytabletitle" width="5%" align="center"><?php echo strtoupper($lg_grade);?></td>
			<td id="mytabletitle" width="5%" align="center">%</td>
			<td id="mytabletitle" width="5%" align="center"><?php echo strtoupper($lg_gp_student);?></td>
	</tr>
	<?php	
	$sql="select stu.*,hc_rep_stu.* from stu INNER JOIN hc_rep_stu ON stu.uid=hc_rep_stu.uid where hc_rep_stu.sid=$sid and year='$year' $sqlclslevel $sqlclscode and exam='$examcode' and gp>0 $sqlsex order by gp,avg desc";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$name=stripslashes(strtoupper($row['name']));
		if(strlen($name)>32){
			$aname=explode(" ", $name);
			$name=$aname[0]." ".$aname[1]." ".$aname[2];
		}
		$sex=$row['sex'];
		$sex=$lg_sexmf[$sex];
		$sql="select * from ses_stu where stu_uid='$uid' and year=$year";
		$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
		$row2=mysql_fetch_assoc($res2);
		$cname=$row2['cls_name'];
		
	
		$tsub=$row['totalsub'];
		$av=$row['avg'];
		$sumgred=$row['totalgred'];
		$gpk=$row['gp'];
		$totalpoint=$row['totalpoint'];
		if(($q++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="";
?>
           <tr bgcolor="<?php echo $bg;?>" style="cursor:pointer" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';" onClick="newwindow('../ehc/hc_stu.php?uid=<?php echo "$uid";?>&year=<?php echo "$year";?>',0)">
              	<td id="myborder" align="center"><?php echo "$q";?></td>
              	<td id="myborder" align="center"><?php echo "$uid";?></td>
			  	<td id="myborder" align="center"><?php echo $sex;?></td>
              	<td id="myborder"><?php echo "$name";?></td>
			  	<td id="myborder"><?php echo "$cname";?></td>
				<td id="myborder" align="center"><?php echo "$tsub"; //echo "$tsub/$totalallsub";?></td>
				<td id="myborder" align="center"><?php echo "$totalpoint";?></td>
				<td id="myborder" align="center"><?php echo $sumgred;//"$avggred";?></td>
				<td id="myborder" align="center"><?php printf("%.02f",$av);?></td>
				<td id="myborder" align="center"><?php printf("%.02f",$gpk);?></td>
            </tr>    
<?php  }  ?>
<?php	
	$sql="select stu.*,hc_rep_stu.* from stu INNER JOIN hc_rep_stu ON stu.uid=hc_rep_stu.uid where hc_rep_stu.sid=$sid and year='$year' $sqlclslevel $sqlclscode and exam='$examcode' and gp=0 $sqlsex order by gp,avg desc";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$ic=$row['ic'];
		$name=stripslashes(strtoupper($row['name']));
		$sex=$row['sex'];
		$cname=$row['cls_name'];
		$tsub=$row['total_sub'];
		$av=$row['avg'];
		$avggred=$row['avg_gred'];
		$sumgred=$row['total_gred'];
		$gpk=$row['gpk'];
		$max=$row['max'];
		$min=$row['min'];
		$totalpoint=$row['total_point'];
		$totalallsub=$row['total_all_sub'];
		if(($q++%2)==0)
			$bg="#FAFAFA";
		else
			$bg="";
?>
	<tr bgcolor="<?php echo $bg;?>" style="cursor:pointer" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';" onClick="newwindow('../ehc/hc_stu.php?uid=<?php echo "$uid";?>&year=<?php echo "$year";?>',0)">
             <td id="myborder" align="center"><?php echo "$q";?></td>
             <td id="myborder" align="center"><?php echo "$uid";?></td>
			 <td id="myborder" align="center"><?php if($sex=="Lelaki")echo "L";if($sex=="Perempuan")echo "P";?></td>
             <td id="myborder"><?php echo "$name";?></td>
			 <td id="myborder"align="center"><?php echo "$cname";?></td>
			<?php 	for($i=1;$i<=$count;$i++) { ?>
			  	<td align="center"><?php $x="g$i"; $y=$row[$x];echo "$y";?></td>
			<?php } ?>
			<td id="myborder" align="center"><?php echo "$tsub"; //echo "$tsub/$totalallsub";?></td>
			<td id="myborder" align="center"><?php echo "$totalpoint";?></td>
			<td id="myborder" align="center"><?php echo $sumgred;//"$avggred";?></td>
			<td id="myborder" align="center"><?php printf("%.02f",$av);?></td>
			<td id="myborder" align="center"><?php printf("%.02f",$gpk);?></td>
	</tr>    
<?php  }  ?>
        
</table>
<div id="mytitle">Jumlah Pelajar <?php echo $q;?></div>

</div></div>
</form>

</body>
</html>
