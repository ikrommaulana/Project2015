<?php
//echo $HEADER_NAME_USE_MASTER;

if($HEADER_NAME_USE_MASTER)
		$sid=1;
	if($sid=="")
		$sid=0;
      
	$sql="select * from sch where id='$sid'";
    $xres=mysql_query($sql)or die("query failed:".mysql_error());
    $xrow=mysql_fetch_assoc($xres);
    $sname=stripcslashes($xrow['name']);
	$slevel=$xrow['level'];
	$saddr=$xrow['addr'];
	$sstate=$xrow['state'];
	$stel=$xrow['tel'];
	$sfax=$xrow['fax'];
	$sweb=$xrow['url'];
	$simg=$xrow['img'];
	if($HEADER_NAME_USE_MASTER)
		$simg=$organization_logo;
    mysql_free_result($xres);	
?>

<div align="center" style="padding:5px ">
		<?php 
			if($simg=="") 
				echo "<img src=$organization_logo>"; 
			else 
				echo "<img src=$simg>";
		?> 
		<br>
		<?php if($HEADER_NAME_USE_MASTER)  echo strtoupper($organization_name); else echo strtoupper($sname);?><br>
		<?php echo "$saddr<br>Tel:$stel&nbsp;&nbsp;Fax:$sfax&nbsp;&nbsp;Http://$sweb";?>
</div>
