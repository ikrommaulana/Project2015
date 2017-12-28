<?php
include ("$MYOBJ/jpgraph/jpgraph.php");
include ("$MYOBJ/jpgraph/jpgraph_bar.php");
include ("$MYOBJ/jpgraph/jpgraph_line.php");

if($report_type!=1){
	$sql="select count(*) from examrank where sch_id=$sid and year='$year' and cls_level='$clslevel' and exam='$examcode'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_row($res);
	$jum_calon=$row[0];
	
	$sqlclslevel="and cls_level=$clslevel";
	//$sql="select count(*) from sub where sch_id=$sid and level=$clslevel";
	$sql="select max(g1) from examrank where sch_id=$sid and year='$year' and cls_level='$clslevel' and exam='$examcode'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_row($res);
	$max_a=$row[0];
	$data_bil[0]=0;         
	$data_per[0]=0;
	$datax[0]="";
	if($max_a>0){
		$j=0;$val=0;$per=0;
		for($i=$max_a;$i>=0;$i--){		
			$sql="select count(*) from examrank where sch_id=$sid and year='$year' and cls_level='$clslevel' and exam='$examcode'  and g1=$i";
			$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			$row=mysql_fetch_row($res);
			$val=$row[0];
			if($val==0)	continue;
			if($jum_calon>0)
				$per=round($val/$jum_calon*100,0);
			else
				$per=0;
			$data_bil[$j]=$val;         
			$data_per[$j]=$per;
			$datax[$j]="$i"."A";
			$j++;
		}
	}

	$plotidx=0;
	$plot[$plotidx] = new BarPlot($data_bil);
	$plot[$plotidx]->value->SetFormat('%d');//or will ada 1 titik perpuluhan
	$plot[$plotidx]->SetFillColor($color[$plotidx]);
	$plot[$plotidx]->SetShadow('gray@0.1',3);
	$plot[$plotidx]->value->Show();//show val top off bar
	$plot[$plotidx]->SetLegend("Bilangan");
	
	$plot[$plotidx+1] = new BarPlot($data_per);
	$plot[$plotidx+1]->value->SetFormat('%d');//or will ada 1 titik perpuluhan
	$plot[$plotidx+1]->SetFillColor($color[$plotidx+1]);
	$plot[$plotidx+1]->SetShadow('gray@0.1',3);
	$plot[$plotidx+1]->value->SetFormat('%d%%');
	$plot[$plotidx+1]->value->Show();//show val top off bar
	$plot[$plotidx+1]->SetLegend("Peratusan");
	
	$graph = new Graph(680,250,"auto");    
	$graph->SetScale("textlin");
	$graph->SetFrame(false);
	$graph->img->SetMargin(40,100,40,40);
	
	$gplot = new GroupBarPlot($plot);// Create the grouped bar plot
	$graph->Add($gplot);
	$graph->xaxis->title->Set("GRED SKOR A PEPERIKSAAN");
	$graph->yaxis->title->Set("Peratus & Bilangan Pelajar");
	
	$graph->xaxis->SetTickLabels($datax);// add text val for x
	//$graph->title->SetFont(FF_FONT1,FS_BOLD);
	//$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
	//$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
	//$graph->SetBackgroundGradient('blue','black:0.5',GRAD_HOR,BGRAD_PLOT);
	//$graph->legend->SetShadow('gray@0.4',1);
	$graph->legend->SetPos(0,0.1,'right','top');
	// Display the graph
	$filename="../tmp/".time().".jpg";
	$graph->Stroke($filename);
	
} 
if($report_type){
		
		$graph = new Graph(880,250,"auto");    
		$graph->SetScale("textlin");
		$graph->SetFrame(false);
		$graph->img->SetMargin(40,100,40,0);

		$sql="select max(g1) from examrank INNER JOIN sch ON examrank.sch_id=sch.id where sch.level='$slevel' and examrank.year='$year' and examrank.cls_level='$clslevel' and examrank.exam='$examcode'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_row($res);
		$max_a=$row[0];

		$sql="select * from sch where level='$slevel'";
		$resx=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$j=0;
		$datax[0]="";
		for($i=$max_a;$i>=0;$i--)
			$datax[$j++]="$i"."A";
		$plotidx=0;
		while($rowx=mysql_fetch_assoc($resx)){
			$xsname=$rowx['sname'];
			$xsid=$rowx['id'];
			$sql="select count(*) from examrank where sch_id=$xsid and year='$year' and cls_level='$clslevel' and exam='$examcode'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			$row=mysql_fetch_row($res);
			$jum_calon=$row[0];
			$data_bil[0]=0;         
			$data_per[0]=0;
			$data_total[0]=0;
			$j=0;
			for($i=$max_a;$i>=0;$i--){		
				$sql="select count(*) from examrank where sch_id=$xsid and year='$year' and cls_level='$clslevel' and exam='$examcode'  and g1=$i";
				$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				$row=mysql_fetch_row($res);
				$val=$row[0];
				$data_bil[$j]=$val;
				if($jum_calon>0)
					$data_per[$j]=$val/$jum_calon*100;
				else
					$data_per[$j]=0;
				$data_total[$j]=$jum_calon;
				//echo "XX:$val/$jum_calon*100=".$data_per[$j]."<br>";
				$j++;
			}
			//create plot for this school/
			$plot[$plotidx] = new BarPlot($data_per);
			$plot[$plotidx]->value->SetFormat('%d');//or will ada 1 titik perpuluhan
			$plot[$plotidx]->SetFillColor($color[$plotidx]);
			//$plot[$plotidx]->SetShadow('gray@0.1',3);
			$plot[$plotidx]->value->Show();//show val top off bar
			$plot[$plotidx]->SetLegend($xsname);
			
			// Create the linear plot
			/**
			$line[$plotidx]=new LinePlot($data_per);
			$line[$plotidx]->SetColor($color[$plotidx]);
			$line[$plotidx]->value->SetFormat('%d%%');
			$line[$plotidx]->value->Show();
			$line[$plotidx]->SetWeight(2);
			$line[$plotidx]->SetBarCenter();
			$graph->Add($line[$plotidx]);
			**/
			// Create acc bar
			/**
			$line[$plotidx]=new BarPlot($data_total);
			$line[$plotidx]->SetFillColor("white");
			$line[$plotidx]->value->SetFormat('%d');
			$line[$plotidx]->value->Show();
			$abplot[$plotidx]  = new AccBarPlot (array($plot[$plotidx] ,$line[$plotidx]));
			**/
			$plotidx++;
		}

		$gplot = new GroupBarPlot($plot);// Create the grouped bar plot
		$graph->Add($gplot);
		
		$graph->xaxis->title->Set("GRED SKOR A PEPERIKSAAN");
		$graph->yaxis->title->Set("Peratusan A Pelajar");
		
		$graph->xaxis->SetTickLabels($datax);// add text val for x
		//$graph->title->SetFont(FF_FONT1,FS_BOLD);
		//$graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
		//$graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);
		//$graph->SetBackgroundGradient('blue','black:0.5',GRAD_HOR,BGRAD_PLOT);
		//$graph->legend->SetShadow('gray@0.4',1);
		$graph->legend->SetPos(0,0.1,'right','top');
		// Display the graph
		$filename="../tmp/xx".time().".jpg";
		$graph->Stroke($filename);
}
?>
<table width="100%" >
<tr>
<td width="100%" align="center"><img src="<?php echo $filename;?>"></td>
</tr>
</table>
