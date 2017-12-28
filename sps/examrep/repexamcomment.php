<?php
//29/03/10 4.1.0 - multi print
//04/06/10 - update nama pelajar base on current ses
//24/06/10 - update kira gpp
//02/02/11 - update skor
$vmod="v5.2.0";
$vdate="020211";
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify('ADMIN|AKADEMIK|KEWANGAN|GURU|HR|SOKONGAN');
$username=$_SESSION['username'];
$isprint=$_REQUEST['isprint'];


	$sort_ranking=$_REQUEST['sort_ranking'];
	$smt=$_REQUEST['smt'];
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
	$slvl=0;
	if($sid!=0){
		$sql="select * from sch where id=$sid";
        $res=mysql_query($sql)or die("query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=$row['name'];
		$ssname=$row['sname'];
		$slevel=$row['level'];
		$slvl=$row['lvl'];
		$simg=$row['img'];
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
			$sqlclscode="and ses_stu.cls_code='$clscode'";
			//$sqlclscode="and exam.cls_code='$clscode'";
			$sql="select * from ses_cls where sch_id=$sid and cls_code='$clscode'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
            $row=mysql_fetch_assoc($res);
            $clsname=stripslashes($row['cls_name']);
			$clslevel=$row['cls_level'];
			$sqlclslevel="and sub.level='$clslevel'";
			$sql="select * from type where sid='$sid' and prm='$clslevel' and grp='classlevel'";

    		$res=mysql_query($sql)or die("query failed:".mysql_error());
        	$row=mysql_fetch_assoc($res);
			$grading=$row['code'];

	}

	$year=$_REQUEST['year'];
	if($year=="")
		$year=date('Y');
		
	$curryear=date('Y');
	if($curryear==$year)
		$sqlstatuspelajar="and stu.status=6";
		
		$xx=$year+1;
		if(($sid==2)||($sid==3))
			$sesyear="$year";
		else
			$sesyear=$year;
			
	$sql="select * from ses_cls where year='$year' and cls_code='$clscode' and sch_id=$sid ";
	$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
	$row2=mysql_fetch_assoc($res2);
	$gurukelas=$row2['usr_name'];
		
	$exam=$_REQUEST['exam'];
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
		
	$op=$_REQUEST['op'];
	if($op=="save"){
		$amsg=$_REQUEST['msg'];
		$acm=$_REQUEST['cm'];
		$akg=$_REQUEST['kg'];
		$auid=$_REQUEST['uid'];
		$tatt=$_REQUEST['att'];
		$tday=$_REQUEST['day'];
		$tsakit=$_REQUEST['sakit'];
		$tizin=$_REQUEST['izin'];
		$talpa=$_REQUEST['alpa'];
		$smt=$_REQUEST['smt'];
		if($smt==""){
			if($exam=='PS1'||$exam=='UTSGA'){
				$sem="1";
			} else {
				$sem="2";
			}
		} else {
			$sem=$smt;
		}
		for ($i=0; $i<count($auid); $i++) {
			$uid=$auid[$i];
			$cm=$acm[$i];
			$kg=$akg[$i];
			$att=$tatt[$i];
			$day=$tday[$i];
			$sakit=$tsakit[$i];
			$izin=$tizin[$i];
			$alpa=$talpa[$i];
			$msg=addslashes($amsg[$i]);
			//echo $msg."<BR>";
			$sql="delete from exam_stu_summary where uid='$uid' and year='$year' and exam='$exam' and sid='$sid' and smt='$sem' and type is null";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			//$sql="insert into exam_stu_summary(uid,sid,msg,cls,year,exam,hg,wg,totalatt,totalday,ts,adm)values('$uid',$sid,'$msg','$cls','$year','$exam','$cm','$kg','$att','$day',now(),'$adm')";
			$sql="insert into exam_stu_summary(uid,sid,msg,cls,year,exam,ts,adm,sakit,izin,noreason,smt)values('$uid',$sid,'$msg','$clscode','$year','$exam',now(),'$adm','$sakit','$izin','$alpa','$sem')";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
		}
		$f="<font color=blue>&lt;SUCCESSFULY UPDATE&gt;</font>";
	}
	
	$cons=$_POST['cons'];
	if($cons!=""){
		$sqlcons="and sub_grp='$cons'";
		$sqlgrouptype='';$subgrouptype="";$subject_type="";
		
		$sql="select ses_sub.sub_code,sub.grading from ses_sub,sub where ses_sub.sub_code=sub.code and year='$year' and ses_sub.sch_id=$sid and cls_code='$clscode' $sqlclslevel $sqlcons";
		$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$total_all_sub=mysql_num_rows($res2);
		$row2=mysql_fetch_assoc($res2);
		$p=$row2['sub_code'];
		$grading=$row2['grading'];
	}
	$sql="select ses_sub.sub_code,sub.grading from ses_sub,sub where ses_sub.sub_code=sub.code and year='$year' and ses_sub.sch_id=$sid and cls_code='$clscode' $sqlclslevel $sqlcons";
		$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
		$total_all_sub=mysql_num_rows($res2);
		$row2=mysql_fetch_assoc($res2);
		$p=$row2['sub_code'];
		$grading=$row2['grading'];
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
<link rel="stylesheet" type="text/css" href="<?php echo $MYOBJ;?>/popwin/sample.css" />
<script type="text/javascript" src="<?php echo $MYOBJ;?>/popwin/popup-window.js"></script>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript">
function process_save(op){
	ret = confirm("Save the record ??");
    if (ret == true){
    	document.myform.op.value=op;
        document.myform.submit();
    }
}
function process_kira(op){
	ret = confirm("Calculate Absence Record??");
    if (ret == true){
    	document.myform.op.value=op;
        document.myform.submit();
    }
}
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
<script type="text/javascript" src="<?php echo $MYOBJ;?>/wz_tooltip531/wz_tooltip.js"></script>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<input type="hidden" name="p" value="../examrep/repexamstucls">
	<input name="curr" type="hidden">
	<input name="qq" type="hidden">
	<input name="op" type="hidden">
	<input name="sort" type="hidden" value="<?php echo $sort;?>">
	<input name="order" type="hidden" value="<?php echo $order;?>">
	<input type="hidden" name="isprint" value="<?php echo $isprint;?>">
	<input type="hidden" name="smt" value="<?php echo $smt;?>">
	
<div id="content">
<div id="mypanel">
<div id="mymenu" align="center">
<a href="#" onClick="process_save('save')" id="mymenuitem"><img src="../img/save.png"><br>Save</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="window.print()" id="mymenuitem"><img src="../img/printer.png"><br>Print</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="clear_newwin();document.myform.submit();" id="mymenuitem"><img src="../img/reload.png"><br>Refresh</a>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
	<div id="mymenu_seperator"></div>
	<div id="mymenu_space">&nbsp;&nbsp;</div>
<a href="#" onClick="<?php if($f!=""){?>top.document.myform.submit();<?php }?>window.close()" id="mymenuitem"><img src="../img/close.png"><br>Close</a>
</div> <!-- end mymenu -->

<div id="viewpanel" align="right">
	<a href="#" title="<?php echo $vdate;?>"><?php echo $vmod;?></a><br><br>
	
			  	<input type="hidden" name="year" value="<?php echo $year;?>">
				<input type="hidden" name="clscode" value="<?php echo $clscode;?>">
				<input type="hidden" name="sid" value="<?php echo $sid;?>">
				<input type="hidden" name="exam" value="<?php echo $exam;?>">
	 
</div><!-- end viewpanel -->
</div><!-- end mypanel -->

<div id="story">


<div id="mytitlebg">LEGGER CATATAN WALI KELAS <?php echo " $f";?></div>
<table width="100%" id="mytitlebg">
  <tr>
    <td width="50%">
		<table width="100%"  border="0" cellpadding="0">
		  <tr>
			<td width="20%"><?php echo strtoupper($lg_school);?></td>
			<td width="1%">:</td>
			<td><?php $sname=stripcslashes($sname);echo strtoupper($sname);?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_class);?></td>
			<td>:</td>
			<td><?php echo strtoupper("$clsname");?></td>
		  </tr>
		  <tr>
			<td><?php echo strtoupper($lg_year);?></td>
			<td>:</td>
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
			<td><?php echo strtoupper($lg_class_teacher);?></td>
			<td>:</td>
			<td><?php echo strtoupper($gurukelas);?></td>
		  </tr>
		</table>
	
	</td>
  </tr>
