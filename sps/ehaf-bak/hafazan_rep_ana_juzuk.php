<?php
include ("$MYOBJ/jpgraph/jpgraph.php");
include ("$MYOBJ/jpgraph/jpgraph_bar.php");
$vmod="v5.0.0";
$vdate="100909";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");

$color[0]="gold";
$color[1]="pink3";
$color[2]="deepskyblue4";
$color[3]="yellow";
$color[4]="gray7";
$color[5]="ivory2";
$color[6]="mediumpurple";
$color[7]="orangered";
$color[8]="salmon";
$color[9]="coral";
$color[10]="thistle";
$color[11]="tan";

verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');

if($_SESSION['sid']!=0)
	$disabled="disabled";
		
	$p=$_REQUEST['p'];

	$sid=$_POST['sid'];
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
            $schname=$row['name'];
			$simg=$row['img'];
			$schsname=$row['sname'];
			$schlevel=$row['level'];
			$namatahap=$row['clevel'];
            mysql_free_result($res);					  
	}
		
	$all_school=$_POST['all_school'];
	if($all_school=="")
		$all_school="0";
	$view_graph=$_POST['view_graph'];
	if($view_graph=="")
		$view_graph="0";
	$report_type=$_POST['report_type'];
	if($report_type=="")
		$report_type="1";
		
	$clslevel=$_POST['clslevel'];
	if($clslevel=="")
		$clslevel="0";
	if($clslevel>0)
		 $sqlclslevel=" and cls_level=$clslevel";


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

	$grading="Juzuk_T$clslevel";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>

<body >

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">

<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
<input name="order" type="hidden" id="order" value="<?php echo $order;?>">
<input type="hidden" name="p" value="<?php echo $p;?>">




<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
</div>


<div id="viewpanel" align="right" >
<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
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
		   <select name="sid" id="sid"  onchange="document.myform.submit();">
                <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select $lg_school -</option>";
			else
                echo "<option value=$sid>$schname</option>";
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
					$s=$row['name'];
					$t=$row['id'];
					echo "<option value=$t>$s</option>";
				}
				mysql_free_result($res);
			}					  
			
?>
        </select>
		<select name="clslevel" id="clslevel" onchange="document.myform.submit();">
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
		
		   <input type="button" onClick="document.myform.submit();" value="View">		
	
	<br>
	<input type="checkbox" name="all_school" value="1" <?php if($all_school) echo "checked";?> onClick="document.myform.submit();"><?php echo $lg_school_analysis;?>

</div><!-- view panel -->


</div>

<div id="story">

<div id="mytitlebg" align="center">
	<?php echo strtoupper($lg_total_juz_analysis_report );?> : <?php echo strtoupper("$monthname $year");?>
</div>

<table width="100%" id="mytitle" style="background:none">
  <tr>
    <td width="100%" align="center">
		<table width="100%" >
		  <tr>
			<td width="33%" align="right"><?php echo $lg_school;?> : <?php if($all_school!="1")echo $schname; else echo "$schlevel";?></td>
			<td width="33%" align="center">&nbsp;</td>
			<td width="33%" align="center"><?php echo $lg_class;?>  : <?php if($clslevel==0) echo "$lg_all"; else echo "$namatahap $clslevel";?></td>
		  </tr>
		</table>
	</td>
  </tr>
</table>

<table width="100%"  cellspacing="0" cellpadding="2">
	<tr>
		<td id="mytableheader" width="1%" align="center" rowspan="2"><?php echo strtoupper($lg_no);?></td>
        <td id="mytableheader" style="border-left:none" width="15%" align="center" rowspan="2"><?php echo strtoupper($lg_school);?></td>
		<td id="mytableheader" style="border-left:none" width="3%" align="center" rowspan="2"><?php echo strtoupper($lg_total_student);?></td>
<?php
$num_of_grade=0;
$minval=0;$maxval=0;
$sql="select * from grading where name='$grading' order by val";
$res=mysql_query($sql)or die("query failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){
			$d=$row['des'];
			$g=$row['grade'];
			$p=$row['point'];
			$v=$row['val'];
			$datax[$num_of_grade]="$g";
			$total_grade[$num_of_grade]=0;
			$num_of_grade++;
			$colspan=2+($v-$p)+1;
			if(($minval==0)||($minval>$p))
				$minval=$p;
			if(($maxval==0)||($maxval<$p))
				$maxval=$v;
?>
			<td id="mytableheader" style="border-left:none" width="4%" align="center" colspan="<?php echo $colspan;?>"><?php echo "$g<br>$p - $v Juzuk";?></td>
<?php } ?>
    </tr>
	<tr>
