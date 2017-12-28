<?php
//110525 - use credit hr
//110619 - patch TT
//110622 - allow add comment ini $EXAMSLIP_TEACHER_COMMENT=1;
//110626 - tak kira peratus jika point not value
$vmod="v6.0.2";
$vdate="110622";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');
$username=$_SESSION['username'];


	$sortranking=$_REQUEST['sortranking'];//sortranking flag
	$op=$_REQUEST['op'];
	$smt=$_REQUEST['smt'];
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
	$slvl=0;
	if($sid!=0){
		$sql="select * from sch where id=$sid";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=stripslashes($row['name']);
		$ssname=stripslashes($row['sname']);
		$slevel=$row['level'];
		$slvl=$row['lvl'];
		$simg=$row['img'];
		$addr=$row['addr'];
		$state=$row['state'];
		$tel=$row['tel'];
		$fax=$row['fax'];
		$web=$row['url'];
		$school_img=$row['img'];
		            $issemester=$row['issemester'];	
			$startsemester=$row['startsemester'];
     	mysql_free_result($res);					  
	}
			
	$year=$_POST['year'];
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
		
	$curryear=date('Y');
	if($curryear==$year)
		$sqlstatuspelajar="and stu.status=6";
		
	$lvl=$_REQUEST['lvl'];
	if($clscode!=""){
			$sql="select * from ses_cls where sch_id=$sid and cls_code='$clscode' and year='$year'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=stripslashes($row['cls_name']);
			$clslevel=$row['cls_level'];
			
			$sql="select * from type where sid='$sid' and prm='$clslevel' and grp='classlevel'";
    		$res=mysql_query($sql)or die("query failed:".mysql_error());
        	$row=mysql_fetch_assoc($res);
			$grading=$row['code'];
	}

		
	$sql="select * from ses_cls where year='$year' and cls_code='$clscode' and sch_id=$sid ";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$row2=mysql_fetch_assoc($res2);
	$gurukelas=stripslashes($row2['usr_name']);
		
	$exam=$_POST['exam'];
	if($exam==""){
			$sql="select * from type where grp='ujian' and (lvl=0 or lvl='$clslevel') and (sid=0 or sid=$sid) order by idx";
        	$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $examname=$row['prm'];
			$exam=$row['code'];
	}else{
			$sql="select * from type where grp='ujian' and code='$exam' and (lvl=0 or lvl='$clslevel') and (sid=0 or sid=$sid)";
        	$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $examname=$row['prm'];
	}

	$subgrouptype=$_POST['subgrouptype'];
	if($subgrouptype=="")
		$subgrouptype="0";
	if($subgrouptype=="0"){
		$sqlgrouptype='and sub_grptype=0';
		$subject_type=0;
	}
	if($subgrouptype=="1"){
		$sqlgrouptype='and sub_grptype=1';
		$subject_type=1;
	}
	if($subgrouptype=="2"){
		$sqlgrouptype='';
		$subject_type=0;
	}
		
	$cons=$_POST['cons'];
	if($cons!=""){
		$sqlcons="and sub_grp='$cons'";
		$sqlgrouptype='';$subgrouptype="";$subject_type="";
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
	if($sort==""){
		$sqlsort="order by sex desc,name";
	}
	else{
		$sqlsort="order by $sort";
	}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SPS</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript">
function processform(operation){
		if(document.myform.exam.value==""){
			alert("Please select exam");
			document.myform.exam.focus();
			return;
		}
		if(document.myform.sid.value==""){
			alert("Please select school");
			document.myform.sid.focus();
			return;
		}
		document.myform.submit();
}
function process_sort(fsort,forder){
		if(document.myform.sid.value=="0"){
			alert("Please select school");
			document.myform.sid.focus();
			return;
		}
		if(document.myform.clscode.value==""){
			alert("Please select class");
			document.myform.clscode.focus();
			return;
		}
		if(document.myform.exam.value==""){
			alert("Please select exam");
			document.myform.exam.focus();
			return;
		}
		document.myform.sort.value=fsort;
		document.myform.order.value=forder;
		document.myform.submit();
}
function clear_newwin(){
	document.myform.action="";
	document.myform.target="";
}
var newwin = "";
function newwindowww(page) 
{ 
	var cflag=false;
	if(document.myform.sid.value=="0"){
			alert("Please select school");
			document.myform.sid.focus();
			return;
	}
	if(document.myform.clscode.value==""){
			alert("Please select class");
			document.myform.clscode.focus();
			return;
	}
	if(document.myform.exam.value==""){
			alert("Please select examination");
			document.myform.exam.focus();
			return;
	}
	for (var i=0;i<document.myform.elements.length;i++){
                var e=document.myform.elements[i];
                if ((e.id=='stuid')){
                        if(e.checked==true)
                               cflag=true;
    
                }
    }
	if(!cflag){
			alert('Please checked the item to show');
			return;
	}
		
	document.myform.action=page;
	document.myform.target="newwindow";
    newwin = window.open("","newwindow","HEIGHT=600,WIDTH=1000,scrollbars=yes,status=yes,resizable=yes,top=0,toolbar");
	var a = window.setTimeout("document.myform.submit();",500);
    newwin.focus();
}
</script>
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
</head>
<body>

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="<?php echo $p;?>">
	<input name="curr" type="hidden">
	<input name="op" type="hidden">
	<input name="sortranking" type="hidden" value="<?php echo $sortranking;?>">
	<input name="sort" type="hidden" value="<?php echo $sort;?>">
	<input name="order" type="hidden" value="<?php echo $order;?>">
	<input name="lvl" type="hidden" value="<?php echo $clslevel;?>">
	
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br><?php echo $lg_print;?></a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="clear_newwin();document.myform.sortranking.value=0;document.myform.sort.value='';document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br><?php echo $lg_refresh;?></a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="newwindowww('../tk/exam_stu_slip.php')" id="mymenuitem"><img src="../img/letters.png"><br><?php echo $lg_slip;?></a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
    
</div> <!-- end mymenu -->

<div id="viewpanel" align="right">
	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
<?php if(is_verify("ADMIN|AKADEMIK|CEO")){?>
	  <select name="sid" id="sid" onchange="clear_newwin();document.myform.clscode[0].value='';document.myform.exam[0].value='';document.myform.year[0].value='';document.myform.submit();">
        <?php	
      		if($sid=="0")
            	echo "<option value=\"0\">- $lg_school -</option>";
			else
                echo "<option value=$sid>$ssname</option>";
				
			if($_SESSION['sid']==0){
				$sql="select * from sch where id!='$sid' order by name";
				$res=mysql_query($sql)or die("query failed:".mysql_error());
				while($row=mysql_fetch_assoc($res)){
							$s=stripslashes($row['sname']);
							$t=$row['id'];
							echo "<option value=$t>$s</option>";
				}
				mysql_free_result($res);
			}							  
			
?>
	</select>
	<select name="year" id="year" onChange="clear_newwin();document.myform.submit();">
 <?php
            echo "<option value=$year>$lg_year $sesyear</option>";
			$sql="select * from type where grp='session' and prm!='$year' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
						echo "<option value=\"$s\">$lg_year $s</option>";
			}
?>  </select>
        <select name="smt" id="smt" onchange="clear_newwin();document.myform.submit();">
		<option value="">Pilih Semester</option>
		<option value="1" <?php if($smt=='1') { echo "selected"; } ?>>Semester 1</option>
		<option value="2" <?php if($smt=='2') { echo "selected"; } ?>>Semester 2</option>
	</select>
      <select name="exam" id="exam" onchange="clear_newwin();document.myform.submit();">
        <option value="">Pilih Ujian</option>
	<?php	
      		$sql="select * from type where grp='ujian'";
            	$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           	while($row=mysql_fetch_assoc($res)){
			$a=$row['prm'];
			$b=$row['code'];
			if($exam==$b){
				$select="selected";
			} else {
				$select="";
			}
			echo "<option value='$b' $select>$a</option>";
		}
	?>
      </select>
	  <?php } else {?>
			  	<input type="hidden" name="year" value="<?php echo $year;?>">
				<input type="hidden" name="clscode" value="<?php echo $lvl;?>">
				<input type="hidden" name="sid" value="<?php echo $sid;?>">
                                <input type="hidden" name="exam" value="<?php echo $exam;?>">
                                <input type="hidden" name="smt" value="<?php echo $smt;?>">
	  <?php } ?>
	
      
      
     

      <input type="button" name="Submit" value="View" onClick="clear_newwin();processform()" >
	  <br>
	 

