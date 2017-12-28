<?php
include ("$MYOBJ/jpgraph/jpgraph.php");
include ("$MYOBJ/jpgraph/jpgraph_bar.php");
include ("$MYOBJ/jpgraph/jpgraph_line.php");
$vmod="v5.0.0";
$vdate="12/11/2010";

include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN|HEP|HEP-OPERATOR");
$username = $_SESSION['username'];

		$uid=$_REQUEST['uid'];
		$sid=$_REQUEST['sid'];
		$year=$_REQUEST['year'];
		if($year=="")
			$year=date("Y");

		$sql="select * from stu where uid='$uid' and sch_id=$sid";
		$res=mysql_query($sql) or die(mysql_error());
		$row=mysql_fetch_assoc($res);
		$name=$row['name'];
		$uid=$row['uid'];
		$ic=$row['ic'];
		$sid=$row['sch_id'];
		$rdate=$row['rdate'];
		$file=$row['file'];
				
		$sql="select * from sch where id='$sid'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=$row['name'];
		$slevel=$row['level'];
		$addr=$row['addr'];
		$state=$row['state'];
		$tel=$row['tel'];
		$fax=$row['fax'];
		$web=$row['url'];
		$school_img=$row['img'];
        mysql_free_result($res);
		
		$clslevel=0;
		$sql="select * from ses_stu where stu_uid='$uid' and year='$year'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		if($row=mysql_fetch_assoc($res)){
			$clsname=$row['cls_name'];
			$clscode=$row['cls_code'];
			$clslevel=$row['cls_level'];
		}
		$sql="select * from type where sid='$sid' and prm='$clslevel' and grp='classlevel'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$grading=$row['code'];
		
		$exam=$_REQUEST['exam'];
		$exam="PS2";
		$sql="select * from type where grp='exam' and code='$exam'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $examname=$row['prm'];
			
		//check guru kelas for this subject
		$sql="select count(*) from ses_cls where sch_id=$sid and cls_code='$clscode' and usr_uid='$username' and year='$year'";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
		$row2=mysql_fetch_row($res2);
		$gurukelas=$row2[0];
		
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<input type="hidden" name="uid" value="<?php echo "$uid";?>">
		<input type="hidden" name="sid" value="<?php echo "$sid";?>">
		<input type="hidden" name="p" value="../examrep/rep_exam_stu_performance">

<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
		<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
	</div>
	<div align="right">
	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
	<select name="year" id="year" onchange="document.myform.submit();">
<?php
            echo "<option value=$year>$lg_seesion $year</option>";
			$sql="select * from type where grp='session' and prm!='$year' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                        echo "<option value=\"$s\">$lg_seesion $s</option>";
            }
            mysql_free_result($res);					  
?>
		</select>
	
	</div>
</div><!-- end mypanel -->

<div id="story" >
<?php include ('../inc/school_header.php'); ?> 
<div id="mytitle" align="center" style="background:none ">ANALISIS PRESTASI PELAJAR  <?php echo $year;?></div>

<table width="100%" >
  <tr>
  <?php if($file!=""){?>
  	<td width="8%" align="center">
		<img name="picture" src="<?php if($file!="") echo "$dir_image_student$file"; ?>"  width="70" height="75" id="myborderfull" style="padding:3px 3px 3px 3px ">
	</td>
 <?php } ?>
    <td width="50%" valign="top">	
	<table width="100%" >
      <tr>
        <td width="35%"><?php echo strtoupper($lg_name);?></td>
        <td width="1%">:</td>
        <td width="64%">&nbsp;<?php echo "$name";?></td>
      </tr>
      <tr>
        <td><?php echo strtoupper($lg_matric);?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$uid";?> </td>
      </tr>
      <tr>
        <td><?php echo strtoupper($lg_ic_number);?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$ic";?> </td>
      </tr>
		<tr>
        <td><?php echo strtoupper($lg_register);?> </td>
        <td>:</td>
        <td>&nbsp;<?php list($xy,$xm,$xd)=split('[-]',$rdate); echo "$xd-$xm-$xy";?></td>
      </tr>
     
    </table>


	</td>
    <td width="50%" valign="top">
	
	<table width="100%" >
      <tr>
        <td width="30%" ><?php echo strtoupper($lg_school);?></td>
        <td width="1%">:</td>
        <td width="69%">&nbsp;<?php echo "$sname";?></td>
      </tr>
	   <tr>
        <td><?php echo strtoupper($lg_session);?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$year";?> </td>
      </tr>
	  <tr>
        <td><?php echo strtoupper($lg_class);?></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$clsname";?> </td>
      </tr>
    </table>
 	</td>
  </tr>
