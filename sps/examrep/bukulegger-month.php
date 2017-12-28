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
		$year="$year/$xx";	
	
}else{
		$year="$year";
}
		
	$curryear=date('Y');
	if($curryear==$year)
		$sqlstatuspelajar="and stu.status=6";
		
	$clscode=$_REQUEST['clscode'];
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
			$sql="select * from type where grp='exam' and (lvl=0 or lvl='$clslevel') and (sid=0 or sid=$sid) order by idx";
        	$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $examname=$row['prm'];
			$exam=$row['code'];
	}else{
			$sql="select * from type where grp='exam' and code='$exam' and (lvl=0 or lvl='$clslevel') and (sid=0 or sid=$sid)";
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
	if($sort=="")
		$sqlsort="order by sex desc,name";
	else
		$sqlsort="order by $sort";
		

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
	
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="clear_newwin();document.myform.sortranking.value=0;document.myform.sort.value='';document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="newwindowww('../exam/slip_exam.php')" id="mymenuitem"><img src="../img/letters.png"><br>Print&nbsp;Slip</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>

<a href="#" onClick="showhide('tipsdiv')" id="mymenuitem"><img src="../img/help22.png"><br>HowTo</a>
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
            echo "<option value=$year>$lg_year $year</option>";
			$sql="select * from type where grp='session' and prm!='$year' order by val desc";
            $res=mysql_query($sql)or die("query failed:".mysql_error());
            while($row=mysql_fetch_assoc($res)){
                        $s=$row['prm'];
						$v=$row['val'];
						echo "<option value=\"$s\">$lg_year $s</option>";
			}				  
?>
      </select>
	  <select name="clscode" id="clscode" onchange="clear_newwin();document.myform.submit();">
        <?php	
      				if($clscode=="")
						echo "<option value=\"\">- $lg_class -</option>";
					else
						echo "<option value=\"$clscode\">$clsname</option>";
				
					$sql="select * from ses_cls where sch_id=$sid and cls_code!='$clscode' and year='$year' order by cls_level";
            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=stripslashes($row['cls_name']);
						$a=$row['cls_code'];
                        echo "<option value=\"$a\">$b</option>";
            		}
            		
			?>
      </select>
	  <?php } else {?>
			  	<input type="hidden" name="year" value="<?php echo $year;?>">
				<input type="hidden" name="clscode" value="<?php echo $clscode;?>">
				<input type="hidden" name="sid" value="<?php echo $sid;?>">
	  <?php } ?>
      <select name="exam" id="exam" onchange="clear_newwin();document.myform.submit();">
        <?php	
      				if($exam=="")
						echo "<option value=\"\">- $lg_exam -</option>";
					else
						echo "<option value=\"$exam\">$examname</option>";
					$sql="select * from type where grp='exam' and code!='$exam' and (lvl=0 or lvl='$clslevel') and (sid=0 or sid=$sid) order by idx";
            		$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $a=$row['prm'];
						$b=$row['code'];
                        echo "<option value=\"$b\">$a</option>";
            		}
			?>
      </select>
      
      
      <select name="cons" id="cons" onchange="clear_newwin();document.myform.submit();">
        <?php	
      				if($cons=="")
						echo "<option value=\"\">- $lg_all $lg_group $lg_subject -</option>";
					else
						echo "<option value=\"$cons\">$cons</option>";
					
					$sql="select * from type where grp='subtype' and prm!='$cons' and (sid=0 or sid=$sid) and (lvl=0 or lvl=$clslevel) order by idx";
            		$res=mysql_query($sql)or die("$sql-query failed:".mysql_error());
           		 	while($row=mysql_fetch_assoc($res)){
                        $b=$row['prm'];
                        echo "<option value=\"$b\">$b</option>";
            		}
            		if($cons!="")
						echo "<option value=\"\">- $lg_all -</option>";

			?>
      </select>

      <input type="button" name="Submit" value="View" onClick="clear_newwin();processform()" >
	  <br>
	  <?php if($VARIANT=="MUSLEH"){?>
		  <label style="cursor:pointer;"><input type="radio" name="subgrouptype" onClick="document.myform.cons[0].value='';document.myform.submit();" value="0" <?php if($subgrouptype=="0") echo "checked";?>>Subjek Ilmu</label>&nbsp;&nbsp;
		  <label style="cursor:pointer;"><input type="radio" name="subgrouptype" onClick="document.myform.cons[0].value='';document.myform.submit();" value="1" <?php if($subgrouptype=="1") echo "checked";?>>Bukan Ilmu</label>&nbsp;&nbsp;
		  <label style="cursor:pointer;"><input type="radio" name="subgrouptype" onClick="document.myform.cons[0].value='';document.myform.submit();" value="2" <?php if($subgrouptype=="2") echo "checked";?>>Semua</label>&nbsp;&nbsp;
	  <?php } ?>
	  <?php if($VARIANT=="MDQ"){?>
	  	<a href="#" onClick="newwindow('../examrep/repexamstucls_final.php?sid=<?php echo "$sid";?>&clscode=<?php echo "$clscode";?>&year=<?php echo "$year";?>',0)">(SHOW FINAL REPORT)</a>
	  <?php } ?>


