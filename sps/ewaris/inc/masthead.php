<!-- main -->
<?php
include_once("$MYLIB/inc/language_$LG.php");
include_once('session.php');
$schid=$_SESSION['sid'];
$uid=$_SESSION['uid'];
$year=date('Y');

$sqlschtype="select * from sch where id='$schid'";
$resschtype=mysql_query($sqlschtype)or die("$sqlschtype query failed:".mysql_error());
$rowschtype=mysql_fetch_assoc($resschtype);
$schtype=$rowschtype['level'];
$issemester=$rowschtype['issemester'];
$startsemester=$rowschtype['startsemester'];
$month=date('m');
if($month<$startsemester){$year=$year-1;}

if($issemester){
	$sqlses="select * from type where grp='session' and val like '$year%'";
	$resses=mysql_query($sqlses)or die("$sqlses query failed:".mysql_error());
	$rowses=mysql_fetch_assoc($resses);
	$year=$rowses['prm'];

}

?>

<div id="masthead" class="printhidden" >
<div id="div_main_menu" class="printhidden">&nbsp;</div>
    <div id="siteName" class="printhidden" ><?php echo strtoupper("$organization_name");?> - PARENT ONLINE <?php //echo $year;?></div>
    
	<div id="siteLogo">	
<?php 
	if(isset($_SESSION['username'])) 
		echo "<img height=50 src=$default_logo>"; 
	else
		echo "<img height=50 src=$system_logo>";
?>
	</div>
	
<div id="epanel" align="center" class="printhidden">
	
<?php if(is_verify()){?>
	<a href="#" onClick="javascript: href='main.php'" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/home32.png"><br>
	<?php if($LG=="BM") echo "Utama"; else echo "Home";?></a>
<?php }else{ ?>
		<a href="index.php" id="mymenuitem">
        	<img src="<?php echo $MYLIB;?>/img/terminal32.png"><br><?php if($LG=="BM") echo "Login"; else echo "";?></a>
<?php } ?>
   
<?php 
	if(isset($_SESSION['username'])) {
?>
	<?php
		$sql="select * from type where grp='openexam' and prm='EPEPERIKSAAN'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$sta=$row['val'];
		if($sta>-1){

	?>
	
	<a href="#" onClick="javascript: href='p.php?p=exam'" id="mymenuitem">
    	<img src="<?php echo $MYLIB;?>/img/register32b.png"><br>Rapot Ujian Akademik</a>
	
	<?php
	if ($schid==4){
	if($ON_TK){?>
	<a href="#" onClick="javascript: href='p.php?p=examtk'" id="mymenuitem">
    	<img src="<?php echo $MYLIB;?>/img/register32b.png"><br>Rapot Ujian <?php echo $TK;?> </a>

		<?php
	}
	}//on tk
	}
		$sql="select * from type where grp='openexam' and prm='EHOMEWORK'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$sta=$row['val'];
		if($sta>-1){
		$tahun=explode("/",$year);
		$y1=$tahun[0];
		$y2=$tahun[1];
			
			$sql="select count(*) from hwork_stu where uid='$uid' and (dt like '$y1%' or dt like '$y2%') and sta='0'";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			$row=mysql_fetch_row($res);
			$jumhwork=$row[0];
	?>
	
	<a href="#" onClick="javascript: href='p.php?p=hwork'" id="mymenuitem">
    	<img src="<?php echo $MYLIB;?>/img/hwork2.png"><br>Pekerjaan Rumah (<?php echo "$jumhwork"; ?>)</a>
	<?php
	}	
		$sql="select * from type where grp='openexam' and prm='EYURAN'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$sta=$row['val'];
		if($sta>-1){
	?>
	<a href="#" onClick="javascript: href='p.php?p=fee'"id="mymenuitem">
    	<img src="<?php echo $MYLIB;?>/img/finance32b.png"><br>Biaya</a>
	<?php
	}
		$sql="select * from type where grp='openexam' and prm='EHAFAZAN'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$sta=$row['val'];
		if($sta>-1){
	?>
	<a href="#" onClick="javascript: href='p.php?p=haf_slip'" id="mymenuitem">
    	<img src="<?php echo $MYLIB;?>/img/quran32.png"><br>Hafalan</a>
	<?php
	}
		$sql="select * from type where grp='openexam' and prm='EKEDATANGAN'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$sta=$row['val'];
		if($sta>-1){
	?>
	<a href="#" onClick="javascript: href='p.php?p=att'" id="mymenuitem">
    	<img src="<?php echo $MYLIB;?>/img/calendar32.png"><br>Kehadiran</a>
	<?php
	}
		$sql="select * from type where grp='openexam' and prm='EDISIPLIN'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$sta=$row['val'];
		if($sta>-1){
	?>
	<a href="#" onClick="javascript: href='p.php?p=dis'" id="mymenuitem">
    	<img src="<?php echo $MYLIB;?>/img/warning32.png"><br><?php if($LG=="BM") echo "Disiplin"; else echo "Kedisiplinan";?></a>
	<?php
	}
		$sql="select * from type where grp='openexam' and prm='EHOSTEL'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$sta=$row['val'];
		if($sta>-1){
	?>
	<a href="#" onClick="javascript: href='p.php?p=outing-stu'" id="mymenuitem">
    	<img src="<?php echo $MYLIB;?>/img/hostel32.png"><br>Hostel</a>
	<?php } ?>
	
	<?php if(is_verify()){?>
		<a href="#" onClick="javascript: href='p.php?p=change_pass'" id="mymenuitem">
		<img src="<?php echo $MYLIB;?>/img/option.png" width="32px" height="32px"><br>Ubah Password</a>
	<?php } ?>

	<?php if(is_verify()){?>
		<a href="logout.php" id="mymenuitem">
        	<img src="<?php echo $MYLIB;?>/img/close32.png"><br><?php if($LG=="BM") echo "Logout"; else echo "Logout";?></a>
	<?php } ?>
<?php }//if username ?>	
</div>
	

 </div><!-- end masthead --> 