<?php
$sql="select * from grading where name='$grading' order by val";
$res=mysql_query($sql)or die("query failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){
	$p=$row['point'];
	$v=$row['val'];
	for($i=$p;$i<=$v;$i++){
?>
		<td id="mytableheader" style="border-left:none; border-top:none" width="2%" align="center"><?php echo $i;?></td>
<?php } ?>
		<td id="mytableheader" style="border-left:none; border-top:none" width="2%" align="center">%</td>
		<td id="mytableheader" style="border-left:none; border-top:none" width="2%" align="center"><?php echo $lg_no;?></td>
<?php } ?>
    </tr>

<?php
	$plotidx=0;
	$total_all_stu=0;
	if($all_school)
		$sql="select * from sch where level='$schlevel'";
	else
		$sql="select * from sch where id='$sid'";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$xschname=$row['name'];
		$xschsname=$row['sname'];
		$xsid=strtoupper($row['id']);
		
		$sql="select count(*) from hafazan_sem where sid=$xsid and year='$year' and cls_level=$clslevel and (currjk>=$minval and currjk<=$maxval)";
		$resx=mysql_query($sql)or die("$sql - failed:".mysql_error());
		$rowx=mysql_fetch_row($resx);
		$jum_calon=$rowx[0];
		$total_all_stu=$total_all_stu+$jum_calon;
		$q++;
?>
	<tr>
    	<td id="myborder_lrb" width="1%" align="center"><?php echo $q;?></td>
    	<td id="myborder_rb" width="15%"><?php echo $xschname;?></td>
		<td id="myborder_rb" width="3%" align="center"><?php echo $jum_calon;?></td>
<?php
		$i=0;
		$sql="select * from grading where name='$grading' order by val";
    	$resx=mysql_query($sql)or die("query failed:".mysql_error());
        while($rowx=mysql_fetch_assoc($resx)){
			$g=$rowx['grade'];
			$p=$rowx['point'];
			$v=$rowx['val'];
			$bil=0;
			$sql="select count(*) from hafazan_sem where sid=$xsid and year='$year' and cls_level=$clslevel and (currjk>=$p and currjk<=$v)";
			$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
			if($rowxx=mysql_fetch_row($resxx))
				$bil=$rowxx[0];
			if($bil>0) 
				$per=round($bil/$jum_calon*100,1); 
			else 
				$per=0;
			$data_bil[$i]=$bil;
			$data_per[$i]=$per;
			$jumlah_pelajar=$jumlah_pelajar+$bil;
			$total_grade[$i]=$total_grade[$i]+$bil;
			$i++;
					

	for($j=$p;$j<=$v;$j++){
		$xbil=0;
		$sql="select count(*) from hafazan_sem where sid=$xsid and year='$year' and cls_level=$clslevel and currjk=$j";
		$resxxx=mysql_query($sql)or die("$sql failed:".mysql_error());
		if($rowxxx=mysql_fetch_row($resxxx))
			$xbil=$rowxxx[0];
?>
		<td id="myborder_rb" width="2%" align="center"><?php echo $xbil;?></td>
<?php } ?>
			<td id="myborder_rb" width="2%" align="center" style="background-color:#FAFAFA "><?php printf("%.1f",$per);?></td>
			<td id="myborder_rb" width="2%" align="center" style="background-color:#FAFAFA "><?php echo "$bil";?></td>
<?php }//while ?>
    </tr>
