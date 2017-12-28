<?php
/**
ver 2.6
list all fee
**/
include_once('../etc/db.php');
include_once('session.php');
include_once("$MYLIB/inc/language_$LG.php");
verify();

	
	$uid = $_SESSION['uid'];
	$sid = $_SESSION['sid'];
	
	$stu_id=$_POST['stu_id'];
	$year=$_POST['year'];
	$total=$_POST['total'];
	$bank=$_POST['bank'];
	$bankno=$_POST['bankno'];
	$mel=$_POST['mel'];
	$hp=$_POST['hp'];
		
	$paydate=$_POST['paydate'];
	if($paydate=="")
		$paydate=date("Y-m-d");

	$feetype=$_REQUEST['feetype'];
	if($feetype=="")
		$feetype=0;
	$sql="select * from type where grp='feetype' and val=$feetype order by idx";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$feetypename=$row['prm']; 
				
	$year=$_REQUEST['year'];
	if($year=="")
		$year=date("Y");
		
	$sql="select * from stu where uid='$uid' and sch_id=$sid";
	$res=mysql_query($sql) or die(mysql_error());
	if($row=mysql_fetch_assoc($res)){
			$stu_name=$row['name'];
			$mel=$row['mel'];
			$hp=$row['hp'];
	}		

	$checkbox=$_POST['checkbox'];
if (count($checkbox)>0) {

		
	$sql="select * from stu where uid='$uid' and sch_id=$sid";
	$res=mysql_query($sql) or die(mysql_error());
	if($row=mysql_fetch_assoc($res)){
			$stu_name=$row['name'];
			$stu_ic=$row['ic'];
			$stu_id=$row['id'];
	}
	$sql="insert into feeonlinetrans (cdate,year,sch_id,stu_id,stu_uid,rm,stu_ic,bank,bankno,paydate)
	values(now(),$year,$sid,$stu_id,'$uid',$total,'$stu_ic','$bank','$bankno','$paydate')";
	
	//echo $sql;
   	mysql_query($sql)or die("query failed:".mysql_error());
	$feeid=mysql_insert_id($link);
	if (count($checkbox)>0) {
			for ($i=0; $i<count($checkbox); $i++) {
				$data=$checkbox[$i];
				$prm=strtok($data,"|");
				$val=strtok("|");
				$sql="insert into feeonlinepay (cdate,year,sch_id,stu_id,stu_uid,rm,fee,tid)values(now(),$year,$sid,$stu_id,'$uid',$val,'$prm',$feeid)";
				//echo $sql;
				mysql_query($sql)or die("query failed:".mysql_error());
			}
	}
	if($bank=="1"){
		$des=urlencode("Fee Payment");
		$mel=urlencode($mel);
		$hp=urlencode($hp);
		echo "<script language=\"javascript\">location.href='https://www.onlinepayment.com.my/NBepay/pay/test4957/?amount=$total&orderid=$feeid&bill_email=$mel&bill_mobile=$hp&bill_desc=$des'</script>";
		echo "<a href=\"https://www.onlinepayment.com.my/NBepay/pay/test4957/?amount=$total&orderid=$feeid&bill_email=$mel&bill_mobile=$hp&bill_desc=$des\">
			PLEASE CLICK HERE TO REDIRECTING TO ONLINE PAYMENT..</a>";
	}else{
			echo "<script language=\"javascript\">location.href='feetransview.php?id=$feeid'</script>";

	}
}else{
	
	echo "UNABLE TO PROCESS. PLEASE TRY AGAIN LATER";	
}
?>

