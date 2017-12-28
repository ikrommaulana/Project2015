<?php
//08/05/2010 - studen dah brenti boleh masuk Nilai kes SPM
//29/03/2010 - patch examsave.php
//04/06/10 4.1.0 - upgrade for use curryear and select basic name if tak ada rekod exam lagi
//24/06/10 4.1.0 - upgrade examreg_edit modify max point
//30/07/10 4.1.0 - upgrade examsave th/tt base on grade point
//110412 - locking exam
//110626 - patch for attamimi..
//110626 - update fail
$vdate="110626";
$vmod="v6.1.0";

include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify("ADMIN|AKADEMIK|KEWANGAN|GURU");
$username = $_SESSION['username'];
$isprint=$_REQUEST['isprint'];

		$newfile=$_FILES['file1']['tmp_name'];
		if($newfile!=""){
				require_once 'excel_reader2.php';
				$data = new Spreadsheet_Excel_Reader($newfile);
				//echo "$newfile";
				for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
										$c1=$data->sheets[0]['cells'][$i][1];
										$c2=$data->sheets[0]['cells'][$i][2];
										$c3=$data->sheets[0]['cells'][$i][3];
										$c1=str_replace("\"","",$c1);
										//echo "$c1-$c2-$c3<br>";
				}
				
		}


	$showheader=$_REQUEST['showheader'];
	$gid=$_REQUEST['usr_uid'];
	//if(!is_verify('ADMIN|AKADEMIK|ROOT'))
	if($gid=="")
		$gid=$_SESSION['username'];

	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
		
	$year=$_REQUEST['year'];
	if($year=="")
		$year=date('Y');
	
	$curryear=date('Y');
	if($curryear==$year)
		$sqlstatuspelajar="and stu.status=6";
		
	$clscode=$_REQUEST['clscode'];
	$subcode=$_REQUEST['subcode'];
 	$edit=$_REQUEST['edit'];
	$exam=$_REQUEST['exam'];
	if($exam!=""){
			$sql="select * from type where grp='exam' and code='$exam'";
            $res=mysql_query($sql)or die("$sql query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $examname=$row['prm'];
	}
	$sql="select * from usr where uid='$gid' order by name";
    $res=mysql_query($sql)or die("$sql failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
    $gname=$row['name'];
	if($sid!=0){
			$sql="select * from sch where id='$sid'";
            $res=mysql_query($sql)or die("$sql  failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $schname=$row['name'];
			$schname=stripslashes($schname);
			$sname=$row['sname'];
			$stype=$row['level'];
			$level=$row['clevel'];
			$schlevel=$row['lvl'];
            mysql_free_result($res);	  
	}
	else{
		$level="Tahap";
		$schlevel=0;
	}
	$clslevel="0";
	if($clscode!=""){
			$sql="select * from ses_sub where sch_id=$sid and usr_uid='$gid' and sub_code='$subcode' and cls_code='$clscode'";
            $res=mysql_query($sql)or die("$sql failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clslevel=$row['cls_level'];
			$clsname=stripslashes($row['cls_name']);
	}
		
	if($subcode!=""){
			$sql="select * from sub where code='$subcode' and level='$clslevel' and sch_id=$sid";
            $res=mysql_query($sql)or die("$sql failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $grptype=$row['grptype'];
			$subname=stripslashes($row['name']);
			$gradingset=$row['grading'];
			
			$sql="select type from grading where name='$gradingset'";
            $res=mysql_query($sql)or die("$sql failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $gradingtype=$row['type'];			  
		}
		

//echo "$gradingset";
/** sorting control **/
	$order=$_POST['order'];
	if($order=="")
		$order="asc";
		
	if($order=="desc")
		$nextdirection="asc";
	else
		$nextdirection="desc";
		
	$sort=$_POST['sort'];
	if($sort==""){
		//$sqlsort="order by sex desc, stu.name";
		$sqlsort="order by stu.name";
}else{
		//$sqlsort="order by $sort $order, name asc";
		$sqlsort="order by $sort $order";

}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<SCRIPT LANGUAGE="JavaScript">
function check_grade(e,idx){
	var str=e.value
	var arr=str.split("|")
	p=parseInt(arr[0]);
	c=arr[1];		
	ele="g"+idx;
	document.myform.elements[ele].value=c;
}


function process_myform(){
	if(document.myform.exam.value==""){
    	alert("Please select exam..");
        document.myform.exam.focus();
        return;
	}
	ret = confirm("Save the record ??");
    if (ret == true){
    	document.myform.p.value='examsave';
		document.myform.action="../exam/examsave.php";
		document.myform.target="";
        document.myform.submit();
    }
}

function myform_clear_print(){
	document.myform.action="";
	document.myform.target="";
}

function process_edit(exam){
	document.myform.exam.value=exam;
	document.myform.submit();
}

function process_mark(idx)
{
	var cw = document.getElementById("markah"+idx).value;
	var max = document.getElementById("max"+idx).value;
	//alert(max);
	xcw=cw;
	part_mark1=max;

	if(xcw.length<1){
		xcw=0;
	}
	xcw=parseFloat(xcw);
	part_mark1=parseFloat(part_mark1);
	
	if(xcw>part_mark1){
			alert('Exceeded Value : '+xcw+' , max='+part_mark1);
			document.getElementById("markah"+idx).value='';
			return false;
	}
	
	jum=xcw+xms+xfs;
	if(jum%1!=0){
	jum=jum.toFixed(1);
	}
	//}
	document.getElementById("markah"+idx).value=jum;
}

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo strtoupper($lg_exam);?> : <?php echo strtoupper($subname);?></title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>

<body>


<form name="myform" method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<input name="year" type="hidden" value="<?php echo $year;?>">
	<input name="sid" type="hidden" value="<?php echo $sid;?>">
	<input name="clscode" type="hidden" value="<?php echo $clscode;?>">
	<input name="subcode" type="hidden" value="<?php echo $subcode;?>">
	<input name="usr_uid" type="hidden" value="<?php echo $gid;?>">
	<input name="exam" type="hidden" value="<?php echo $exam;?>">
	<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
	<input name="order" type="hidden" id="order" value="<?php echo $order;?>">
	


<div id="content">
<div id="mypanel">
	<div id="mymenu" align="center">
		<a href="<?php echo "../examrep/rep_exam_subject_one.php?exam=$exam&year=$year&sid=$sid&clscode=$clscode&subcode=$subcode";?>" id="mymenuitem"><img src="../img/graphbar.png"><br><?php echo $lg_report;?></a>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
            <div id="mymenu_seperator"></div>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="process_myform('')" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/save.png"><br><?php echo $lg_save;?></a>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
            <div id="mymenu_seperator"></div>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
        <a href="#" onClick="showhide('divupload','')" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/goup.png"><br>UPLOAD</a>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
            <div id="mymenu_seperator"></div>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
        <a href="<?php echo "../exam/excel_download.php?exam=$exam&year=$year&sid=$sid&clscode=$clscode&subcode=$subcode";?>" id="mymenuitem">
        <img src="<?php echo $MYLIB;?>/img/down22.png"><br>DOWNLOAD</a>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
            <div id="mymenu_seperator"></div>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="window.print()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/printer.png"><br><?php echo $lg_print;?></a>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
            <div id="mymenu_seperator"></div>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="document.myform.exam.value='';document.myform.submit();" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/reload.png"><br><?php echo $lg_refresh;?></a>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
            <div id="mymenu_seperator"></div>
            <div id="mymenu_space">&nbsp;&nbsp;</div>
		<a href="#" onClick="window.close()" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/close.png"><br><?php echo $lg_close;?></a>
	</div>
	<div align=right>
	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br>
<br>

	<input type="checkbox" name="showheader" value="1"  onClick="showhide('showheader','')" <?php if($showheader) echo "checked";?>>Show Header 
	 
	</div>
</div>
<div id="story">
<div id="divupload" style="display:none;" class="mytitle">
<div id="mytitlebg">UPLOAD FAIL NILAI SISWA </div>
<br>
<br>

PILIH FILE <input type="file" name="file1"><input type="button" value="UPLOAD" onClick="document.myform.submit();">
<br>
<br>

</div>


<div id="showheader" <?php if(!$showheader) echo "style=\"display:none\"";?> >
	<?php  include('../inc/school_header.php')?>
</div>
	<div id="mytitlebg"><?php echo strtoupper($lg_exam);?></div>
                         
<table width="100%" id="mytitle">
  <tr>
    <td width="50%">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="20%"><?php echo strtoupper($lg_school);?></td>
			<td width="1%">:</td>
			<td><?php echo strtoupper($schname);?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_class);?></td>
			<td>:</td>
			<td><?php echo strtoupper("$clsname");?></td>
		  </tr>
          <tr>
			<td><?php echo strtoupper($lg_year);?></td>
			<td>:</td>
			<td><?php echo strtoupper("$year");?></td>
		  </tr>
		</table>
	</td>
    <td width="50%">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td><?php echo strtoupper($lg_subject);?></td>
			<td>:</td>
			<td><?php echo strtoupper($subname);?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_teacher);?></td>
			<td>:</td>
			<td><?php echo strtoupper($gname);?></td>
		  </tr>
		</table>
	
	</td>
  </tr>
</table>

<table width="100%" cellspacing="0">
<tr>
              <td id="mytabletitle" width="3%" align="center" rowspan="2" ><?php echo strtoupper($lg_no);?></td>
              <td id="mytabletitle" width="5%" align="center" rowspan="2"><a href="#" onClick="myform_clear_print();formsort('stu.uid','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_matric);?></a></td>
			  <td id="mytabletitle" width="2%" align="center" rowspan="2"><a href="#" onClick="myform_clear_print();formsort('stu.sex','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_mf);?></a></td>	
			  <td id="mytabletitle" width="20%" rowspan="2"><a href="#" onClick="myform_clear_print();formsort('stu.name','<?php echo "$nextdirection";?>')" title="Sort"><?php echo strtoupper($lg_name);?></a></strong></td>
<?php
$sql="select * from type where grp='exam' and (lvl=0 or lvl='$clslevel') and (sid=0 or sid=$sid) order by idx,id";
$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
$bilexam=mysql_num_rows($res);
while($row=mysql_fetch_assoc($res)){
	$a=$row['prm'];
	$b=$row['code'];
	
	$locking=0;
	$sql="select * from examlock where year='$year' and (sid=0 or sid=$sid) and (lvl='0' or lvl='$clslevel') and exam='$b'";
	$res2=mysql_query($sql)or die("$sql query failed:".mysql_error());
	$row2=mysql_fetch_assoc($res2);
	$offdate=$row2['off'];
	$nowdate=date('Y-m-d');
	
	if(($offdate<$nowdate)&&($offdate!=""))
			$locking=1;

	//hard code for attamimi - make sure no scool use F1 n F2 exam code
	if(($b=="F1")||($b=="F2"))
			$locking=1;
?>
		<td id="mytabletitle" width="10%" colspan="2" align="center">
			<?php if($locking){?>
				<img src="../img/lock8.png">&nbsp;<?php echo strtoupper($a);?>
			<?php } else if($subcode!=""){?>
				<a href="#" onClick="process_edit('<?php echo $b;?>')" title="<?php echo strtoupper($a);?>"><img src="../img/edit12.png">&nbsp;<?php echo strtoupper($a);?></a>
			<?php } else { 
				$locking=1;?>
				<img src="../img/lock8.png">&nbsp;<?php echo strtoupper($a);?>
			<?php } ?>
		</td>
<?php } ?>
</tr>
</tr>
<?php for($i=0;$i<$bilexam;$i++){ ?>
			  <td id="mytabletitle" width="5%" align="center"><?php echo strtoupper($lg_grade);?></td>
              <td id="mytabletitle" width="5%" align="center"><?php echo strtoupper($lg_mark);?></td>
<?php } ?>
</tr>

<?php
		$q=0;
		$sql="select ses_stu.*,stu.sex,stu.name,stu.status from ses_stu INNER JOIN stu ON ses_stu.stu_uid=stu.uid where stu.sch_id=$sid and ses_stu.cls_code='$clscode' and ses_stu.year='$year' $sqlstatuspelajar $sqlsort";
		$res=mysql_query($sql)or die("$sql failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$stuname=strtoupper(stripslashes($row['name']));
			$stuuid=$row['stu_uid'];
			$sex=$row['sex'];
			$status=$row['status'];
		
			$q++;
			if($status==6)
				$bg="$bglyellow";
			else
				$bg="#FAFAFA";
?>
		<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';">
    		<td id=myborder align=center><?php echo "$q";?></td>
			<td id=myborder align=center><?php echo "$stuuid";?></td>
			<td id=myborder align=center><?php echo $lg_sexmf[$sex];?></td>
			<td id=myborder><?php echo "$stuname";?></td>
<?php
			$sql="select * from type where grp='exam' and (lvl=0 or lvl='$clslevel') and (sid=0 or sid=$sid) order by idx,id";
			$res3=mysql_query($sql)or die("$sql query failed:".mysql_error());
			while($row3=mysql_fetch_assoc($res3)){
				$a=$row3['prm'];
				$b=$row3['code'];
				
				$locking=0;
				$sql="select * from examlock where year='$year' and (sid=0 or sid=$sid) and (lvl='0' or lvl='$clslevel') and exam='$b'";
				$res2=mysql_query($sql)or die("$sql query failed:".mysql_error());
				$row2=mysql_fetch_assoc($res2);
				$offdate=$row2['off'];
				$nowdate=date('Y-m-d');
				
				if(($offdate<$nowdate)&&($offdate!=""))
						$locking=1;
				
				//hard code for attamimi - make sure no scool use F1 n F2 exam code
				if(($b=="F1")||($b=="F2"))
						$locking=1;
			
				/** check if existing **/
				$sql="select point,grade from exam where sch_id=$sid and year='$year' and cls_code='$clscode' and sub_code='$subcode' and stu_uid='$stuuid' and examtype='$b'";
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
				if(($exam==$b)&&($locking!=1)){
					include ('../exam/examreg_edit.php');
				}
				else{
					echo "<td id=myborder align=center>$gg</td>";
					echo "<td id=myborder align=center>$pp</td>";
				}
			}
	 		echo "</tr>";
		}
		
?>
<tr>
	<td id=myborder></td>
	<td id=myborder></td>
	<td id=myborder></td>
	<td id=myborder></td>
		<?php 
		$sql="select * from type where grp='exam' and (lvl=0 or lvl='$clslevel') and (sid=0 or sid=$sid) order by idx,id";
		$res=mysql_query($sql)or die("$sql query failed:".mysql_error());
		$bilexam=mysql_num_rows($res);
		while($row=mysql_fetch_assoc($res)){
			$a=$row['prm'];
			$b=$row['code'];
		 ?>
		<td id="myborder" colspan=2 align=center>
			<?php if($exam==$b){?>  <input type="button" name="Submit2" value="<?php echo "$lg_update $lg_mark";?>" onClick="return process_myform('')" <?php if($exam=="") echo "disabled";?>> <?php } ?>
		</td>
	<?php } ?>
</tr>

</table>

</div> <!-- story -->
</div> <!-- content -->

</form>
<form name="formwindow" method="post" action="rep_exam_subject_one.php" target="newwindow">
	<input name="curr" type="hidden" id="curr" value="<?php echo $curr;?>">
	<input name="sort" type="hidden" id="sort" value="<?php echo $sort;?>">
	<input name="order" type="hidden" id="order" value="<?php echo $order;?>">
	<input type="hidden" name="p" value="rep_exam_subject">
	<input name="exam" type="hidden" id="exam" value="<?php echo $exam;?>">
	<input name="sid" type="hidden" id="sid" value="<?php echo $sid;?>">
	<input name="clscode" type="hidden" id="clscode" value="<?php echo $clscode;?>">
	<input name="subcode" type="hidden" id="subcode" value="<?php echo $subcode;?>">
	<input name="year" type="hidden" id="year" value="<?php echo $year;?>">
	<input name="isprint" type="hidden" id="isprint" value="1">
</form>
</body>
</html>
