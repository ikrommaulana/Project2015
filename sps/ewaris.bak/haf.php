<?php 
include_once('../etc/db.php');
include_once('session.php');
verify();

	$sql="select * from type where grp='openexam' and prm='EHAFAZAN'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$sta=$row['val'];
	if($sta!="1")
		echo "<script language=\"javascript\">location.href='p.php?p=close'</script>";
		

	$sid=$_SESSION['sid'];
	$uid=$_SESSION['uid'];
	
	$year=$_POST['year'];
	if($year=="")
		$year=date('Y');
		
	$sql="select count(*) from hafazan_rec where sid=$sid and uid='$uid'";
			$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			$row2=mysql_fetch_row($res2);
			$jumms=$row2[0];
			if($jumms>603)
				$jumms=603;// mainten at 603..kes ulang
			$jumlah=sprintf("%d",$jumms/20);
	

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Untitled Document</title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
</head>

<body>
<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" >
		<input type="hidden" name="p" value="haf">
<div id="panelleft">
	<?php include('inc/lmenu.php');?>
</div>
<div id="content2"> 
<div align="center" style="font-size:14px; font-weight:bold; padding:2px; font-family:Arial; color:#FFFFFF; background-image:url(../img/bg_title_lite.jpg);border: 1px solid #99BBFF;">
		<?php echo strtoupper($name);?>
</div>
<div id="mypanel">
	<div align="center" style="font-size:24px ">JUMLAH JUZUK DIHAFAL (<?php echo $jumlah;?>)</div>
</div>
<div id="story">

<table width="100%" cellspacing="0">
	<tr>
		<td width="8%"  id="mytabletitle" style="border-right:none ">NO JUZUK</td>
		<td width="92%" id="mytabletitle" style="border-left:none " align="center">MUKASURAT</td>
	</tr>
</table>
<table width="100%" cellspacing="0"  cellpadding="2px" style="font-size:10px">
<?php 
	$j=2;
	for($i=1;$i<=30;$i++){
?>
	<tr>
		<td width="3%" id="mytabletitle">Juzuk&nbsp;<?php echo $i;?></td>
		<?php 	for($ii=0;$ii<20;$ii++,$j++){ 
			$sql="select * from hafazan_rec where sid=$sid and uid='$uid' and ms=$j order by id desc limit 1";
			$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
			if($row2=mysql_fetch_assoc($res2)){
				$cc=$row2['ms'];
				$ENABLE=0;
				if($row2['ulang']==0){
					$bgc="bgcolor=#66FF66";
					if($ULANG==1)
						$ENABLE=1;
				}
				if($row2['ulang']==1){
					$bgc="bgcolor=#CCCC66";
				}
			}else{
				$cc=0;
				$bgc="";
				$ENABLE=1;
			}
		?>
		<td width="3%" id="myborder" <?php echo $bgc;?> align="center" <?php if($ENABLE){?>onMouseOver="myin(this);" onMouseOut="myout(this);" onClick="process_mouse(<?php echo $j;?>,1)"<?php }?>>
			<?php echo $j;?>
		</td>
		<?php } ?>
	</tr>
<?php } ?>
	<tr>
		<td width="3%" id="mytabletitle">Juzuk&nbsp;30</td>
		<?php 	
		for($ii=0;$ii<3;$ii++,$j++){ 
				$sql="select * from hafazan_rec where sid=$sid and uid='$uid' and ms=$j order by id desc limit 1";
				$res2=mysql_query($sql)or die("$sql:query failed:".mysql_error());
				if($row2=mysql_fetch_assoc($res2)){
					$cc=$row2['ms'];
					$ENABLE=0;
					if($row2['ulang']==0){
						$bgc="bgcolor=#66FF66";
						if($ULANG==1)
							$ENABLE=1;
					}
					if($row2['ulang']==1)
						$bgc="bgcolor=#CCCC66";
				}else{
					$cc=0;
					$bgc="";
					$ENABLE=1;
				}

		?>
		<td width="3%" id="myborder" <?php echo $bgc;?> align="center" <?php if($ENABLE){?>onMouseOver="myin(this);" onMouseOut="myout(this);" onClick="process_mouse(<?php echo $j;?>,1)"<?php }?>>
			<?php echo $j;?>
		</td>
		<?php } ?>
	</tr>
</table>

</div></div><!-- content/story -->
</form>
</body>
</html>
