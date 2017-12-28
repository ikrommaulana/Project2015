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
	
	
	$exam=$_POST['exam'];

	$semester=$_POST['semester'];
		if($semester!="") 
			$sqlsem=" and code='$semester'";

	$sid=$_SESSION['sid'];
	$uid=$_SESSION['uid'];
	
	$year=$_POST['year'];
	if($year==""){
			$sql="select * from ses_stu where stu_uid='$uid' and sch_id='$sid' and year!='$year' order by id desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $year=$row['year'];
	    $cls=$row['cls_level'];
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
		<input type="hidden" name="p" value="haf_slip">
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
	<div id="mytitlebg" align="right">

		<select name="semester" onChange="document.myform.submit();">
		<?php    
			echo "<option value=\"\">- Semester -</option>";
			if($semester=="1"){$selected="selected";}else{$selected="";}
			echo "<option value=1 $selected>Semester 1</option>";
			if($semester=="2"){$selected="selected";}else{$selected="";}
			echo "<option value=2 $selected>Semester 2</option>";
					
		?>
	  </select>&nbsp;<select name="exam" onchange="document.myform.submit();">
		<?php	
      			if($exam==""){
				echo "<option value=\"\">- $lg_exam -</option>";
			}
				$sql="select * from type where grp='examnas' order by idx";
		    		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
				$a=$row['prm'];
				$b=$row['code'];
				if($exam==$b){$selected="selected";}else{$selected="";}
				echo "<option value=\"$b\" $selected>$a</option>";
				}
					if($exam!=""){
					echo "<option value=\"\">- $lg_exam -</option>";
					}

			?>
</select>
</div>
<table width="100%" cellspacing="0">
<tr bgcolor="<?php echo $bg;?>" style="cursor:default;" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
		<td class="mytableheader" style="border-right:none;" width="10%" align="center">No</td>
		<td class="mytableheader" style="border-right:none;" width="25%" align="center">Nama Jilid</td>
		<td class="mytableheader" style="border-right:none;" width="20%" align="center">Status</td>
                <td class="mytableheader" style="border-right:none;" width="45%" align="center">Komentar</td>
</tr>
<?php	
            $sql1="select * from surah_stu_status where grp='qiroati' and sid='$sid' and lvl='$cls' and sem='$semester' and year='$year' and exam='$exam' and uid='$uid' order by dt asc";
            $read=mysql_query($sql1)or die("query failed:".mysql_error());
	    $b=1;
            while($show=mysql_fetch_array($read)){
                $id=$show['id'];
                $des=$show['des'];
                $surah=$show['surahname'];
                $status=$show['status'];
                echo "<tr>
			<td class=\"myborder\" style=\"border-right:none; border-top:none;\" align=\"center\"> $b</td>	
                    <td class=\"myborder\" style=\"border-right:none; border-top:none;\" align=\"left\"> <a href=\"#\" onClick=\"document.myform.id.value=$id;document.myform.submit();\" title=\"edit\" > $surah </a></td>
                <td class=\"myborder\" style=\"border-right:none; border-top:none;\" align=\"center\">$status</td>
                <td class=\"myborder\" style=\"border-right:none; border-top:none;\" align=\"center\">$des</td>
                </tr>";
            $b++;
        }
						
?>


</table>
</div></div><!-- content/story -->
</form>
</body>
</html>
