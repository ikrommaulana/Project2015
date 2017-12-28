<?php
	if($HEADER_NAME_USE_MASTER)
		$sid=1;
	if($sid=="")
		$sid=1;

    $sql="select * from sch where id='$sid'";
    $xres=mysql_query($sql)or die("query failed:".mysql_error());
    $xrow=mysql_fetch_assoc($xres);
    $sname=stripslashes($xrow['name']);
	$slevel=$xrow['level'];
	$saddr=$xrow['addr'];
	$saddr1=$xrow['addr1'];
	$xxs=$xrow['state'];
	$xxp=$xrow['poskod'];
	$xxd=$xrow['daerah'];
	$xxc=$xrow['country'];
	$stel=$xrow['tel'];
	$sfax=$xrow['fax'];
	$sweb=$xrow['url'];
	$simg=$xrow['img'];
?>

<div align="center" style="padding:5px; font-size:12px;">
		<?php echo "<img src=$simg height=\"70px\">";?><br>
		<?php  echo strtoupper("sekolah alam bogor");?><br>
		<?php echo "$saddr $saddr1 $xxp $xxd $xxs $xxc";?><br />
		<?php echo "Tel:$stel&nbsp;&nbsp;Fax:$sfax&nbsp;&nbsp;Http://$sweb";?>
</div>
