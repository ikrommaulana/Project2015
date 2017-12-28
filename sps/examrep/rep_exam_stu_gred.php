<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK|KEWANGAN|GURU');

	if($_SESSION['sid']!=0)
		$disabled="disabled";
		
		$isprint=$_REQUEST['isprint'];
		$update_result=$_REQUEST['update_result'];

		$sid=$_POST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
	
		$view_graph=$_POST['view_graph'];
		
		$examcode=$_POST['exam'];
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
			$simg=$row['img'];
			$slevel=$row['level'];
			$namatahap=$row['clevel'];
            mysql_free_result($res);					  
		}
		else{
			$namatahap="Tahap";
		}
		
		$report_type=$_POST['report_type'];
		
		$xclslevel=$_REQUEST['clslevel'];
		if($xclslevel=="")
			$clslevel="-1";
		else
			$clslevel=$xclslevel;
			
		$sqlclslevel="and cls_level=$clslevel";
			
			if($report_type)
				$sql="select max(g1) from examrank where year='$year' and cls_level='$clslevel' and exam='$examcode'";
			else
				$sql="select max(g1) from examrank where sch_id=$sid and year='$year' and cls_level='$clslevel' and exam='$examcode'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			$row=mysql_fetch_row($res);
			$jum_sub=$row[0];

		
		if($jum_sub=="")
			$jum_sub=0;
	
		$sql="select * from type where sid='$sid' and prm='$clslevel' and grp='classlevel'";
    	$res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$grading=$row['code'];
		$sql="select grade from grading where name='$grading' order by val desc limit 1";
    	$res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$grade=$row['grade'];
		
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
		$sqlsort="order by point $order";
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
<input type="hidden" name="p" value="../examrep/rep_exam_stu_gred">
<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
<input name="order" type="hidden" id="order" value="<?php echo $order;?>">
<input type="hidden" name="isprint" value="<?php echo $isprint;?>">

<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
</div>


<div id="viewpanel" align="right" >
		  <select name="year" id="year" onchange="document.myform.submit();">
<?php
            echo "<option value=$year>SESI $year</option>";
			$sql="select * from type where grp='session' and prm!='$year' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        echo "<option value=\"$s\">SESI $s</option>";
            }
            mysql_free_result($res);					  
?>
          </select>
		   <select name="sid" id="sid"  onchange="document.myform.submit();">
                <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- Pilih Sekolah -</option>";
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
		<select name="clslevel" id="clslevel" onchange="document.myform.submit();">
<?php    
		if($xclslevel=="")
            	echo "<option value=\"\">- Pilih Tahap -</option>";
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
		<select name="exam" id="exam" onchange="document.myform.submit();">
             <?php	
      				if($examcode=="")
						echo "<option value=\"\">- Pilih Ujian -</option>";
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
		   <input type="button" onClick="document.myform.submit();" value="View">		
	
	<br>
	<input type="checkbox" name="report_type" value="1" <?php if($report_type) echo "checked";?> onClick="document.myform.submit();">Analisis Perbandingan Sekolah
	<input type="checkbox" name="view_graph" value="1" <?php if($view_graph) echo "checked";?> onClick="document.myform.submit();">View Graph
</div><!-- view panel -->


</div>

<div id="story">

<?php if($simg!=""){?>
<div id="mytitle" align="center" style="border:none" class="screen_hide">
	<img src=<?php echo "$simg";?> ><br>
</div>
<?php } ?>

<div id="mytitle" align="center" style="border:none">
LAPORAN SKOR A
</div>

<table width="100%" id="mytitle">
  <tr>
    <td width="80%">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="33%" align=right><?php echo $lg_sekolah;?> : <?php if($report_type!="1")echo $sname; else echo "$slevel";?></td>
			<td width="33%" align=center><?php if($clscode!="") echo "$clsname / $year"; else echo "$namatahap $clslevel / $year";?></td>
			<td width="33%" align=left>Peperiksaan : <?php echo $examname;?></td>
		  </tr>
		</table>
	
	</td>
  </tr>
</table>

<table width="100%">
	<tr>
		<td id="mytabletitle" width="1%" align="center" rowspan="2">NO</td>
        <td id="mytabletitle" width="15%" align="center" rowspan="2">Sekolah</td>
		<td id="mytabletitle" width="3%" align="center" rowspan="2">Jumlah<br>Calon</td>
		<?php for($i=$jum_sub;$i>=0;$i--){?>
		<td id="mytabletitle" width="4%" align="center" colspan="2"><?php echo "$i&nbsp;$grade";?></td>
		<?php } ?>
    </tr>
	<tr>
		<?php for($i=$jum_sub;$i>=00;$i--){?>
		<td id="mytabletitle" width="2%" align="center">Bil</td>
		<td id="mytabletitle" width="2%" align="center">%</td>
		<?php } ?>
    </tr>

<?php
	if($report_type)
		$sql="select * from sch where level='$slevel'";
	else
		$sql="select * from sch where id='$sid'";
		
	$resx=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($rowx=mysql_fetch_assoc($resx)){
		$sname=$rowx['name'];
		$xsid=strtoupper($rowx['id']);
		
		$sql="select count(*) from examrank where sch_id=$xsid and year='$year' and cls_level='$clslevel' and exam='$examcode'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_row($res);
		$jum_calon=$row[0];
		$q++;
?>
	<tr>
    	<td id="myborder" width="1%" align="center"><?php echo $q;?></td>
    	<td id="myborder" width="15%"><?php echo $sname;?></td>
		<td id="myborder" width="3%" align="center"><?php echo $jum_calon;?></td>
		<?php 
			for($i=$jum_sub;$i>=0;$i--){
					$sql="select count(*) from examrank where sch_id=$xsid and year='$year' and cls_level='$clslevel' and exam='$examcode'  and g1=$i";
					$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					$row=mysql_fetch_row($res);
					$val=$row[0];
			?>
			<td id="myborder" width="2%" align="center"><?php echo "$val";?></td>
			<td id="myborder" width="2%" align="center"><?php if($jum_calon>0) echo round($val/$jum_calon*100,2);?></td>
		<?php } ?>
    </tr>
<?php } ?>
</table>
<br>



<?php
if($view_graph){
	include('../examrep/rep_exam_stu_gredg.php');
}
?>
</div></div>
</form>	
<form name="formwindow" method="post" action="../examrep/rep_exam_stu_gred.php" target="newwindow">
	<input name="isprint" type="hidden" id="isprint" value="1">
	<input name="clslevel" type="hidden" id="exam" value="<?php echo $clslevel;?>">
	<input name="view_graph" type="hidden" id="exam" value="<?php echo $view_graph;?>">
	<input name="sid" type="hidden" id="sid" value="<?php echo $sid;?>">
	<input name="clscode" type="hidden" id="clscode" value="<?php echo $clscode;?>">
	<input name="subcode" type="hidden" id="clscode" value="<?php echo $subcode;?>">
	<input name="year" type="hidden" id="year" value="<?php echo $year;?>">
	<input name="exam" type="hidden" id="year" value="<?php echo $examcode;?>">
	<input name="sex" type="hidden" id="year" value="<?php echo $sex;?>">
	<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
	<input name="report_type" type="hidden" id="clscode" value="<?php echo $report_type;?>">
	<input name="order" type="hidden" id="order" value="<?php echo $order;?>">
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