<?php
$vmod="v5.0.0";
$vdate="160910";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");

verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR');

	
		$showheader=$_REQUEST['showheader'];
		$showgraph=$_REQUEST['showgraph'];
		$skora=$_REQUEST['skora'];

		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
	
		$exam=$_REQUEST['exam'];
		if($exam!=""){
			$sql="select * from type where grp='exam' and code='$exam'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $examname=$row['prm'];
			$sqlcls="";
		}
		
		$year=$_REQUEST['year'];
		if($year=="")
			$year=date('Y');
		$sqlyear="and year='$year'";
		
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$ssname=$row['sname'];
			$simg=$row['img'];
			$namatahap=$row['clevel'];			  
		}
		else{
			$namatahap="Tahap";
		}
		
		$lvl=$_REQUEST['lvl'];

		$sql="select * from cls where sch_id='$sid' and code='$clscode'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$clsname=$row['name'];

		$sql="select * from type where sid='$sid' and prm='$lvl' and grp='classlevel'";
    	$res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$grading=$row['code'];
		
		$sql="select * from grading where name='$grading' order by val desc limit 1";
    	$res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
		$topgrade=$row['grade'];
		
		$sql="select count(*) from sub where sch_id='$sid' and level='$lvl' and grptype=0";
    	$res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_row($res);
		$jumlah_sub=$row[0];
		
		if((strtoupper($namatahap)=="TINGKATAN")&&($lvl>3))
			$show_a1a2=1;
		else
			$show_a1a2=0;
		if($VARIANT=="MDQ")
			$show_a1a2=1;
		if($show_a1a2){
			$sql="select g1+g2 as jumsub from examrank where sch_id='$sid' and cls_level='$clslevel' and year='$year' order by jumsub desc limit 1";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			$row2=mysql_fetch_row($res2);
			$jumlah_sub=$row2[0];
			if($jumlah_sub=="")
				$jumlah_sub=0;
			$topgrade="A";
		}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>

<body>

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="p" value="../examrep/rep_skora_stu">
<input type="hidden" name="sid" value="<?php echo $sid;?>">
<input type="hidden" name="lvl" value="<?php echo $lvl;?>">
<input type="hidden" name="exam" value="<?php echo $exam;?>">
<input type="hidden" name="skora" value="<?php echo $skora;?>">

<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
	<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<a href="#" onClick="document.myform.submit()" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
	<a href="#" onClick="window.close();parent.parent.GB_hide();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
</div>

<div id="right" align="right">
		<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
		<input type="checkbox" name="showheader" value="1"  onClick="showhide('showheader','')" <?php if($showheader) echo "checked";?>>Show Header
</div><!-- end panel right -->
	
</div><!-- end panel -->


<div id="story">

<div id="showheader" <?php if(!$showheader) echo "style=\"display:none\"";?> >
	<?php  include('../inc/school_header.php')?>
</div>

<div id="story_title">
<?php echo strtoupper("$lg_student $year - $namatahap $lvl");?>
</div>

<table width="100%" cellpadding=""="2"  cellspacing="0">
  <tr>
    <td id="mytableheader" style="border-right:none " align="center" width="5%"><?php echo strtoupper($lg_no);?></td>
	<td id="mytableheader" style="border-left:none; border-right:none " align="center" width="15%">&nbsp;</td>
    <td id="mytableheader" style="border-left:none; border-right:none " width="70%"><?php echo strtoupper($lg_student);?></td>
	<td id="mytableheader" style="border-left:none " width="10%" align="center"><?php echo strtoupper($lg_grade);?></td>
  </tr>
<?php 
	if($show_a1a2)
		$sql="select examrank.*,stu.name,stu.file from examrank INNER JOIN stu ON examrank.stu_uid=stu.uid where examrank.sch_id=$sid and cls_level='$lvl' and exam='$exam' and year='$year' and (g1+g2)='$skora' order by gpk asc,avg desc";
	else
		$sql="select examrank.*,stu.name,stu.file from examrank INNER JOIN stu ON examrank.stu_uid=stu.uid where examrank.sch_id=$sid and cls_level='$lvl' and exam='$exam' and year='$year' and g1='$skora' order by gpk asc,avg desc";
	$res=mysql_query($sql)or die("$sql failed:".mysql_error());
     while($row=mysql_fetch_assoc($res)){
          	$uid=$row['stu_uid'];
			$file=$row['file'];
			$name=stripslashes(strtoupper($row['name']));
		  	$gpk=$row['gpk'];
		  	$avg=$row['avg'];
		  	$gred=$row['total_gred'];
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
			<td>:&nbsp;&nbsp;<?php echo $name;?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_matric);?></td>
			<td>:&nbsp;&nbsp;<?php echo $uid;?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_gp_student);?></td>
			<td>:&nbsp;&nbsp;<?php printf("%0.2f",$gpk);?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_average_mark);?></td>
			<td>:&nbsp;&nbsp;<?php printf("%0.2f",$avg);?></td>
		  </tr>
		</table>
		<br>
	</td>
	<td id="myborder_b" align="center"><font size="+1" style="font-weight:bold "><?php echo $gred;?></font></td>
  </tr>
<?php }?>
</table>

</div></div>
</form>
</body>
</html>
