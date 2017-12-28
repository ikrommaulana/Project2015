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
        $sname=$row['name']; //school name
		$sch_lvl=$row['level'];
}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<!-- DW6 -->
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<?php include_once("$MYLIB/inc/myheader_setting.php");?>	

<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/login-box.css" type="text/css" />


<script language="JavaScript">
<!--
function process_form(op){
		if(document.form1.sid.value==""){
			alert("Please select school");
			document.form1.sid.focus();
			return;
		}
		if(document.form1.username.value==""){
			alert("Please enter IC number");
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
        <td><H2>Parent&nbsp;Online</H2></td>
     </tr>
</table>
        <font color="#FF9900" style="font-weight:bold">
        <?php 
						if($login=="0"){
							if($LG=="BM") 
								echo "Maaf. KP tidak dijumpai. Hubungi pihak sekolah untuk pengemaskinian"; 
							else 
								echo "Sorry. IC not found. Call school admin to update your IC. TQ";
						}
										
		?>
        </font>


        					<select name="sid" class="form-login">
                              <?php	
							  	if($sid=="")
									echo "<option value=\"\">- $lg_select $lg_school -</option>";
								else
									echo "<option value=\"$sid\">$sname</option>";
									$sql="select * from sch";
									$res=mysql_query($sql)or die("query failed:".mysql_error());
									while($row=mysql_fetch_assoc($res)){
												$s=$row['name'];
												$t=$row['id'];
												echo "<option value=$t>$s</option>";
									}	
							?>
                            </select><br><br>
                            <?php if($LG=="BM") echo "KAD PENGENALAN IBU/BAPA"; else echo "PARENT'S IC NUMBER";?><br>
		
							<input name="username" type="text" class="form-login">
                            <input type="button" name="button" style="height:41px;" value="<?php echo $lg_enter;?>" onClick="process_form()"><br>

							  <?php if($LG=="BM") echo "Cth: 500103071011 (tiada '-' atau 'ruang'"; else echo "Eg. 500103071011 (no space or -) ";?>
                              <br><br>
                              
							  
         <!--          
        <div id="login-box-name" style="margin-top:20px; color:#FFF">Username:</div><div id="login-box-field" style="margin-top:20px;"><input name="xuser" class="form-login" value="" size="30" maxlength="2048" /></div>
        <div id="login-box-name" style="color:#FFF">Password:</div><div id="login-box-field"><input name="xpass" type="password" class="form-login" value="" size="30" maxlength="2048"/></div>
        <div id="login-box-name" style="color:#FFF">Validation:</div><div id="login-box-field"><input name="xkey" type="password" class="form-login" value="" size="30" maxlength="2048" /></div>
       	<div align="right" style="padding-right:30px"><input type="button" name="button" value="<?php echo $lg_enter;?>" onClick="process_form()"></div>
        -->
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