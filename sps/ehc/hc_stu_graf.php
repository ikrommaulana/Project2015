<?php
include_once('../etc/config.php');
include ("$MYOBJ/jpgraph350/src/jpgraph.php");
include ("$MYOBJ/jpgraph350/src/jpgraph_bar.php");
include ("$MYOBJ/jpgraph350/src/jpgraph_line.php");



//29/03/10 4.1.0 - gui
$vmod="v5.0.0";
$vdate="100909";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");

	$adm=$_SESSION['username'];
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
	
	$show=$_REQUEST['show'];
	if($show=="")
		$show=0;

	$uid=$_REQUEST['uid'];
	$year=$_REQUEST['year'];
	if($year=="")
		$year=date('Y');
		
	$sub=$_REQUEST['sub'];
	if($sub!="")
		$sqlsub="and hc_sub.subcode='$sub'";
	
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
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>
<body>

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">

	<input type="hidden" name="uid" value="<?php echo $uid;?>">
	<input type="hidden" name="sid" value="<?php echo $sid;?>">
	<input type="hidden" name="year" value="<?php echo $year;?>">
	<input type="hidden" name="sub" value="<?php echo $sub;?>">
	
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="../ehc/hc_stu.php?uid=<?php echo "$uid&year=$year";?>" id="mymenuitem"><img src="../img/goback.png"><br>Back</a>
<a href="#" onClick="window.print();" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
</div> <!-- end mymenu -->
<div align="right">
	  <a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		<select name="show"  onChange="document.myform.submit();">
        <?php
			if($show==1)
				echo "<option value=1>Target Result</option>";
		
			if($show==2)
				echo "<option value=2>Actual Result</option>"; 
				
            echo "<option value=0>All Result</option>";
			echo "<option value=1>Target Result</option>";
			echo "<option value=2>Actual Result</option>"; 
			
				 
		?>
      </select>
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
<?php 

	$sql="select *,sub.name from hc_sub INNER JOIN sub ON hc_sub.subcode=sub.code where uid='$uid' and year=$year and sub.level=$clslevel and sub.sch_id=$sid $sqlsub $sqlsort";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
		unset($ydat);unset($ydat2);
		$sc=$row['subcode'];
		$sn=$row['name'];
		
		if($row['tov_m']>0) $ydat[0]=$row['tov_m'];
		if($row['otr1_m']>0) $ydat[1]=$row['otr1_m'];
		if($row['otr2_m']>0) $ydat[2]=$row['otr2_m'];
		if($row['otr3_m']>0) $ydat[3]=$row['otr3_m'];
		if($row['otr4_m']>0) $ydat[4]=$row['otr4_m'];
		
		if($row['tov_m']>0) $ydat2[0]=$row['tov_m'];
		if($row['ar1_m']>0) $ydat2[1]=$row['ar1_m'];
		if($row['ar2_m']>0) $ydat2[2]=$row['ar2_m'];
		if($row['ar3_m']>0) $ydat2[3]=$row['ar3_m'];
		if($row['ar4_m']>0) $ydat2[4]=$row['ar4_m'];
				
		if($q++%2==0)
			$bg="#FAFAFA";
		else
			$bg="#FAFAFA";
		$bg="";
		
		// Size of the overall graph
if($sub==""){
$width=350;
$height=250;
}else{
$width=600;
$height=400;
}
 
// Create the graph and set a scale.
// These two calls are always required
$graph=new Graph($width,$height,"auto");
$graph->SetScale('textlin');//intlin
 
 // Setup margin and titles
$graph->SetMargin(40,20,20,40);
$graph->title->Set(strtoupper($sn));
//$graph->subtitle->Set(strtoupper($sn));
$graph->xaxis->title->Set($lg_exam);
$graph->yaxis->title->Set($lg_mark);
$graph->yaxis->scale->SetAutoMin(0);
$graph->yaxis->scale->SetAutoMax(100);

// Create the linear plot
$lineplot=new LinePlot($ydat);
$lineplot->SetColor('blue');//linecolor
$lineplot->SetWeight(10);//Two pixel wide 
$lineplot->value->Show();//showing value
$lineplot->mark->SetType(MARK_SQUARE);//adding mark
$lineplot->mark->SetColor('red');
$lineplot->mark->SetFillColor('red');
$lineplot->SetLegend("Target");

$lineplot2=new LinePlot($ydat2);
$lineplot2->SetColor('blue');//linecolor
$lineplot->SetWeight(10);//Two pixel wide 
$lineplot2->mark->SetType(MARK_SQUARE); 
$lineplot2->mark->SetColor('blue');
$lineplot2->mark->SetFillColor('blue');
$lineplot2->value->Show();
$lineplot2->SetLegend("Actual");

// Add the plot to the graph
if($show==0){
	$graph->Add($lineplot);
	$graph->Add($lineplot2);
}
if($show==1)
	$graph->Add($lineplot);
if($show==2)
	$graph->Add($lineplot2);

// Display the graph
//$graph->Stroke();
$filename="../tmp/".time()."-$q".".jpg";
$graph->Stroke($filename);

if(($q%3)==1)
	echo "<tr>";

?>
 
		<td id="myborder" align="center">
			<a href="../ehc/hc_stu_graf.php?uid=<?php echo "$uid&year=$year&sub=$sc";?>"><img src="<?php echo $filename;?>"></a>
		</td>
 
<?php } ?>

 </tr>

</table>





</div></div>
</form>
</body>
</html>
