<?php
$vmod="v6.0.0";
$vdate="110916";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("");

	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
		
	if($sid==0)
		$sql="select * from sch";
	else
		$sql="select * from sch where id=$sid";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
	$sid=$row['id'];
	$sname=$row['sname'];
	$namatahap=$row['clevel'];
	$issemester=$row['issemester'];
	$startsemester=$row['startsemester'];
	
	$year=$_POST['year'];
	/*if($year==""){
		$year=date('Y');
		if(($issemester)&&(date('n')<$startsemester))
			$year=$year-1;
	}
	$xx=$year+1;
	if($issemester)
		$sesyear="$year/$xx";	  
	else*/
		$sesyear="$year";
	
	$exam=$_POST['exam'];
	if($exam=="")
		$sql="select * from type where grp='exam' and (sid=0 or sid=$sid) order by idx";
	else
		$sql="select * from type where grp='exam' and code='$exam' and (sid=0 or sid=$sid) order by idx";
	
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$examname=$row['prm'];
	$exam=$row['code'];
	
	$cons=$_POST['cons'];
	if($cons=="")
		$sql="select * from type where grp='subtype' and (sid=0 or sid=$sid) order by idx";
	else
		$sql="select * from type where grp='subtype' and prm='$cons' and (sid=0 or sid=$sid) order by idx";
	
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$cons=$row['prm'];
	
	$sqlcons="and sub_grp='$cons'";

?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>


<!-- SETTING GRAY BOX -->
<script type="text/javascript"> var GB_ROOT_DIR = "<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/"; </script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_scripts.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/greybox/gb_styles.css" rel="stylesheet" type="text/css" media="all" />
<!-- apai remark
<script type="text/javascript" src="<?php echo $MYOBJ;?>/GreyBox_v5_53/static_files/help.js"></script>
<link href="<?php echo $MYOBJ;?>/GreyBox_v5_53/static_files/help.css" rel="stylesheet" type="text/css" media="all" />
-->

<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<!-- SETTING GRAPH CHART -->
<script language="javascript">AC_FL_RunContent = 0;</script>
<script language="javascript"> DetectFlashVer = 0; </script>
<script src="<?php echo $MYOBJ;?>/charts/AC_RunActiveContent.js" language="javascript"></script>
<script language="JavaScript" type="text/javascript">
<!--
var requiredMajorVersion = 9;
var requiredMinorVersion = 0;
var requiredRevision = 45;
-->
</script>

</head>

<body>

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br><?php echo $lg_print;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
	</div>
	
	<div align="right">
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
	</div>	
</div>
<!-- end mypanel-->


<div id="mytabletitle" class="printhidden" style="padding:5px 5px 5px 5px;margin:0px 1px 0px 1px;" align="right">

<select name="sid" id="sid" onchange="document.myform.exam[0].value='';document.myform.submit();">
        <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_select $lg_school -</option>";
			else
                echo "<option value=$sid>$sname</option>";
				
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['sname'];
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
			}							  
?>
	</select>
	<select name="year" onChange="document.myform.submit();">
        <?php
            //echo "<option value=$year>$lg_session $sesyear</option>";
			$sql="select * from type where grp='session' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
                       // $xx=$s+1;
						//if($issemester)
						//		$xsesyear="$s/$xx";
						//else
						//		$xsesyear=$s;
						if($s==$sesyear){$selected="selected";}else{$selected="";}
						echo "<option value=\"$s\" $selected>$lg_session $s</option>";
            }				  
?>
    </select>
	<select name="exam" id="exam" onchange="document.myform.submit();">
        <?php	
      				if($exam=="")
						echo "<option value=\"\">- $lg_select $lg_exam -</option>";
					else
						echo "<option value=\"$exam\">$examname</option>";
					$sql="select distinct(code),prm from type where grp='exam' and code!='$exam' and (sid=0 or sid=$sid) order by idx";
            		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $a=$row['prm'];
						$b=$row['code'];
                        echo "<option value=\"$b\">$a</option>";
            		}
            		mysql_free_result($res);	
			?>
      </select>
      <!--
	   <select name="cons" id="cons" onchange="document.myform.submit();">
         <?php	
      				if($cons=="")
						echo "<option value=\"\">- $lg_select $lg_group $lg_subject -</option>";
					else
						echo "<option value=\"$cons\">$cons</option>";
					$sql="select * from type where grp='subtype' and prm!='$cons' and (sid=0 or sid=$sid) order by idx";
            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=$row['prm'];
                        echo "<option value=\"$b\">$b</option>";
            		}

			?>
       </select>
	  
