<div class="printhidden">
	<?php 
    include_once("$MYLIB/inc/language_$LG.php");
    if(is_verify("")){ 
    ?>
            <?php if($MENU_TOP_ON){?>
                    <SCRIPT language=JavaScript src="../adm/inc/menutop.php" type=text/javascript></SCRIPT>
                    <SCRIPT language=JavaScript src="<?php echo $MYOBJ;?>/hmenu/mmenu.js" type=text/javascript></SCRIPT>
                    <div id="div_main_menu" class="printhidden">&nbsp;</div>                
            <?php } else{?>
                    <div id="div_main_menu" class="printhidden">&nbsp;</div>
                    <div id="siteName" class="printhidden" ><?php echo strtoupper("$organization_name");?></div>
            <?php } ?>
    <?php }else{ ?>
            <div id="div_main_menu" class="printhidden">&nbsp;</div>
            <div id="siteName" class="printhidden" ><?php echo strtoupper("$organization_name");?></div>
    <?php } ?>
</div>

<div id="masthead" class="printhidden" style="border-color:#666333;">
	<div id="siteLogo">	
		<?php if(isset($_SESSION['username'])) echo "<img height=50 src=$default_logo>"; ?>
	</div>
	<?php include('../inc/epanel.php');?>
</div><!-- end masthead --> 

<!--THHIS IS ONLY FOR INDEX.PHP-->
<?php if(!isset($_SESSION['username'])){ ?>
	<div id="mainmenu" class="printhidden"></div>
<?php } ?>
