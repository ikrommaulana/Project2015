<?php
//30/07/10 - kira th/tt base on gp
//110525   - use credit hr
//standard edition
$vmod="v6.0.0";
$vdate="110525";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');

if(!is_verify('ADMIN|SUPERUSER|AKADEMIK'))
	$disable_not_admin="disabled";
	
		$op=$_REQUEST['op'];
		$showheader=$_REQUEST['showheader'];
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
		
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
			$simg=$row['img'];
			$namatahap=$row['clevel'];	
			$schlevel=$row['level'];	
		}
		else{
			$namatahap=$lg_level;
		}
		
		$clslevel=$_REQUEST['clslevel'];
		if($clslevel!="")
			$sqlclslevel="and cls_level='$clslevel'";
		else
			$sqlclslevel="and cls_level='-1'";
		 
		 $examcode=$_REQUEST['exam'];
		if($examcode!=""){
			$sql="select * from type where grp='exam' and code='$examcode' and (sid=0 or sid='$sid') and (lvl=0 or lvl='$clslevel')";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $examname=$row['prm'];
			$sqlcls="";
		}
		
		$clscode=$_REQUEST['clscode'];
		if($clscode!="")
			$sqlclscode="and cls_code='$clscode'";
			
		$sex=$_REQUEST['sex'];
		if($sex!="")
			$sqlsex="and sex='$sex'";
		

		$sql="select * from ses_cls where sch_id='$sid' and cls_code='$clscode' and year='$year'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$clsname=stripslashes($row['cls_name']);

		$sql="select * from type where sid='$sid' and prm='$clslevel' and grp='classlevel'";
    	$res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$grading=$row['code'];
				
				
	if($op=='update_final_result'){
			$examcode="PS3";
			
			$sql="select distinct(stu_uid) from exam where sch_id=$sid and year='$year' and cls_level='$clslevel'";
			$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			while($row=mysql_fetch_assoc($res)){
				$uid=$row['stu_uid'];
				
				$sql="delete from exam where sch_id=$sid and year='$year' and cls_level='$clslevel' and examtype='$examcode' and stu_uid='$uid'";
				$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			
				$sql4="select * from exam where sub_type='0' and stu_uid='$uid' and year='$year' and examtype='PS1' order by sub_name";
				$res4=mysql_query($sql4)or die("query failed:".mysql_error());
				while($row4=mysql_fetch_assoc($res4)){

					$sc=$row4['sub_code'];
					$sn=stripslashes($row4['sub_name']);
					$cc=$row4['cls_code'];
					$cn=stripslashes($row4['cls_name']);
					$cl=$row4['cls_level'];
					$grp=$row4['sub_grp'];
					$grptype=$row4['sub_type'];
					$point1=$row4['point'];
					$grade1=$row4['grade'];
					
					$sql="select * from exam where stu_uid='$uid' and year='$year' and examtype='PS2' and sub_code='$sc'";
					$res5=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					$row5=mysql_fetch_assoc($res5);
					$point2=$row5['point'];
					$grade2=$row5['grade'];
					
					$point3=round(($point1+$point2)/2,0);
					$sql="select * from grading where name='$grading' and point<=$point3 order by val desc";
					$res5=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					$row5=mysql_fetch_assoc($res5);
					$grade3=$row5['grade'];
					//if($uid=='SDQ0451')
						//echo "$point1:$point2:$point3:$grade3:$sn:$sql<br>";
					$cn=addslashes($cn);
					$sn=addslashes($sn);
					$sql="insert into exam(dt,sch_id,year,cls_name,cls_level,cls_code,
								stu_uid,sub_code,sub_name,sub_grp,sub_type,point,val,grade,examtype,adm,ts) values 
								(now(),'$sid','$year','$cn','$cl','$cc',
								'$uid','$sc','$sn','$grp','$grptype','$point3','$point3','$grade3','$examcode','$username',now())";
					$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
					
				}
			}
			$sql="select * from type where grp='exam' and code='$examcode' and (sid=0 or sid='$sid') and (lvl=0 or lvl='$clslevel')";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $examname=$row['prm'];
			$f="<font color=blue>&lt;Successfully update&gt;</font>";
		}//if update result

		$sql="select * from type where grp='examconf' and sid=$sid and lvl='$clslevel' and prm='RANKING_FLOW'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $rankingflow=$row['val'];
		if($rankingflow==""){
				$sql="select * from type where grp='examconf' and sid=$sid and prm='RANKING_FLOW'";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				$row=mysql_fetch_assoc($res);
				$rankingflow=$row['val'];
		}
		if($rankingflow=="1")	
				$sqlsort_ranking="order by gpk asc,avg desc";//rendah terbaik - default
		elseif($rankingflow=="2")	
				$sqlsort_ranking="order by gpk desc,avg desc";//tinggi terbaik
		elseif($rankingflow=="3")	
				$sqlsort_ranking="order by avg desc";//percentage
		elseif($rankingflow=="4")	
				$sqlsort_ranking="order by total_point desc";//total mark
		else
				$sqlsort_ranking="order by gpk asc,avg desc"; //rendah terbaik
			

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<script language="JavaScript">
function processform(op){
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
		if(document.myform.clslevel.value==""){
			alert("Please select level");
			document.myform.clslevel.focus();
			return;
		}
		document.myform.clscode.value="";
		document.myform.op.value=op;
		document.myform.submit();
}
function clear_newwin(){
	document.myform.action="";
	document.myform.target="";
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>

<body>

<form name="myform" method="post">
<input type="hidden" name="p" value="<?php echo $p;?>">
<input name="op" type="hidden">

<div id="content">
  <div id="mypanel">
    <div id="mymenu" align="center"> 
		<a href="#" onClick="clear_newwin();window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a> 
		<a href="#" onClick="clear_newwin();document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a> 
	</div>
    <div align="right">
	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>

      <select name="sid" id="sid"  onChange="clear_newwin();document.myform.clslevel[0].value='';document.myform.exam[0].value='';document.myform.clscode[0].value='';document.myform.submit();">
        <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_school -</option>";
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
			}					  
			
