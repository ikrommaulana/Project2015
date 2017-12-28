<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
verify('ADMIN|AKADEMIK|KEUANGAN|GURU');
$username = $_SESSION['username'];

	$id=$_POST['id'];
	$sid=$_POST['sid'];
	$uid=$_POST['uid'];
	$tempuid=$_POST['tempuid'];
	$pass=md5($uid);
	  
	$sqlstu="select * from stu where id='$id'";
	$resstu=mysql_query($sqlstu)or die("$sqlstu query failed:".mysql_error());
	$rowstu=mysql_fetch_assoc($resstu);
	$olduid=$rowstu['uid'];
	$stuname=$rowstu['name'];
	$stuic=$rowstu['ic'];
	$stutempuid=$rowstu['tempuid'];

	if($stutempuid!=""){
		$tempuid=$stutempuid."|".$tempuid;
	}
	
	$sql="select * from sch where id='$sid'";
    $res=mysql_query($sql)or die("query failed:".mysql_error());
    $row=mysql_fetch_assoc($res);
    $schlevel=$row['level'];
	$pref=$row['stuprefix'];
    mysql_free_result($res);					  
	
	
	$operation=$_POST['operation'];

	if($operation=="update"){
			$sqlcek="select * from stu where uid='$uid' and sch_id='$sid'";
			$rescek=mysql_query($sqlcek)or die("$sqlcek query failed:".mysql_error());
			$rowcek=mysql_fetch_assoc($rescek);
			$numcek=mysql_num_rows($rescek);
			if($numcek<=0){
			$sql="update stu set uid='$uid',tempuid='$tempuid', password='$pass' where id=$id";
			mysql_query($sql)or die("$sql 3query failed:".mysql_error());		
			$sql="update stureg set uid='$uid' where ic='$stuic' and name='$stuname'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update bacaan_stu_read set uid='$uid' where uid='$olduid' and sid='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update bacaan_stu_status set uid='$uid' where uid='$olduid' and sid='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update bmi set uid='$uid' where uid='$olduid' and sid='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update dis set stu_uid='$uid' where stu_uid='$olduid' and sch_id='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update exam set stu_uid='$uid' where stu_uid='$olduid' and sch_id='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update examrank set stu_uid='$uid' where stu_uid='$olduid' and sch_id='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update exam_stu_summary set uid='$uid' where uid='$olduid' and sid='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update feeonlinepay set stu_uid='$uid' where stu_uid='$olduid' and sch_id='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update feeonlinetrans set stu_uid='$uid' where stu_uid='$olduid' and sch_id='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update feepay set stu_uid='$uid' where stu_uid='$olduid' and sch_id='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update feepay_del set stu_uid='$uid' where stu_uid='$olduid' and sch_id='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update feestu set uid='$uid' where uid='$olduid' and sid='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update feetrans set stu_uid='$uid' where stu_uid='$olduid' and sch_id='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update feetrans_del set stu_uid='$uid' where stu_uid='$olduid' and sch_id='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update hafazan set uid='$uid' where uid='$olduid' and sid='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update hafazan_rec set uid='$uid' where uid='$olduid' and sid='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update hafazan_rep set uid='$uid' where uid='$olduid' and sid='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update hafazan_sem set uid='$uid' where uid='$olduid' and sid='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update hafaz_exam set stu_uid='$uid' where stu_uid='$olduid' and sch_id='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update hafaz_stu_summary set uid='$uid' where uid='$olduid' and sid='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update hwork_stu set uid='$uid' where uid='$olduid' and sid='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update koq_note set uid='$uid' where uid='$olduid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update koq_stu set uid='$uid' where uid='$olduid' and sid='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update outing set uid='$uid' where uid='$olduid' and sid='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update ses_stu set stu_uid='$uid' where stu_uid='$olduid' and sch_id='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update ses_stusub set uid='$uid' where uid='$olduid' and sid='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update stuatt set stu_uid='$uid' where stu_uid='$olduid' and sch_id='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update sub_stu_summary set uid='$uid' where uid='$olduid' and sid='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update surah_stu_read set uid='$uid' where uid='$olduid' and sid='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update surah_stu_status set uid='$uid' where uid='$olduid' and sid='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
			$sql="update syaathir_rec set uid='$uid' where uid='$olduid' and sid='$sid'";
			mysql_query($sql)or die("$sql 4query failed:".mysql_error());
						$st=1;
			}else{
				$st=3;
			}


	}
?>