</table>
<!-- 
<table width="100%" id="mytable">
  <tr>
    <td id="mytabletitle" align="center" width="3%">NO</td>
	<td id="mytabletitle" width="30%">&nbsp;PEPERIKSAAN</td>
    <td id="mytabletitle" align="center" width="10%">JUMLAH SUBJEK</td>
	<td id="mytabletitle" align="center" width="10%">NILAI PURATA</td>
	<td id="mytabletitle" align="center" width="10%">GRED POINT</td>
  </tr>
 -->
<?php
$x=0;
$sql="select * from type where grp='exam' and val=1 and (lvl=0 or lvl=$clslevel) and (sid=0 or sid=$sid) order by idx";
$resxxx=mysql_query($sql)or die("query failed:".mysql_error());
while($rowxxx=mysql_fetch_assoc($resxxx)){
		$examname=$rowxxx['prm'];
		$exam=$rowxxx['code'];
		
		$FOUND=0;
		$sql="select stu.*,examrank.* from stu INNER JOIN examrank ON stu.uid=examrank.stu_uid where examrank.sch_id=$sid and year='$year' and cls_code='$clscode' and exam='$exam' and stu_uid='$uid' order by avg desc,g1 desc,g2 desc,g3 desc,g4 desc,g5 desc";
		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$avg=0;$totalsub=0;$min=0;$max=0;$totalpoint=0;$avggred=0;$gpk=0;
		if($row=mysql_fetch_assoc($res)){
			$xuid=$row['uid'];
			$avg=$row['avg'];
			$max=$row['max'];
			$min=$row['min'];
			$gpk=$row['gpk'];
			$totalsub=$row['total_sub'];
			$totalpoint=$row['total_point'];
			$avggred=$row['avg_gred'];
			$avg=sprintf("%d",$avg);
			$gpk=sprintf("%.02f",$gpk);
			$FOUND=1;
		}
		$jenis_exam[$x] = $exam; 
		$markah_purata[$x] = $avg;
		$kedudukan_kelas=0;
		$kedudukan_tahap = 0;
		$total_tahap=0;
		$total_kelas=0;
		if($totalsub>0){
			//check kedudukan pelajar..
			$sql="select stu.*,examrank.* from stu INNER JOIN examrank ON stu.uid=examrank.stu_uid where examrank.sch_id=$sid and year='$year' and cls_code='$clscode' and exam='$exam' order by avg desc,g1 desc,g2 desc,g3 desc,g4 desc,g5 desc";
			$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			$total_kelas=mysql_num_rows($res);
			while($row=mysql_fetch_assoc($res)){
				$xuid=$row['uid'];
				$kedudukan_kelas++;
				if($xuid==$uid)
					break;
			}
			//check kedudukan pelajar..
			$sql="select stu.*,examrank.* from stu INNER JOIN examrank ON stu.uid=examrank.stu_uid where examrank.sch_id=$sid and year='$year' and examrank.cls_level='$clslevel' and exam='$exam' order by avg desc,g1 desc,g2 desc,g3 desc,g4 desc,g5 desc";
			$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			$total_tahap=mysql_num_rows($res);
			while($row=mysql_fetch_assoc($res)){
				$xuid=$row['uid'];
				$kedudukan_tahap++;
				if($xuid==$uid)
					break;
			}
		}else{
			$min=0;
		}
		$x++;
		if(($q++%2)==0)
			$bg="bgcolor=#FAFAFA";
		else
			$bg="bgcolor=#FAFAFA";
		/**
		 	echo "<tr $bg>";
			echo "<td id=myborder align=center>$q</td>";
			echo "<td id=myborder>&nbsp;$examname</td>";
			echo "<td id=myborder align=center>$totalsub</td>";
			echo "<td id=myborder align=center>$avg</td>";
			echo "<td id=myborder align=center>$gpk</td>";
			echo "</tr>";	
		**/  
	}