?>
      </select>
      <select name="year" id="year" onChange="clear_newwin();document.myform.submit();">
<?php
            echo "<option value=$year>SESI $year</option>";
			$sql="select * from type where grp='session' and prm!='$year' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        echo "<option value=\"$s\">SESI $s</option>";
            }				  
?>
      </select>
      <select name="clslevel" id="clslevel" onChange="clear_newwin();document.myform.clscode[0].value=''; document.myform.submit();">
        <?php    
		if($clslevel=="")
            	echo "<option value=\"\">- $lg_level -</option>";
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
      <select name="clscode" id="clscode" onChange="clear_newwin();document.myform.submit();">
        <?php	
      				if($clscode==""){
						echo "<option value=\"\">- $lg_all $lg_class -</option>";
						$sql="select * from ses_cls where sch_id=$sid and cls_level='$clslevel' and year='$year' order by cls_level";
					}
					else{
                        echo "<option value=\"$clscode\">$clsname</option>";
						$sql="select * from ses_cls where sch_id=$sid and cls_level='$clslevel' and cls_code!='$clscode' and year='$year' order by cls_level";
					}
            		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $a=stripslashes($row['cls_name']);
						$v=$row['cls_code'];
                        echo "<option value=\"$v\">$a</option>";
            		}
					if($clscode!=""){
						echo "<option value=\"\">- $lg_all -</option>";
					}
			?>
      </select>
      <select name="exam" onChange="clear_newwin();document.myform.submit();">
        <?php	
      				if($examcode=="")
						echo "<option value=\"\">- $lg_exam -</option>";
					else
						echo "<option value=\"$examcode\">$examname</option>";
					$sql="select * from type where grp='exam' and code!='$examcode' and (lvl=0 or lvl='$clslevel') and (sid=0 or sid=$sid) order by idx";
            		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $a=$row['prm'];
						$b=$row['code'];
                        echo "<option value=\"$b\">$a</option>";
            		}
			?>
      </select>
      <select name="sex" id="sex" onChange="clear_newwin();document.myform.submit();">
        <?php	
					$sexname=$lg_malefemale[$sex];
      				if($sex=="")
						echo "<option value=\"\">- $lg_all -</option>";
					else
						echo "<option value=\"$sex\">$sexname</option>";
					$sql="select * from type where grp='sex'";
            		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $a=$row['prm'];
						 $v=$row['val'];
                        echo "<option value=\"$v\">$a</option>";
            		}
					if($sex!="")
						echo "<option value=\"\">- $lg_all -</option>";
			?>
      </select>
      <input type="button" onClick="clear_newwin();document.myform.submit();" value="View">
	  	
<?php if($VARIANT=="MDQ"){?>
    	<input type="button" name="Submit" value="UPDATE FINAL RESULT" onClick="clear_newwin();processform('update_final_result')" <?php echo "$disable_not_admin";?>>
<?php } ?>
     
	  
</div><!-- end viewpanel -->
</div><!-- end mypanel -->


<div id="story">

<div id="showheader" <?php if(!$showheader) echo "style=\"display:none\"";?> >
<?php  include('../inc/school_header.php')?>
</div>

<div id="mytitlebg" >
	<?php echo strtoupper("$sname");?> 
	<?php if($clscode!="") echo strtoupper("/ $clsname / $year / $examname"); else echo strtoupper("/ $namatahap $clslevel / $year / $examname");?> 
	<?php echo strtoupper("$f");?>
	
