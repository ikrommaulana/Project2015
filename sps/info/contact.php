<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
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

<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/login-box.css" type="text/css" />
</head>
<body> 

<?php include('../inc/masthead.php')?>
<div id="content" style="background:none;">
<br>
<br>
<table id="tbl_info"><tr>
    <td width="40%">&nbsp;</td>
    <td width="20%">
<div id="login-box" align="center">
		<img src="../img/kmt.png" height="70px" style="padding:10px 10px 10px">
		<br>
		<br>

			  PT. Kuantum Mikroteknik<br>
              Jalan Ragunan Raya No. 46 Jati Padang,<br>
              Jakarta Selatan 12540<br>
              Indonesia<br>
			  <br>
              Tel: 021-7823282<br>
              Fax: 021-7823282<br>
			  Email: info@kuantum-mikro.com<br>
			  Http://www.kuantum-mikro.com</div>

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
</body>
</html>
