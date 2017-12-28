<div class="printhidden">
<?php 
include("../adm/ver.php");
if (isset($_SESSION['username'])){
?>
	<div id="mainmenu" style="z-index:99 ">
			<?php include("inc/mainmenu.php");?>
	</div>
<?php } ?>
</div>
<div id="masthead" class="printhidden">

	<div id="siteLogo" align="center">
		<img src=../img/logosps.png>
	</div>
	<div id="siteName"><?php echo "$organization_name";?> - <?php  if (isset($_SESSION['username']))  echo strtoupper($_SESSION['username']); else echo "SPS";?></div> 
	<div id="siteTitle"><?php echo "$version";?></div>

	<div id="utility" >
		<?php if (isset($_SESSION['username'])){?>
			&nbsp;&nbsp;<a href="logout.php">Logout</a> <img src="../img/close_tiny.png">&nbsp;&nbsp;
		<?php } else {?>
			&nbsp;&nbsp;
			<a href="index.php">Login</a> <img src="../img/collapseh.gif"> |
			<a href="../adm/">Goto SPS</a> <img src="../img/new_window_tiny.png">&nbsp;&nbsp;
		<?php } ?>
			
	</div> <!-- end utility -->
	
 </div><!-- end masthead --> 
 
<div id="mystatuspanel" class="printhidden">

</div>