</div>
<table width="100%" cellspacing="0" cellpadding="2">
	<tr>
			  <td id="mytabletitle" width="3%" align="center"><?php echo strtoupper("$lg_ranking_student");?></td>
              <td id="mytabletitle" width="5%" align="center"><?php echo strtoupper("$lg_matric");?></td>
			  <td id="mytabletitle" width="2%" align="center"><?php echo strtoupper("$lg_mf");?></td>
			  <td id="mytabletitle" width="27%">&nbsp;<?php echo strtoupper("$lg_name");?></td>
			  <td id="mytabletitle" width="15%">&nbsp;<?php echo strtoupper("$lg_class");?></td>
			<?php 
				$sql="select * from grading where name='$grading' order by val desc";
				$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				while($row2=mysql_fetch_assoc($res2)){
					$p=$row2['grade'];
					$count++;
			 ?>
			 <td id="mytabletitle" width="3%" align="center"><?php echo "$p";?></td>
			<?php } ?>
			<td id="mytabletitle" width="5%" align="center"><?php if($LG=="BM") echo "Jumlah<br>SUB"; else echo "Total<br>SUB";?></td>
			<td id="mytabletitle" width="5%" align="center"><?php if($LG=="BM") echo "Jumlah<br>MAR"; else echo "Total<br>MARK";?></td>
			<td id="mytabletitle" width="5%" align="center"><?php echo strtoupper("$lg_grade");?></td>
			<td id="mytabletitle" width="5%" align="center">%</td>
			<td id="mytabletitle" width="5%" align="center"><?php echo strtoupper("$lg_gp_student");?></td>
        </tr>
	<?php	
	
		
	if($examcode=="")
		$sql="select stu.*,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid and ses_stu.year='$year' and cls_level='$clslevel' $sqlclscode $sqlsex";
	else
		$sql="select stu.*,examrank.* from stu INNER JOIN examrank ON stu.uid=examrank.stu_uid where examrank.sch_id=$sid and year='$year' $sqlclslevel $sqlclscode and exam='$examcode' and gpk>0 $sqlsex $sqlsort_ranking";

	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$ic=$row['ic'];
		$name=strtoupper(stripslashes($row['name']));
		$sex=$row['sex'];
		$cname=strtoupper(stripslashes($row['cls_name']));
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
           <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
		   		<td id="myborder" align="center"><?php echo "$q";?></td>
              	<td id="myborder" align="center"><?php echo "$uid";?></td>
				<td id="myborder" align="center"><?php echo $lg_sexmf[$sex];?></td>
			  	<td id="myborder">
					<a href="#" onClick="newwindow('../exam/slip_exam.php?<?php echo "uid=$uid&year=$year&sid=$sid&exam=$examcode";?>',0)">
						<?php echo "$name";?>
					</a>
				</td>
			  	<td id="myborder">&nbsp;<?php echo "$cname";?></td>
				<?php 	for($i=1;$i<=$count;$i++) { ?>
			  		<td id="myborder" align="center"><?php $x="g$i"; $y=$row[$x];echo "$y";?></td>
				<?php } ?>
				<td id="myborder" align="center"><?php echo "$tsub"; //echo "$tsub/$totalallsub";?></td>
				<td id="myborder" align="center"><?php echo "$totalpoint";?></td>
				<td id="myborder" align="center"><?php echo $sumgred;//"$avggred";?></td>
				<td id="myborder" align="center"><?php printf("%.02f",$av);?></td>
				<td id="myborder" align="center"><?php printf("%.02f",$gpk);?></td>
        </tr>    
<?php  
}// while gpk>0
	$sql="select stu.*,examrank.* from stu INNER JOIN examrank ON stu.uid=examrank.stu_uid where examrank.sch_id=$sid and year='$year' $sqlclslevel $sqlclscode and exam='$examcode' and gpk=0 $sqlsex $sqlsort";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$ic=$row['ic'];
		$name=strtoupper(stripslashes($row['name']));
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
           <tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
             <td id="myborder" align="center"><?php echo "$q";?></td>
              <td id="myborder" align="center"><?php echo "$uid";?></td>
			  <td id="myborder" align="center"><?php echo $lg_sexmf[$sex];?></td>
              <td id="myborder">
					<a href="#" onClick="newwindow('../exam/slip_exam.php?<?php echo "uid=$uid&year=$year&sid=$sid&exam=$examcode";?>',0)">
						<?php echo "$name";?>
					</a>
			 </td>
			  <td id="myborder">&nbsp;<?php echo "$cname";?></td>
			<?php 	for($i=1;$i<=$count;$i++) { ?>
			  <td id="myborder" align="center"><?php $x="g$i"; $y=$row[$x];echo "$y";?></td>
			<?php } ?>
			<td id="myborder" align="center"><?php echo "$tsub"; //echo "$tsub/$totalallsub";?></td>
			<td id="myborder" align="center"><?php echo "$totalpoint";?></td>
			<td id="myborder" align="center"><?php echo $sumgred;//"$avggred";?></td>
			<td id="myborder" align="center"><?php printf("%.02f",$av);?></td>
			<td id="myborder" align="center"><?php printf("%.02f",$gpk);?></td>
        </tr>    
<?php  }  ?>
        
</table>
<div id="mytitle"><?php echo strtoupper("$lg_total $lg_student");?> : <?php echo $q;?></div>

</div></div>
</form>
</body>
</html>
