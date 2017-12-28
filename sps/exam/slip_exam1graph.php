<?php
include ("$MYOBJ/jpgraph/jpgraph.php");
include ("$MYOBJ/jpgraph/jpgraph_bar.php");
include ("$MYOBJ/jpgraph/jpgraph_line.php");
//29/03/2010 - patch clsname
//02/07/2010 - update logo
//110626   - use credit hr
$vmod="v6.0.2";
$vdate="110626";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN|HEP|HEP-OPERATOR");
$username = $_SESSION['username'];

		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
			
		$stu=$_REQUEST['stu'];//case of multi student
		$uid=$_REQUEST['uid'];//case of one student
		$exam=$_REQUEST['exam'];
		$year=$_REQUEST['year'];
		$clscode=$_REQUEST['clscode'];
				$clslevel="0";
	
		if(count($stu)==0)
			$stu[0]=$uid;
		if(count($stu)==1)
			$uid=$stu[0];
	
		$sql="select * from type where grp='reportcard' and sid=$sid";
		$res=mysql_query($sql) or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$prm=$row['prm'];
			$CONFIG[$prm]['val']=$row['val'];
			$CONFIG[$prm]['code']=$row['code'];
			$CONFIG[$prm]['etc']=$row['etc'];
			$CONFIG[$prm]['des']=$row['des'];
		}
		$sql="select * from sch where id=$sid";
        $res=mysql_query($sql)or die("$sql failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=$row['name'];
		$schlevel=$row['level']; 
		$addr=$row['addr'];
		$state=$row['state'];
		$tel=$row['tel'];
		$fax=$row['fax'];
		$web=$row['url'];
		$school_img=$row['img'];
        mysql_free_result($res);
		
		if($year=="")
			$year=date('Y');
		
		if($uid!=""){
			$sql="select * from ses_stu where stu_uid='$uid' and year='$year'";
			$res=mysql_query($sql) or die("$sql failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$clslevel=$row['cls_level'];
		}
		if($clscode!=""){
			$sql="select * from cls where sch_id='$sid' and code='$clscode'";
			$res=mysql_query($sql)or die("$sql failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$clslevel=$row['level'];
		}
		$sql="select * from type where sid='$sid' and prm='$clslevel' and grp='classlevel'";
        $res=mysql_query($sql)or die("$sql failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$grading=$row['code'];
		$sql="select * from grading where name='$grading' order by val desc";
		$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
		$i=1;
		while($row3=mysql_fetch_assoc($res3)){
			$xg=$row3['grade'];
			$xgp=$row3['gp'];
			$arr_grade[$xg]=0;
			$arr_point[$xg]=$xgp;
		}

		if($exam!="")
			$sql="select * from type where grp='exam' and code='$exam' and (lvl=0 or lvl='$clslevel') and (sid=0 or sid=$sid) order by idx";
		else
			$sql="select * from type where grp='exam' and (lvl=0 or lvl='$clslevel') and (sid=0 or sid=$sid) order by idx";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$examname=$row['prm'];
		$exam=$row['code'];
		
			$sql="select * from type where grp='examconf' and sid=$sid and prm='RANKING_FLOW'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $rankingflow=$row['val'];
			
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
				
		if(($schlevel=="Menengah")&&($clslevel==6))
			$sqlsort_ranking="order by gpk desc,avg desc";
		
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
		<input type="hidden" name="p" value="../exam/slip_exam">
		<input type="hidden" name="sid" value="<?php echo "$sid";?>">
		<input type="hidden" name="uid" value="<?php echo "$uid";?>">
		<input type="hidden" name="clscode" value="<?php echo "$clscode";?>">
		
<div id="content">

<div id="mypanel" class="printhidden">
	<div id="mymenu" align="center">
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
		<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
		<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
	</div>
	
	<div id="mytable" align="right">

<?php if(count($stu)<=1){?>
<select name="year" id="year" onchange="document.myform.submit();">
<?php
            echo "<option value=$year>$lg_session $year</option>";
			$sql="select year from ses_stu where stu_uid='$uid' and year!='$year' order by year desc";
            $res=mysql_query($sql)or die("$sql failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['year'];
                        echo "<option value=\"$s\">$lg_session $s</option>";
            }
            mysql_free_result($res);					  
?>
</select>

<select name="exam" onchange="document.myform.submit();">
<?php	
      				if($exam=="")
						echo "<option value=\"\">- $lg_select -</option>";
					else
						echo "<option value=\"$exam\">$examname</option>";
					$sql="select * from type where grp='exam' and code!='$exam' and (lvl=0 or lvl=$clslevel) and (sid=0 or sid=$sid) order by idx";
            		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $a=$row['prm'];
						$b=$row['code'];
                        echo "<option value=\"$b\">$a</option>";
            		}
            		mysql_free_result($res);	
?>
</select>
<?php }else{ ?>
<input type="hidden" name="year" value="<?php echo $year;?>">
<input type="hidden" name="exam" value="<?php echo $exam;?>">
<?php }?>
<br>
<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
</div>

</div><!-- end mypanel -->
<?php
$totalpage=count($stu);
for($numberofstudent=0;$numberofstudent<count($stu);$numberofstudent++){
		$pageno++;
		$uid=$stu[$numberofstudent];
		echo "<input type=\"hidden\" name=\"stu[]\" value=\"$uid\">";
		
		$sql="select * from ses_stu where stu_uid='$uid' and year='$year'";
		$res=mysql_query($sql) or die(mysql_error());
		$row=mysql_fetch_assoc($res);
		$ccode=$row['cls_code'];
		$cname=stripslashes($row['cls_name']);
		
		$sql="select * from stu where uid='$uid' and sch_id=$sid";
		$res=mysql_query($sql) or die("$sql failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$name=stripslashes($row['name']);
		$ic=$row['ic'];
		$sex=$row['sex'];
		$mentor=$row['mentor'];
		$rdate=$row['rdate'];
		$file=$row['file'];
		$totaljk=$row['hafazan_totaljk'];

		$sql="select * from usr where uid='$mentor'";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$mentorname=$row['name'];

		
		$sql="select stu.*,examrank.* from stu INNER JOIN examrank ON stu.uid=examrank.stu_uid where examrank.sch_id=$sid and year='$year' and examrank.cls_level='$clslevel' and exam='$exam' and gpk>0 $sqlsort_ranking";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$total_tahap=mysql_num_rows($res);
		$kedudukan_tahap = 0;
		while($row=mysql_fetch_assoc($res)){
			$xuid=$row['uid'];
			$kedudukan_tahap++;
			if($xuid==$uid)
				break;
		}
		
		$sql="select stu.*,examrank.* from stu INNER JOIN examrank ON stu.uid=examrank.stu_uid where examrank.sch_id=$sid and year='$year' and cls_code='$ccode' and exam='$exam' and gpk>0 $sqlsort_ranking";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$total_kelas=mysql_num_rows($res);
		$kedudukan_kelas = 0;
		while($row=mysql_fetch_assoc($res)){
			$xuid=$row['uid'];
			$purata_markah=$row['avg'];
			$jumlah_subjek=$row['total_sub'];
			$kedudukan_kelas++;
			if($xuid==$uid)
				break;
		}
		
		$sql="select stu.*,examrank.* from stu INNER JOIN examrank ON stu.uid=examrank.stu_uid where examrank.sch_id=$sid and year='$year' and cls_level='$clslevel' and sex='$sex' and exam='$exam' and gpk>0 $sqlsort_ranking";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$total_sex=mysql_num_rows($res);
		$kedudukan_sex = 0;
		while($row=mysql_fetch_assoc($res)){
			$xuid=$row['uid'];
			$kedudukan_sex++;
			if($xuid==$uid)
				break;
		}


?>
<div id="story" >
<div id="mytitlebg" class="printhidden" style="color:#CCCCCC" align="right">PAGE <?php echo "$pageno/$totalpage";?></div>
<?php 
if($CONFIG['FILE_HEADER']['val'])
	include ($CONFIG['FILE_HEADER']['etc']); 
else
	include ('../inc/header_school.php');
?>
<div id="mytitlebg" align="center"><?php if($LG=="BM") echo "LAPORAN PENTAKSIRAN PELAJAR"; else  echo "STUDENT EXAMINATION RESULT";?> </div>

<table width="100%" id="mytitlebg">
  <tr>
  <?php if(($file!="")&&($CONFIG['SHOW_PICTURE']['val'])){?>
  	<td width="8%" align="center">
		<img name="picture" src="<?php if($file!="") echo "$dir_image_student$file"; ?>"  width="70" height="75" id="myborderfull" style="padding:3px 3px 3px 3px ">
	</td>
 <?php } ?>
    <td width="50%" valign="top">	
	<table width="100%" >
      <tr>
        <td width="30%" valign="top"><?php echo strtoupper($lg_name);?></td>
        <td width="1%" valign="top">:</td>
        <td width="70%"><?php echo "$name";?></td>
      </tr>
      <tr>
        <td><?php echo strtoupper($lg_matric);?></td>
        <td>:</td>
        <td><?php echo "$uid";?> </td>
      </tr>
      <tr>
        <td><?php echo strtoupper($lg_ic_number);?></td>
        <td>:</td>
        <td><?php echo "$ic";?> </td>
      </tr>
    </table>


	</td>
    <td width="50%" valign="top">
	
	<table width="100%" >
      <tr>
        <td width="20%" valign="top"><?php echo strtoupper($lg_school);?></td>
        <td width="1%" valign="top">:</td>
        <td width="79%"><?php echo strtoupper("$sname");?></td>
      </tr>
	  <tr>
        <td><?php echo strtoupper($lg_exam);?></td>
        <td>:</td>
        <td><?php echo strtoupper("$examname");?></td>
      </tr>
      <tr>
        <td><?php echo strtoupper($lg_class);?></td>
        <td>:</td>
        <td><?php echo strtoupper("$cname / $year");?> </td>
      </tr>
	  <?php if($CONFIG['SHOW_MENTOR']['val']){?>
	  <tr>
        <td>MENTOR</td>
        <td>:</td>
        <td><?php echo strtoupper("$mentorname");?> </td>
      </tr>
	  <?php } ?>
    </table>
 	</td>
  </tr>
</table>

<table width="100%" >
  <tr>
    <td valign="top" id="myborder" width="50%">

   <?php
		$totalsubject=0;
		$totalpoint=0;
		$totalgp=0;
		$totalcredit=0;
		$xx=0;
		if($CONFIG['SHOW_SCORE']['val'])
	        $sql3="select * from type where grp='subgroup' and code='1' and sid=$sid order by idx";
		else
			$sql3="select * from type where grp='subgroup' and val=0 and code='1'  and sid=$sid  order by idx";
        $res3=mysql_query($sql3)or die("query failed:".mysql_error());
        while($row3=mysql_fetch_assoc($res3)){
        	$contruct=$row3['prm'];
			$contype=$row3['val'];
			echo "<table width=\"100%\" cellspacing=0>";
  			echo "<tr id=mytitlebg style=font-size:100%>";
			echo "<td id=\"myborder\" width=\"10%\">&nbsp;$lg_code</td>";
			echo "<td id=\"myborder\" width=\"50%\">&nbsp;$contruct</td>";
			echo "<td id=\"myborder\" width=\"20%\" align=center>$lg_mark</td>";
			echo "<td id=\"myborder\" width=\"20%\" align=center>$lg_grade</td>";
		  	echo "</tr>";
			$sql4="select * from exam where sub_grp='$contruct' and stu_uid='$uid' and year='$year' and examtype='$exam' order by sub_name";
			$res4=mysql_query($sql4)or die("query failed:".mysql_error());
			$q=0;
			while($row4=mysql_fetch_assoc($res4)){
				$sub_code=$row4['sub_code'];
				$sub_name=$row4['sub_name'];
				$point=$row4['point'];
				$grade=$row4['grade'];
				$isfail=$row4['isfail'];
				if($isfail)
					$fontcolor="style=\"color:#FF0000\"";
				else
					$fontcolor="";
				$credit=$row4['credit'];
				$gradingtype=$row4['gradingtype'];
				$gp=$row4['gp'];
				if($grade=="TT"){
					continue; //dont show tt
				}
				if($grade=="TH")
					$point=0;
				if(($q++%2)==0)
					echo "<tr bgcolor=#FFFFFF>";
				else
					echo "<tr bgcolor=#FFFFFF>";
				echo "<td id=\"myborder\" $fontcolor>&nbsp;$sub_code</td>";
				echo "<td id=\"myborder\" $fontcolor>&nbsp;$sub_name</td>";

				
				if($credit>0){
						$arr_grade[$grade]=$arr_grade[$grade]+1;
						if($gred!="TT"){
									if(is_numeric($point)){
										$totalpoint=$totalpoint+$point;
										$totalsubject=$totalsubject+1;
									}
									if($gp>=0){
										$totalgp=$gp*$credit+$totalgp;
										$totalcredit=$totalcredit+$credit;
									}
						}
				}
				if($gradingtype==1)
					$point="-";
				echo "<td id=\"myborder\" align=center $fontcolor>$point</td>";
				echo "<td id=\"myborder\" align=center $fontcolor>$grade</td>";
				echo "</tr>";
				
			}
			echo "</table>";
		}
?>


<table width="100%" cellspacing="0"><tr><td width="50%" valign="top">
	<div id="mytitlebg" align="center" style="border:1px solid #EEEEEE "><?php echo $lg_achievement;?></div>
<table width="100%" cellspacing="0">
   <tr id="mytitlebg">
	<td id="myborder" width="50%">&nbsp;<?php if($LG=="BM") echo "Bidang Subjek"; else  echo "Subject Group";?></td>
<?php
	$sql="select * from grading where name='$grading' order by val desc";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$num=mysql_num_rows($res2);
	if($num>0)
		$size=round(50/$num);
	else
		$size=0;
	while($row2=mysql_fetch_assoc($res2)){
		$p=$row2['grade'];
		$arr_total_grade[$p]="";
		if($p=="TT")
			continue;	
		
?>	
            <td  id="myborder" width="<?php echo $size; ?>%" align="center" ><?php echo strtoupper("$p");?></td>
<?php } ?>
          </tr>
<?php
		$totalfail=0;
        $sql3="select * from type where grp='subgroup' and val=0 and sid=$sid";
        $res3=mysql_query($sql3)or die("query failed:".mysql_error());
        while($row3=mysql_fetch_assoc($res3)){
        	$contruct=$row3['prm'];
?>
          <tr >
            <td id="myborder" bgcolor="#FFFFFF" >&nbsp;<?php echo $contruct;?></td>
<?php
	
	$sql="select * from grading where name='$grading' order by val desc";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row2=mysql_fetch_assoc($res2)){
			$p=$row2['grade'];
			$isfail=$row2['sta'];
			if($p=="TT")
					continue;	
			
?>
            <td  id="myborder" bgcolor="#FFFFFF" align="center">
			<?php 
			$sql="select * from exam where sub_grp='$contruct' and stu_uid='$uid' and year='$year' and examtype='$exam' and grade='$p'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			$jum=mysql_num_rows($res);
			echo "$jum";
			$arr_total_grade[$p] = $arr_total_grade[$p] + $jum;
			if($isfail)
				$totalfail=$totalfail+$jum;
			?>
			
			</td>
	<?php } ?>
          </tr>
<?php } ?>
       <tr id="mytitlebg" >
	   		<td id="myborder">&nbsp;<?php echo $lg_total;?></td>
<?php
	$sql="select * from grading where name='$grading' order by val desc";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row2=mysql_fetch_assoc($res2)){
		$p=$row2['grade'];
		//dont show tt
		if($p=="TT")
			continue;	
?>
			<td id="myborder" align="center" ><?php echo $arr_total_grade[$p];?></td>
<?php } ?>
	   </tr>
 </table>
<div id="mytitlebg" align="center"><?php echo $lg_summary;?></div>
<table width="100%" cellspacing="0">
		<tr>
			<td width="50%" id="myborder">&nbsp;<?php echo $lg_total_subject;?></td>
			<td width="50%" id="myborder"><?php echo "$totalsubject";?></td>
		</tr>
		<tr>
			<td width="25%" id="myborder">&nbsp;<?php echo $lg_pass;?></td>
			<td width="25%" id="myborder"><?php if($totalsubject>0)echo round(($totalsubject-$totalfail)/$totalsubject*100);?>% </td>
		</tr>
		<tr>
			<td width="25%" id="myborder">&nbsp;<?php echo $lg_total_mark;?></td>
			<td width="25%" id="myborder"><?php $total_max_point=$totalsubject*100; echo "$totalpoint";//echo "$totalpoint/$total_max_point";?></td>
		</tr>
		<tr>
			<td width="25%" id="myborder">&nbsp;<?php echo $lg_average_mark;?></td>
			<td width="25%" id="myborder"><?php if($totalsubject>0) printf("%.02f",$totalpoint/$totalsubject); else echo "-"; ?></td>
		</tr>
	   <?php if($CONFIG['SHOW_RANKING']['val']){?>
	   <tr>
			<td width="25%" id="myborder">&nbsp;<?php echo $lg_ranking_class;?></td>
			<td width="25%" id="myborder"><?php echo "$kedudukan_kelas/$total_kelas";?></td>
		</tr>
		<tr>
			<td width="25%" id="myborder">&nbsp;<?php echo $lg_ranking_level;?></td>
			<td width="25%" id="myborder"><?php echo "$kedudukan_tahap/$total_tahap";?></td>
		</tr>
		<?php } ?>
		
		<?php 		
		/**
					$cyear=date("Y");
					$d=date("m-d");
					if($cyear==$year)
						$dt="$year-$d";
					else
						$dt="$year-12-31";
					$sql="select count(*) from ses_cal where sta=0 and year='$year' and d<='$dt'";
					$resxx=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					$rowxx=mysql_fetch_row($resxx);
					$tothari=$rowxx[0];
		*/
					$sql="select count(*) from stuatt where stu_uid='$uid' and sta=1 and year='$year'";
					$resxx=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					$rowxx=mysql_fetch_row($resxx);
					$tothadir=$rowxx[0];
					
					$sql="select count(*) from stuatt where stu_uid='$uid' and sta=0 and year='$year'";
					$resxx=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					$rowxx=mysql_fetch_row($resxx);
					$totxhadir=$rowxx[0];
					
					$sql="select count(*) from stuatt where stu_uid='$uid' and year='$year'";
					$resxx=mysql_query($sql)or die("$sql:query failed:".mysql_error());
					$rowxx=mysql_fetch_row($resxx);
					$tothari=$rowxx[0];
					/*
					$tothadir=$tothari-$totxhadir;
					if($tothari>0){
						$perhadir=round($tothadir/$tothari*100,2);
						$percent="$perhadir%";
					}else{
						$percent="";
					}
					*/
	?>
		<tr>
			<td width="25%" id="myborder">&nbsp;<?php echo $lg_gp_student;?></td>
			<td width="25%" id="myborder"><?php if($totalcredit>0) printf("%.02f",$totalgp/$totalcredit); else echo "0.00"; ?></td>
		</tr>
<?php if($CONFIG['SHOW_ATTENDANCE']['val']){?>
		<tr>
			<td width="25%" id="myborder">&nbsp;<?php echo $lg_attendance;?></td>
			<td width="25%" id="myborder"><?php if($CONFIG['SHOW_AUTO_ATTENDANCE']['val']) echo "$tothadir / $tothari";?></td>
		</tr>
<?php }?>
<?php if($CONFIG['SHOW_JUMLAH_JUZUK']['val']){?>
		<tr>
			<td width="25%" id="myborder">&nbsp;<?php echo $lg_total_hafazan;?></td>
			<td width="25%" id="myborder"><?php echo "$totaljk";?> Juzuk</td>
		</tr>
<?php }?>
		
    </table>
    
    
    
    

</td> <!-- end left-->

<td valign="top"><!-- right -->

<div id="mytitlebg" align="center" style="border:1px solid #EEEEEE "><?php if($LG=="BM") echo "Rujukan Gred"; else  echo "Grade Reference";?></div>

	<table width="100%" cellspacing="0">
      <tr id="mytitlebg" >
        <td id="myborder" width="20%" align="center" ><?php echo $lg_grade;?></td>
		<td id="myborder" width="20%" align="center" ><?php echo $lg_mark;?></td>
        <td id="myborder">&nbsp;&nbsp;<?php echo $lg_achievement;?></td>
      </tr>
<?php
	$sql="select * from grading where name='$grading' and val>-1 order by val desc";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row2=mysql_fetch_assoc($res2)){
		$p=$row2['grade'];
		$d=$row2['des'];	
		$v=$row2['val'];
		$m=$row2['point'];
?>
      <tr>
        <td id="myborder" align="center"><?php echo "$p";?></td>
		<td id="myborder" align="center"><?php if($v>=0) echo "$m-$v"; else echo $m;?></td>
        <td id="myborder"">&nbsp;&nbsp;<?php echo "$d";?></td>
      </tr>
<?php }?>
   	</table>
	


</td></tr></table>


</td></tr></table><!-- end right -->

<div id="mytitlebg" align="center">Analisa Prestasi</div>


<?php
		
		$gidx=0;$data_val[0]=0;
		$sql="select * from type where grp='subtype' and val=0 and code='1' order by idx";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$BILGROUP=0;
		while($row=mysql_fetch_assoc($res)){
			$FOUND=0;
			$BILGROUP++;
			$subgrp=strtoupper($row['prm']);
			$plotidx=0;
			$sql="select * from type where grp='exam' and val=1 and (lvl=0 or lvl=$clslevel) and (sid=0 or sid=$sid) order by idx";
            $res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			while($row2=mysql_fetch_assoc($res2)){
				$examcode=$row2['code'];
				$examname=$row2['prm'];
				$i=0;
				$dataxname="";
				$data_val=array();
				$datax=array();
				//$sql="select sub_code,sub_name from ses_sub where year='$year' and sch_id=$sid and cls_code='$clscode' and sub_grp='$subgrp'";
				$sql="select sub_code,sub_name from ses_sub where year='$year' and sch_id=$sid and cls_code='$ccode' and sub_grp='$subgrp'";
				$jkjk=0;
				$res3=mysql_query($sql)or die("query failed:".mysql_error());
           		while($row3=mysql_fetch_assoc($res3)){
					$sn=$row3['sub_name'];
					$sc=$row3['sub_code'];
					$point=0;
					//$sql="select point,examtype from exam where stu_uid='$uid' and year='$year' and sch_id=$sid and cls_code='$clscode' and sub_code='$sc' and examtype='$examcode'";
					$sql="select point,examtype from exam where stu_uid='$uid' and year='$year' and sch_id=$sid and cls_code='$ccode' and sub_code='$sc' and examtype='$examcode'";
					$res4=mysql_query($sql)or die("query failed:".mysql_error());
					if($row4=mysql_fetch_assoc($res4)){
						$point=$row4['point'];
					}
					if(!is_numeric($point))
						$point=0;
					$data_val[$i]=$point;
					//$datax[$i]=$sc;
					$datax[$i]=str_replace("'","",$sn);
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
					//$graph->img->SetAngle(90);
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
	
?>

<table width="100%" >
	<tr><td width="100%"><?php echo "<div id=mytitlebg>$subgrp</div>";?></td></tr>
	<tr><td width="100%"><img src="<?php echo $filename;?>" width="100%" height="120px";></td></tr>
</table>
<?php 
}//found
}// while group
?>



<table width="100%">
<?php if($CONFIG['SHOW_CTT_GURU']['val']){?>
	<tr id="mytitlebg"><td id="myborder" width="75%" valign="top"><?php echo $CONFIG['SHOW_CTT_GURU']['etc'];?></td></tr>
	<tr><td><br><br><br><br><br><br></td></tr>
<?php }if($CONFIG['SHOW_COP']['val']){?>
	<tr id="mytitlebg"><td id="myborder" width="75%" valign="top"><?php echo $CONFIG['SHOW_COP']['etc'];?></td></tr>
	<tr><td><br><br><br><br><br><br></td></tr>
<?php }?>
</table>

<?php if($CONFIG['SHOW_SIGNING']['val']){?>
	<table width="100%">
		<tr id="mytitle">
	<?php if($CONFIG['SHOW_TT_GURU']['val']){?>
		<td id="myborder" width="33%" valign="top" align="center"><?php echo $CONFIG['SHOW_TT_GURU']['etc'];?>
			<br><br><br><br><br><br><br>
		</td>
	<?php }if($CONFIG['SHOW_TT_PENGETUA']['val']){?>
		<td id="myborder" width="33%" valign="top"  align="center"><?php echo $CONFIG['SHOW_TT_PENGETUA']['etc'];?>
			<?php 
			if($CONFIG['SHOW_TT_PENGETUA']['des']!=""){
				$sain=$CONFIG['SHOW_TT_PENGETUA']['des'];
				echo "<br><br><img src=\"$sain\">";
			}else{
				echo "<br><br><br><br><br><br><br>";
			}
			?>
		</td>
	<?php }if($CONFIG['SHOW_TT_PENJAGA']['val']){?>
		<td id="myborder" width="33%" valign="top"  align="center"><?php echo $CONFIG['SHOW_TT_PENJAGA']['etc'];?>
			<br><br><br><br><br><br><br>
		</td>
	<?php }?>
		</tr>
	</table>
<?php } ?><!-- end signing -->
	</tr>
</table>



<?php if($CONFIG['SHOW_CETAKKAN']['val']){?>
<div align="center" style="font-size:60% "><?php echo $CONFIG['SHOW_CETAKKAN']['etc'];?></div>
<?php } ?>
</div> <!-- story -->
<?php if($pageno!=$totalpage){?>
<div style="page-break-after:always"></div>
<?php } ?>
<?php }//end for loop ?>


</div> <!-- content -->


</form>

</body>
</html>
