<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
verify("ADMIN|AKADEMIK|KEWANGAN|GURU");
$username = $_SESSION['username'];

		$uid=$_REQUEST['uid'];
		$sid=$_REQUEST['sid'];

		$sql="select * from stu where uid='$uid'";
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
</form>
<div id="content">

<div id="mypanel" class="printhidden">
	<div id="mymenu" align="center">
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	</div>
</div><!-- end mypanel -->

<div id="story" >
<?php include ('../inc/school_header.php'); ?> 
<div id="mytitle" align="center">TRANSKRIP AKADEMIK PELAJAR</div>

<table width="100%"  border="0">
  <tr>
  <?php if($file!=""){?>
  	<td width="8%" align="center">
		<img name="picture" src="<?php if($file!="") echo "$dir_image_student$file"; ?>"  width="70" height="75" id="myborderfull" style="padding:3px 3px 3px 3px ">
	</td>
 <?php } ?>
    <td width="50%" valign="top">	
	<table width="100%"   bgcolor="#FFFFFF" style="font-size:80% ">
      <tr>
        <td width="35%" ><strong>Nama</strong> </td>
        <td width="1%" >:</td>
        <td width="64%">&nbsp;<?php echo "$name";?></td>
      </tr>
      <tr>
        <td><strong>ID Pelajar</strong></td>
        <td>:</td>
        <td>&nbsp;<?php echo "$uid";?> </td>
      </tr>
      <tr>
        <td><strong>Kad Pengenalan</strong> </td>
        <td>:</td>
        <td>&nbsp;<?php echo "$ic";?> </td>
      </tr>

    </table>


	</td>
    <td width="50%" valign="top">
	
	<table width="100%"  border="0"  bgcolor="#FFFFFF" style="font-size:80% ">
      <tr>
        <td width="30%" ><strong><?php echo "$lg_sekolah";?></strong></td>
        <td width="1%">:</td>
        <td width="69%">&nbsp;<?php echo "$sname";?></td>
      </tr>
	  <tr>
        <td><strong>Tarikh Daftar </strong></td>
        <td>:</td>
        <td>&nbsp;<?php list($xy,$xm,$xd)=split('[-]',$rdate); echo "$xd-$xm-$xy";?></td>
      </tr>
      <tr>
        <td><strong>Tarikh Tamat </strong></td>
        <td>:</td>
        <td>&nbsp;<?php list($xy,$xm,$xd)=split('[-]',$edate); echo "$xd-$xm-$xy";?> </td>
      </tr>
	 
	  
    </table>
 	</td>
  </tr>
</table>

</div> <!-- story -->
</div> <!-- content -->


<?php

$sqlyear="select distinct(year) from ses_stu where stu_uid='$uid' order by year desc";
$resyear=mysql_query($sqlyear)or die("query failed:".mysql_error());
while($rowyear=mysql_fetch_assoc($resyear)){
		$year=$rowyear['year'];
		
		$sql="select * from ses_stu where stu_uid='$uid' and year='$year'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$clsname=$row['cls_name'];
		$clscode=$row['cls_code'];
		$clslevel=$row['cls_level'];
		
		$sql="select * from type where sid='$sid' and prm='$clslevel' and grp='classlevel'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$grading=$row['code'];
?>
<div id="content" style="font-size:70%; ">
<div id="story">
<div id="mytitle">TRANSKRIP AKADEMIK <?php echo "TAHUN $year"?> / KELAS <?php echo strtoupper($clsname);?></div>


<?php 
$sql="select * from type where grp='exam' order by idx";
$res2x=mysql_query($sql)or die("$sql:query failed:".mysql_error());
while($row2x=mysql_fetch_assoc($res2x)){
	$exam=$row2x['code'];
	$examname=$row2x['prm'];
?>
	<table width="100%" id=mytable>
  		<tr id="mytabletitle">
		<td id=myborder width="10%">Kod</td>
    	<td id=myborder width="60%"><?php echo "$examname";?></td>
		<td id=myborder width="15%" align=center>Nilai</td>
		<td  id=myborder width="15%" align=center>Gred</td>
		</tr>
    <?php
		
        $sql3="select * from type where grp='subtype' order by idx";
        $res3=mysql_query($sql3)or die("query failed:".mysql_error());
		$totalgrp=mysql_num_rows($res3);
        while($row3=mysql_fetch_assoc($res3)){
        	$contruct=$row3['prm'];
			$contype=$row3['val'];
			$sql4="select * from exam where sub_grp='$contruct' and stu_uid='$uid' and year='$year' and examtype='$exam'";
			$res4=mysql_query($sql4)or die("query failed:".mysql_error());
			
			while($row4=mysql_fetch_assoc($res4)){
				$sub_code=$row4['sub_code'];
				$sub_name=$row4['sub_name'];
				$point=$row4['point'];
				$grade=$row4['grade'];
				if(($q++%2)==0)
					echo "<tr bgcolor=#FAFAFA>";
				else
					echo "<tr bgcolor=#FAFAFA>";
				echo "<td id=myborder>&nbsp;$sub_code</td>";
				echo "<td id=myborder>&nbsp;$sub_name</td>";
				echo "<td id=myborder align=center>$point</td>";
				echo "<td id=myborder align=center>$grade</td>";
				echo "</tr>";
				
			}
			

		}
		
?>
</table>

<?php }?>
</div> <!-- story -->
</div> <!-- content -->
<br>
<?php }//while examcode sqlyear ?>



</body>
</html>
