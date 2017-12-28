<?php
$vmod="v5.2.0";
$vdate="110704";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU');

		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
		$view_graph=$_POST['view_graph'];
		
		
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
            mysql_free_result($res);					  
		}
		else{
			$namatahap="Tahap";
		}
		
		$xclslevel=$_REQUEST['clslevel'];
		if($xclslevel=="")
			$clslevel="-1";
		else
			$clslevel=$xclslevel;
		
		$sqlclslevel="and cls_level=$clslevel";
		
		$clscode=$_REQUEST['clscode'];
		if($clscode!="")
			$sqlclscode="and cls_code='$clscode'";
			
		$subcode=$_REQUEST['subcode'];
		if($subcode!=""){
			$sqlsubcode="and sub_code='$subcode'";
			$sql="select * from sub where sch_id='$sid' and code='$subcode' and level=$clslevel";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$subname=$row['name'];
		}
		$sqlsubcode="and sub_code='$subcode'";
		
		$sex=$_REQUEST['sex'];
		if($sex!="")
			$sqlsex="and sex='$sex'";
		
		$examcode=$_REQUEST['exam'];
		if($examcode!=""){
			$sql="select * from type where grp='exam' and code='$examcode' and (lvl=0 or lvl=$clslevel) and (sid=0 or sid=$sid)";
            $res=mysql_query($sql)or die("$sql query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $examname=$row['prm'];
			$sqlcls="";
		}
		
		$sql="select * from ses_cls where sch_id='$sid' and cls_code='$clscode' and year='$year'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$clsname=stripslashes($row['cls_name']);
		
		$sql="select * from sub where code='$subcode' and level=$clslevel and sch_id=$sid";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$gradingset=$row['grading'];
		$sql="select * from grading where name='$gradingset' order by val desc";
		$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
		$i=1;$j=1;
		while($row3=mysql_fetch_assoc($res3)){
			$xg=$row3['grade'];
			$isfail=$row3['sta'];
			$xgp=$row3['gp'];
			$arr_grading_info[$xg]['grade']=0;
			$arr_grading_info[$xg]['point']=$xgp;
			$arr_grading_info[$xg]['isfail']=$isfail;
		}

/** sorting control **/
	$order=$_POST['order'];
	if($order=="")
		$order="desc";
		
	if($order=="desc")
		$nextdirection="asc";
	else
		$nextdirection="desc";
		
	$sort=$_POST['sort'];
	if($sort=="")
		$sqlsort="order by val $order";
	else
		$sqlsort="order by $sort $order, name";			

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
<input type="hidden" name="p" value="../examrep/rep_exam_sub_stu">
<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
<input name="order" type="hidden" id="order" value="<?php echo $order;?>">


<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
                        <div id="mymenu_seperator"></div>
                        <div id="mymenu_space">&nbsp;&nbsp;</div>
	</div>
	<div align="right"  ><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br></div>
	</div> <!-- end mypanel -->
	<div id="mytabletitle" class="printhidden" align="right" >
		<a href="#" title="<?php echo $vdate;?>"></a><br><br>

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
		   <select name="sid" id="sid"  onchange="document.myform.clslevel[0].value='';document.myform.subcode[0].value='';document.myform.exam[0].value='';document.myform.clscode[0].value='';document.myform.submit();">
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
				mysql_free_result($res);
			}					  
			
?>
        </select>
		<select name="clslevel" id="clslevel" onchange="document.myform.clscode[0].value='';document.myform.subcode[0].value=''; document.myform.submit();">
<?php    
		if($xclslevel=="")
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
		                <select name="clscode" id="clscode" onchange="document.myform.submit();">
                  <?php	
      				if($clscode==""){
						echo "<option value=\"\">- $lg_class -</option>";
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
            mysql_free_result($res);	
			?>
            </select>
		<select name="subcode" id="subcode" onchange="document.myform.submit();">
<?php    
		if($subcode=="")
            	echo "<option value=\"\">- $lg_subject -</option>";
		else
			echo "<option value=\"$subcode\">$subname</option>";
			$sql="select * from sub where sch_id=$sid and level=$clslevel order by grp,name";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $v=$row['code'];
						$s=$row['name'];
                        echo "<option value=\"$v\">$s</option>";
            }

