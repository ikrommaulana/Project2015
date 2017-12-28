<?php 
include_once('../etc/db.php');
include_once('session.php');
verify();

	$sql="select * from type where grp='openexam' and prm='EHAFAZAN'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$sta=$row['val'];
	if($sta!="1")
		echo "<script language=\"javascript\">location.href='p.php?p=close'</script>";
		

	$sid=$_SESSION['sid'];
	$uid=$_SESSION['uid'];
	
	$year=$_POST['year'];
	if($year==""){
			$sql="select * from ses_stu where stu_uid='$uid' and sch_id='$sid' and year!='$year' order by id desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $year=$row['year'];
	}
	
			$sql="select * from stu where uid='$uid'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $xname=$row['name'];
			$xic=$row['ic'];
			$file=$row['file'];
			
			$cname="Tiada";
			$sql="select * from ses_stu where stu_uid='$uid'  and sch_id=$sid order by id desc";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			if($row2=mysql_fetch_assoc($res2)){
				$clsname=$row2['cls_name'];
				$clscode=$row2['cls_code'];
				$clslevel=$row2['cls_level'];
			}

	
	

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>	

</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" >
		<input type="hidden" name="p" value="haf">
<div id="panelleft">
	<?php include('inc/lmenu.php');?>
</div>
<div id="content2"> 
<div id="masthead_title" style="border-right:none; border-top:none;" >
		<?php echo strtoupper($name);?>
</div>
<div style="font-size:11px; font-weight:bold; color:#333333; border-bottom:2px solid #666;"></div>


<div id="story">
	<div id="mytitlebg">STATUS HAFALAN QURAN</div>
<table width="100%"  cellspacing="0" cellpadding="4" style="font-size:18px ">
	<tr>
<?php 
	$sql="select * from type where grp='surahhafazan' and ((sid=0)||(sid='$sid'))&&((lvl=0)||(lvl='$clslevel')) order by idx";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
		$surahname=stripslashes($row['prm']);
		$surah=addslashes($row['prm']);
		$i++;
						$sql="select * from surah_stu_status where uid='$uid' and surahname='$surah'";
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
			<a href="#" ><?php echo "$i.$surahname";?></a>
		</td>
<?php if($i%3==0) echo "</tr><tr>"; } ?>
	</tr>
</table>

</div></div><!-- content/story -->
</form>
</body>
</html>
