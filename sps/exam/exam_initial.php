<?php
$vmod="v5.0.0";
$vdate="10/05/2010";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
is_verify("ADMIN|AKADEMIK|KEWANGAN|GURU");
$username = $_SESSION['username'];

	$usr_uid=$_REQUEST['usr_uid'];
	if(!is_verify('ADMIN|AKADEMIK'))
		$usr_uid=$_SESSION['username'];

	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
		
			
	$year=$_REQUEST['year'];
	if($year=="")
		$year=date('Y');
	$clscode=$_REQUEST['clscode'];
 
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql  failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$stype=$row['level'];
			$level=$row['clevel'];
			$slvl=$row['lvl'];
            mysql_free_result($res);	  
		}
		else{
			$level="Tahap";
			$slvl=0;
		}
		
		if($clscode!=""){
			$sql="select * from ses_sub where sch_id=$sid and cls_code='$clscode'";
            $res=mysql_query($sql)or die("$sql failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clslevel=$row['cls_level'];
			$clsname=stripslashes($row['cls_name']);
			
			$sql="select * from ses_cls where year='$year' and cls_code='$clscode' and sch_id=$sid ";
			$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			$row2=mysql_fetch_assoc($res2);
			$gurukelas=stripslashes($row2['usr_name']);
		}
		$subcode=$_REQUEST['subcode'];
		if($subcode!=""){
			$sql="select distinct(sub_code),sub_name,sub_grp from ses_sub where sch_id=$sid and cls_code='$clscode' and year='$year' and sub_code='$subcode'";
            $res=mysql_query($sql)or die("$sql failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
        	$subname=stripslashes($row['sub_name']);
			$subgroup=stripslashes($row['sub_grp']);
			$subcode=$row['sub_code'];
			$sql="select * from ses_sub where year='$year' and cls_code='$clscode' and sub_code='$subcode' and sch_id=$sid ";
			$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			$row2=mysql_fetch_assoc($res2);
			$gurusubjek=stripslashes($row2['usr_name']);
		}

//echo "$gradingset";
/** sorting control **/
	$order=$_POST['order'];
	if($order=="")
		$order="desc";
		
	if($order=="desc")
		$nextdirection="asc";
	else
		$nextdirection="desc";
		
	$sort=$_POST['sort'];
	if($sort=="")
		$sqlsort="order by sex desc,name asc";
	else
		$sqlsort="order by $sort $order, name asc";
?>
<!-- 
Version		: 2.6
Author		: razali212@yahoo.com
13/11/2008	: 
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<SCRIPT LANGUAGE="JavaScript">
function process_form(operation){
	document.myform.action="";
	document.myform.target="";
	document.myform.submit();
}
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo $lg_exam_initial;?></title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body >
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
	<input name="order" type="hidden" id="order" value="<?php echo $order;?>">

<div id="content">
<div id="mypanel" class="printhidden">
	<div id="mymenu" align="center">
		<a href="#" onClick="window.print();" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>        
		<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>        
		<a href="#" onClick="window.close();" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>        
	</div>
<div align="right"  ><a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br></div>
</div> <!-- end mypanel -->
<div id="mytabletitle" class="printhidden" align="right" >
				<a href="#" title="<?php echo $vdate;?>"></a><br><br>
<?php if(is_verify("ADMIN|AKADEMIK|CEO")){?>
			  	<select name="year" id="year" onchange="document.myform.submit();">
				<?php
					echo "<option value=$year>$lg_session $year</option>";
					$sql="select * from type where grp='session' and prm!='$year' order by val desc";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
						$s=$row['prm'];
						$v=$row['val'];
						echo "<option value=\"$s\">$lg_session $s</option>";
					}
				?>
          	</select> 
			<select name="sid" id="sid" onchange="document.myform.clscode[0].value='';process_form(1);">
			<?php	
      		if($sid=="0")
            	echo "<option value=\"\">- $lg_school -</option>";
			else
                echo "<option value=\"$sid\">$sname</option>";
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("$sql failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=$row['name'];
							$t=$row['id'];
							echo "<option value=\"$t\">$s</option>";
				} 
			}
			?>
              </select>
			 <select name="clscode" id="clscode" onchange="process_form(4);">
			<?php	
      		if($clscode==""){
            	echo "<option value=\"\">- $lg_class -</option>";
				$sql="select distinct(cls_code),cls_name from ses_sub where sch_id=$sid and year='$year' order by cls_level,cls_name";
			}
			else{
				echo "<option value=\"$clscode\">$clsname</option>";		
				$sql="select distinct(cls_code),cls_name from ses_sub where sch_id=$sid and year='$year' and cls_code!='$clscode' order by cls_level,cls_name";
			}
            $res=mysql_query($sql)or die("$sql failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $a=$row['cls_code'];
						$b=$row['cls_name'];
                        echo "<option value=\"$a\">$b</option>";
            }
			?>
              </select>
				<select name="subcode" id="subcode" onchange="process_form(3);">
                <?php	
			if($subcode==""){
            	echo "<option value=\"\">- $lg_subject -</option>";
				$sql="select distinct(sub_code),sub_name,sub_grp from ses_sub where sch_id=$sid and cls_code='$clscode' and year='$year'";
			}
			else{
					
                echo "<option value=\"$subcode\">$subcode $subname</option>";				
				$sql="select distinct(sub_code),sub_name,sub_grp from ses_sub where sch_id=$sid and cls_code='$clscode' and year='$year' and sub_code!='$subcode'";
			}
			
            $res=mysql_query($sql)or die("$sql failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                $a=$row['sub_name'];
				$c=$row['sub_code'];
                echo "<option value=\"$c\">$c $a</option>";
				
            }
			?>
              </select>
			  <input type="button" name="Button" value="View" onclick="process_form();">
			  <?php } else {?>
			  	<input type="hidden" name="year" value="<?php echo $year;?>">
				<input type="hidden" name="clscode" value="<?php echo $clscode;?>">
				<input type="hidden" name="subcode" value="<?php echo $subcode;?>">
				<input type="hidden" name="sid" value="<?php echo $sid;?>">
			  <?php } ?>
