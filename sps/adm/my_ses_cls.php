<?php
$vmod="v5.0.0";
$vdate="10/05/2010";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("");
$username = $_SESSION['username'];
$f=$_REQUEST['f'];
	/**	
		$year=$_REQUEST['year'];
		if($year=="")
			$year=date("Y");
	**/

$year=$_REQUEST['year'];
if($year==""){
		$year=date('Y');
		if(($issemester)&&(date('n')<$startsemester))
			$year=$year-1;
		$xx=$year+1;
		$sesyear="$year/$xx";	
	
}else{
		$sesyear="$year";
}
$year=$sesyear;

		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid=$_SESSION['sid'];
		
		if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $sname=$row['name'];
			$stype=$row['level'];
            mysql_free_result($res);					  
		}

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
		$sqlsort="order by id $order";
	else
		$sqlsort="order by $sort $order, id desc";
		
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script language="JavaScript">
<!--
function process_form(sid,uid,clscode,subcode,op){
		document.myform.sid.value=sid;
		document.myform.usr_uid.value=uid;
		document.myform.clscode.value=clscode;
		document.myform.subcode.value=subcode;
		document.myform.operation.value="4";
		if(op==0){
			document.myform.sort.value="";
			document.myform.order.value="";
			document.myform.p.value="examreg";
		}else{
			document.myform.p.value="att_cls_rep";
			document.myform.action="p.php";
		}
		document.myform.submit();
}
//-->
</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body>
 
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<input type="hidden" name="p" value="my_ses_cls">
<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
<input name="order" type="hidden" id="order" value="<?php echo $order;?>">
<input type="hidden" name="operation">

<input name="sid" type="hidden" id="sid">
<input name="usr_uid" type="hidden" id="usr_uid">
<input name="clscode" type="hidden" id="clscode">
<input name="subcode" type="hidden" id="subcode">


<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="../adm/my_ses_cls.php" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>	
		<a href="#" onClick="window.close();parent.$.fancybox.close();"id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/close.png"><br>Close</a>
						<div id="mymenu_space">&nbsp;&nbsp;</div>
						<div id="mymenu_seperator"></div>
						<div id="mymenu_space">&nbsp;&nbsp;</div>        
	</div>
		<div align="right">
				<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
		</div>
</div>
<div id="story">
<div id="mytabletitle" style="padding:5px;">
<select name="year" onChange="document.myform.submit()" style="font-size:18px; font-weight:bold;">
                <?php 
					echo "<option value=$year>$lg_session $year</option>";
					$sql="select * from type where grp='session' and prm!='$year' order by val desc limit 1";
					$res=mysql_query($sql)or die("query failed:".mysql_error());
					while($row=mysql_fetch_assoc($res)){
								$s=$row['prm'];
								echo "<option value=$s>$lg_session $s</option>";
					}
				?>
          </select>
</div>
         
<div id="mytitle2"><?php echo $lg_class_teacher;?></div>
<table width="100%" cellspacing="0" cellpadding="10" style="font-size:12px; font-weight:bold;">
<?php 
	$q=0;
	$sql="select * from ses_cls where usr_uid='$username' and year='$year' order by sch_id desc, cls_level asc";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$id=$row['id'];
		$sid=$row['sch_id'];
    	$cn=stripslashes($row['cls_name']);
		$cc=$row['cls_code'];
		$cl=$row['cls_level'];
		$sid=$row['sch_id'];
		$sql="select * from sch where id=$sid";
        $res2=mysql_query($sql)or die("query failed:".mysql_error());
        $row2=mysql_fetch_assoc($res2);
        $sname=$row2['name'];
		if(($q++%2)==0)
			$bg="";
		else
			$bg="";