</table>
<div id="tipsdiv" style="display:none ">
Tips:<br>
1. Klik pada nama pelajar untuk melihat slip keputusan peperiksaan pelajar.<br>
2. Untuk print slip keputusan beramai-ramai, tick kan pada senarai nama, dan klik butang Slip, dan pada window baru klik butang Print<br>
3. Klik pada Kod Subjek untuk melihat laporan subjek atau untuk memasukkan/edit markah. Untuk edit markah apabila window baru dibuka, klik edit<br>
</div>
<table width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td id="mytabletitle" rowspan="2" width="1%" align="center" class="printhidden"><input type=checkbox name=checkall value="0" onClick="check(1)"></td>
    <td id="mytabletitle" rowspan="2" width="1%" align="center"><?php echo strtoupper($lg_no);?></td>
	<td id="mytabletitle" rowspan="2" width="2%" align="center"><a href="#" onClick="formsort('stu_uid <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_matric);?></a></td>
    <td id="mytabletitle" rowspan="2" width="1%" align="center"><a href="#" onClick="formsort('sex <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_mf);?></a></td>
	<td id="mytabletitle" rowspan="2" width="15%" ><a href="#" onClick="formsort('stu_name <?php echo "$nextdirection";?>','<?php echo "$nextdirection";?>')" title="sort"><?php echo strtoupper($lg_name);?></a></td>
	<td id="mytabletitle" rowspan="2" width="40%" align="center">CATATAN</td>
