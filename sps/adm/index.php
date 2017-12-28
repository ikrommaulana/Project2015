<?php
//100521 - add ago
//110109 - facelift online chat
//$vdate="110729"; - facelift online chat
$vdate="120316";//facelisf news, chat, calendar
$vmod="v6.1.0";

include_once('../etc/db.php');
if (($ON_HTTPS)&&($_SERVER['SERVER_PORT']!=443)){
        $url = "https://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        header("Location: $url");
}
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");

if (isset($_SESSION['username']))
	verify("");
else if(isset($_POST['xuser']))
	process_login();
else
	$login=$_REQUEST['login'];
	//exit;
	
function dateDiff($beginDate, $endDate)
{
    	$diff = abs(strtotime($endDate) - strtotime($beginDate));

		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
		$days = floor(($diff)/ (60*60*24)); //kira day all the way

		//printf("%d years, %d months, %d days\n", $years, $months, $days);
		return $days;
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<!-- DW6 -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>

<!-- SETTING JQUERY -->
<script src="<?php echo $MYOBJ;?>/jquery/jquery-1.6.4.js"></script>
<script src="<?php echo $MYOBJ;?>/jquery/jquery-ui-1.8.16.custom.min.js"></script>
<!-- SETTING FANCYBOX -->
<script type="text/javascript" src="<?php echo $MYOBJ;?>/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
<script type="text/javascript" src="<?php echo $MYOBJ;?>/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $MYOBJ;?>/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<!-- MY SETTING -->
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo $MYLIB;?>/inc/myfancybox.js" type="text/javascript"></script>	

<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/login-box.css" type="text/css" />

<script type="text/javascript">

function ajx_alert(){
		var xmlhttp;
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				document.getElementById("alert_div").innerHTML=xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET","ajx_alert.php",true);
		xmlhttp.send();
}


function process_form(){
		if(document.form1.xuser.value==""){
			alert("<?php echo $lg_please_enter_username;?>");
			document.form1.xuser.focus();
			return;
		}
		if(document.form1.xpass.value==""){
			alert("<?php echo $lg_please_enter_password;?>");
			document.form1.xpass.focus();
			return;
		}
		if(document.form1.xkey.value==""){
			alert("<?php echo $lg_validation;?> ?");
			document.form1.xkey.focus();
			return;
		}
		$('#tbl_login').fadeOut('slow');
		document.form1.submit();
}

</script>


</head>
<?php if(isset($_SESSION['username'])){?>
<body onLoad="ajx_alert();"> 
<form name="myform" method="post">
	<input type="hidden" name="op">
	<input type="hidden" name="id">
<?php }else{?>
<body > 
<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
<?php }?>


<?php include('inc/masthead.php')?>
  
<?php if(!isset($_SESSION['username'])){?>
<div id="content" style="background:none;">
<br>
<br>
<table id="tbl_login"><tr>
<td width="40%">&nbsp;</td>
<td width="20%">
<div id="login-box">
		<table width="100%" border="0" style=" margin-top:-20px">
        	<tr>
                <td align="center">
                    <?php echo "<img height=100 src=$default_logo>"; ?>
                </td>
        	</tr>
        </table>
		<div align="center" style="font-weight:bold; color:#FF0; font-size:14px; padding: 5px;">
        <?php 
		//if($login=="0")      echo $lg_logout;//logout
		if($login=="-1") echo $lg_invalid_login;
		elseif($login=="-2") echo $lg_session_lost;
		elseif($login=="-3") echo $lg_session_expired;
		elseif($login=="-4") echo $lg_invalid_session;
		else echo "&nbsp;";
	?>
        </div>
	
        <div id="login-box-name" style="color:#FFF; font-weight:bold;"><br><?php echo $lg_staff_id;?></div><div id="login-box-field"><input name="xuser" class="form-login" value="" size="30" maxlength="2048" /></div>
        <div id="login-box-name" style="color:#FFF; font-weight:bold;"><br><?php echo $lg_password;?></div><div id="login-box-field"><input name="xpass" type="password" class="form-login" value="" size="30" maxlength="2048"/></div>
        <div id="login-box-name" style="color:#FFF; font-weight:bold;"><br><?php echo $lg_validation;?></div><div id="login-box-field"><input name="xkey" type="password" class="form-login" value="" size="30" maxlength="2048" /></div>
       	<div align="right" style="padding-right:30px">
     	<input type="button" value="Login" onClick="process_form();">
        </div>
        <span class="login-box-options">
            <div align="center" style="color:#F1F1F1; font-size:11px;">
                <i>
                <img src="<?php echo $MYLIB;?>/img/lock8.png"> <?php echo $lg_secured_ssl;?><br>
                <?php echo $lg_best_view_with_firefox_and_IE_latest_edition;?>
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
		<td align="center" style="font-size:11px; font-weight:bold; color:#333;"><?php echo "$system - $syscode";?></td>
		<td></td>
	</tr>
</table>
<br>
<br>
</div>

<?php }else{  ?>
 
<div id="panelleft" style="background-image:url(<?php echo $MYLIB;?>/img/bg_panel2.jpg);">  
    <div id="masthead_title" style="border-right:none; border-top:none;">
            <?php echo $lg_personal_information;?>
    </div>
    <?php include('inc/mymenu.php'); ?>
</div>

		
<div id="content3" style="background:none;">

	<div id="masthead_title" style="border-top:none;">&nbsp;
				<?php if(is_verify('ADMIN|AKADEMIK|HEP|HR|KEWANGAN')){?>
                        <a href="../news/news_edit.php" class="fbmedium" target="_blank" style="color:#FFFFFF; text-decoration:none; ">
                            <?php echo $lg_announcement;?>&nbsp;
                            <img src="<?php echo $MYLIB;?>/img/pencil16.png" height="12px">
                        </a>
                <?php } else {?>
                        <strong><?php echo $lg_announcement;?> &nbsp;</strong>
                <?php } ?>
	</div>
<table width="100%" cellspacing="0" cellpadding="2" style="background-image:url(<?php echo $MYLIB;?>/img/bg_panel2.jpg)">
	<tr>
        <td width="70%" valign="top" id="myborder" style="border-right:none;">        	
            	<?php include('../news/ajx_news.php');?>
        </td>
		<td width="30%" valign="top" id="myborder">
				<?php include('../calendar/div_countdown.php'); ?>    
                <?php if($ON_LEAVE){ include('../ecuti/div_leave.php');} ?>    
                <?php if($ON_STAFF_AWARD){include('../award/div_staff.php');}?>
    	</td>
	</tr>
</table>

	
<?php if((is_verify('ADMIN'))&&($ON_SYSTEM_ALERT)){?>
        <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td width="70%" valign="top" style="font-size:9px">
                <div style="border-bottom:none; margin-top:2px"></div>
                <div id="alert_div" style="padding:10px">
                        <img src="<?php echo $MYLIB;?>/img/loading.gif"> Loading...
                </div>
           </td>
           <td width="30%" valign="top">
             
           </td>
        </tr>
        </table>
<?php } ?>



</div> <!--end content--> 

<div id="panelright" style="width:20%; border:none;">
	<?php include("../calendar/ajx_cal_small.php");?>   
	<?php include('../chat/ajx_chat.php');?>
</div> <!--end panelright--> 
<?php } ?>
 
 
<?php include('../inc/site_footer.php');?>
</body>
</html>
