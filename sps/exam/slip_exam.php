<?php
	include_once('../etc/db.php');
	include_once('../etc/session.php');
	verify("");
	$username = $_SESSION['username'];
	$sid=$_REQUEST['sid'];
	if($sid=="")
		$sid=$_SESSION['sid'];
	
	$sql="select * from type where grp='reportcard' and sid=$sid";
	$res=mysql_query($sql) or die(mysql_error());
	while($row=mysql_fetch_assoc($res)){
			$prm=$row['prm'];
			$CONFIG[$prm]['val']=$row['val'];
			$CONFIG[$prm]['code']=$row['code'];
			$CONFIG[$prm]['etc']=$row['etc'];
	}
	
	include($CONFIG['FILE_NAME']['etc']);
?>
