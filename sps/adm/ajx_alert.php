<?php 
		include_once('../etc/db.php');
		include_once('../etc/session.php');
		$sid=$_SESSION['sid'];
		if($sid==0)
			$sql="select * from sch";
		else
			$sql="select * from sch where id=$sid";
		$alertfound=0;
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
			$sid=$row['id'];
			$sname=$row['sname'];
			$sql="select count(*) from stu where uid NOT IN (select stu_uid from ses_stu where year='".date('Y')."') and sch_id=$sid and stu.status=6";
			$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			$row2=mysql_fetch_row($res2);
			$total=$row2[0];
			if($total>0){
				echo "<a href=\"p.php?p=../ses/ses_stu_change_cls&sid=$sid&clscode=0\" title=
				\"Update..\" style=\"color:#FF0000\"><img src=\"../img/warning12.png\" style=\"margin:-1px;\">&nbsp;$total pelajar $sname tiada kelas..</a>&nbsp;&nbsp;&nbsp;";
				$alertfound++;
			}
		}
?>
