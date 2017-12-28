<?php 
		include_once('../etc/db.php');
		$found=0;
		$sid=$_REQUEST['sid'];
		if($sid=="")
			$sid="0";
		$ic=$_REQUEST['ic'];
		$sql="select * from stureg where sch_id='$sid' and ic='$ic' and confirm=1";
		$alertfound=0;
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		if($row=mysql_fetch_assoc($res)){
			$name=$row['name'];
			echo "<img src=\"$MYLIB/img/warning12.png\">";
			echo "&nbsp;<a href=\"statusreg.php?sid=$sid&ic=$ic\" style=\"color:#FF0000\">
				ic $ic '$name' already exist!<br>
				Please proceed to registration status..(click here)</a>
			";
			$found++;
		}
		if($found==0){
			echo "<img src=\"$MYLIB/img/check12.png\">";
			echo "&nbsp;IC Verified";
		}
?>

