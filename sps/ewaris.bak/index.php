<?php
include_once('../etc/db.php');
include_once('session.php');
include_once("$MYLIB/inc/language_$LG.php");

if (isset($_SESSION['username'])){
	$username=$_SESSION['username'];
	$syslevel=$_SESSION['syslevel'];
	$uid=$_SESSION['username'];
	$sql="select * from usr where uid='$uid'";
	$res=mysql_query($sql,$link);
	$row=mysql_fetch_assoc($res);
	$img=$row['file'];
	echo "<script language=\"javascript\">location.href='main.php'</script>";
}
else if(isset($_POST['username'])){
	process_login();
}
else{
	$login=$_REQUEST['login'];
}

$sid=$_REQUEST['sid'];
if($sid!=0){

		$sql="select * from sch where id=$sid";
        $res=mysql_query($sql)or die("$sql query failed:".mysql_error());
        $row=mysql_fetch_assoc($res);
        $sname=stripcslashes($row['name']); //school name
		$sch_lvl=$row['level'];
}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<!-- DW6 -->
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('inc/sitetitle.php')?></title>
<!-- SETTING JQUERY -->
<script src="<?php echo $MYOBJ;?>/jquery/jquery-1.6.4.js"></script>
<script src="<?php echo $MYOBJ;?>/jquery/jquery-ui-1.8.16.custom.min.js"></script>

<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/login-box.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="my.js" type="text/javascript"></script>

<script language="JavaScript">
<!--
function process_form(op){
		if(document.form1.sid.value==""){
			alert("Sila Pilih Sekolah");
			document.form1.sid.focus();
			return;
		}
		if(document.form1.username.value==""){
			alert("Sila masukkan no KTP");
			document.form1.username.focus();
			return;
		}
		$('#tbl_login').fadeOut('slow');
		document.form1.submit();
}


//-->
</script>
</head>

<body style="background-image:none; background-color:#F1F1F1;" onLoad="$('#tbl_login').fadeIn('slow');"> 
<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" >
<?php include('inc/masthead.php'); ?>
<div id="content" style="background-image:url(<?php echo $MYLIB;?>/img/bg_body.jpg);background-repeat:repeat;">
<br>
<br>
<br>
<table id="tbl_login" style="display:none;"><tr>
<td width="40%">&nbsp;</td>
<td width="20%">
<div id="login-box">
<table width="100%" border="0" style="margin-left:-20px">
	<tr>
		<td align="center">
        	<?php 
			if($organization_logo!="") 
				echo "<img src=$organization_logo  height=\"70\">";
			else  
				echo "<img src=$default_logo height=\"70\">";?>
         </td>
	</tr>
	<tr>
        <td><H2>Parent Online</H2></td>
     </tr>
</table>
        <font color="#FF9900" style="font-weight:bold">
        <?php 
						if($login=="0"){
							
								echo "Maaf. KTP tidak dijumpai. Hubungi pihak sekolah untuk dikemaskini"; 
							
						}
										
		?>
        </font>


        					<select name="sid" class="form-login">
                              <?php	
							  	if($sid=="")
									echo "<option value=\"\">- Pilih Sekolah -</option>";
								else
									echo "<option value=\"$sid\">$sname</option>";
									$sql="select * from sch";
									$res=mysql_query($sql)or die("query failed:".mysql_error());
									while($row=mysql_fetch_assoc($res)){
												$s=stripcslashes($row['name']);
												$t=$row['id'];
												echo "<option value=$t>$s</option>";
									}	
							?>
                            </select><br><br>
                            KTP WALI SISWA<br>
		
							<input name="username" type="text" class="form-login">
                            <input type="button" name="button" style="height:41px;" value="<?php echo $lg_enter;?>" onClick="process_form()"><br>

							  
                              <br><br>
                              
		
        <span class="login-box-options">
            <div align="center" style="color:#F1F1F1; font-size:10px;">
                <i><br>
                    Best view with Mozilla Firefox &amp; Intenet Explorer
                </i>
         	</div>
        </span>
        <br />
        <br />
        
</div>
		</td>
    	<td width="40%">&nbsp;</td>
    </tr>
    <tr>
        <td></td>
		<td align="center" style="font-size:11px; font-weight:bold; color:#333;">&reg; <?php echo date('Y');?> <?php echo $organization_name;?></td>
		<td></td>
	</tr>
</table>
<br>
<br>
</div>
 <?php include('../inc/site_footer.php');?>
 </form>
</body>
</html>