?>

  <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
	<td id="myborder" width="20%">&nbsp;<?php echo "$cn";?></td>
	<td id="myborder" width="15%"><a href="../stu/student.php?<?php echo "year=$year&clscode=$cc&sid=$sid";?>" title="<?php echo $lg_student;?>" target="_blank"><img src="../img/users12.png" style="float:left ">&nbsp;<?php echo $lg_student_profile;?></a></td>
	<td id="myborder" width="18%"><a href="../eatt/att_cls_rep.php?<?php echo "year=$year&sid=$sid&cls=$cc";?>" target="_blank" title="<?php echo $lg_update;?>"><img src="../img/edit12.png" style="float:left ">&nbsp;<?php echo $lg_school_attendance;?></a></td>
	<td id="myborder" width="12%"><a href="../examrep/repexamstucls.php?<?php echo "year=$year&clscode=$cc&sid=$sid";?>" title="<?php echo $lg_report;?>" target="_blank"><img src="../img/users12.png" style="float:left ">&nbsp;<?php echo "Rapot Ujian";?></a></td>
	<td id="myborder" width="15%"><a href="../tk/exam_stu_list.php?<?php echo "year=$year&clscode=$cc&sid=$sid";?>" title="<?php echo $lg_report;?>" target="_blank"><img src="../img/users12.png" style="float:left ">&nbsp;<?php echo "Nilai / Rapot $TK";?></a></td>
    <td id="myborder" width="20%"><a href="../bmi/bmi.php?<?php echo "year=$year&clscode=$cc&sid=$sid";?>" title="<?php echo $lg_report;?>" target="_blank"><img src="../img/users12.png" style="float:left ">&nbsp;<?php echo $lg_height_and_weight;?></a></td>
  </tr>
<?php } ?>
 </table> 
<br>
<br>
 
 <div id="mytitle2"><?php echo $lg_teaching_subject;?></div>
 <table width="100%" cellspacing="0" cellpadding="5px" style="font-size:11px;">
  <tr>
  	
    <td id="mytabletitle" style="border-left:none;" align="center" width="2%"><?php echo $lg_no;?></td>
    <td id="mytabletitle" style="border-left:none;" width="10%">&nbsp;<a href="#" onClick="formsort('cls_name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo $lg_student_profile;?></a></td>
	<td id="mytabletitle" style="border-left:none;" width="5%">&nbsp;<a href="#" onClick="formsort('sub_code','<?php echo "$nextdirection";?>')" title="Sort"><?php echo $lg_subject;?></a></td>
	<td id="mytabletitle" style="border-left:none;" width="15%">&nbsp;<a href="#" onClick="formsort('sub_name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo $lg_exam;?></a></td>
	<td id="mytabletitle" style="border-left:none;" width="5%" align="center"><?php echo $lg_printout;?></td>    
<?php if($ON_HEADCOUNT){?>    
	<td id="mytabletitle" style="border-left:none;" width="10%" align="center">Headcount</td>
<?php } ?>     
<?php if($ON_HOMEWORK){?>    
    <td id="mytabletitle" style="border-left:none;" width="10%" align="center"><?php echo $lg_homework;?></td>
<?php } ?>     
<?php if($ON_ATTENDANCE_CLASS){?>    
    <td id="mytabletitle" style="border-left:none;" width="10%" align="center"><?php echo $lg_class_attendance;?></td>
<?php } ?>     
<?php if($ON_DAILY_READ){?>
	<td id="mytabletitle" style="border-left:none;" width="10%" align="center"><?php echo $lg_daily_reading;?></td>
<?php } ?>
<?php if($ON_LESSON_PLAN){?>
	<td id="mytabletitle" style="border-left:none;" width="10%" align="center"><?php echo $lg_lesson_plan;?></td>
<?php } ?>    
  </tr>


<?php 
	$q=0;
	$sql="select * from ses_sub where usr_uid='$username' and year='$year' $sqlsort";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
		$id=$row['id'];
		$sid=$row['sch_id'];
    	$cn=stripslashes($row['cls_name']);
		$cc=$row['cls_code'];
		$cl=$row['cls_level'];
		$sc=$row['sub_code'];
		$sn=$row['sub_name'];
		$sg=$row['sub_grp'];
		$sid=$row['sch_id'];
		$sql="select * from sch where id=$sid";
            $res2=mysql_query($sql)or die("query failed:".mysql_error());
            $row2=mysql_fetch_assoc($res2);
            $sname=$row2['name'];
			
		if(($q++%2)==0)
			$bg="";
		else
			$bg="";