</div><!-- end viewpanel -->
</div><!-- end mypanel -->

<div id="story">

<div id="mytitlebg">NILAI RAPORT LEGGER - <?php echo strtoupper($sname);?></div>
<table width="100%" id="mytitlebg">
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
			<td><?php echo strtoupper($lg_smt);?></td>
			<td>:</td>
			<td><?php echo strtoupper($smt);?></td>
		  </tr>
		</table>
	
	</td>
  </tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
	<td id="mytabletitle" width="15%" ><a href="#" onClick="document.myform.sortranking.value=0;formsort('stu_name <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper("kelas");?></a></td>
	<td id="mytabletitle" width="5%" align="center">PILAR TAQWA</td>
        <td id="mytabletitle" width="5%" align="center">PILAR ILMU</td>
        <td id="mytabletitle" width="5%" align="center">PILAR KEPEMIMPINAN</td>
  </tr>
<?php
$sql="select distinct(cls_level) from ses_cls where sch_id=$sid and year='$year'";
$res=mysql_query($sql)or die("query failed:".mysql_error());
while($row=mysql_fetch_assoc($res)){
    $lvl=$row['cls_level'];
    ?>
    <tr>
    <td id="myborder">Kelas <?php echo $lvl ?></td>
    <td id="myborder" align="center">
        <?php
                        $sql="select * from sub_construct where exam='$exam' and sid=$sid and year='$year' and grp='taqwa' and lvl='$lvl'";
                        $res3=mysql_query($sql)or die("$sql failed:".mysql_error());
                        $row3=mysql_num_rows($res3);
                        if($row3>0) {
                                $img="../img/check12.png";
                        }
                        else {
                                $img="../img/edit12.png";
                        }
                ?>
                <a href="../sub/sub_taqwa.php?<?php echo "lvl=$lvl&year=$year&exam=$exam&sid=$sid&grp=taqwa&smt=$smt";?>" id="pos_bottom<?php echo $q;?>" target="_blank"
                       <div id="checkimg<?php echo $q;?>"><img src="<?php echo $img;?>"></div>
                </a>
    </td>
    <td id="myborder" align="center">
        <?php
                        $sql="select * from exam_stu_summary where uid='$uid' and exam='$exam' and sid=$sid and year='$year' and grp='internal'";
                        $res3=mysql_query($sql)or die("$sql failed:".mysql_error());
                        $row3=mysql_fetch_assoc($res3);
                        $mm=$row3['msg'];
                        if($mm!='')
                                $img="../img/check12.png";
                        else
                                $img="../img/edit12.png";
                ?>
                <a href="../examrep/repexamcomment.php?<?php echo "clscode=$clscode&year=$year&exam=$exam&sid=$sid&grp=internal&smt=$smt";?>" id="pos_bottom<?php echo $q;?>" target="_blank"
                       <div id="checkimg<?php echo $q;?>"><img src="<?php echo $img;?>"></div>
                </a>
    </td>
    <td id="myborder" align="center">
    <?php
                        $sql="select * from exam_stu_summary where uid='$uid' and exam='$exam' and sid=$sid and year='$year' and grp='internal'";
                        $res3=mysql_query($sql)or die("$sql failed:".mysql_error());
                        $row3=mysql_fetch_assoc($res3);
                        $mm=$row3['msg'];
                        if($mm!='')
                                $img="../img/check12.png";
                        else
                                $img="../img/edit12.png";
                ?>
                <a href="../examrep/repexamcomment.php?<?php echo "clscode=$clscode&year=$year&exam=$exam&sid=$sid&grp=internal&smt=$smt";?>" id="pos_bottom<?php echo $q;?>" target="_blank"
                       <div id="checkimg<?php echo $q;?>"><img src="<?php echo $img;?>"></div>
                </a>
    </td>
    </tr>
<?php } ?>
</table>
</div></div>
</form>

</body>
</html>

<!--
v2.7
27/11/08: Gui
v2.6
15/11/08: fixed percent culculation
13/11/08: update interface
Author: razali212@yahoo.com
-->