?>

	<!-- </table>  -->

<?php
		
		$gidx=0;$data_val[0]=0;
		$sql="select * from type where grp='subtype' and val=0 and code='1' order by idx";
		$res=mysql_query($sql)or die("query failed:".mysql_error());

		while($row=mysql_fetch_assoc($res)){
			$FOUND=0;
			
			$subgrp=strtoupper($row['prm']);
			
			$plotidx=0;
			$sql="select * from type where grp='exam' and (lvl=0 or lvl=$clslevel) and (sid=0 or sid=$sid) order by idx";
            $res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			while($row2=mysql_fetch_assoc($res2)){
				$examcode=$row2['code'];
				$examname=$row2['prm'];
				$i=0;
				$dataxname="";
				$data_val=array();
				$datax=array();
				$sql="select sub_code,sub_name from ses_sub where year='$year' and sch_id=$sid and cls_code='$clscode' and sub_grp='$subgrp'";
				//echo $sql."<br>";
				$jkjk=0;
				$res3=mysql_query($sql)or die("query failed:".mysql_error());
           		while($row3=mysql_fetch_assoc($res3)){
					$sn=$row3['sub_name'];
					$sc=$row3['sub_code'];
					
					$point=0;
					$sql="select point,examtype from exam where stu_uid='$uid' and year='$year' and sch_id=$sid and cls_code='$clscode' and sub_code='$sc' and examtype='$examcode'";
					$res4=mysql_query($sql)or die("query failed:".mysql_error());
					if($row4=mysql_fetch_assoc($res4)){
						$point=$row4['point'];
					}
					if(!is_numeric($point))
						$point=0;
					
					$data_val[$i]=$point;
					$datax[$i]=$sc;
					//$datax[$i]=$sn;
					$BR="";
					if(($jkjk++%6)==0)
						$BR="<br>";
											
					$dataxname=$dataxname."$sn($sc)&nbsp;&nbsp;&nbsp;";
					$i++;
					$FOUND=1;
            	}//while subject
				
				if($FOUND){
						$plot[$plotidx] = new BarPlot($data_val);
						$plot[$plotidx]->value->SetFormat('%d');//or will ada 1 titik perpuluhan
						$plot[$plotidx]->SetFillColor($color[$plotidx]);
						/**$plot[$plotidx]->SetShadow('gray@0.1',3);**/
						$plot[$plotidx]->value->Show();//show val top off bar
						$plot[$plotidx]->SetLegend($examname);
						$plotidx++;
				}
			}
			if($FOUND){
					$graph = new Graph(1200,220,"auto");    
					//$graph->SetScale("textlin");
					 $graph-> SetScale( "textlin",0,100 );
					$graph->SetFrame(false);
					$graph->img->SetMargin(40,150,20,20);
					$gplot = new GroupBarPlot($plot);// Create the grouped bar plot
					$graph->Add($gplot);
					$graph->yaxis->title->Set("$lg_mark");
					$graph->xaxis->title->Set("$lg_subject");
					$graph->xaxis->SetTickLabels($datax);// add text val for x
					//$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,7);
					//$graph->xaxis->SetLabelAngle(10);
					$graph->legend->SetPos(0,0.1,'right','top');
					// Display the graph
					$filename="../tmp/$gidx"."_".time().".jpg";
					$graph->Stroke($filename);
					$gidx++;
			}
if($FOUND){
	if($bilangan==0)
		echo "<table>";
	if(($bilangan++%2)==0)
		echo "<tr><td width=\"50%\" id=myborder>";
	else
		echo "<td width=\"50%\" id=myborder>";
?>

<table width="100%" >
	<tr><td width="100%"><?php echo "<div id=mytitlebg>$subgrp</div>";?></td></tr>
	<tr><td width="100%"><img src="<?php echo $filename;?>" width="100%"></td></tr>
	<tr><td width="100%" style="font-size:8px" bgcolor="#FFFF00"><?php echo strtoupper($lg_legend);?>:<br><?php echo $dataxname;?></td></tr>
</table>
<br>
<?php 
} //if found
echo "</tr></table>";
}// while group

?>



</div> <!-- content -->
</form>

</body>
</html>
