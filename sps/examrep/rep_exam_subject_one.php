<?php
//29/03/2010 - update gui
//04/06/10 4.1.0 - upgrade for use curryear and select basic name if tak ada rekod exam lagi
$vmod="v5.0.0";
$vdate="100909";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
	verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN|HEP|HEP-OPERATOR');
	$username=$_SESSION['username'];
	$isprint=$_REQUEST['isprint'];
	$showheader=$_REQUEST['showheader'];
	
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
		
	if($sid!=0){
		$sql="select * from sch where id='$sid'";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=stripslashes($row['name']);
		$slevel=$row['level'];
		$addr=$row['addr'];
		$state=$row['state'];
		$tel=$row['tel'];
		$fax=$row['fax'];
		$web=$row['url'];
		$school_img=$row['img'];
     	mysql_free_result($res);					  
	}
	$clscode=$_REQUEST['clscode'];
	if($clscode!=""){
			//$sqlclscode="and ses_stu.cls_code='$clscode'";
			$sqlclscode="and exam.cls_code='$clscode'";
			$sql="select * from cls where sch_id=$sid and code='$clscode'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=$row['name'];
			$clslevel=$row['level'];
	}
	else
		$clslevel=0;
	$year=$_REQUEST['year'];
	if($year=="")
		$year=date('Y');
	
	$curryear=date('Y');
	if($curryear==$year)
		$sqlstatuspelajar="and stu.status=6";
		
	$exam=$_REQUEST['exam'];
	if($exam==""){
			$sql="select * from type where grp='exam' and (lvl=0 or lvl='$clslevel') and (sid=0 or sid=$sid) order by idx";
        	$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $examname=$row['prm'];
			$exam=$row['code'];
	}else{
			$sql="select * from type where grp='exam' and code='$exam'";
        	$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $examname=$row['prm'];
	}

	$subcode=$_REQUEST['subcode'];
	$sql="select * from ses_sub where year='$year' and cls_code='$clscode' and sub_code='$subcode' and sch_id=$sid ";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$row2=mysql_fetch_assoc($res2);
	$subname=$row2['sub_name'];
	$gurusubjek=stripslashes($row2['usr_name']);
	$gid=$row2['usr_uid'];
	
	$sql="select * from ses_cls where year='$year' and cls_code='$clscode' and sch_id=$sid ";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$row2=mysql_fetch_assoc($res2);
	$gurukelas=$row2['usr_name'];
	
	$sql="select * from sub where code='$subcode' and level=$clslevel and sch_id=$sid";
    $res=mysql_query($sql)or die("$sql failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
	$gradingset=$row['grading'];
	$sql="select * from grading where name='$gradingset' order by val desc";
	$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
	$i=1;$j=1;
	while($row3=mysql_fetch_assoc($res3)){
		$xg=$row3['grade'];
		$xgp=$row3['gp'];
		$isfail=$row3['sta'];
		$arr_grading_info[$xg]['grade']=0;
		$arr_grading_info[$xg]['point']=$xgp;
		$arr_grading_info[$xg]['isfail']=$isfail;
	}
	
	$sql="select * from rep_exam_note_sub where sid=$sid and gid='$gid' and year='$year' and cls='$clscode'and sub='$subcode' and exam='$exam'";
	$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
	$row3=mysql_fetch_assoc($res3);
	$des=$row3['des'];
	$noteid=$row3['id'];
	
	$op=$_REQUEST['op'];
	if($op=="save"){
		$des=$_REQUEST['gurunote'];
		$sql="delete from rep_exam_note_sub where id='$noteid'";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		
		$sql="insert into rep_exam_note_sub (sid,gid,year,cls,sub,exam,des,adm,ts)values($sid,'$gid',$year,'$clscode','$subcode','$exam','$des','$username',now())";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		$f="<font color=blue>Successfully update</font>";
	}
/** sorting control **/
	$order=$_POST['order'];
	if($order=="")
		$order="asc";
		
	if($order=="desc")
		$nextdirection="asc";
	else
		$nextdirection="desc";
		
	$sort=$_POST['sort'];
	if($sort=="")
		$sqlsort="order by sex desc, name";
	else
		$sqlsort="order by $sort $order, stu.id desc";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo strtoupper($lg_subject);?> : <?php echo strtoupper($subname);?></title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<script language="JavaScript">
<!--
function process_form(op){
	var ret="";
	var cflag=false;
	
		ret = confirm("Are you sure want to save??");
		if (ret == true){
			document.myform.op.value=op;
			document.myform.submit();
		}
		return;

}


//-->
</script>
</head>
<body >

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="../examrep/rep_exam_subject_one">
	<input type="hidden" name="op">
	<input name="isprint" type="hidden" id="isprint" value="<?php echo $isprint;?>">
	<input name="sid" type="hidden" id="sid" value="<?php echo $sid;?>">
	<input name="clscode" type="hidden" id="clscode" value="<?php echo $clscode;?>">
	<input name="subcode" type="hidden" id="subcode" value="<?php echo $subcode;?>">
	<input name="year" type="hidden" id="year" value="<?php echo $year;?>">
	<input name="curr" type="hidden" id="curr" value="<?php echo $curr;?>">
	<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
	<input name="order" type="hidden" id="order" value="<?php echo $order;?>">


<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="../exam/examreg.php?<?php echo"sid=$sid&year=$year&subcode=$subcode&clscode=$clscode&exam=$exam&usr_uid=$gid";?>"  id="mymenuitem"><img src="../img/tool.png"><br>Edit</a>
<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
<a href="#" onClick="document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
<a href="#" onClick="showhide('tipsdiv')" id="mymenuitem"><img src="../img/help22.png"><br>HowTo</a>
<a href="#" onClick="window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
</div> <!-- end mymenu -->
<div align="right">
<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a>
<br>
<br>

<select name="exam" id="exam" onchange="document.myform.submit();">
        <?php	
      				if($exam=="")
						echo "<option value=\"\">- $lg_select -</option>";
					else
						echo "<option value=\"$exam\">$examname</option>";
					$sql="select * from type where grp='exam' and code!='$exam' and (lvl=0 or lvl='$clslevel') and (sid=0 or sid=$sid) order by idx";
            		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $a=$row['prm'];
						$b=$row['code'];
                        echo "<option value=\"$b\">$a</option>";
            		}
            		mysql_free_result($res);	
			?>
      </select>

