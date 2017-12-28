<?php
require_once "excel_writer.php";
$vdate="110626";
$vmod="v6.1.0";

include_once('../etc/db.php');
include_once('../etc/session.php');
//include_once("$MYLIB/inc/language_$LG.php");
verify("ADMIN|AKADEMIK|KEWANGAN|GURU");
$username = $_SESSION['username'];
$isprint=$_REQUEST['isprint'];

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
			$sql="select * from sub where code='$subcode'  and sch_id=$sid";
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



$xls = new ExcelExport();
/**
$xls->addRow(Array("First Name","Last Name","Website","ID"));
$xls->addRow(Array("james","lin","www.chumby.net",0));
$xls->addRow(Array("bhaven","mistry","www.mygumballs.com",1));
$xls->addRow(Array("erica","truex","www.wholegrainfilms.com",2));
$xls->addRow(Array("eliot","gann","www.dissolvedfish.com",3));
$xls->addRow(Array("trevor","powell","gradius.classicgaming.gamespy.com",4));

$xls->download("websites.xls");
**/
$xls->addRow(Array("MATAPELAJARAN : ".$subname));
$xls->addRow(Array("NIS","NILAI","NAMA"));

	$sql="select ses_stu.*,stu.sex,stu.name,stu.status from ses_stu INNER JOIN stu ON ses_stu.stu_uid=stu.uid where stu.sch_id=$sid and ses_stu.cls_code='$clscode' and ses_stu.year='$year' $sqlstatuspelajar $sqlsort";
	$res=mysql_query($sql)or die("$sql failed:".mysql_error());
	while($row=mysql_fetch_assoc($res)){
			$name=strtoupper(stripslashes($row['name']));
			$uid=$row['stu_uid'];
			$q++;
			//echo "$tel\t$tel2\t$fax\t$email\t$grp\t$ic\t$name\t$fname\r\n";
			$xls->addRow(Array("\"".$uid."\"",'',$name));
	}

$xls->download("marksheet.xls");
 ?> 
