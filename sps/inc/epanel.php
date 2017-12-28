<?php
$sid=$_SESSION['sid'];
if($sid!=0)
	$sqlsid="and sch_id='$sid'";
if($sid!=0)
	$sqlsid2="and sid='$sid'";
?>
<div id="utility" align="right">
	<?php if (isset($_SESSION['username'])){?>
		<a href="../adm/logout.php"><?php echo $lg_logout;?></a>&nbsp;&nbsp;
        <a href="../tutorsps/p.php?p=tutorial"><?php echo $lg_tutorial;?></a>&nbsp;&nbsp;
	<?php } else {?>
		<a href="../adm/index.php"><?php echo $lg_login;?></a>&nbsp;&nbsp;
	<?php } ?>
		<a href="../info/about.php"><?php echo $lg_about;?></a>&nbsp;&nbsp;
		<a href="../info/contact.php"><?php echo $lg_contact;?></a>&nbsp;&nbsp;
        <?php echo "$VERSION$EXTERNAL_INI";?>
</div>

<?php if (isset($_SESSION['username'])){?>
<div id="epanel" align="center" class="printhidden">
	<a href="../adm/index.php" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/home32.png"><br><?php echo $lg_home;?></a>
    
	<?php if($ON_TUTOR){?>        
    	<a href="../tutor/p.php?p=main" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/edu32.png"><br>E-Tutor</a>
    <?php } ?>
	<?php if((ISACCESS("dashboard",0))&&($ON_DASHBOARD)){?>
		<a href="../edash/p.php?p=main" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/monitoring32.png"><br>Grafik Sekolah</a>
    <?php } ?>
	<?php if((ISACCESS("estaff",0))&&($ON_ESTAFF)){?>
		<a href="../estaff/p.php?p=usrlist" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/user32.png"><br><?php echo $lg_staff;?></a>
    <?php } ?> 
	<?php if((ISACCESS("asset",0))&&($ON_ASSET)){?>
		<a href="../asset/p.php?p=asset" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/asset48.png" width="32"><br><?php echo "Sarpras";?></a>
    <?php } ?> 
 	<?php if((ISACCESS("ejob",0))&&($ON_JOB)){
						$sql="select count(*) from workreg where status=0 and confirm=1";
						$res=mysql_query($sql)or die("query failed".mysql_error());
						$row=mysql_fetch_row($res);
						$y=$row[0];
	?>
		<a href="../ecareer/p.php?p=work_online" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/register32a.png"><br><?php echo "Jobs($y)";?></a>
    <?php } ?>    
    <?php if((ISACCESS("eleave",0))&&($ON_LEAVE)){
						$sql="select count(*) from myleave where status=0 $sqlsid2";
						$res=mysql_query($sql)or die("query failed".mysql_error());
						$row=mysql_fetch_row($res);
						$y=$row[0];
			
	?>
			<a href="../ecuti/p.php?p=leave_pro" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/calendar32.png"><br><?php echo $lg_leave;?><?php echo "($y)";?></a>
    <?php } if($ON_BOOKING){?>
			<a href="../booking/p.php?p=booking_cal" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/booking48.png" height="32"><br><?php echo $lg_booking;?></a>
	<?php } if((ISACCESS("eregister",0))&&($ON_EREGISTER)){				
						$sql="select count(*) from stureg where status=0 and isdel=0 $sqlsid";
						$res=mysql_query($sql)or die("query failed".mysql_error());
						$row=mysql_fetch_row($res);
						$x=$row[0];
	?>
		<a href="../edaftar/p.php?p=stu_register" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/register32b.png"><br><?php echo $lg_registration;?>(<?php echo $x;?>)</a>
    <?php } ?>
	<?php if($ON_KOQ){
				if(ISACCESS("ekoq",0)){?>
					<a href="../ekoq/p.php?p=koq_stu_list" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/sport32b.png"><br><?php echo $lg_cocurriculum;?></a>
	<?php }} if($ON_HOSTEL){
				if(ISACCESS("hostel",0)){?>
					<a href="../ehostel/p.php?p=hos_stu_list" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/hostel32.png"><br><?php echo $lg_hostel;?></a>
	<?php }} if($ON_QURAN){
					if(ISACCESS("qiroati",0)){?>
					<a href="../ehaf/p.php?p=qiroati" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/quran32.png"><br>Qiroati</a>
	<?php }}if($ON_DISCIPLINE){
					if(ISACCESS("discipline",0)){?>
					<a href="../edis/p.php?p=dis_stu_list" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/warning32.png"><br><?php echo $lg_discipline;?></a>
	<?php }}?>
	<?php if((ISACCESS("franchise",0))&&($ON_FRANCHISE)){?>
				<a href="../franchise/index.php" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/franchise.png"><br>Franchise</a>
	<?php } if((ISACCESS("alumni",0))&&($ON_ALUMNI)){?>
				<a href="../alumni/p.php?p=alu-list" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/users48.png" height="32"><br>Alumni</a>
	<?php } if((ISACCESS("hrms",0))&&($ON_HRMS)){?>
				<a href="../hr/index.php" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/hr32.png"><br>HR</a>
	<?php } if((ISACCESS("finance",0))&&($ON_FINANCE)){?>
				<a href="../finance/index.php" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/finance32.png"><br>Finance</a>                
    <?php } if((ISACCESS("profiling",0))&&($ON_PROFILING)){?>
				<a href="../users/index.php" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/oss32.png" height="32"><br>Profiling</a>
     <?php } if((ISACCESS("ga",0))&&($ON_GA)){?>
				<a href="../ga/index.php" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/oss32.png" height="32"><br>GA</a>
     <?php } if((ISACCESS("crm",0))&&($ON_CRM)){?>
				<a href="../crm/index.php" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/franchise.png" height="32"><br>CRM</a>	
	<?php } if((ISACCESS("jobprogress",0))&&($ON_JOB_PROGRESS)){?>
				<a href="../jobprogress/p.php?p=task" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/progress32.png"><br>Progress</a> 
	<?php } if((ISACCESS("shareddoc",0))&&($ON_SHARED_DOC)){?>
				<a href="../shareddoc/p.php?p=edoc" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/register32a.png"><br>SharedDoc</a>                                
	<?php } if((ISACCESS("maintenance",0))&&($ON_MAINTENANCE)){?>
				<a href="../mtn/index.php" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/tools48.png" height="32"><br>Maintenance</a>     
	<?php } ?>
				<a href="../adm/logout.php" id="mymenuitem"><img src="<?php echo $MYLIB;?>/img/close32.png"><br>Logout</a>
<?php } ?>
</div> <!-- end epanel -->
