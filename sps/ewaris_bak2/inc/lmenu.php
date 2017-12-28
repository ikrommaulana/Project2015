<?php 
include_once("$MYLIB/inc/language_$LG.php");
if (isset($_SESSION['username'])){
include_once('../etc/db.php');
include_once('session.php');

	$username=$_SESSION['username'];
	$sid=$_SESSION['sid'];
	$uid=$_POST['stuuid'];
	if($uid=="")
		$uid=$_SESSION['uid'];
	else
		$_SESSION['uid']=$uid;

	$sql="select * from stu where uid='$uid' and sch_id=$sid";
	$res=mysql_query($sql,$link);
	$row=mysql_fetch_assoc($res);
	$name=stripslashes($row['name']);
	$ic=$row['ic'];
	$img=$row['file'];
	$xp1name=$row['p1name'];
	$xp2name=$row['p2name'];
	
	$xyear=date("Y");
	$sql="select * from ses_stu where stu_uid='$uid' and sch_id=$sid and year='$xyear'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	if($row=mysql_fetch_assoc($res)){
		$clsname=$row['cls_name'];
		$clslevel=$row['cls_level'];
	}
		
	if($sid!=0){
		$sql="select * from sch where id='$sid'";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		$row=mysql_fetch_assoc($res);
		$sname=stripcslashes($row['name']);
		$stype=$row['level'];
		mysql_free_result($res);					  
	}

?>


<div id="masthead_title" style="border-right:none; border-top:none;" align="center">
		 Profil Siswa
</div>
<div style="font-weight:bold; color:#333333; border-bottom:2px solid #666;"></div>
<div id="contents" style="font-size:11px;  border-right:solid 1px #EEE; padding:10px;min-height:400px; height:400px; overflow-y:auto;background-image:url(<?php echo $MYLIB;?>/img/bg_panel2.jpg)"> 
<table width="100%">
<?php 
		$sql="select * from stu where sch_id=$sid and (p1ic='$username' or p2ic='$username') and status=6";
		$res=mysql_query($sql)or die("query failed:".mysql_error());
		while($row=mysql_fetch_assoc($res)){
				$xuid=$row['uid'];
				$xname=stripslashes($row['name']);
				if($xuid==$_SESSION['uid'])
					$checked="checked";
				else
					$checked="";
?>
<tr>
	<td id="myborder" width="100%">
    	<label style="cursor:pointer">
        	<input type="radio" name="stuuid" value="<?php echo $xuid;?>" <?php echo $checked;?> onClick="document.myform.submit();" style="cursor:pointer">
    		<?php echo $xname;?>
    	</label>
    </td>
</tr>
<?php } ?>
</table>


<table width="90" height="90" style="font-size:9px; background-color:#CCCCFF ">
   <tr>
     <td valign="top" id="myborderfull" align="center">
		<?php if(($img!="")&&(file_exists("$dir_image_student$img"))){?>
				<img src="<?php echo "$dir_image_student$img";?>" width="75" height="75">
		<?php } else echo "Picture";?>
	 </td>
   </tr>
 </table>
	<table width="100%">
       <tr>
		 <td width="20%"><?php echo strtoupper($lg_matric);?></td>
		 <td width="1%">:</td>
		 <td width="80%"><?php echo $uid;?></td>
       </tr>
       <tr>
		 <td valign="top"><?php echo strtoupper($lg_class);?></td>
		 <td valign="top">:</td>
		 <td><?php echo "$clsname";?></td>
       </tr>
	   <tr>
		 <td valign="top"><?php echo strtoupper($lg_school);?></td>
		 <td valign="top">:</td>
		 <td><?php echo "$sname";?></td>
       </tr>
	   <tr>
		 <td valign="top"></td>
		 <td valign="top"></td>
		 <td></td>
       </tr>
	   <tr>
		 <td valign="top"><?php echo strtoupper($lg_father);?></td>
		 <td valign="top">:</td>
		 <td><?php echo ucwords(strtolower("$xp1name"));?></td>
       </tr>
	   <tr>
		 <td valign="top"><?php echo strtoupper($lg_mother);?></td>
		 <td valign="top">:</td>
		 <td><?php echo ucwords(strtolower("$xp2name"));?></td>
       </tr>
       </table>
	   
	   
	   
 <?php } ?>
</div>