?>		
  		</select>

		<select name="exam" id="exam" onchange="document.myform.submit();">
             <?php	
      				if($examcode=="")
						echo "<option value=\"\">- $lg_exam -</option>";
					else
						echo "<option value=\"$examcode\">$examname</option>";
					$sql="select * from type where grp='exam' and code!='$examcode' and (lvl=0 or lvl=$clslevel) and (sid=0 or sid=$sid) order by idx";
            		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $a=$row['prm'];
						$b=$row['code'];
                        echo "<option value=\"$b\">$a</option>";
            		}
            		mysql_free_result($res);	
			?>
           </select>
			
		   <select name="sex" id="sex" onchange="document.myform.submit();">
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
            		mysql_free_result($res);	
			?>
           </select>
		   <input type="button" onClick="document.myform.submit();" value="View">	
		   <br>
			<input type="checkbox" name="view_graph" value="1" <?php if($view_graph) echo "checked";?> onClick="document.myform.submit();">View Graph
</div><!-- end mypanel -->
<div id="story">

<div id="mytitlebg"><?php if($LG=="BM") echo "ANALISA LAPORAN SUBJEK"; else echo "ANALISYS SUBJECT REPORT";?></div>
<table width="100%">
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
			<td><?php if($clscode!="") echo strtoupper("$clsname / $year"); else echo strtoupper("$namatahap $clslevel / $year");?></td>
		  </tr>
		</table>
	</td>
    <td width="50%" valign="top">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="20%"><?php echo strtoupper($lg_exam);?></td>
			<td width="1%">:</td>
			<td><?php echo strtoupper($examname);?></td>
		  </tr>
		  <tr>
			<td width="20%"><?php echo strtoupper($lg_subject);?></td>
			<td width="1%">:</td>
			<td><?php echo strtoupper($subname);?></td>
		  </tr>
		</table>
	
	</td>
  </tr>
</table>

<table width="100%" border="0">
<tr><td width="60%" valign="top">
<table width="100%" cellspacing="0">
	<tr>
            <td id="mytabletitle" width="3%" align="center"><?php echo strtoupper($lg_no);?></td>
            <td id="mytabletitle" width="7%" align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_matric);?></a></td>
			<td id="mytabletitle" width="3%" align="center"><a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_mf);?></a></td>
            <td id="mytabletitle" width="30%"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_name);?></a></td>
			<td id="mytabletitle" width="15%"><a href="#" onClick="formsort('exam.cls_name','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_class);?></a></td>
			<td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('val','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_mark);?></a></td>
			<td id="mytabletitle" width="5%" align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_grade);?></a></td>
    </tr>