<input type="checkbox" name="showheader" value="1"  onClick="document.myform.submit();" <?php if($showheader) echo "checked";?>>Show Header 


</div>
</div><!-- end mypanel -->
<div id="story">
<?php if($showheader) include('../inc/school_header.php')?>
<div id="mytitlebg">RAPORT NILAI LEGGER MATAPELAJARAN <?php echo $f;?></div>
<table width="100%">
  <tr>
    <td width="50%">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="20%"><?php echo strtoupper($lg_school);?></td>
			<td width="1%">:</td>
			<td><?php echo strtoupper($sname);?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_class);?></td>
			<td>:</td>
			<td><?php echo strtoupper("$clsname / $year");?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_class_teacher);?></td>
			<td>:</td>
			<td><?php echo strtoupper($gurukelas);?></td>
		  </tr>
		</table>
	</td>
    <td width="50%">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="20%"><?php echo strtoupper($lg_exam);?></td>
			<td width="1%">:</td>
			<td><?php echo strtoupper($examname);?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_subject);?></td>
			<td>:</td>
			<td><?php echo strtoupper($subname);?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_teacher);?></td>
			<td>:</td>
			<td><?php echo strtoupper($gurusubjek);?></td>
		  </tr>
		</table>
	
	</td>
  </tr>
</table>

<div id="tipsdiv" style="display:none ">
<?php if($LG=="BM"){?>
Tips:<br>
1. Hanya guru subjek yang mengajar boleh membuat catatan laporan.<br>
2. Sila klik "save" di atas ruangan catatan bagi menyimpan maklumat.<br>
3. Klik Matrik, L/P, Nama, Nilai atau Gred untuk sorting menaik atau menurun.
<?php }else{?>
Tips:<br>
1. Only subject teacher can make a report.<br>
2. Click save button to make sure the report being save.<br>
3. Click at the table title to sort (name, m/f, matric, mark, gred)
<?php }?>

