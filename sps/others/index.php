<?php
include_once('../etc/db.php');
include_once('../etc/session.php');
include_once("$MYLIB/inc/language_$LG.php");
ISACCESS("maintenance",1);//autoreject


$sid=$_SESSION['sid'];
if($sid!=0)
	$sqlsid="and sch_id='$sid'";
if($sid!=0)
	$sqlsid2="and sid='$sid'";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<!-- DW6 -->
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php include('../inc/site_title.php')?></title>
<link rel="stylesheet" href="<?php echo $MYLIB;?>/inc/my.css" type="text/css">
<script language="JavaScript1.2" src="<?php echo $MYLIB;?>/inc/my.js" type="text/javascript"></script>
		
<script language="JavaScript">
function process_form(){
	if(document.form1.xuser.value==""){
			alert("Sila masukkan katanama pengguna");
			document.form1.xuser.focus();
			return;
		}
		if(document.form1.xpass.value==""){
			alert("Sila masukkan katalaluan pengguna");
			document.form1.xpass.focus();
			return;
		}
		document.form1.submit();
}
</script>
</head>
<body > 

<?php include('../inc/masthead.php')?>
 

<div id="content"> 
<div id="mypanel">
	<div align="center" style="width:100%; font-size:24px; font-weight:bold; font-family:'Times New Roman'">
	Maintenance Department
	</div>
</div>
<div id="story" style="border:none ">
	
<br>
<br>
<br>
<br>
<table width="100%"  border="0" cellpadding="0" style="width:100%; font-size:20px; font-weight:bold; font-family:'Times New Roman'">
	  <tr>
			<td width="10%" align="center">&nbsp;</td>
			<td width="30%" align="center">
                 <div id="modulelink"><a href="../emaintenance/" id="mymenuitem">
                        <img src="<?php echo $MYLIB;?>/img/tools48.png"><br>Maintenance<br><font size="2">Service &amp; Report</font></a></div>
			</td>
			<td width="30%" align="center">
					<div id="modulelink"><a href="../asset/p.php?p=asset" id="mymenuitem">
                    	<img src="<?php echo $MYLIB;?>/img/money48.png"><br>Asset<br><font size="2">Record &amp; Tagging</font></a></div>
			</td>
			<td width="30%" align="center">
					<div id="modulelink"><a href="../ecareer/p.php?p=work_online" id="mymenuitem">
                    	<img src="<?php echo $MYLIB;?>/img/passport48.png"><br>Vendor<br><font size="2">Supplier &amp; Contractor</font></a></div>
			</td>
	  </tr>
	  <tr></tr>
	  <tr>
			<td width="10%">&nbsp;</td>
			<td width="30%" align="center">
				<div id="modulelink"><a href="../esalary/p.php?p=salarytrans" id="mymenuitem">
                	<img src="<?php echo $MYLIB;?>/img/users48.png"><br>Client<br><font size="2">Customer &amp; Client</font></a></div>
			</td>
	  </tr>
	  
</table>
<br>
<br>
<br>
	</div> 	<!--end story--> 
  </div>   <!--end content--> 
   <?php include('../inc/site_footer.php');?>
</div> 
<!--end pagecell1--> 
</body>
</html>