</div><!-- end viewpanel -->
</div><!-- end mypanel -->

<div id="story">
<div id="tipsdiv" style="display:none ">
Tips:<br>
1. Klik pada nama pelajar untuk melihat slip keputusan peperiksaan pelajar.<br>
2. Untuk print slip keputusan beramai-ramai, tick kan pada senarai nama, dan klik butang Slip, dan pada window baru klik butang Print<br>
3. Klik pada Kod Subjek untuk melihat laporan subjek atau untuk memasukkan/edit markah. Untuk edit markah apabila window baru dibuka, klik edit<br>
</div>
<div id="mytitle2" align="center">LEGGER NILAI RAPOT</div>
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
			<td><?php echo strtoupper($lg_class_teacher);?></td>
			<td>:</td>
			<td><?php echo strtoupper($gurukelas);?></td>
		  </tr>
		</table>
	
	</td>
  </tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td id="mytabletitle" width="1%" align="center" class="printhidden"><input type=checkbox name=checkall value="0" onClick="check(1)"></td>
    <td id="mytabletitle" width="1%" align="center"><?php echo strtoupper($lg_no);?></td>
	<td id="mytabletitle" width="2%" align="center"><a href="#" onClick="document.myform.sortranking.value=0;formsort('uid <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_matric);?></a></td>
    <td id="mytabletitle" width="1%" align="center"><a href="#" onClick="document.myform.sortranking.value=0;formsort('sex <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_mf);?></a></td>
	<td id="mytabletitle" width="15%" ><a href="#" onClick="document.myform.sortranking.value=0;formsort('stu_name <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_name);?></a></td>
<?php if($EXAMSLIP_TEACHER_COMMENT){?>
	<td id="mytabletitle" width="2%" align="center">COMMENT</td>
<?php }?>
<?php 
	$sql="select * from ses_sub where year='$year' and sch_id=$sid and cls_code='$clscode' $sqlcons $sqlgrouptype order by sub_grp,sub_code";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$total_all_sub=mysql_num_rows($res2);
	while($row2=mysql_fetch_assoc($res2)){
		$p=$row2['sub_code'];
		$sql="select * from sub where code='$p' and sch_id=$sid";
		$res3=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$row3=mysql_fetch_assoc($res3);
		$shortname=$row3['sname'];
		$subname=$row3['name'];
		$xmark=$row3['mark'];
		if($xmark>0)
			$tanda="*";
		else
			$tanda="";
		
?>
		<td id="mytabletitle" width="2%" colspan="2" align="center">
		<a href="<?php echo "../examrep/rep_exam_subject_one.php?exam=$exam&year=$year&sid=$sid&clscode=$clscode&subcode=$p&isprint=1";?>" title="<?php echo $subname;?>" style="text-decoration:none" target="_blank">
		<?php echo "$tanda$p";?><br><div style="font-weight:normal"><?php echo "$shorname";?></div></a>
		</td>
<?php } ?>
<!--
	<td id="mytabletitle" width="2%" align="center"><?php echo strtoupper($lg_subject);?></td>
	<td id="mytabletitle" width="2%" align="center"><?php echo strtoupper($lg_grade);?></td>
