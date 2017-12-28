<?php
include_once('../etc/db.php');
include_once('session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify();
$sid=$_SESSION['sid'];
//$sql="select * from type where grp='openexam' and prm='EPEPERIKSAAN' and sid='$sid'";
$sql="select * from type where grp='openexam' and prm='EPEPERIKSAAN'  and (sid='$sid' or sid=0)";
$res=mysql_query($sql)or die("query failed:".mysql_error());
$row=mysql_fetch_assoc($res);
$sta=$row['val'];
if($sta!="1")
	echo "<script language=\"javascript\">location.href='p.php?p=close'</script>";

$uid=$_SESSION['uid'];

$sql="select * from stu where uid='$uid'";
$res=mysql_query($sql)or die("query failed:".mysql_error());
$row=mysql_fetch_assoc($res);
$isblock=$row['isblock'];
if($isblock)
	echo "<script language=\"javascript\">location.href='p.php?p=block'</script>";
	
	
	$year=$_POST['year'];
	if($year=="")
		$year=date('Y');

	$clevel=0;
	$sql="select * from ses_stu where stu_uid='$uid' and year=$year";
	$res2=mysql_query($sql)or die("query failed:".mysql_error());
	if($row2=mysql_fetch_assoc($res2)){
		$cname=$row2['cls_name'];
		$clevel=$row2['cls_level'];
	}
	
	$exam=$_POST['exam'];
	if($exam==""){
			$sql="select * from type where grp='exam' and (lvl=0 or lvl=$clevel) and (sid=0 or sid=$sid) order by idx";
            $res=mysql_query($sql)or die("$sql failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $examname=$row['prm'];
			$exam=$row['code'];
			$sqlexam="and code='$exam'";
	}else{
			$sql="select * from type where grp='exam' and code='$exam' and (lvl=0 or lvl=$clevel) and (sid=0 or sid=$sid) ";
            $res=mysql_query($sql)or die("$sql failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $examname=$row['prm'];
			$sqlexam="and code='$exam'";
	}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">

</head>

<body >
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" >
		<input type="hidden" name="p" value="exam">
		
<div id="panelleft">
	<?php include('inc/lmenu.php');?>
</div> 

<div id="content2"> 
<!-- 
<div align="center" style="font-size:14px; font-weight:bold; padding:2px; font-family:Arial; color:#FFFFFF; background-image:url(../img/bg_title_lite.jpg);border: 1px solid #99BBFF;">
		<?php echo strtoupper($name);?>
</div>
 -->
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="#" id="mymenuitem" onClick="document.myform.submit()" ><img src="../img/reload.png"><br>Refresh</a> 
		<!--
		<a href="#" id="mymenuitem" onClick="location.href='p.php?p=ana&year=<?php echo "$year";?>'" ><img src="../img/graphbar.png"><br>Analisys</a>
		-->
	</div>

	<div align="right">
<select name="year" onchange="document.myform.submit();">
<?php
		if($year!="")
            echo "<option value=$year>$lg_session $year</option>";
			$sql="select * from ses_stu where stu_uid='$uid' and sch_id='$sid' and year!='$year' order by year desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $y=$row['year'];
                        echo "<option value=\"$y\">$lg_session $y</option>";
            }
            mysql_free_result($res);					  
?>
</select>
<select name="exam" onchange="document.myform.submit();">
  <?php	
      				if($exam=="")
						echo "<option value=\"\">- $lg_exam -</option>";
					else
						echo "<option value=\"$exam\">$examname</option>";
					$sql="select * from type where grp='exam' and code!='$exam' and (lvl=0 or lvl=$clevel) and (sid=0 or sid=$sid) order by idx";
            		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $a=$row['prm'];
						$b=$row['code'];
                        echo "<option value=\"$b\">$a</option>";
            		}

            		
			?>
</select>
<input name="button" type="button" value="View" onClick="document.myform.submit();">
</div>
</div>


<div id="story">
<?php

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
        $sname=stripcslashes($row['name']);
		$schlevel=$row['level']; 
		$addr=$row['addr'];
		$state=$row['state'];
		$tel=$row['tel'];
		$fax=$row['fax'];
		$web=$row['url'];
		$school_img=$row['img'];
        mysql_free_result($res);
		
		if($year==""){
			//$year=date('Y');
			$sql="select * from ses_stu where stu_uid='$uid' and sch_id='$sid' order by year desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $year=$row['year'];
                      
		}
		
		if($uid!=""){
			$sql="select * from ses_stu where stu_uid='$uid' and year=$year";
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

		
		$sql="select * from ses_stu where stu_uid='$uid' and year=$year";
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


<?php 
/**
if($CONFIG['FILE_HEADER']['val'])
	include ($CONFIG['FILE_HEADER']['etc']); 
else
	include ('../inc/header_school.php');
**/
?>
<div id="mytitlebg" align="center">RAPOT UJIAN (TIDAK RASMI) </div>

<table width="100%" >
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
       <tr>
        <td><?php echo strtoupper($lg_register_date);?></td>
        <td>:</td>
        <td><?php list($xy,$xm,$xd)=split('[-]',$rdate); echo "$xd-$xm-$xy";?> </td>
      </tr>
	   <tr>
        <td></td>
        <td></td>
        <td></td>
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
        <td><?php echo strtoupper("$cname");?> </td>
      </tr>
	  <tr>
        <td><?php echo strtoupper($lg_session);?></td>
        <td>:</td>
        <td><?php echo strtoupper("$year");?> </td>
      </tr>

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
			echo "<table width=\"100%\" cellspacing=0 style=\"font-size:14px\">";
  			echo "<tr id=mytitlebg >";
			echo "<td id=\"myborder\" width=\"10%\">&nbsp;$lg_code</td>";
			echo "<td id=\"myborder\" width=\"50%\">&nbsp;$contruct</td>";
			echo "<td id=\"myborder\" width=\"20%\" align=center>$lg_mark</td>";
			//echo "<td id=\"myborder\" width=\"20%\" align=center>$lg_grade</td>";
		  	echo "</tr>";
			$sql4="select * from exam where sub_grp='$contruct' and stu_uid='$uid' and year='$year' and examtype='$exam' order by sub_name";
			$res4=mysql_query($sql4)or die("query failed:".mysql_error());
			$q=0;
			while($row4=mysql_fetch_assoc($res4)){
				$sub_code=$row4['sub_code'];
				$sub_name=$row4['sub_name'];
				$point=$row4['point'];
				$grade=$row4['grade'];
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
				echo "<td id=\"myborder\" >&nbsp;$sub_code</td>";
				echo "<td id=\"myborder\" >&nbsp;$sub_name</td>";

				
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
				echo "<td id=\"myborder\" align=center>$point</td>";
				//echo "<td id=\"myborder\" align=center>$grade</td>";
				echo "</tr>";
				
			}
			echo "</table>";
		}
?>




<font color="red">
<strong>
<?php if($LG=="BM"){?>
**KEPUTUSAN DAN ANALISA peperiksaan lengkap hanya akan di keluarkan oleh pihak sekolah pada sesi perjumpaan ibu bapa. Terima Kasih
<?php } else{?>
**This examination slip is not valid until certisfied by school.
<?php } ?>
</strong></font>
</div></div>
</form>
</body>
</html>