?>
  <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
		<td id="myborder" style="border-left:none; border-top:none;" align="center"><?php echo "$q";?></td>
		<td id="myborder" style="border-left:none; border-top:none;"><a href="../stu/student.php?<?php echo "year=$year&clscode=$cc&sid=$sid";?>" target="_blank" title="<?php echo $lg_profile;?>"><?php echo "$cn";?></a></td>
		<td id="myborder" style="border-left:none; border-top:none;">&nbsp;<?php echo "$sc";?></td>		
		<td id="myborder" style="border-left:none; border-top:none;">
			<?php if($sg=='Subjek Hafalan'){?>
			<a href="../ehaf/hafazan_stu_surah.php?<?php echo "year=$year&clscode=$cc&sid=$sid";?>" title="<?php echo $lg_report;?>" target="_blank"><img src="../img/edit12.png" style="float:left ">&nbsp;<?php echo "Catatan Surat / Nilai Hafalan";?></a>
			<?php }else{?>
			<a href="../exam/examreg.php?<?php echo "year=$year&sid=$sid&usr_uid=$username&clscode=$cc&subcode=$sc&isprint=1";?>" target="_blank" title="<?php echo $lg_update;?>">
			<img src="../img/edit12.png" style="float:left ">&nbsp;<?php echo "$sn";?></a>
			<?php }?>
		</td>
		<td id="myborder" style="border-left:none; border-top:none;" align="center"><a href="../exam/exam_initial.php?<?php echo "year=$year&clscode=$cc&subcode=$sc&sid=$sid";?>" target="_blank" title="<?php echo "Checklist Student Exam Attendance";?>">
			<img src="../img/printer12.png"></a></td>        
<?php if($ON_HEADCOUNT){?>         
		<td id="myborder" style="border-left:none; border-top:none;" align="center">
			<a href="<?php echo "../ehc/hc_sub_stu.php?year=$year&sid=$sid&clscode=$cc&subcode=$sc";?>" target="_blank">
			<img src="../img/edit12.png"><?php echo $lg_update;?></a>
		</td>
<?php } ?>        
<?php if($ON_HOMEWORK){?>        
        <td id="myborder" style="border-left:none; border-top:none;" align="center">
        <?php
			$sql="select count(*) from hwork where uid='$username' and cls='$cc' and sub='$sc' and year='$year'";
			$res2=mysql_query($sql)or die("query failed:".mysql_error());
			$row2=mysql_fetch_row($res2);
			$num=$row2[0];
		?>
			<a href="<?php echo "../hwork/hwork.php?year=$year&sid=$sid&clscode=$cc&subcode=$sc";?>">
			<img src="../img/edit12.png"> <?php echo $num;?></a>
		</td>
<?php } ?>         
<?php if($ON_ATTENDANCE_CLASS){?>        
        <td id="myborder" style="border-left:none; border-top:none;" align="center">
			<a href="<?php echo "../clsatt/attstu.php?year=$year&sid=$sid&cls=$cc&sub=$sc";?>" target="_blank">
			<img src="../img/edit12.png"><?php echo $lg_update;?></a>
		</td>
<?php } ?>      
<?php if($ON_DAILY_READ){?>
		<td id="myborder" style="border-left:none; border-top:none;" align="center">
			<a href="<?php echo "../dailyread/attstu.php?year=$year&sid=$sid&cls=$cc&sub=$sc";?>" target="_blank">
			<img src="../img/edit12.png"><?php echo $lg_update;?></a>
		</td>
<?php } ?>
<?php if($ON_LESSON_PLAN){?>
		<td id="myborder" style="border-left:none; border-top:none;" align="center">
			<a href="<?php echo "../elesson/elesson.php?year=$year&sid=$sid&clscode=$cc&subcode=$sc";?>">
			<img src="../img/edit12.png">&nbsp;<?php echo $lg_update;?></a> 
		</td>
        
  </tr>
<?php } ?>

<?php } ?>
 </table> 

 </div> </div>
 </form>
</body>
</html>