-->    
	<td id="mytabletitle" width="2%" align="center">
    	<a href="#" onClick="clear_newwin();document.myform.sortranking.value=1;
        process_sort('examrank.total_point <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="sort">
		Nilai</a></td>
<?php if($subject_type!="1"){?>
	<td id="mytabletitle" width="2%" align="center">
    	<a href="#" onClick="clear_newwin();document.myform.sortranking.value=1;
        process_sort('examrank.avg <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="sort">
        Nilai<br>Rata2</a></td>

<?php }?>

	

  </tr>
<?php 
if($clscode!=""){
	$q=0;

				
	$sql="select count(*) from stu,examrank where stu.sch_id=$sid and stu.uid=examrank.stu_uid  and (stu.status=6 or stu.status=3 ) and examrank.year='$year' and examrank.cls_code='$clscode' and examrank.exam='$exam'";
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
  	$row=mysql_fetch_row($res);
	$foundrank=$row[0];

	if(($sortranking==1)&&($foundrank>0)){
		$sql="select stu.sex,stu.name,stu.uid from stu,ses_stu,examrank where stu.sch_id=$sid and stu.uid=ses_stu.stu_uid and stu.uid=examrank.stu_uid  and (stu.status=6 or stu.status=3 ) and ses_stu.year='$year' and examrank.year='$year' and ses_stu.cls_code='$clscode' and examrank.exam='$exam' $sqlsort";
	}else{//tak boleh sort rank sbb empty table rank
		$sql="select stu.sex,stu.name,stu.uid from stu INNER JOIN ses_stu  ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid and (stu.status=6 or stu.status=3) and year='$year' and ses_stu.cls_code='$clscode' $sqlsort";
	}
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$name=strtoupper(stripslashes($row['name']));
		$sex=$row['sex'];
		if($q++%2==0)
			$bg="#FAFAFA";
		else
			$bg="";
			

?>
  <tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';" >
  	<td id="myborder" align="center" class="printhidden"><input type=checkbox name=stu[] id="stuid" value="<?php echo "$uid";?>" onClick="check(0)"></td>
	<td id="myborder" align="center"><?php echo $q?></td>
	<td id="myborder" align="center"><?php echo $uid?></td>
	<td id="myborder" align="center"><?php echo $lg_sexmf[$sex];?></td>
	<td id="myborder" ><?php echo "<a href=\"#\" title=\"Report Card\" onClick=\"newwindow('../exam/slip_exam.php?uid=$uid&year=$year&exam=$exam&sid=$sid',0)\">$name</a>";?></td>
<?php if($EXAMSLIP_TEACHER_COMMENT){?>
	 <td id="myborder" align="center">
                <?php
                        $sql="select * from exam_stu_summary where uid='$uid' and exam='$exam' and sid=$sid and year='$year'";
                        $res3=mysql_query($sql)or die("$sql failed:".mysql_error());
                        $row3=mysql_fetch_assoc($res3);
                        $mm=$row3['msg'];
                        if($mm!='')
                                $img="../img/check12.png";
                        else
                                $img="../img/edit12.png";
                ?>
                <a href="../examrep/repexamcomment.php?<?php echo "clscode=$clscode&year=$year&exam=$exam&sid=$sid";?>" id="pos_bottom<?php echo $q;?>" target="_blank"
                       <div id="checkimg<?php echo $q;?>"><img src="<?php echo $img;?>"></div>
                </a>
        </td>
	<?php
}
				$sql="select * from grading where name='$grading' order by val desc";
				$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
				$i=1;
				while($row3=mysql_fetch_assoc($res3)){
					$xg=$row3['grade'];
					$xgp=$row3['gp'];
					$arr_grade[$xg]=0;
					$arr_gpoint[$xg]=$xgp;
				}

				$jum_markah=0;
				$jum_subjek=0;
				$jum_gp=0;
				$jum_credit=0;
				$gpp=0;
				$jum_subskor=0;
				$jumskor=0;
				
				$sql="select * from ses_sub where year='$year' and sch_id=$sid and cls_code='$clscode' $sqlcons  $sqlgrouptype order by sub_grp,sub_code";
				$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				while($row2=mysql_fetch_assoc($res2)){
					$p=$row2['sub_code'];
					$n=$row2['sub_name'];
					$subtype=$row2['sub_grptype'];
					
					$sql4="select * from exam where stu_uid='$uid' and sub_code='$p' and cls_code='$clscode' and year='$year' and examtype='$exam'";
					$res4=mysql_query($sql4)or die("query failed:".mysql_error());
					$row4=mysql_fetch_assoc($res4);
					$point=$row4['point'];
					$gred=$row4['grade'];
					$credit=$row4['credit'];
					$gp=$row4['gp'];
					$xgrading=$row4['grading'];
					$xgradingtype=$row4['gradingtype'];
					if($gred!="TT"){
							if($credit>0){
									if(is_numeric($point)){
											$jum_markah=$jum_markah+$point;
											$jum_subjek++;
											$markah_purata=$jum_markah/$jum_subjek;
									}
									if($gp>=0){
											$jum_credit=$jum_credit+$credit;
											$jum_gp=$gp*$credit+$jum_gp;
											$gpp=$jum_gp/$jum_credit;
									}
							}
					}
					if($arr_gpoint[$gred]>="0"){
							$arr_grade[$gred]++;
					}
					if($subtype=="1"){
							if(($gred!="")&&($gred!="TT")){
								$jum_subskor++;
								$jumskor=$jumskor+$gred;
							}
					}
if($xgradingtype==0){
?>
		<td id="myborder" align="center" style="border-right:none"><?php if($subtype==0){ if(($gred=="TT")||($gred=="")) echo "-"; else echo "$point";	}?>	</td>
		<td id="myborder" align="center" style="border-left:none"><!--<?php  if(($gred=="TT")||($gred=="")) echo "-"; else echo "$gred";?>--></td>
<?php } else {?>
		<td id="myborder" align="center" colspan="2" style="border-left:none"><?php  if(($gred=="TT")||($gred=="")) echo "-"; else echo "$gred";?></td>
<?php 	
	}//else 
}//habis loop subjeck

			$sql="select * from grading where name='$grading' and gp>=0 order by val desc";
			$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
			$sumgred="";
			while($row3=mysql_fetch_assoc($res3)){
				$xg=$row3['grade'];
				if($arr_grade[$xg]>0)
					$sumgred=$sumgred.$arr_grade[$xg]."$xg";
			}
?>
<!--
		<td id="myborder" align="center"><?php if($subtype=="0") echo "$jum_subjek/$total_all_sub"; else echo "$jum_subskor/$total_all_sub";?></td>
		<td id="myborder" align="center"><?php if($subtype=="0") echo $sumgred; else { if ($jum_subskor>0)printf("%.02f",$jumskor/$jum_subskor);}?></td>
-->		
        <td id="myborder" align="center"><?php echo $jum_markah;?></td>
		<td id="myborder" align="center"><?php printf("%.02f",$markah_purata);?></td>

  </tr>
<?php } }?>
</table>

<table width="100%" id="mytitle">
  <tr>
    <td align="center" width="33%"><?php echo "$lg_sekolah_tt1";?><br><br><br><br><br></td>
    <td align="center" width="33%"><?php echo "$lg_sekolah_tt2";?><br><br><br><br><br></td>
    <td align="center" width="33%"><?php echo "$lg_sekolah_tt3";?><br><br><br><br><br></td>
  </tr>
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