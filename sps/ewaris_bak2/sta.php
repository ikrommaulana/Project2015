<?php 
	include_once('../etc/db.php');
	include_once("../inc/language.php");
		
	$sql="select * from type where grp='openexam' and prm='ESTATUS'";
	$res=mysql_query($sql)or die("query failed:".mysql_error());
	$row=mysql_fetch_assoc($res);
	$sta=$row['val'];
	if($sta!="1")
		echo "<script language=\"javascript\">location.href='close.php'</script>";
	$ic="";
	$name="";
	$lvl="";
	$statusmohon="";
	$p=$_REQUEST['p'];
	$yic=$_POST['ic'];
	$sid=$_POST['sid'];
	if($yic!=""){
		$sql="select * from stureg where ic='$yic' and confirm=1 and sch_id=$sid";
		$res=mysql_query($sql) or die(mysql_error());
		$num=mysql_num_rows($res);
		if($num>0){	
			$row=mysql_fetch_assoc($res);
			$xname=$row['name'];
			$xic=$row['ic'];
			$xid=$row['id'];
			$sid=$row['sch_id'];
			$status=$row['status'];
			$level=$row['cls_level'];
			mysql_free_result($res);
		
			$sql="select * from sch where id=$sid";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$xsname=$row['name'];
			$lvl=$row['clevel'];
			mysql_free_result($res);
		
			$sql="select * from type where grp='statusmohon' and val=$status";
			$res=mysql_query($sql)or die("query failed:".mysql_error());
			$row=mysql_fetch_assoc($res);
			$statusmohon=$row['prm'];
			mysql_free_result($res);	
			if($p=="")
				echo "<script language=\"javascript\">location.href='../edaftar/reg_slip.php?id=$xid&ic=$xic'</script>";
			else
				echo "<script language=\"javascript\">location.href='p.php?p=../edaftar/reg_slip&id=$xid&ic=$xic'</script>";
		}
	}	

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>	

<script language="JavaScript">
function process_form(op){
		if(document.myform.sid.value==""){
			alert("Please select school..");
			document.myform.sid.focus();
			return;
		}
		if(document.myform.ic.value==""){
			alert("Please enter student IC Number..");
			document.myform.ic.focus();
			return;
		}
		document.myform.submit();
}
</script>

<body  style="background-image:url(../img/bg_content.jpg);">

<form name="myform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" >
	<input type="hidden" name="p" value="<?php echo $p;?>">
  
<div id="content">

	<br>
	<div style="font-size:20px; font-weight:bold; font-family:Palatino Linotype"  align="center">
		<img src="<?php echo "$organization_logo";?>">
		<br>
		<?php echo $organization_name;?><br>
<?php 
	if($LG=="BM") 
		echo "Semakan Pendaftaran Pelajar";
	else
		echo "Student Registration Status";
?>
	</div>

<br>
<br>
<div align="center" style="color:#FF0000; font-size:16px ">&nbsp;
<?php 
if(($yic!="")&&($xic=="")){
	if($LG=="BM") 
		echo "Maaf. Carian pelajar kad pengenalan '$yic' tidak ditemui. Pastikan KP tiada space atau -";
	else
		echo "Sorry. IC number '$yic' cannot be found. Make sure IC is without space or '-'";

}
?>
</div>
<div id="story" style=" width:640px;margin-left:auto; margin-right:auto; ">

<table width="100%" style="font-size:14px; ">
	<tr>
		<td valign="top" >
				<table width="100%"  border="0">
				  	<tr>
						<td valign="top">
								<table  border="0" width="100%" >
										<tr>
												<td colspan="3" align="center">
												<?php if($LG=="BM") {?>
													HANYA UNTUK SEMAKAN STATUS PENDAFTARAN PELAJAR BARU
												<?php } else {?>
													ONLY FOR NEW STUDENT REGISTRATION STATUS
												<?php }?>
													<br><br>
												</td>
										</tr>
										<tr>
												<td width="20%"><strong><font color="#0000FF"><?php echo strtoupper($lg_school);?></font></strong></td>
												<td width="1%"><strong><font color="#0000FF">:</font></strong></td>
												<td>
													<select name="sid" id="sid">
													<?php	
																echo "<option value=\"\">- $lg_select -</option>";
																$sql="select * from sch";
																$res=mysql_query($sql)or die("query failed:".mysql_error());
																while($row=mysql_fetch_assoc($res)){
																			$s=$row['name'];
																			$t=$row['id'];
																			echo "<option value=$t>$s</option>";
																}
																mysql_free_result($res);
													?>
													</select>
												</td>
										</tr>
										<tr>
												<td><strong><font color="#0000FF"><?php echo strtoupper($lg_ic_student);?></font></strong></td>
												<td width="1%"><strong><font color="#0000FF">:</font></strong></td>
												<td ><input name="ic" type="text" id="ic" size="20"  style="font-size:14px; color:#0033FF; font-weight:bold"><input type="submit" name="Submit" value="<?php echo $lg_check;?>" onClick="process_form();return false"></td>
										</tr>
										<tr>
												<td><strong><font color="#0000FF">IP&nbsp;ADDRESS</font></strong></td>
												<td width="1%"><strong><font color="#0000FF">:</font></strong></td>
												<td><a style="text-decoration:blink; color:#FF0000"><?php echo $_SERVER['REMOTE_ADDR'];?></a></td>
										</tr>
								</table> 

						</td>
				  	</tr>
				</table>
			</td>
	</tr>
	
</table>
</div> <!--end story--> 
			<div align="center">
				<font color="#999999" size="1"><strong>Best view MOZILLA FIREFOX with 1280 by 800 pixels</strong></font>
			</div>
	
<br>
<br>
<br>
</div>
</div></div>
</form>
</body>
</html>