</div>

<table width="100%"  border="0" id="mytable">
<tr>
<td width="70%" valign="top"> <!-- start right side -->


<table width="100%" cellspacing="0">
  <tr>
    <td id="mytabletitle" width="5%" valign="top" align="center"><?php echo strtoupper($lg_no);?></td>
    <td id="mytabletitle" width="10%" valign="top" align="center"><a href="#" onClick="formsort('uid','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_matric);?></a></td>
	<td id="mytabletitle" width="5%" valign="top" align="center"><a href="#" onClick="formsort('sex <?php echo "$nextdirection";?>, name','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_mf);?></a></td>
    <td id="mytabletitle" width="45%" valign="top" align="left"><a href="#" onClick="formsort('name','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_name);?></a></td>
	<td id="mytabletitle" align="center" width="10%"><a href="#" onClick="formsort('exam.val','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_mark);?></a></td>
	<td id="mytabletitle" align="center" width="10%"><a href="#" onClick="formsort('exam.grade','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_grade);?></a></td>
    <td id="mytabletitle" align="center" width="10%">CATATAN</td>
  </tr>
<?php 
if($clscode!=""){
	$q=0;
	$markah_tertinggi=0;
	$markah_terendah=100;
	$markah_jumlah=0;
	$jumlah_gp=0;
	$jumlah_fail=0;

	$sql="select stu.*,exam.cls_name,exam.point,exam.grade from stu INNER JOIN exam ON stu.uid=exam.stu_uid where exam.sch_id=$sid and exam.year='$year' and examtype='$exam'  and sub_code='$subcode' $sqlstatuspelajar $sqlclscode $sqlsort";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$num=mysql_num_rows($res);
	if($num==0){
		//tak ada rekod exam..tiada examrank.. so select just for name
		if(($sort=="exam.val")||($sort=="exam.grade"))
			$sqlsort="";
		$sql="select stu.sex,stu.uid,stu.name,stu.status,ses_stu.cls_name from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid  and year='$year' and ses_stu.cls_code='$clscode' $sqlstatuspelajar $sqlsort";
		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	}
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$name=strtoupper(stripslashes($row['name']));
		$sex=$row['sex'];
		$status=$row['status'];
		$point=$row['point'];
		$grade=$row['grade'];
		if(!is_numeric($point))
				$point=0;

		$arr_grading_info[$grade]['grade']=$arr_grading_info[$grade]['grade']+1;
		if($arr_grading_info[$grade]['isfail'])
			$jumlah_fail++;
			
		if(($grade!="TT")&&($grade!="TH")){
			if($markah_terendah>$point)
				$markah_terendah=$point;
			if($markah_tertinggi<$point)
				$markah_tertinggi=$point;
			$markah_jumlah=$markah_jumlah+$point;
			$jumlah_gp=$jumlah_gp+$arr_grading_info[$grade]['point'];
			//echo "jumlah_gp:$jumlah_gp=".$arr_grading_info[$grade]['point']." <br> ";
		}
		
		$q++;
		//if($status==6)
			$bg="$bglyellow";
		//else
			//$bg="#FAFAFA";
?>
<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
  	<td id="myborder" align="center"><?php echo $q?></td>
    <td id="myborder" align="center"><?php echo $uid?></td>
	<td id="myborder" align="center"><?php echo $lg_sexmf[$sex]?></td>
	<td id="myborder"><?php echo $name?></td>
	<td id="myborder" align="center" ><?php echo "$point";?></td>
	<td id="myborder" align="center" ><?php echo "$grade";?></td>
	<?php			
			$sql4="select * from sub_stu_summary where uid='$uid' and exam='$exam' and sid=$sid and year='$year' and sub_code='$subcode'";
			$res4=mysql_query($sql4)or die("$sql4 failed:".mysql_error());
			$row4=mysql_fetch_assoc($res4);
			$mmm=$row4['sub_msg'];
			//if(strlen($mmm)>2)
			if($mmm!='')
				$img="../img/check12.png";
			else
				$img="../img/edit12.png";
				echo "<td id=myborder align=\"center\"><a href=\"repexamsubcomment.php?clscode=$clscode&year=$year&exam=$exam&sid=$sid&subcode=$subcode\" id=\"pos_bottom$q\" target=\"_blank\" onMouseOver=\"Tip('$mmm')\" onMouseOut=\"UnTip();\"><div id=\"checkimg$q\"><img src=\"$img\"></div></a></td>";
			?>    
  </tr>
<?php } }?>
</table>