<?php
if(($sid>0)&&($view_graph==1)&&($clslevel>0)){
// Create the bar plots
if($report_type){
	$plot[$plotidx] = new BarPlot($data_per);
	$plot[$plotidx]->value->SetFormat('%.1f');//or will ada 1 titik perpuluhan
	//$plot[$plotidx]->value->SetAngle(45); //kasi senget set
	//$plot[$plotidx]->value->SetFont(FF_ARIAL,FS_BOLD,10);
}
else{
	$plot[$plotidx] = new BarPlot($data_bil);
	$plot[$plotidx]->value->SetFormat('%d');//or will ada 1 titik perpuluhan
}
$plot[$plotidx]->SetFillColor($color[$plotidx]);
$plot[$plotidx]->value->Show();//show val top off bar
$plot[$plotidx]->SetLegend($xschsname);
//$plot[$plotidx]->SetValuePos('center');// Center the values in the bar or will show kat atas
$plotidx++;
}

}//end while 

?>

<tr id="mytitlebg">
		<td id="myborder_lrb" align="center" ></td>
        <td id="myborder_rb" align="center" ><?php echo strtoupper($lg_total);?></td>
		<td id="myborder_rb" align="center" ><?php echo $total_all_stu;?></td>

<?php
		$i=0;
		if(!$all_school)
			$sqlsid="and sid=$xsid";
		else
			$sqlsid="";
		$sql="select * from grading where name='$grading' order by val";
    	$resx=mysql_query($sql)or die("query failed:".mysql_error());
        while($rowx=mysql_fetch_assoc($resx)){
			$g=$rowx['grade'];
			$p=$rowx['point'];
			$v=$rowx['val'];
			$bil=0;
			$sql="select count(*) from hafazan_sem where year='$year' $sqlsid and cls_level=$clslevel and (currjk>=$p and currjk<=$v)";
			$resxx=mysql_query($sql)or die("$sql failed:".mysql_error());
			if($rowxx=mysql_fetch_row($resxx))
				$bil=$rowxx[0];
			if($bil>0) 
				$per=round($bil/$total_all_stu*100,1); 
			else 
				$per=0;
			$i++;
					

	for($j=$p;$j<=$v;$j++){
		$xbil=0;
		$sql="select count(*) from hafazan_sem where year='$year' $sqlsid and cls_level=$clslevel and currjk=$j";
		$resxxx=mysql_query($sql)or die("$sql failed:".mysql_error());
		if($rowxxx=mysql_fetch_row($resxxx))
			$xbil=$rowxxx[0];
?>
		<td id="myborder_rb" width="2%" align="center"><?php echo $xbil;?></td>
<?php } ?>
			<td id="myborder_rb" width="2%" align="center" style="background-color:#FAFAFA "><?php printf("%.1f",$per);?></td>
			<td id="myborder_rb" width="2%" align="center" style="background-color:#FAFAFA "><?php echo "$bil";?></td>
<?php }//while ?>
    </tr>
	
</table>
<br>
<div id="gpanel" align="right" style="background-color:#FFFFFF; font-weight:bold;  class="printhidden" >
<input type="checkbox" name="view_graph" value="1" <?php if($view_graph) echo "checked";?> onClick="document.myform.submit();"><?php echo $lg_graph;?>
<input type="radio" name="report_type" value="1" <?php if($report_type) echo "checked";?> onClick="document.myform.submit();"><?php echo $lg_grade_percentage;?>
<input type="radio" name="report_type" value="0" <?php if(!$report_type) echo "checked";?> onClick="document.myform.submit();"><?php echo $lg_total_student;?>
</div>
<?php
if(($sid>0)&&($view_graph==1)&&($clslevel>0)){
// Create the graph. These two calls are always required
$graph = new Graph(680,220,"auto");    
$graph->SetScale("textlin");
$graph->SetFrame(false);
$graph->img->SetMargin(40,100,40,40);

$gplot = new GroupBarPlot($plot);// Create the grouped bar plot
$graph->Add($gplot);
$graph->title->Set(strtoupper("$lg_juz_report"));
$graph->xaxis->title->Set($lg_total_juz);
if($report_type)
	$graph->yaxis->title->Set("$lg_student_percentage (%)");
else
	$graph->yaxis->title->Set("$lg_total_student");
$graph->xaxis->SetTickLabels($datax);// add text val for x
$graph->legend->SetShadow('gray@0.4',1);
$graph->legend->SetPos(0,0.1,'right','top');
// Display the graph
$filename="../tmp/".time().".jpg";
$graph->Stroke($filename);
?>

<table width="100%">
<tr><td>
<img src="<?php echo $filename;?>">
</td></tr>
</table>
<?php } ?>

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