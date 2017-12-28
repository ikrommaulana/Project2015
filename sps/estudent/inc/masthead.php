<!-- main -->
<?php include_once("$MYLIB/inc/language_$LG.php");?>

<div id="masthead" class="printhidden" >
<div id="div_main_menu" class="printhidden">&nbsp;</div>
    <div id="siteName" class="printhidden" ><?php echo strtoupper("$organization_name");?> - STUDENT ONLINE</div>
    
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
        	<img src="<?php echo $MYLIB;?>/img/terminal32.png"><br><?php if($LG=="BM") echo "Login"; else echo "Login";?></a>
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
    	<img src="<?php echo $MYLIB;?>/img/register32b.png"><br><?php if($LG=="BM") echo "Akademik"; else echo "Academic";?></a>
	<?php
	}
		$sql="select * from type where grp='openexam' and prm='EYURAN'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$sta=$row['val'];
		if($sta>-1){
	?>
	<a href="#" onClick="javascript: href='p.php?p=fee'"id="mymenuitem">
    	<img src="<?php echo $MYLIB;?>/img/finance32b.png"><br><?php if($LG=="BM") echo "Yuran"; else echo "Fees";?></a>
        
    <a href="fee.php" onClick="return GB_showCenter('News Info',this.href,520,1000)" id="mymenuitem">
    	<img src="<?php echo $MYLIB;?>/img/finance32b.png"><br><?php if($LG=="BM") echo "YuranTest"; else echo "FeesTest";?></a>
	<?php
	}
		$sql="select * from type where grp='openexam' and prm='EHAFAZAN'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$sta=$row['val'];
		if($sta>-1){
	?>
	<a href="#" onClick="javascript: href='p.php?p=haf'" id="mymenuitem">
    	<img src="<?php echo $MYLIB;?>/img/quran32.png"><br><?php if($LG=="BM") echo "Hafazan"; else echo "Quranic";?></a>
	<?php
	}
		$sql="select * from type where grp='openexam' and prm='EKEDATANGAN'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$sta=$row['val'];
		if($sta>-1){
	?>
	<a href="#" onClick="javascript: href='p.php?p=att'" id="mymenuitem">
    	<img src="<?php echo $MYLIB;?>/img/calendar32.png"><br><?php if($LG=="BM") echo "Kedatangan"; else echo "Attendance";?></a>
	<?php
	}
		$sql="select * from type where grp='openexam' and prm='EDISIPLIN'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$sta=$row['val'];
		if($sta>-1){
	?>
	<a href="#" onClick="javascript: href='p.php?p=dis'" id="mymenuitem">
    	<img src="<?php echo $MYLIB;?>/img/warning32.png"><br><?php if($LG=="BM") echo "Disiplin"; else echo "Discipline";?></a>
	<?php
	}
		$sql="select * from type where grp='openexam' and prm='EHOSTEL'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$sta=$row['val'];
		if($sta>-1){
	?>
	<a href="#" onClick="javascript: href='p.php?p=outing-stu'" id="mymenuitem">
    	<img src="<?php echo $MYLIB;?>/img/hostel32.png"><br><?php if($LG=="BM") echo "Asrama"; else echo "Hostel";?></a>
	<?php } ?>
	<?php if(is_verify()){?>
		<a href="logout.php" id="mymenuitem">
        	<img src="<?php echo $MYLIB;?>/img/close32.png"><br><?php if($LG=="BM") echo "Logout"; else echo "Logout";?></a>
	<?php } ?>
<?php }//if username ?>	
</div>
	

 </div><!-- end masthead --> 