</td><!-- end right side -->
<td valign="top" ><!-- start left side -->

<?php
//$jum_pelajar_ditaksir=$q-$arr_grade['TT']-$arr_grade['TH'];
$jum_pelajar_ditaksir=$q-$arr_grading_info['TT']['grade']-$arr_grading_info['TH']['grade'];
?>

<table width="100%"cellspacing="0">
  <tr>
    <td id="mytabletitle" align="center" width="60%"><?php echo strtoupper($lg_summary);?></td>
    <td id="mytabletitle" align="center" width="20%"><?php echo strtoupper($lg_total);?></td>
    <td id="mytabletitle" align="center" width="20%">%</td>
  </tr>
  <tr style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='#FFFFFF';">
    <td id="myborder" align="center"><?php echo $lg_student;?></td>
    <td id="myborder" align="center"><?php echo $q;?></td>
    <td id="myborder" align="center">100</td>
  </tr>
  <tr style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='#FFFFFF';">
    <td id="myborder" align="center"><?php echo $lg_not_evaluate;?></td>
    <td id="myborder" align="center"><?php echo $arr_grading_info['TT']['grade'];?></td>
    <td id="myborder" align="center"><?php if($q>0) echo round($arr_grading_info['TT']['grade']/$q*100);?></td>
  </tr>
  <tr style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='#FFFFFF';">
    <td id="myborder" align="center"><?php echo $lg_not_attend;?></td>
    <td id="myborder" align="center"><?php echo $arr_grading_info['TH']['grade'];?></td>
    <td id="myborder" align="center"><?php if($q>0) echo round($arr_grading_info['TH']['grade']/$q*100);?></td>
  </tr>
  <tr style="cursor:default;font-weight:bold" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='#FFFFFF';">
    <td id="myborder" align="center"><?php echo "$lg_total $lg_student";?></td>
    <td id="myborder" align="center"><?php echo $jum_pelajar_ditaksir;?></td>
    <td id="myborder" align="center"><?php if($q>0) echo round($jum_pelajar_ditaksir/$q*100);?></td>
  </tr>