<?php	
	$q=0;
	$markah_tertinggi=0;
	$markah_terendah=100;
	$markah_jumlah=0;
	$jumlah_gp=0;
	$jumlah_fail=0;
	
	//$sql="SELECT exam.*,stu.name,stu.uid,stu.sex FROM exam INNER JOIN stu ON exam.stu_uid=stu.uid where exam.sch_id=$sid and exam.cls_level=$clslevel $sqlsex $sqlclscode $sqlsubcode and year='$year' and examtype='$examcode' and (grade!='TT' and grade!='TH') $sqlsort";	
    $sql="SELECT exam.*,stu.name,stu.uid,stu.sex FROM exam INNER JOIN stu ON exam.stu_uid=stu.uid where exam.sch_id=$sid and exam.cls_level=$clslevel $sqlsex $sqlclscode $sqlsubcode and year='$year' and examtype='$examcode' and (grade!='TT') $sqlsort";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$name=$row['name'];
		$name=strtoupper($name);
		$name=stripslashes($name);
		if(strlen($name)>32){
			$aname=explode(" ", $name);
			$name=$aname[0]." ".$aname[1]." ".$aname[2];
		}
		$sex=$row['sex'];
		$cname=$row['cls_name'];
		$point=$row['point'];
		$grade=$row['grade'];
		$val=$row['val'];
		
		if($point==""){
			$point="";
			$grade="TT";
		}
		if(($grade=="TT")||($grade=="TH")||($grade=="TB"))
			$point=0;
		//$arr_grade[$grade]++;
		$arr_grading_info[$grade]['grade']=$arr_grading_info[$grade]['grade']+1;
		if($arr_grading_info[$grade]['isfail'])
			$jumlah_fail++;
		if(($grade!="TT")&&($grade!="TH")&&($grade!="TB")){
			if($markah_terendah>$point)
				$markah_terendah=$point;
			if($markah_tertinggi<$point)
				$markah_tertinggi=$point;
			$markah_jumlah=$markah_jumlah+$point;
			$jumlah_gp=$jumlah_gp+$arr_grading_info[$grade]['point'];
		}
		
		if(($q++%2)==0)
			$bg="";
		else
			$bg=""
?>
	<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
    	<td id="myborder" align="center"><?php echo "$q";?></td>
		<td id="myborder" align="center"><?php echo "$uid";?></td>
		<td id="myborder" align="center"><?php echo $lg_sexmf[$sex];?></td>
		<td id="myborder"><?php echo $name;?></td>
		<td id="myborder"><?php echo "$cname";?></td>
		<td id="myborder" align="center"><?php echo "$point";?></td>
		<td id="myborder" align="center"><?php echo "$grade";?></td>
	</tr>  

<?php  }  

$jum_pelajar_ditaksir=$q-$arr_grading_info['TT']['grade']-$arr_grading_info['TH']['grade']-$arr_grading_info['TB']['grade'];

?>
        
</table>






</td>
<td  valign="top">


<table width="100%"cellspacing="0">
  <tr>
    <td id="mytabletitle" align="center" width="60%"><?php echo strtoupper($lg_summary);?></td>
    <td id="mytabletitle" align="center" width="20%"><?php echo strtoupper($lg_total);?></td>
    <td id="mytabletitle" align="center" width="20%">%</td>
  </tr>
  <tr style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='#FFFFFF';">
    <td id="myborder" align="center"><?php echo $lg_student;?></td>
    <td id="myborder" align="center"><?php echo $q;?></td>
    <td id="myborder" align="center">100</td>
  </tr>
  <tr style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='#FFFFFF';">
    <td id="myborder" align="center"><?php echo $lg_not_evaluate;?></td>
    <td id="myborder" align="center"><?php echo $arr_grading_info['TT']['grade'];?></td>
    <td id="myborder" align="center"><?php if($q>0) echo round($arr_grading_info['TT']['grade']/$q*100);?></td>
  </tr>
  <tr style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='#FFFFFF';">
    <td id="myborder" align="center"><?php echo $lg_not_attend;?></td>
    <td id="myborder" align="center"><?php echo $arr_grading_info['TH']['grade']+$arr_grading_info['TB']['grade'];?></td>
    <td id="myborder" align="center"><?php if($q>0) echo round(($arr_grading_info['TH']['grade']+$arr_grading_info['TB']['grade'])/$q*100);?></td>
  </tr>
  <tr style="cursor:default;font-weight:bold" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='#FFFFFF';">
    <td id="myborder" align="center"><?php echo "$lg_total $lg_student";?></td>
    <td id="myborder" align="center"><?php echo $jum_pelajar_ditaksir;?></td>
    <td id="myborder" align="center"><?php if($q>0) echo round($jum_pelajar_ditaksir/$q*100);?></td>
  </tr>
