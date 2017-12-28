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
	if($year==""){
			$sql="select * from ses_stu where stu_uid='$uid' and sch_id='$sid' and year!='$year' order by id desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $year=$row['year'];
	}

	$clevel=0;
	$sql="select * from ses_stu where stu_uid='$uid' and year='$year'";
	$res2=mysql_query($sql)or die("query failed:".mysql_error());
	if($row2=mysql_fetch_assoc($res2)){
		$cname=$row2['cls_name'];
		$clevel=$row2['cls_level'];
	}
	
$exam=$_POST['exam'];
	if($exam==""){
			$sql="select * from type where grp='exam' and (lvl=0 or lvl='$clevel') and (sid=0 or sid=$sid) order by idx";
            $res=mysql_query($sql)or die("$sql failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $examname=$row['prm'];
			$exam=$row['code'];
			$sqlexam="and code='$exam'";
	}else{
			$sql="select * from type where grp='exam' and code='$exam' and (lvl=0 or lvl='$clevel') and (sid=0 or sid=$sid) ";
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
		<input type="hidden" name="p" value="examtk">
		
<div id="panelleft">
	<?php include('inc/lmenu.php');?>
</div>
<div id="content2"> 
<div id="masthead_title" style="border-right:none; border-top:none;" >
		<?php echo strtoupper($name);?>
</div>
<div style="font-size:11px; font-weight:bold; color:#333333; border-bottom:2px solid #666;"></div>


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
					$sql="select * from type where grp='exam' and code!='$exam' and (lvl=0 or lvl='$clevel') and (sid=0 or sid=$sid) order by idx";
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


<?php 
/**
if($CONFIG['FILE_HEADER']['val'])
	include ($CONFIG['FILE_HEADER']['etc']); 
else
	include ('../inc/header_school.php');
**/
?>
<div id="mytitlebg" align="center">RAPOT UJIAN (TIDAK RESMI) </div>

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
    		<td class="mytableheader" style="border-right:none;" width="5%" align="center">No</td>
		    <td class="mytableheader" style="border-right:none;" width="50%" >Deskripsi</td>            
			<td class="mytableheader" style="border-right:none;" width="20%" align="center">Nilai</td>
	</tr>

<?php
 		$sql="select * from type where grp='sub_cate' order by idx";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
      	while($row=mysql_fetch_assoc($res)){
				$cate=$row['prm'];
				$q++;
				$jj=0;
				
?>				
	<tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
					<td id="mytabletitle" style="border-right:none;" align="center"><?php echo $q;?></td>
                    <td id="mytabletitle" style="border-right:none;"><?php echo $cate;?></td>
                    <td id="mytabletitle" style="border-right:none;">&nbsp;</td>
     </tr>
<?php
		$sql="select distinct(type) from sub_construct where cate='$cate' and isdel=0 order by cate";
		$res2=mysql_query($sql)or die("query failed:".mysql_error());
      	while($row2=mysql_fetch_assoc($res2)){
				$type=$row2['type'];
				$jj++;
				$bg="#FAFAFA";
				if($type!=""){
?>				
            <tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">                          
                            <td id="myborder" style="border-top:none;border-right:none;" align="right"><?php echo "$q-$jj";?></td>
                            <td id="myborder" style="border-top:none;border-right:none;"><?php echo $type;?></td>
                            <td id="myborder" style="border-top:none;border-right:none;">&nbsp;</td>
             </tr>
            <?php //show itemized item
		}
				$kk=0;
				$sql="select * from sub_construct where type='$type' and cate='$cate' and sid='$sid' and lvl='$clevel' and exam='$exam' and isdel=0 order by cate";
				$res3=mysql_query($sql)or die("query failed:".mysql_error());
				while($row3=mysql_fetch_assoc($res3)){
						$xid=$row3['id'];
						$cc=$row3['code'];
						$vv=$row3['val'];
						$item=$row3['item'];
						$grading=$row3['grading'];
						$kk++;
						$totalitem=0;
						$bg="";
						?>
                        <tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">                          
                                    <td id="myborder" style="border-top:none;border-right:none;" align="right"></td>
                                    <td id="myborder" style="border-top:none;border-right:none;"><?php echo "$q-$jj-$kk";?>&nbsp;&nbsp;<?php echo "$cc $item";?></td>
                                    <td id="myborder" style="border-top:none;border-right:none;">
									
			
			<?php
						$sql="select * from exam where stu_uid='$uid' and year='$year' and sub_code='$cc' and sch_id='$sid'";
						$res6=mysql_query($sql)or die("$sql failed:".mysql_error());
						$row6=mysql_fetch_assoc($res6);
						$grade=$row6['grade'];
						$point=$row6['point'];
						echo "$grade";
			?>
								
									</td>
                        </tr>
<?php } ?>
<?php } ?> 
<?php } ?>     
 </table>




<font color="red">
<strong>

Rapot Ujian yang ada tandatangan Wali Kelas hanya dikeluarkan oleh pihak sekolah.

</strong></font>
</div></div>
</form>
</body>
</html>
