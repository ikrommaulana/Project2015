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
				<?php echo "<img src=$system_logo height=\"70px\">";?>
			 	<br>
                <br>
			    <table width="100%">
                  <tr>
                    <td width="27%" align="left">System</td>
                    <td width="3%" align="left">:</td>
                    <td width="70%" align="left"><?php echo "$system";?></td>
                  </tr>
                  <tr>
                    <td align="left">Version</td>
                    <td align="left">:</td>
                    <td align="left"><?php echo "$VERSION";?></td>
                  </tr>
                  <tr>
                    <td align="left">Registered</td>
                    <td align="left">:</td>
                    <td align="left"><?php echo "$registered";?></td>
                  </tr>
                <tr>
                    <td align="left">Licensee</td>
                    <td align="left">:</td>
                    <td align="left"><?php echo "$licensee";?></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
			   
                <div align="justify" style="border:1px solid #cccccc;background:#069; padding:4px">
                          Warning: This computer program is protected by copyright law and international treaties. Unauthorized reproduction or distribution of this program, or any portion of it, may result in severe civil and criminal penalties, and will be prosecuted to the maximum extent possible under the law.                        <br>
				</div>
                <br>
				&copy;2012-<?php echo date('Y');?> PT. Kuantum Mikroteknik
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
</body>
</html>
