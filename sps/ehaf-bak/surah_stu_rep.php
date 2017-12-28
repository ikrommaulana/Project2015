<?php
//500 - 27/03/2010 - repair paydate kasi betul kat table feestu
$vmod="v5.0.0";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');
				
		$username = $_SESSION['username'];

		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];

		$year=$_POST['year'];
		if($year=="")
			$year=date('Y');
		
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql - query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
            mysql_free_result($res);					  
		}
		
		$uid=$_REQUEST['uid'];
		if($uid!=""){
			$sql="select * from stu where uid='$uid'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $xname=$row['name'];
			$xic=$row['ic'];
			$file=$row['file'];
			
			$cname="Tiada";
			$sql="select * from ses_stu where stu_uid='$uid' and year='$year' and sch_id=$sid";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			if($row2=mysql_fetch_assoc($res2)){
				$clsname=$row2['cls_name'];
				$clscode=$row2['cls_code'];
				$clslevel=$row2['cls_level'];
			}
		}



?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title></title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">

<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="surah_stu_rep">
	<input type="hidden" name="op">
	<input type="hidden" name="sid" value="<?php echo $sid;?>">
	<input type="hidden" name="uid" value="<?php echo $uid;?>">
	<input type="hidden" name="surah" value="<?php echo $surahno;?>">
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
	<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
</div>
<div id="right" align="right"><?php echo $vmod;?></div>
</div><!-- end mypanel-->
<div id="story">

<div id="mytitlebg"><?php echo $lg_student;?></div>

<table width="100%" border="0"  style="background:none">
  <tr>
	 <td valign="top" id="myborderfull" align="center" width="8%">
		<?php if(($file!="")&&(file_exists("$dir_image_user$file"))){?>
				<img src="<?php echo "$dir_image_user$file";?>" width="90" height="100">
		<?php } else echo "&nbsp;";?>
	 </td>
  	<td width="50%" valign="top">	
	<table width="100%" >
      <tr>
        <td width="14%"><?php echo $lg_name;?></td>
        <td width="1%">:</td>
        <td width="85%"><?php echo "$xname";?></td>
      </tr>
      <tr>
        <td><?php echo $lg_matric;?></td>
        <td>:</td>
        <td><?php echo "$uid";?> </td>
      </tr>
      <tr>
        <td><?php echo $lg_ic;?></td>
        <td>:</td>
        <td><?php echo "$xic";?> </td>
      </tr>
	  <tr>
        <td><?php echo $lg_school;?></td>
        <td>:</td>
        <td><?php echo "$sname";?></td>
      </tr>
	  <tr>
        <td><?php echo $lg_class;?></td>
        <td>:</td>
        <td><?php echo "$clsname / $year";?> </td>
      </tr>
    </table>


	</td>
    <td width="50%" valign="top">
	
	
 	</td>
  </tr>
</table>

<div id="mytitlebg">STATUS HAFAZAN QURAN</div>
<table width="100%"  cellspacing="0" cellpadding="2" style="font-size:9px ">
	<tr>
<?php 
	$sql="select * from alquran order by surahno";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
		$surahname=$row['surahname'];
		$i++;
						$sql="select * from surah_stu_status where uid='$uid' and surahno=$i";
						$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
						$row3=mysql_fetch_assoc($res3);
						$status=$row3['status'];
						$reading=$row3['reading'];
						if($reading=="")
							$bgtd="";
						elseif($reading=="0")
							$bgtd="bgcolor=\"#00FF66\"";
						else
							$bgtd="bgcolor=\"#FFCC99\"";
						if($status=="0")
							$bgtd="bgcolor=\"#FFFF00\"";
?>
		<td <?php echo $bgtd;?> id="myborder" width="5%">
			<a href="../ehaf/surah_stu_reg.php?uid=<?php echo "$uid&sid=$sid&surah=$i";?>" ><?php echo "$i.$surahname";?></a>
		</td>
<?php if($i%6==0) echo "</tr><tr>"; } ?>
	</tr>
</table>


</div></div>

</form> <!-- end myform -->


</body>
</html>
<!-- 
V.1
Author: razali212@yahoo.com
 -->