-->
   
      <input type="button" name="Submit" value="<?php echo $lg_view;?>" onClick="document.myform.submit()" >
      <!--
      <br><br>
      <a href="#" onClick="document.myform.p.value='sub_avg_cls'; document.myform.submit();">VIEW CLASS ANALISYS</a>
	  -->

		
</div>
<div id="story">
<table width="100%"  id="mytitlebg">
  <tr>
    <td width="50%">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="20%"><?php echo strtoupper($lg_school);?></td>
			<td width="1%">:</td>
			<td><?php echo strtoupper($sname);?></td>
		  </tr>
		  <tr>
			<td width="20%"><?php echo strtoupper($lg_session);?></td>
			<td width="1%">:</td>
			<td><?php echo strtoupper("$sesyear");?></td>
		  </tr>
		</table>
	</td>
    <td width="50%" valign="top">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="20%"><?php echo strtoupper($lg_exam);?></td>
			<td width="1%">:</td>
			<td><?php echo strtoupper($examname);?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_report);?></td>
			<td>:</td>
			<td><?php echo strtoupper("$lg_average_mark");?>
			</td>
		  </tr>
		</table>
	
	</td>
  </tr>
</table>
<table width="100%" cellspacing="0" cellpadding="0">
<tr>
<?php
	$sql="select distinct(level) from cls where sch_id='$sid'";
	$res10=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	while($row10=mysql_fetch_assoc($res10)){
			$clslevel=$row10['level'];
?>
<td width="15%" valign="top" id="myborder">
<div id="mytitle2"><?php echo "$namatahap $clslevel";?></div>
<table width="100%" cellpadding=""="2"  cellspacing="0">
  <tr>
    <td id="mytabletitle" style="border-right:none " align="center" width="5%"><?php echo strtoupper($lg_no);?></td>
	<td id="mytabletitle" style="border-left:none; border-right:none " align="center" width="15%">&nbsp;</td>
    <td id="mytabletitle" style="border-left:none; border-right:none " width="70%"><?php echo strtoupper($lg_student);?></td>
  </tr>
<?php 
$q=0;
$sql="select examrank.*,stu.name,stu.file from examrank INNER JOIN stu ON examrank.stu_uid=stu.uid where examrank.sch_id=$sid and examrank.year='$year' and examrank.cls_level=$clslevel and exam='$exam' and total_sub>0 order by avg desc limit 3";
$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){
			$uid=$row['stu_uid'];
			$file=$row['file'];
			$name=stripslashes(strtoupper($row['name']));
		  	$gpk=$row['gpk'];
		  	$avg=$row['avg'];
		  	$gred=$row['total_gred'];
			$totalsub=$row['total_sub'];
			if(($q++%2)==0)
				$bg="#FAFAFA";
			else
				$bg="";

?>

<tr bgcolor="<?php echo $bg;?>">
    <td id="myborder_lb" align="center"><font size="+2" style="font-weight:bold "><?php echo $q;?></font></td>
	<td id="myborder_b" align="center">
	<br>
	<img src="<?php echo "$dir_image_student$file";?>" align="Gambar" height="80" width="80">
	<br><br>
	</td>
    <td id="myborder_b" valign="top">
		<br>
		<table width="100%"  border="0" cellpadding="0" style="font-weight:bold ">
		  <tr>
			<td width="25%"><?php echo strtoupper($lg_name);?></td>
			<td width="1%">:</td>
            <td width="74"><?php echo $name;?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_matric);?></td>
            <td>:</td>
			<td><?php echo $uid;?></td>
		  </tr>
          <!--
		  <tr>
			<td><?php echo strtoupper($lg_gp_student);?></td>
            <td>:</td>
			<td><?php printf("%0.2f",$gpk);?></td>
		  </tr>
          -->
		  <tr>
			<td><?php echo strtoupper($lg_average_mark);?></td>
            <td>:</td>
			<td><?php printf("%0.2f",$avg);?></td>
		  </tr>
          <tr>
			<td><?php echo strtoupper($lg_total_grade);?></td>
            <td>:</td>
			<td><?php echo $gred;?></td>
		  </tr>
          <tr>
			<td><?php echo strtoupper($lg_total_subject);?></td>
            <td>:</td>
			<td><?php echo $totalsub;?></td>
		  </tr>          
		</table>
		<br>
	</td>
  </tr>
  <?php }?>
  </table>
</td>
<?php  $newrow++; if($newrow==2){$newrow=0; echo "</tr><tr>";}}
?>
</tr>
</table>

	
</div></div>
</form>
</body>
</html>