<?php
if(($jum_pelajar_ditaksir>0)&&(is_numeric($markah_terendah))) {
	$sql="select * from grading where name='$gradingset' and point<=$markah_terendah order by val desc limit 1";
	$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
	$row3=mysql_fetch_assoc($res3);
	$gred_terendah=$row3['grade'];
	
	$sql="select * from grading where name='$gradingset' and point<=$markah_tertinggi order by val desc limit 1";
	$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
	$row3=mysql_fetch_assoc($res3);
	$gred_tertinggi=$row3['grade'];

	$markah_purata = $markah_jumlah/$jum_pelajar_ditaksir;
	$sql="select * from grading where name='$gradingset' and point<=$markah_purata order by val desc limit 1";
	$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
	$row3=mysql_fetch_assoc($res3);
	$gred_purata=$row3['grade'];
}else{
	$markah_terendah=0;
	$markah_tertinggi=0;
}
$sql="select * from grading where name='$gradingset' and val>=0 order by val desc";
$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
while($row3=mysql_fetch_assoc($res3)){
	$xg=$row3['grade'];
?>
  <tr style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='#FFFFFF';">
    <td id="myborder" align="center"><?php echo $xg;?></td>
    <td id="myborder" align="center"><?php echo $arr_grading_info[$xg]['grade'];?></td>
    <td id="myborder" align="center"><?php if($jum_pelajar_ditaksir>0) echo round($arr_grading_info[$xg]['grade']/$jum_pelajar_ditaksir*100);?></td>
  </tr>
<?php } ?>
  <tr style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='#FFFFFF';">
    <td id="myborder" align="center"><?php echo $lg_total_pass;?></td>
	<td id="myborder" align="center"><?php echo $jum_pelajar_ditaksir-$jumlah_fail;?></td>
    <td id="myborder" align="center"><?php if($jum_pelajar_ditaksir>0) echo round(($jum_pelajar_ditaksir-$jumlah_fail)/$jum_pelajar_ditaksir*100);?></td>
  </tr>
  <tr style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='#FFFFFF';">
    <td id="myborder" align="center"><?php echo $lg_total_fail;?></td>
	<td id="myborder" align="center"><?php echo $jumlah_fail;?></td>
    <td id="myborder" align="center"><?php if($jum_pelajar_ditaksir>0)echo round($jumlah_fail/$jum_pelajar_ditaksir*100);?></td>
  </tr>
  <tr style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='#FFFFFF';">
    <td id="myborder" align="center"><?php echo $lg_highest_mark;?></td>
    <td id="myborder" align="center"><?php echo $markah_tertinggi;?></td>
    <td id="myborder" align="center"><?php echo $gred_tertinggi;?></td>
  </tr>
  <tr style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='#FFFFFF';">
    <td id="myborder" align="center"><?php echo $lg_lowest_mark;?></td>
    <td id="myborder" align="center"><?php echo $markah_terendah;?></td>
    <td id="myborder" align="center"><?php echo $gred_terendah;?></td>
  </tr>
  <tr style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='#FFFFFF';">
    <td id="myborder" align="center"><?php echo $lg_average_mark;?></td>
    <td id="myborder" align="center"><?php printf("%d",$markah_purata);?></td>
    <td id="myborder" align="center"><?php echo $gred_purata;?></td>
  </tr>
  <tr style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='#FFFFFF';">
    <td id="myborder" align="center"><?php echo $lg_gp_subject;?></td>
    <td id="myborder" align="center"><?php if($jum_pelajar_ditaksir>0) printf("%.02f",$jumlah_gp/$jum_pelajar_ditaksir); ?></td>
    <td id="myborder" align="center"></td>
  </tr>
</table>

<table width="100%" id="myborder" cellspacing="0"> 
  <tr>
    <td id="mytabletitle" width="99%"><?php echo strtoupper($lg_teacher_note);?> : <?php echo strtoupper($gurusubjek);?></td>
	<?php if($username==$gid){?>
	<td id="mytabletitle" width="1%" valign="top"><a href="#" onClick="process_form('save')"><img src="../img/save.png"></a></td>
	<?php } ?>
  </tr>
  <tr><td colspan="2">
  <textarea name="gurunote" style="width:100%;border:none;" rows="16" <?php if($username!=$gid) echo "readonly";?> ><?php echo "$des";?></textarea>
  </td></tr>
</table>



</td><!-- end left side -->
</tr>
</table>

</div></div>
</form>

<form name="formwindow" method="post" action="../examrep/rep_exam_subject_one.php" target="newwindow">
	<input name="curr" type="hidden" id="curr" value="<?php echo $curr;?>">
	<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
	<input name="order" type="hidden" id="order" value="<?php echo $order;?>">
	<input type="hidden" name="p" value="../examrep/rep_exam_subject_one">
	<input name="exam" type="hidden" id="exam" value="<?php echo $exam;?>">
	<input name="sid" type="hidden" id="sid" value="<?php echo $sid;?>">
	<input name="clscode" type="hidden" id="clscode" value="<?php echo $clscode;?>">
	<input name="subcode" type="hidden" id="subcode" value="<?php echo $subcode;?>">
	<input name="year" type="hidden" id="year" value="<?php echo $year;?>">
	<input name="usr_uid" type="hidden" value="<?php echo $gid;?>">
	<input name="isprint" type="hidden" value="<?php echo $isprint;?>">
</form>
</body>
</html>
<!-- 
v2.7
27/11/08: culculate fail student
2.6
13/11/08: new module
Author: razali212@yahoo.com
-->