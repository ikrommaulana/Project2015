<?php
include_once('../etc/db');
	include_once('../etc//session.php');
	verify_admin();
	$username = $_SESSION['username'];
	$mobileid = $_SESSION['mobileid'];
	$userid = $_SESSION['userid'];
	include_once('xsms/.xgate');
	include_once('xsms/xgate');

	
	$id=$_POST['id'];
	$tel=$_POST['tel'];
	$name=$_POST['name'];
	$ctype=$_POST['ctype'];
	$resp=$_POST['resp'];
	$categ=$_POST['categ'];
	$moid=$_POST['moid'];
	if($moid=="")
		$moid=0;
	
	if($categ=="")
		$categ="general";

	$status=$_POST['status'];
	$sms=$_POST['sms'];
	if($sms=="")
		$sms="no";
	$rmsms=$_POST['rmsms'];
	$eprepaid=$_POST['eprepaid'];
	$rmpre=$_POST['rmpre'];
	if($rmpre=="")
		$rmpre=0;
	$admin = $username;
	$operation=$_POST['operation'];
	
	$sql="select * from user where tel='$tel'";
    $res=mysql_query($sql)or die("query failed:".mysql_error());
    while($row=mysql_fetch_assoc($res)){
            $fname=$row['fname'];
			$name=$row['name'];
			$units=$row['units'];
			$email=$row['email'];
			$REG="(REGISTERED)";			
    }
    mysql_free_result($res);
	
	if($operation=='del'){	
		$sql="delete from msg where id='$id'";
	    mysql_query($sql)or die("query failed:".mysql_error());
      	mysql_close($link);
		echo "<script language=\"javascript\">location.href='p.php?p=sms_msg&sta=$sta'</script>";
	}
	else{
		$sql="update msg set rdate=now(),categ='$categ',action='$ctype',admin='$admin',resp='$resp',status='$status',rm='$rmsms',units=$rmpre where id=$id";
		mysql_query($sql)or die("query failed:".mysql_error());
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Service Creation Host</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>


    <table width="50%">
      <tr>
        <td width="46%"><div align="right"><strong>Ref:</strong></div></td>
        <td width="54%"><?php echo $id;?></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Tel:</strong></div></td>
        <td><?php echo $tel;?></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Name:</strong></div></td>
        <td><?php echo $name;?></td>
      </tr>
	  <tr>
        <td><div align="right"><strong>SMS:</strong></div></td>
        <td><?php echo $sms;?></td>
      </tr>
	  
	  <tr>
        <td><div align="right"><strong>SMS Charge:</strong></div></td>
        <td>RM<?php printf("%.02f",$rmsms/100);?>
		</td>
      </tr>
	  <tr>
        <td><div align="right"><strong>ePrepaid Charge:</strong></div></td>
        <td>RM<?php printf("%.02f",$rmpre/100);?>
		</td>
      </tr>
	<tr>
        <td><div align="right"><strong>Status:</strong></div></td>
        <td>&nbsp;<?php echo $status;?></td>
      </tr>

    </table>
	<?php
	
               
	if(($rmpre>0)&&($status=="complete")){
		$sql="update user set units=units-$rmpre where tel=$tel";
		mysql_query($sql)or die("query failed:".mysql_error());
		//$sql="insert into prepaid (cdate,tel,app,ref,des,units) value (now(),'$tel','eTanya',$id,'',$rmpre)";
		$sql="insert into prepaid (cdate,tel,app,units) value (now(),'$tel','eTanya',$rmpre)";
		$res=mysql_query($sql) or die("query failed".mysql_error());
	}
	if(($sms=="yes")&&($status=="complete")){
		$net=substr($tel,0,4);
        $net4=substr($tel,0,5);
        if(($net=="6016")||($net4=="60146")||($net4=="60143"))
	    	$moid=0;
        //xgate_send_sms($xgateip,$xgateport,"drtuah","drpass","33221",$tel,$id,$moid,0,$resp,$rmsms,"tuah",0);
		xgate_send_sms($xgateip,$xgateport,"drtuah","drpass","33221",$tel,$id,$moid,0,$resp,0,"tuah",10);			
	}
?>

</body>
</html>
