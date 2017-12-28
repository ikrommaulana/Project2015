

<?php 
if (isset($_SESSION['username'])){
	$xxusername=$_SESSION['username'];
	$xxsyslevel=$_SESSION['syslevel'];
	$sql="select * from usr where uid='$xxusername'";
	$res=mysql_query($sql,$link);
	$row=mysql_fetch_assoc($res);
	$xximg=$row['file'];
	$xxsid=$_SESSION['sid'];
	if($xxsid!=0){
		$sql="select * from sch where id='$xxsid'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$xxsname=$row['sname'];
		$xxstype=$row['level'];
		mysql_free_result($res);					  
	}else{
		$xxsname="All Access";
	}

 ?>

<div style="padding:5px;">


<table width="90" height="90" style="font-size:9px; background-color:#CCCCFF ">
   <tr>
     <td  valign="top" id="myborderfull" align="center">
		<?php if(($xximg!="")&&(file_exists("$dir_image_user$xximg"))){?>
				<img src="imgresize.php?w=88&h=90&img=<?php echo "$dir_image_user$xximg";?>" width="88" height="90">
		<?php } else echo $lg_picture;?>
	 </td>
   </tr>
 </table>

 <table width="100%" style="font-size:9px " cellspacing="0">
       <tr>
         <td width="10%" valign="top"><?php echo $lg_id;?></td>
		 <td valign="top">:</td>
         <td><?php echo strtoupper($_SESSION['username']);?></td>
       </tr>
	   <tr>
         <td valign="top"><?php echo $lg_name;?></td>
		 <td valign="top">:</td>
         <td><?php echo ucwords(strtolower(stripslashes($_SESSION['name'])));?></td>
       </tr>
       <tr>
         <td valign="top"><?php echo $lg_system;?></td>
		 <td valign="top">:</td>
         <td><?php echo ucwords(strtolower($_SESSION['syslevel']));?></td>
       </tr>
	    <tr>
         <td valign="top"><?php echo "Hak Akses";?></td>
		 <td valign="top">:</td>
         <td><?php echo ucwords(strtolower($xxsname));?></td>
       </tr>
 </table>


<div style="font-size:11px; font-weight:bold; color:#666666; border-bottom:1px solid #F1F1F1;padding-top:3px"><?php echo "Pribadi";?></div>
<div id="sectionLinks">
	<a href="../estaff/myprofile.php" class="fbbig" target="_blank">
    	<img src="../img/expandh.gif"> <?php echo $lg_profile;?></a>

<?php if ($ON_MY_CLASS){?>	
    <a href="../adm/my_ses_cls.php" class="fbbig" target="_blank">
    	<img src="../img/expandh.gif"> <?php echo $lg_my_class;?></a>
<?php } ?>
<?php if ($ON_LEAVE){?>
    <a href="../ecuti/myleave.php" class="fbbig" target="_blank"><img src="../img/expandh.gif"> <?php echo $lg_my_leave;?>
<?php } ?>
<?php if ($ON_SALARY){?>
	<a href="../esalary/mysalary.php" class="fbbig" target="_blank"><img src="../img/expandh.gif"> <?php echo $lg_my_salary;?></a>
<?php } ?>
<?php if ($ON_CLAIM){?>
	<a href="p.php?p=../eclaim/myclaim"><img src="../img/expandh.gif"> <?php echo $lg_claim;?></a>
<?php } ?>
<?php if ($ON_JOB_PROGRESS){?>
	<a href="../jobprogress/mytask.php" class="fbbig" target="_blank">
    	<img src="../img/expandh.gif" > <?php echo $lg_my_work;?></a>
<?php } ?>
<?php if ($ON_TRAINING){?>
	<a href="../etraining/trainform.php?uid=<?php echo $_SESSION['username'];?>"  class="fbbig" target="_blank">
    	<img src="../img/expandh.gif"> <?php echo $lg_my_course;?></a>
<?php } ?>
</div>

<div style="font-size:11px; font-weight:bold; color:#666666; border-bottom:1px solid #F1F1F1;padding-top:3px"><?php echo $lg_application;?></div>
<div id="sectionLinks">
	<!--<a href="../adm/email.php" title="Check your EMail" ><img src="../img/expandh.gif"> <?php echo $lg_school_email;?></a> -->
<!-- if((ISACCESS("cctv",0))&&($ON_CCTV)){	?>
	<a href="../adm/cctv.php" target="_blank"><img src="../img/expandh.gif"> <?php echo $lg_school_cctv;?></a>
 } -->
<?php if($ON_QURANEXPLORER){?>
	<a href="http://www.quranexplorer.com/quran/" title="Courtesy of QuranExplorer.com"><img src="../img/expandh.gif"> Quran EXplorer</a>
<?php } ?>
</div>
<?php } ?>


</div>