<?php
if(($jum_pelajar_ditaksir>0)&&(is_numeric($markah_terendah))) {
	$sql="select * from grading where name='$gradingset' and point<=$markah_terendah order by val desc limit 1";
	$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
	$row3=mysql_fetch_assoc($res3);
	$gred_terendah=$row3['grade'];
	
	$sql="select * from grading where name='$gradingset' and point<=$markah_tertinggi order by val desc limit 1";
	$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
	$row3=mysql_fetch_assoc($res3);
	$gred_tertinggi=$row3['grade'];

	$markah_purata = $markah_jumlah/$jum_pelajar_ditaksir;
	$sql="select * from grading where name='$gradingset' and point<=$markah_purata order by val desc limit 1";
	$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
	$row3=mysql_fetch_assoc($res3);
	$gred_purata=$row3['grade'];
}else{
	$markah_terendah=0;
	$markah_tertinggi=0;
}
$sql="select * from grading where name='$gradingset' and val>=0 order by val desc";
$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
while($row3=mysql_fetch_assoc($res3)){
	$xg=$row3['grade'];
?>
  <tr style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='#FFFFFF';">
    <td id="myborder" align="center"><?php echo $xg;?></td>
    <td id="myborder" align="center"><?php echo $arr_grading_info[$xg]['grade'];?></td>
    <td id="myborder" align="center"><?php if($jum_pelajar_ditaksir>0) echo round($arr_grading_info[$xg]['grade']/$jum_pelajar_ditaksir*100);?></td>
  </tr>
<?php } ?>
  <tr style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='#FFFFFF';">
    <td id="myborder" align="center"><?php echo $lg_total_pass;?></td>
	<td id="myborder" align="center"><?php echo $jum_pelajar_ditaksir-$jumlah_fail;?></td>
    <td id="myborder" align="center"><?php if($jum_pelajar_ditaksir>0) echo round(($jum_pelajar_ditaksir-$jumlah_fail)/$jum_pelajar_ditaksir*100);?></td>
  </tr>
  <tr style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='#FFFFFF';">
    <td id="myborder" align="center"><?php echo $lg_total_fail;?></td>
	<td id="myborder" align="center"><?php echo $jumlah_fail;?></td>
    <td id="myborder" align="center"><?php if($jum_pelajar_ditaksir>0)echo round($jumlah_fail/$jum_pelajar_ditaksir*100);?></td>
  </tr>
  <tr style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='#FFFFFF';">
    <td id="myborder" align="center"><?php echo $lg_highest_mark;?></td>
    <td id="myborder" align="center"><?php echo $markah_tertinggi;?></td>
    <td id="myborder" align="center"><?php echo $gred_tertinggi;?></td>
  </tr>
  <tr style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='#FFFFFF';">
    <td id="myborder" align="center"><?php echo $lg_lowest_mark;?></td>
    <td id="myborder" align="center"><?php echo $markah_terendah;?></td>
    <td id="myborder" align="center"><?php echo $gred_terendah;?></td>
  </tr>
  <tr style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='#FFFFFF';">
    <td id="myborder" align="center"><?php echo $lg_average_mark;?></td>
    <td id="myborder" align="center"><?php printf("%d",$markah_purata);?></td>
    <td id="myborder" align="center"><?php echo $gred_purata;?></td>
  </tr>
  <tr style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='#FFFFFF';">
    <td id="myborder" align="center"><?php echo $lg_gp_subject;?></td>
    <td id="myborder" align="center"><?php if($jum_pelajar_ditaksir>0) printf("%.02f",$jumlah_gp/$jum_pelajar_ditaksir); ?></td>
    <td id="myborder" align="center"></td>
  </tr>
</table>


<?php
if(($view_graph)&&($jum_pelajar_ditaksir>0)){
	include('../examrep/rep_exam_sub_stug.php');
}
?>

</td>
</tr></table>

</div></div>
</form>	


</body>
</html>
<!-- 
**musleh version**
v2.7
20/11/2008	:gpk
v2.6
15/11/2008	: fixed percent culculation
11/11/2008	: fixed susun atur A B C
Author		: razali212@yahoo.com
 -->