</div>
<div id="story">
<div id="mytitlebg"><?php echo strtoupper($lg_exam_initial);?></div>
<table width="100%" style="font-size:12px ">
  <tr>
    <td width="50%" valign="top">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="20%"><?php echo $lg_school;?></td>
			<td width="1%">:</td>
			<td><?php echo $sname;?></td>
		  </tr>
		  <tr>
			<td><?php echo $lg_year_session;?></td>
			<td>:</td>
			<td><?php echo "$year";?></td>
		  </tr>
		  <tr>
			<td><?php echo $lg_class;?></td>
			<td>:</td>
			<td><?php echo "$clsname ";?></td>
		  </tr>
		  <tr>
			<td><?php echo $lg_class_teacher;?></td>
			<td>:</td>
			<td><?php echo $gurukelas;?></td>
		  </tr>
		</table>
	</td>
    <td width="50%" valign="top">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="20%"><?php echo $lg_subject;?></td>
			<td width="1%">:</td>
			<td><?php echo "$subcode $subname";?></td>
		  </tr>
		  <tr>
			<td><?php echo $lg_exam_supervisor;?></td>
			<td>:</td>
			<td></td>
		  </tr>
		  <tr>
			<td><?php echo $lg_exam;?></td>
			<td>:</td>
			<td><?php //echo date("d-m-Y");?></td>
		  </tr>
		  <tr>
			<td><?php echo $lg_date;?></td>
			<td>:</td>
			<td><?php //echo date("d-m-Y");?></td>
		  </tr>
		</table>
	
	</td>
  </tr>
</table>

<table width="100%" cellspacing="0" cellpadding="3" style="font-size:11px">
<tr>
              <td id="mytabletitle" width="3%" align="center"><?php echo strtoupper($lg_no);?></td>
              <td id="mytabletitle" width="7%" align="center"><a href="#" onClick="formsort('stu_uid','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_matric);?></a></strong></td>
			  <td id="mytabletitle" width="2%" align="center"><a href="#" onClick="formsort('sex','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_mf);?></a></td>	
			  <td id="mytabletitle" width="50%"><a href="#" onClick="formsort('stu_name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></strong></td>
              <td id="mytabletitle" width="10%" align="center"><?php echo $lg_exam_number; ?></td>
			  <td id="mytabletitle" width="10%" align="center">TTD Peserta</td>
</tr>
<?php
		$q=0;
		$sql="select ses_stu.*,stu.sex from ses_stu INNER JOIN stu ON ses_stu.stu_uid=stu.uid where stu.sch_id=$sid and ses_stu.cls_code='$clscode' and ses_stu.year='$year' and stu.status=6 $sqlsort";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$stuname=$row['stu_name'];
			$stuuid=$row['stu_uid'];
			$sex=$row['sex'];
			/** check if existing **/
			$sql="select point,grade from exam where sch_id=$sid and year='$year' and cls_code='$clscode' and sub_code='$subcode' and stu_uid='$stuuid' and examtype='$examtype'";
			//echo $sql;
			$pp="TT";
			$gg="TT";
			$res2=mysql_query($sql)or die("$sql failed:".mysql_error());
			$nn=mysql_num_rows($res2);
			if($nn>0){
				$row2=mysql_fetch_assoc($res2);
				$pp=$row2['point'];
				$gg=$row2['grade'];
			}
			if(($q++%2)==0)
				$bg="";
			else
				$bg="#FAFAFA";
?>
		<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
    		<td id=myborder align=center><?php echo $q;?></td>
			<td id=myborder align=center><?php echo $stuuid?></td>
			<td id=myborder align=center><?php echo $lg_sexmf[$sex];?></td>
			<td id=myborder><?php echo $stuname?></td>
			<td id=myborder>&nbsp;</td>
			<td id=myborder>&nbsp;</td>
	 	</tr>
<?php } ?>

</table>
<table width="100%"  border="0" cellpadding="0" id=mytitle>
  <tr>
    <td width=30%><?php echo strtoupper($lg_exam_supervisor);?> I : </td>
    <td width=30%></td>
    <td width=30%><?php echo strtoupper($lg_exam_supervisor);?> II :</td>
    <td></td>
  </tr>
</table>

</div></div>
</form>
  
</body>
</html>
