

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
		$xxsname=$row['name'];
		$xxstype=$row['level'];
		mysql_free_result($res);					  
	}else{
		$xxsname="All School";
	}

 ?>

<div style="padding:5px 2px 2px 5px ">

<table width="90" height="90" id="mytable">
   <tr>
     <td valign="top" id="myborderfull">
		<?php if(($xximg!="")&&(file_exists("$dir_image_user$xximg"))){?>
				<img src="<?php echo "$dir_image_user$xximg";?>" width="70" height="72">
		<?php } else echo "&nbsp;";?>
	 </td>
   </tr>
 </table>
 <table width="100%" style="font-size:9px " bgcolor="#FAFAFA">
       <tr>
         <td width="10%" valign="top">USERID</td>
		 <td valign="top">:</td>
         <td><?php echo strtoupper($_SESSION['username']);?></td>
       </tr>
	   <tr>
         <td valign="top">Name</td>
		 <td valign="top">:</td>
         <td><?php echo $_SESSION['name'];?></td>
       </tr>
       <tr>
         <td valign="top">System</td>
		 <td valign="top">:</td>
         <td><?php echo $_SESSION['syslevel'];?></td>
       </tr>
	    <tr>
         <td valign="top"> Access</td>
		 <td valign="top">:</td>
         <td><?php echo $xxsname;?></td>
       </tr>
       </table>

</div>
<!-- 
<div id="sectionLinks">
	<a href="p.php?p=my_usr_reg"><img src="../img/expandh.gif"> My Profile</a>
</div>
-->
<?php } ?>