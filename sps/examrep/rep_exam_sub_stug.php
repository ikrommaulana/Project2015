<?php
include ("$MYOBJ/jpgraph/jpgraph.php");
include ("$MYOBJ/jpgraph/jpgraph_bar.php");
include ("$MYOBJ/jpgraph/jpgraph_pie.php");

	$plotidx=0;
	//$sql="select count(*) from exam where sch_id=$sid and year='$year' and cls_level=$clslevel $sqlclscode $sqlsubcode and examtype='$examcode' and (grade!='TT' and grade!='TH')";
	$sql="SELECT count(*) FROM exam INNER JOIN stu ON exam.stu_uid=stu.uid where exam.sch_id=$sid and exam.cls_level=$clslevel $sqlsex $sqlclscode $sqlsubcode and year='$year' and examtype='$examcode' and (grade!='TT' and grade!='TH')";	
	$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$row3=mysql_fetch_row($res3);
	$jum=$row3[0];
		
	$i=0;
	$sql="select * from grading where name='$gradingset' and val!=-1 order by val desc";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row2=mysql_fetch_assoc($res2)){
		$p=$row2['grade'];
		$datax[$i]=$p;
		$datax_pie[$i]="$p-%d%%";
		$isfail=$row2['sta'];
		//$sql="select count(*) from exam where sch_id=$sid and year='$year' and cls_level=$clslevel $sqlclscode $sqlsubcode and examtype='$examcode' and grade='$p'";
		$sql="SELECT count(*) FROM exam INNER JOIN stu ON exam.stu_uid=stu.uid where exam.sch_id=$sid and exam.cls_level=$clslevel $sqlsex $sqlclscode $sqlsubcode and year='$year' and examtype='$examcode' and grade='$p'";
		$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$row3=mysql_fetch_row($res3);
		$x=$row3[0];
		if($jum>0)
			$per=round($x/$jum*100,1);
		else
			$per=0;

			 
		$data_bil[$i]=$x;         
		$data_per[$i]=round($per);
		$data_pie_label[$i]="$p (".round($per)."%%)";
		$i++;
	}
	
if($jum>0){

	$plot[$plotidx] = new BarPlot($data_bil);
	$plot[$plotidx]->value->SetFormat('%d');//or will ada 1 titik perpuluhan
	$plot[$plotidx]->SetFillColor($color[$plotidx]);
	//$plot[$plotidx]->SetShadow('gray@0.1',3);
	$plot[$plotidx]->value->Show();//show val top off bar
	$plot[$plotidx]->SetLegend("Bilangan");
	
	$plot[$plotidx+1] = new BarPlot($data_per);
	$plot[$plotidx+1]->value->SetFormat('%d');//or will ada 1 titik perpuluhan
	$plot[$plotidx+1]->SetFillColor($color[$plotidx+1]);
	//$plot[$plotidx+1]->SetShadow('gray@0.1',3);
	$plot[$plotidx+1]->value->SetFormat('%d');
	$plot[$plotidx+1]->value->Show();//show val top off bar
	$plot[$plotidx+1]->SetLegend("Peratusan");
	
	$graph = new Graph(350,200,"auto");    
	$graph->SetScale("textlin");
	$graph->SetFrame(false);
	$graph->img->SetMargin(30,30,30,30);
	
	$gplot = new GroupBarPlot($plot);// Create the grouped bar plot
	$graph->Add($gplot);
	//$graph->title->Set("Rumusan Pencapaian");
	//$graph->xaxis->title->Set("Gred Peperiksaan");
	//$graph->yaxis->title->Set("Pelajar");
	
	$graph->xaxis->SetTickLabels($datax);// add text val for x
	//$graph->title->SetFont(FF_FONT1,FS_BOLD);
	//$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
	//$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
	//$graph->SetBackgroundGradient('blue','black:0.5',GRAD_HOR,BGRAD_PLOT);
	//$graph->legend->SetShadow('gray@0.4',1);
	$graph->legend->SetPos(0,0,'right','top');
	// Display the graph
	$filebar="../tmp/".time().".jpg";
	$graph->Stroke($filebar);
}
?>

<?php
$graph  = new PieGraph (400,300);
//$graph->SetShadow();
//$graph->title-> Set( "Peratus Pencapaian $subname");
$p1 = new PiePlot($data_bil);
//$p1->SetLegends($datax);
//$p1->SetLabelType(PIE_VALUE_PER);
//$p1->value->SetFormat('%2.1f%% ss');
$p1->SetGuideLines();
$p1->SetGuideLinesAdjust(1.4);
//$p1->ExplodeAll(10);
//$p1->value->SetFont(FF_ARIAL,FS_BOLD,10);
$p1->SetLabels($data_pie_label);

//$graph->legend->SetPos(0,0,'right','top');
$graph->SetFrame(false);
$graph->Add( $p1);
$filepie="../tmp/".time()."x.jpg";
$graph->Stroke($filepie);
?>
<div id="myborder">
<div id="mytitle" align="center">Pencapaian  <?php echo "$examname - $subname - "; if($clscode!="") echo "$clsname / $year"; else echo "$namatahap $clslevel / $year";?></div>
<table width="100%">
<tr>
<td width="100%" align="center"><img src="<?php echo $filebar;?>" width="100%"></td>
</tr>
<!-- 
<tr>
<td width="100%" align="center"><img src="<?php echo $filepie;?>" width="100%"></td>
</tr>
 -->
</table>

</div>