<!--
	<td id="mytabletitle" width="2%" align="center">Height(Cm)</td>
	<td id="mytabletitle" width="2%" align="center">Weight(Kg)</td>
	<td id="mytabletitle" width="2%" align="center">Attendance</td>
-->
	<td id="mytabletitle" colspan="3" width="2%" align="center"><input type="button" name="updateabsent" value="PERBARUAN KETIDAKHADIRAN" style="height:30px;" onclick="process_kira('kira')"></td>
	
  </tr>
  <tr>
	<td id="mytabletitle" width="2%" align="center">SAKIT</td>
	<td id="mytabletitle" width="2%" align="center">IZIN</td>
	<td id="mytabletitle" width="2%" align="center">ALPA</td>
  </tr>
<?php 
	$q=0;
	if($sort_ranking){
		if($sid==4)
				$sql="select stu.sex,stu.name,stu.uid,examrank.cls_name from stu INNER JOIN examrank ON stu.uid=examrank.stu_uid where examrank.sch_id=$sid and (stu.status=6 or stu.status=3 ) and examrank.year='$year' and examrank.cls_code='$clscode' and examrank.exam='$exam' $sqlstatuspelajar order by examrank.avg desc";
		elseif($sid==1){
				if($clslevel>3)
						$sql="select stu.sex,stu.name,stu.uid,examrank.cls_name  from stu INNER JOIN examrank ON stu.uid=examrank.stu_uid where examrank.sch_id=$sid and (stu.status=6 or stu.status=3 ) and examrank.year='$year' and examrank.cls_code='$clscode' and examrank.exam='$exam' $sqlstatuspelajar order by examrank.gpk desc,examrank.total_point desc";
				else
						$sql="select stu.sex,stu.name,stu.uid,examrank.cls_name  from stu INNER JOIN examrank ON stu.uid=examrank.stu_uid where examrank.sch_id=$sid and (stu.status=6 or stu.status=3 ) and examrank.year='$year' and examrank.cls_code='$clscode' and examrank.exam='$exam' $sqlstatuspelajar order by examrank.g1 desc,examrank.total_point desc";
		
		}
		elseif($sid==3)
				$sql="select stu.sex,stu.name,stu.uid,examrank.cls_name from stu INNER JOIN examrank ON stu.uid=examrank.stu_uid where examrank.sch_id=$sid and (stu.status=6 or stu.status=3 ) and examrank.year='$year' and examrank.cls_code='$clscode' and examrank.exam='$exam' $sqlstatuspelajar order by examrank.avg desc,examrank.gpk asc";
		else
				$sql="select stu.sex,stu.name,stu.uid,examrank.cls_name  from stu INNER JOIN examrank ON stu.uid=examrank.stu_uid where examrank.sch_id=$sid and (stu.status=6 or stu.status=3 ) and examrank.year='$year' and examrank.cls_code='$clscode' and examrank.exam='$exam' $sqlstatuspelajar order by examrank.gpk desc,examrank.total_point desc";
	}
	else{
		$sql="select stu.sex,stu.name,stu.uid,ses_stu.cls_name  from stu INNER JOIN ses_stu ON stu.uid=ses_stu.stu_uid where ses_stu.sch_id=$sid and (stu.status=6 or stu.status=3 ) and year='$year' $sqlstatuspelajar $sqlclscode  $sqlsort";
	}
		//echo $sql;
	$res=mysql_query($sql)or die("$sql:query failed:".mysql_error());
  	while($row=mysql_fetch_assoc($res)){
		$uid=$row['uid'];
		$name=strtoupper(stripslashes($row['name']));
		$sex=$row['sex'];
		if($q++%2==0)
			$bg="#FAFAFA";
		else
			$bg="";
			
		$sql="select * from exam_stu_summary where uid='$uid' and exam='$exam' and sid=$sid and year='$year' and smt='$smt' and type is null";
		$res3=mysql_query($sql)or die("$sql failed:".mysql_error());
		$row3=mysql_fetch_assoc($res3);
		$mm=$row3['msg'];
		$kg=$row3['wg'];
		$cm=$row3['hg'];
		$at=$row3['totalatt'];
		$dy=$row3['totalday'];
		$sakit=$row3['sakit'];
		$izin=$row3['izin'];
		$alpa=$row3['noreason'];
?>
<tr bgcolor="<?php echo $bg;?>" style="cursor:default" onMouseOver="this.bgColor='#CCCCFF';" onMouseOut="this.bgColor='<?php echo $bg?>';" >
		<td id="myborder" align="center" class="printhidden"><input type=checkbox name=stu[] id="stuid" value="<?php echo "$uid";?>" onClick="check(0)"></td>
		<td id="myborder" align="center"><?php echo $q?></td>
		<td id="myborder" align="center"><?php echo $uid?></td>
		<td id="myborder" align="center"><?php echo $lg_sexmf[$sex];?></td>
		<td id="myborder" ><?php echo $name;?></td>
		<td id="myborder">
		<!--<input type="text" style="width:99%" name="msg[]" value="<?php echo $mm;?>">-->
		<textarea name="msg[]" style="width:99%"><?php echo $mm;?></textarea>
        <!--
		<select id="msg[]" name="msg[]">
                        <option value="">- Pilih Komen -</option>
                <?php
                        $sql4="select * from type where (sid=$sid or sid=0) and grp='comment'";
                        $res4=mysql_query($sql4)or die("$sql4 fail : ".mysql_error());
                        while($row4=mysql_fetch_assoc($res4)){
                        $comment=$row4['prm'];
                        $comcode=$row4['code'];
                ?>
                        <option value="<?php echo $comcode;?>" <?php if($comcode==$mm) echo "selected";?>><?php echo $comcode." - ".$comment;?></option>
                <?php		
                        }
                ?>
		</select>
        -->
		</td>
<!--		<td id="myborder" align="center"><input type="text" style="width:80%" name="cm[]" value="<?php echo $cm;?>"></td>
		<td id="myborder" align="center"><input type="text" style="width:80%" name="kg[]" value="<?php echo $kg;?>"></td>
		<td id="myborder" align="center"><input type="text" style="width:40%" name="att[]" value="<?php echo $at;?>">/<input type="text" 
style="width:40%" name="day[]" value="<?php echo $dy;?>"></td>-->
<?php

if($op=="kira"){
	$sqls="select count(*) as sakit from stuatt where stu_uid='$uid' and year='$year' and des='S'";
	$ress=mysql_query($sqls)or die("$sqls query failed:".mysql_error());
	$rows=mysql_fetch_assoc($ress);
	$sakit=$rows['sakit'];
	$sqli="select count(*) as izin from stuatt where stu_uid='$uid' and year='$year' and des='I'";
	$resi=mysql_query($sqli)or die("$sqli query failed:".mysql_error());
	$rowi=mysql_fetch_assoc($resi);
	$izin=$rowi['izin'];
	$sqla="select count(*) as alpa from stuatt where stu_uid='$uid' and year='$year' and des='A'";
	$resa=mysql_query($sqla)or die("$sqla query failed:".mysql_error());
	$rowa=mysql_fetch_assoc($resa);
	$alpa=$rowa['alpa'];
	}
?>
		<td id="myborder" align="center"><input type="text" size="5" name="sakit[]" value="<?php echo $sakit;?>"></td>
		<td id="myborder" align="center"><input type="text" size="5" name="izin[]" value="<?php echo $izin;?>"></td>
		<td id="myborder" align="center"><input type="text" size="5" name="alpa[]" value="<?php echo $alpa;?>"></td>
		
</tr>
<input type="hidden" name="uid[]" value="<?php echo $uid;?>">
<?php } ?>
</table>
</div></div>
</form>
</body>
</html>
